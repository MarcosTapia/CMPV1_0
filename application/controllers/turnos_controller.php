<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Turnos_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuarios_herramentales;
    private $usuarioHerramental;

    private $usuariosGlobal;
    private $usuariosOperadoresGlobal;
    private $maquinasSimple;
    private $areasSimple;
    private $categoriasGlobal;
    
    private $gatewayRest;

    function __construct(){
        parent::__construct();
        $this->load->model('sistema_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->load->library('ciqrcode');
        
        $this->error = "";
        
        //para subir imagenes
        $this->load->helper("URL", "DATE", "URI", "FORM");
        $this->load->library('upload');
        
        //llamado a controlador util global
        $this->load->library('../controllers/util_controller');
        
        $this->datosEmpresaGlobal = $this->util_controller->cargaDatosEmpresa();
        $this->nombreEmpresaGlobal = $this->datosEmpresaGlobal[0]->{'nombreEmpresa'};
        $this->usuarios_herramentales = $this->util_controller->cargaDatosUsuariosHerramentales();
        $this->usuarioHerramental = $this->util_controller->obtenerUsuarioHerramental();
        $this->aplicadores = $this->util_controller->cargaDatosAplicadores();
        
        $this->maquinasSimple = $this->util_controller->cargaDatosMaquinasSimple();
        $this->usuariosGlobal = $this->util_controller->cargaDatosUsuarios();
        $this->areasSimple = $this->util_controller->cargaDatosAreas();
        $this->usuariosOperadoresGlobal = $this->util_controller->obtenerUsuariosOperadores();
        $this->categoriasGlobal = $this->util_controller->cargaDatosCategorias();
    }
    
    function index(){
        $this->load->view('login_view');
    }
    
    function generar_qr($user_id) {
        $params['data'] = $user_id;
        $params['level'] = 'H';
        $params['size'] = 10;
        //decimos el directorio a guardar el codigo qr, en este 
        //caso una carpeta en la raíz llamada qr_code
        $params['savename'] = FCPATH . "uploads/qr_code/qr_$user_id.png";
        //generamos el código qr
        $this->ciqrcode->generate($params);
        $data['img'] = "qr_$user_id.png";
        $this->load->view('qr/codigoqr_view', $data);
    }    
    
    function inicio() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'permisos'=>$this->session->userdata('permisos'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'opcionClickeada' => 'Inicio'
                );
            if ($this->session->userdata('permisos') == "Técnico"){
                $this->load->view('layouts/headerTecnico_view',$data);
            } else {
                if ($this->session->userdata('permisos') == "Administrador"){
                    $this->load->view('layouts/headerAdministrador_view',$data);
                }
                if ($this->session->userdata('permisos') == "Otros"){
                    $this->load->view('layouts/headerOtros_view',$data);
                }
            }
            $this->load->view('principal_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarTurnosGeneral() {
        echo "estoy solicitando turnos";
        //$this->load->view('turnos/atencion_view');
    }

    function solicitarAtencion($idUsuarioOperador,$tipoUsuario,$idArea) {
        //tipoUsuario = 1 Supervisor, 2 lider
        //echo $idUsuarioOperador." - ".$tipoUsuario."<br>";
        $idAreaElegida = -1;
        $idTecnicoElegido = -1;
        $data;
        
        //obtiene datos del solicitante si es lider
        if ($tipoUsuario == "2") {
            $url = RUTAWS.'usuarios_turnos/obtener_usuarioturno_por_id.php?idUsuarioOperador='.$idUsuarioOperador;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo "-".$datos->{'usuario'}->{'idArea'};
            $idAreaElegida = $datos->{'usuario'}->{'idArea'};
        } else {
            $idAreaElegida = $idArea;
        }

        //obtiene maquinas por area
        $url = RUTAWS.'maquinaria/obtener_maquinas_por_idArea.php?idArea='.$idAreaElegida;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataMaquinas = curl_exec($ch);
        $datosMaquinas = json_decode($dataMaquinas);
        curl_close($ch);      
        $maquinas = $datosMaquinas->{'maquinas'};
        
        //TRAIGO TODOS LOS TECNICOS Y OBTENGO EL TECNICO QUE REALIZARA LA ATENCION
        //obtener_turnos_por_turno_y_disponibilidad
        $url = RUTAWS.'turnos/obtener_tecnicos_en_turnos.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataTurnos = curl_exec($ch);
        $datosTurnos = json_decode($dataTurnos);
        curl_close($ch);            //
        //echo $dataTurnos;
        
        //elige el tecnico que dara el servicio
        foreach ($datosTurnos->{'turnos'} as $fila) {
            if (($fila->{'status'} == "0") && (
                    ($fila->{'idArea1'} == $idAreaElegida) || 
                    ($fila->{'idArea2'} == $idAreaElegida) || 
                    ($fila->{'idArea3'} == $idAreaElegida) || 
                    ($fila->{'idArea4'} == $idAreaElegida) ||
                    ($fila->{'idArea5'} == $idAreaElegida)
                    )) {
                $idTecnicoElegido = $fila->{'idUsuario'};
                break;
            }
        }
        
        //si estan todos los tecnicos ocupados no continua con la solicitud
        if ($idTecnicoElegido == -1) {
            $idTecnicoElegido = 1;
            //echo "<script>window.history.back();</script>";
        } 
        $url = RUTAWS.'usuarios/obtener_usuario_por_id.php?idUsuario='.$idTecnicoElegido;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataUsuario = curl_exec($ch);
        $datosUsuario = json_decode($dataUsuario);
        curl_close($ch);            

        //obtiene los datos del tecnico elegido
        $url = RUTAWS.'usuarios/obtener_usuario_por_id.php?idUsuario='.$idTecnicoElegido;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataTecnico = curl_exec($ch);
        $datosTecnico = json_decode($dataTecnico);
        curl_close($ch);
        $nombreUsuario = $datosTecnico->{'usuario'}->{'apellido_paterno'}." ".$datosTecnico->{'usuario'}->{'apellido_materno'}." ".$datosTecnico->{'usuario'}->{'nombre'};
        
        //obtiene los datos de las fallas por area
        $url = RUTAWS.'fallas_notificaciones/obtener_fallasnotificaciones_por_idarea.php?idArea='.$idAreaElegida;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataFallasNotificaciones = curl_exec($ch);
        $datosFallasNotificaciones = json_decode($dataFallasNotificaciones);
        curl_close($ch);
        $fallasNotificaciones = $datosFallasNotificaciones->{'fallasnotificaciones'};

        //obtiene los datos de los proyectos
        $url = RUTAWS.'proyectos/obtener_proyectos.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataProyectos = curl_exec($ch);
        $datosProyectos = json_decode($dataProyectos);
        curl_close($ch);
        $proyectos = $datosProyectos->{'proyectos'};
        
        $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
        $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
        
        //si no hay tecnico elegido
        if ($nombreUsuario != "") {
            $data = array(
                'usuario'=>$datosUsuario->{'usuario'},            
                'user'=>$this->session->userdata('user'),
                'pass'=>$this->session->userdata('pass'),
                'fallasNotificaciones'=>$fallasNotificaciones,
                'proyectos'=>$proyectos,
                'idUsuario'=>$idTecnicoElegido,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => 'Atencion',
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'idUsuarioOperador'=>$idUsuarioOperador,
                'maquinasSimple'=>$maquinas,
                'nombreUsuario'=>$nombreUsuario);        
            $this->load->view('layouts/headerOperador_view',$data);
            $this->load->view('turnos/solicitud_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } 
    }
    
    function reanudarSolicitud($idNotificacion,$etapa) {
        $url = RUTAWS.'notificaciones/obtener_notificacion_por_id.php?id='.$idNotificacion;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);        
        
        $url = RUTAWS.'usuarios/obtener_usuario_por_id.php?idUsuario='.$datos->{'notificacion'}->{'idUsuarioTecnico'};
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataUsuario = curl_exec($ch);
        $datosUsuario = json_decode($dataUsuario);
        curl_close($ch);
            
        $url = RUTAWS.'turnos/obtener_tecnicos_en_turnos.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataTecnicos = curl_exec($ch);
        $datosTecnicos = json_decode($dataTecnicos);
        curl_close($ch);        
        
        $url = RUTAWS.'usuarios/obtener_usuarios.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataUsuarios = curl_exec($ch);
        $datosUsuarios = json_decode($dataUsuarios);
        curl_close($ch);
        
        $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
        $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
        $data = array(
            'usuarios'=>$datosUsuarios->{'usuarios'},
            'notificacion'=>$datos->{'notificacion'},
            'usuario'=>$datosUsuario->{'usuario'},            
            'turnos'=>$datosTecnicos->{'turnos'},
            'etapa'=>$etapa,
            'idNotificacion'=>$idNotificacion,
            'user'=>$this->session->userdata('user'),
            'pass'=>$this->session->userdata('pass'),
            'nombre_Empresa'=>$this->nombreEmpresaGlobal,
            'permisos' => $this->session->userdata('permisos'),
            'opcionClickeada' => 'Atencion',
            'usuarioDatos' => $this->session->userdata('nombre'),
            'fecha' => $fechaIngreso);        
        $this->load->view('layouts/headerOperador_view',$data);
        $this->load->view('turnos/solicitud_reanudar_view',$data);
        $this->load->view('layouts/pie_view',$data);
    }

    function solicitudesEnCursoTurno($idUsuarioOperador,$tipoUsuario) {
        if ($this->is_logged_in()){
            $url;
            if ($tipoUsuario == "2") {
                $url = RUTAWS.'notificaciones/obtener_notificaciones_dia_activas_lider.php?idUsuarioOperador='.$idUsuarioOperador;
            } else {
                $url = RUTAWS.'notificaciones/obtener_notificaciones_dia.php';
            }
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array areas
            $areasArray = array();
            $j = 0;
            foreach ($this->areasSimple as $area){
                $areasArray[$area->{'descripcion'}."|".$j."|"] = "".$area->{'idArea'};
                $j++;
            }    
            
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
             
            if ($datos->{'estado'}==1) {
                $data = array('notificaciones'=>$datos->{'notificaciones'},
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerOperador_view',$data);
                $this->load->view('turnos/adminNotificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'notificaciones'=>null,
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerOperador_view',$data);
                $this->load->view('turnos/adminNotificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function solicitudesEnCurso($idUsuarioOperador,$tipoUsuario) {
        if ($this->is_logged_in()){
            $url;
            if ($tipoUsuario == "2") {
                $url = RUTAWS.'notificaciones/obtener_notificaciones_dia_lider.php?idUsuarioOperador='.$idUsuarioOperador;
            } else {
                $url = RUTAWS.'notificaciones/obtener_notificaciones_dia.php';
            }
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array areas
            $areasArray = array();
            $j = 0;
            foreach ($this->areasSimple as $area){
                $areasArray[$area->{'descripcion'}."|".$j."|"] = "".$area->{'idArea'};
                $j++;
            }    
            
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            /****/
            $url = RUTAWS.'proyectos/obtener_proyectos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataProyectos = curl_exec($ch);
            $datosProyectos = json_decode($dataProyectos);
            curl_close($ch);
            
            if ($datos->{'estado'}==1) {
                $data = array(
                    'notificaciones'=>$datos->{'notificaciones'},
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Atencion'
                        );
                $this->load->view('layouts/headerOperador_view',$data);
                $this->load->view('turnos/adminNotificacionesCurrent_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'notificaciones'=>null,
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Atencion'
                        );
                $this->load->view('layouts/headerOperador_view',$data);
                $this->load->view('turnos/adminNotificacionesCurrent_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarNotificacionesLider() {
        $idUsuarioOperador = $this->session->userdata('idUsuarioOperador');
        $tipoUsuario = 2;
        if ($this->is_logged_in()){
            $url = RUTAWS.'notificaciones/obtener_notificaciones_lider_general.php?idUsuarioOperador='.$idUsuarioOperador;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array areas
            $areasArray = array();
            $j = 0;
            foreach ($this->areasSimple as $area){
                $areasArray[$area->{'descripcion'}."|".$j."|"] = "".$area->{'idArea'};
                $j++;
            }    
            
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 

            /****/
            $url = RUTAWS.'proyectos/obtener_proyectos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataProyectos = curl_exec($ch);
            $datosProyectos = json_decode($dataProyectos);
            curl_close($ch);
            
            if ($datos->{'estado'}==1) {
                $data = array(
                    'notificaciones'=>$datos->{'notificaciones'},
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerOperador_view',$data);
                $this->load->view('turnos/adminNotificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'notificaciones'=>null,
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerOperador_view',$data);
                $this->load->view('turnos/adminNotificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function mostrarNotificacionesGeneral() {
        $idUsuarioOperador = $this->session->userdata('idUsuarioOperador');
        $tipoUsuario = 1;
        if ($this->is_logged_in()){
            /****/
            $url = RUTAWS.'proyectos/obtener_proyectos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataProyectos = curl_exec($ch);
            $datosProyectos = json_decode($dataProyectos);
            curl_close($ch);
            
            $url = RUTAWS.'notificaciones/obtener_notificaciones_dia.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array areas
            $areasArray = array();
            $j = 0;
            foreach ($this->areasSimple as $area){
                $areasArray[$area->{'descripcion'}."|".$j."|"] = "".$area->{'idArea'};
                $j++;
            }    
            
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
             
            if ($datos->{'estado'}==1) {
                $data = array(
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'notificaciones'=>$datos->{'notificaciones'},
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerOperador_view',$data);
                $this->load->view('turnos/adminNotificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'notificaciones'=>null,
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerOperador_view',$data);
                $this->load->view('turnos/adminNotificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function muestraNotificaciones($idUsuario) {
        if ($this->is_logged_in()){
            $url = RUTAWS.'turnos/obtener_notificaciones.php?idUsuario='.$idUsuario;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            $count = 0;
            $notificaciones = null;
            if ($datos->{'estado'} != "2") {
                $count = sizeof($datos->{'notificaciones'});
                $notificaciones = $datos->{'notificaciones'};
            }
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'idUsuario' => $idUsuario,
                'count'=>$count,
                'notificaciones'=>$notificaciones,
                'usuario_herramental'=>$this->usuarioHerramental,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => 'Notificacioes'
                    );
            $this->load->view('layouts/headerTecnico_view',$data);
            $this->load->view('turnos/notificaciones_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function mostrarTurnos() {
        if ($this->is_logged_in()){
            $url = RUTAWS.'turnos/obtener_turnos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array areas
            $areasArray = array();
            $j = 0;
            foreach ($this->areasSimple as $area){
                $areasArray[$area->{'descripcion'}."|".$j."|"] = "".$area->{'idArea'};
                $j++;
            }    
            
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
             
            if ($datos->{'estado'}==1) {
                $data = array('turnos'=>$datos->{'turnos'},
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'turnos'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/adminTurnos_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array('turnos'=>null,
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'turnos'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/adminTurnos_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function estadisticas(){
        if ($this->is_logged_in()) {
            //obtiene notificaciones
            $url = RUTAWS.'notificaciones/obtener_notificaciones.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);

            //obtiene turnos
            $url = RUTAWS.'turnos/obtener_turnos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataTurnos = curl_exec($ch);
            $datosTurnos = json_decode($dataTurnos);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    
            
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if ($datos->{'estado'}==1) {
                $data = array('notificaciones'=>$datos->{'notificaciones'},
                    'turnos'=>$datosTurnos->{'turnos'},
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'turnos'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/estadisticos_notificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array('turnos'=>null,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'turnos'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/estadisticos_notificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function consultaParos(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d");
            
            //trae los proyectos
            $url = RUTAWS.'proyectos/obtener_proyectos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataProyectos = curl_exec($ch);
            $datosProyectos = json_decode($dataProyectos);
            curl_close($ch);

            $fecha1 = $fechaIngreso;
            $fecha2 = $fechaIngreso;
            $ddate = $fechaIngreso;
            $date = new DateTime($ddate);
            $idProyecto = 0;
            if ($this->input->post('submit')){
                $fecha1 = $this->input->post("fecha1");
                $fecha2 = $this->input->post("fecha2");
            }
            
            //echo "--->".$fecha1."-".$fecha2;
            $url = RUTAWS.'turnos/obtener_notificaciones_consulta_graficos.php?fecha1='.$fecha1.'&fecha2='.$fecha2;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array Usuarios operadores(lideres supervisores)
            $usuariosArrayOperadores = array();
            $j = 0;
            foreach ($this->usuariosOperadoresGlobal as $usuario){
                $usuariosArrayOperadores[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuarioOperador'};
                $j++;
            }    
            
            //obtiene nombre del responsable di lo hay
            $idResponsableMaq = 0;
//            if ($idMaquina) {
//                foreach ($this->maquinasSimple as $maquina){
//                    if ($idMaquina == $maquina->{'idMaquina'}) {
//                        $idResponsableMaq = $maquina->{'responsable_maquina'};
//                    }
//                }
//            }
            
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            //echo sizeof($datos->{'paros'});
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'proyectos'=>$datosProyectos->{'proyectos'},
                        'idResponsable'=>$idResponsableMaq,
                        'proyectos'=>$datosProyectos->{'proyectos'},
                        'categorias'=>$this->categoriasGlobal,
                        'areas' => $this->areasSimple,
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'usuariosArrayOperadores' => $usuariosArrayOperadores,
                        'maquinasArray' => $maquinasArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'paros'=>$datos->{'paros'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('turnos/consultaParos_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'idResponsable'=>$idResponsableMaq,
                        'proyectos'=>$datosProyectos->{'proyectos'},
                        'categorias'=>$this->categoriasGlobal,
                        'areas' => $this->areasSimple,
                        'paros'=>null,
                        'areas' => $this->areasSimple,
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('turnos/consultaParos_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'idResponsable'=>$idResponsableMaq,
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'categorias'=>$this->categoriasGlobal,
                    'areas' => $this->areasSimple,
                    'paros'=>null,
                    'maquinasSimple' => $this->maquinasSimple,
                    'usuarios' => $this->usuariosGlobal,
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/consultaParos_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarTurno($idTurno) {
        if ($this->is_logged_in()) {
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'turnos/obtener_turno_por_id.php?idTurno='.$idTurno;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            if ($datos->{'estado'}==1) {
                $data = array(
                    'usuarios' => $this->usuariosGlobal,
                    'areas' => $this->areasSimple,
                    'turno'=>$datos->{'turno'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/actualizaTurno_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarTurnoFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idTurno = $this->input->post("idTurno");
                $idUsuario = $this->input->post("idUsuario");
                $hora_entrada = $this->input->post("hora_entrada");
                $hora_salida = $this->input->post("hora_salida");
                
                $idArea1 = $this->input->post("idArea1");
                $idArea2 = $this->input->post("idArea2");
                $idArea3 = $this->input->post("idArea3");
                $idArea4 = $this->input->post("idArea4");
                $idArea5 = $this->input->post("idArea5");
                if ($idArea2 == "") {
                    $idArea2 = 17;
                }
                if ($idArea3 == "") {
                    $idArea3 = 17;
                }
                if ($idArea4 == "") {
                    $idArea4 = 17;
                }
                if ($idArea5 == "") {
                    $idArea5 = 17;
                }
                
                $data = array(
                    "idTurno" => $idTurno, 
                    "idUsuario" => $idUsuario, 
                    "hora_entrada" => $hora_entrada, 
                    "hora_salida" => $hora_salida,
                    "idArea1" => $idArea1,
                    "idArea2" => $idArea2,
                    "idArea3" => $idArea3,
                    "idArea4" => $idArea4,
                    "idArea5" => $idArea5                    
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'turnos/actualizar_turno.php');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                $result = curl_exec($ch);
                curl_close($ch);
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
                }        
                redirect('/index.php/turnos_controller/mostrarTurnos');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function eliminarTurno($idTurno) {
        if ($this->is_logged_in()){
            $data = array("idTurno" => $idTurno);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'turnos/borrar_turno.php');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            $result = curl_exec($ch);
            curl_close($ch);
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro eliminado correctamente <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se eliminó el registro <br>");
            }        
            redirect('/index.php/turnos_controller/mostrarTurnos');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function nuevoTurno() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'usuarios' => $this->usuariosGlobal,
                'areas' => $this->areasSimple,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                 'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '7'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('turnos/nuevoTurno_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoTurnoFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idUsuario = $this->input->post("idUsuario");
                $hora_entrada = $this->input->post("hora_entrada");
                $hora_salida = $this->input->post("hora_salida");
                
                $idArea1 = $this->input->post("idArea1");
                $idArea2 = $this->input->post("idArea2");
                $idArea3 = $this->input->post("idArea3");
                $idArea4 = $this->input->post("idArea4");
                $idArea5 = $this->input->post("idArea5");
                
                if ($idArea2 == "") {
                    $idArea2 = 17;
                }
                if ($idArea3 == "") {
                    $idArea3 = 17;
                }
                if ($idArea4 == "") {
                    $idArea4 = 17;
                }
                if ($idArea5 == "") {
                    $idArea5 = 17;
                }
                $data = array(
                    "idUsuario" => $idUsuario, 
                    "hora_entrada" => $hora_entrada, 
                    "hora_salida" => $hora_salida,
                    "idArea1" => $idArea1,
                    "idArea2" => $idArea2,
                    "idArea3" => $idArea3,
                    "idArea4" => $idArea4,
                    "idArea5" => $idArea5
                    
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'turnos/insertar_turno.php');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                $result = curl_exec($ch);
                curl_close($ch);
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro guardado <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se guardó el registro <br>");
                }        
                redirect('/index.php/turnos_controller/mostrarTurnos');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    //Fin llamada a webservices de usuarios
    
    //Importar desde Excel con libreria de PHPExcel
    public function importarUsersExcel(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '7'
                );
            $this->load->view('layouts/header_view',$data);
            $this->load->view('usuarios/importarUsersFromExcel_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }        

    //Importar desde Excel con libreria de PHPExcel
    public function importarExcel(){
        if ($this->is_logged_in()){
            //Cargar PHPExcel library
            $this->load->library('excel');
            $name   = $_FILES['excel']['name'];
            $tname  = $_FILES['excel']['tmp_name'];
            $obj_excel = PHPExcel_IOFactory::load($tname);       
            $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
            $arr_datos = array();
            $result;
            foreach ($sheetData as $index => $value) {            
                if ( $index != 1 ){
                    $arr_datos = array(
                            'usuario' => $value['A'],
                            'clave' => $value['B'],
                            'permisos' => $value['C'],
                            'nombre' => $value['D'],
                            'apellido_paterno' => $value['E'],
                            'apellido_materno' => $value['F'],
                            'telefono_casa' => $value['G'],
                            'telefono_celular' => $value['H'],
                            'idSucursal' => $value['I']
                    ); 
                    foreach ($arr_datos as $llave => $valor) {
                        $arr_datos[$llave] = $valor;
                    }
                    //$this->db->insert('usuarios',$arr_datos);

                    //Llamada de ws para insertar
                    $data_string = json_encode($arr_datos);
                    $ch = curl_init(RUTAWS.'usuarios/insertar_usuario.php');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_string))
                    );
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    //execute post
                    $result = curl_exec($ch);
                    //close connection
                    curl_close($ch);
                    //echo $result;
                } 
            }
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro guardado <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se guardó el registro <br>");
            }        
            redirect('/usuarios_controller/mostrarUsuarios');
        } else {
            redirect($this->cerrarSesion());
        }
    }        
    //Fin Importar desde Excel con libreria de PHPExcel
    
    function telegram($msg) {
        // Set your Bot ID and Chat ID.
        //https://api.telegram.org/bot
        //1676714028:AAH0aWRKqqhVIauq-5M6B7Fqz0AundeBdT0/getUpdates        
        $telegrambot='1676714028:AAH0aWRKqqhVIauq-5M6B7Fqz0AundeBdT0';
        $telegramchatid=1539275351;
//        $telegrambot='1591382343:AAGTgrUiF8j0AF-SQu6pxf-06nVmnCHjTX0';
//        $telegramchatid=1539275351;

        //global $telegrambot,$telegramchatid;

        $url='https://api.telegram.org/bot'.$telegrambot.'/sendMessage';$data=array('chat_id'=>$telegramchatid,'text'=>$msg);
        $options=array('http'=>array('method'=>'POST','header'=>"Content-Type:application/x-www-form-urlencoded\r\n",'content'=>http_build_query($data),),);
        $context=stream_context_create($options);
        $result=file_get_contents($url,false,$context);
        return $result;
    }
    
    
    function enviaTelegram() {
        $numeroRequisicion = rand();
        $this->telegram ("Numero Requisición: ".$numeroRequisicion);
        $this->session->set_flashdata('correcto', "Mensaje enviado. <br>");
        redirect('/index.php/inventariotoolcrib_controller/mostrarInventario');
    }    
    
    function layoutParos(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'usuarios' => $this->usuariosGlobal,
                'idUsuario' => $this->session->userdata('idUsuario'),
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => 'Mantenimientos'
                    );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('turnos/layout_view',$data);
            $this->load->view('layouts/pie_view',$data);            
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarGraficoTecnico($idUsuarioTecnico){
        if ($this->is_logged_in()){
            $fecha1="";
            $fecha2="";
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d");                 
            $ddate = $fechaIngreso;
            $date = new DateTime($ddate);
            $week = $week = $date->format("W")."|";
            $idResponsable = $idUsuarioTecnico;
            $idMaquina = 0;
            if ($this->input->post('submit')){
                $idMaquina = $this->input->post("maquinaConsulta");
                $idResponsable = $this->input->post("responsable");
                $fecha1 = $this->input->post("fecha1");
                $fecha2 = $this->input->post("fecha2");
            }
            
            //echo "--->".$fecha1."-".$fecha2;
            $url = RUTAWS.'turnos/obtener_notificaciones_consulta.php?idResponsable='.$idResponsable."&week=".$week."&idMaquina=".$idMaquina."&fecha1=".$fecha1."&fecha2=".$fecha2;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array Usuarios operadores(lideres supervisores)
            $usuariosArrayOperadores = array();
            $j = 0;
            foreach ($this->usuariosOperadoresGlobal as $usuario){
                $usuariosArrayOperadores[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuarioOperador'};
                $j++;
            }    
            
            //obtiene nombre del responsable di lo hay
            $idResponsableMaq = 0;
            if ($idMaquina) {
                foreach ($this->maquinasSimple as $maquina){
                    if ($idMaquina == $maquina->{'idMaquina'}) {
                        $idResponsableMaq = $maquina->{'responsable_maquina'};
                    }
                }
            }
            
            
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'idUsuarioTecnico' => $idUsuarioTecnico,
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'usuariosArrayOperadores' => $usuariosArrayOperadores,
                        'maquinasArray' => $maquinasArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'paros'=>$datos->{'paros'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('turnos/graficoTecnicoNotificaciones_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'idUsuarioTecnico' => $idUsuarioTecnico,
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        
                        'paros'=>null,
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('turnos/graficoTecnicoNotificaciones_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'idUsuarioTecnico' => $idUsuarioTecnico,
                    'idMaquina' => $idMaquina,
                    'idResponsable' => $idResponsable, 
                    'idResponsableMaq' => $idResponsableMaq,
                    
                    'paros'=>null,
                    'maquinasSimple' => $this->maquinasSimple,
                    'usuarios' => $this->usuariosGlobal,
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => $week,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/graficoTecnicoNotificaciones_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function consultaParosGraficas(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d");
            
            //trae los proyectos
            $url = RUTAWS.'proyectos/obtener_proyectos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataProyectos = curl_exec($ch);
            $datosProyectos = json_decode($dataProyectos);
            curl_close($ch);

            $fecha1 = $fechaIngreso;
            $fecha2 = $fechaIngreso;
            $ddate = $fechaIngreso;
            $date = new DateTime($ddate);
            $idProyecto = 0;
            if ($this->input->post('submit')){
                $fecha1 = $this->input->post("fecha1");
                $fecha2 = $this->input->post("fecha2");
            }
            
            //echo "--->".$fecha1."-".$fecha2;
            $url = RUTAWS.'turnos/obtener_notificaciones_consulta_graficos.php?fecha1='.$fecha1.'&fecha2='.$fecha2;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array Usuarios operadores(lideres supervisores)
            $usuariosArrayOperadores = array();
            $j = 0;
            foreach ($this->usuariosOperadoresGlobal as $usuario){
                $usuariosArrayOperadores[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuarioOperador'};
                $j++;
            }    
            
            /*
            //obtiene nombre del responsable di lo hay
            $idResponsableMaq = 0;
            if ($idMaquina) {
                foreach ($this->maquinasSimple as $maquina){
                    if ($idMaquina == $maquina->{'idMaquina'}) {
                        $idResponsableMaq = $maquina->{'responsable_maquina'};
                    }
                }
            }*/
            
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            //echo sizeof($datos->{'paros'});
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'proyectos'=>$datosProyectos->{'proyectos'},
                        'categorias'=>$this->categoriasGlobal,
                        'areas' => $this->areasSimple,
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'usuariosArrayOperadores' => $usuariosArrayOperadores,
                        'maquinasArray' => $maquinasArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'paros'=>$datos->{'paros'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('turnos/consultaParosGraficas_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'proyectos'=>$datosProyectos->{'proyectos'},
                        'categorias'=>$this->categoriasGlobal,
                        'areas' => $this->areasSimple,
                        'paros'=>null,
                        'areas' => $this->areasSimple,
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('turnos/consultaParosGraficas_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'categorias'=>$this->categoriasGlobal,
                    'areas' => $this->areasSimple,
                    'paros'=>null,
                    'maquinasSimple' => $this->maquinasSimple,
                    'usuarios' => $this->usuariosGlobal,
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/consultaParosGraficas_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    //Exportar datos a Excel
    public function exportarExcelLider(){
        if ($this->is_logged_in()){
            /****/
            $url = RUTAWS.'proyectos/obtener_proyectos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataProyectos = curl_exec($ch);
            $datosProyectos = json_decode($dataProyectos);
            curl_close($ch);
            
            //Llena array Proyectos
            $arrayProyectos = array();
            $j = 0;
            foreach ($datosProyectos->{'proyectos'} as $proyecto){
                $arrayProyectos[$proyecto->{'descripcion_proyecto'}] = "".$proyecto->{'idProyecto'};
                $j++;
            } 
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }             
            
            $idUsuarioOperador = $this->session->userdata('idUsuarioOperador');
            $url = RUTAWS.'notificaciones/obtener_notificaciones_lider_general.php?idUsuarioOperador='.$idUsuarioOperador;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            $nilai=$datos->{'notificaciones'};
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            $heading=array('Descripción de Falla','Respuesta del Técnico','Inicio Atención','Fin Atención','Duración','Técnico'
                ,'Status','Calificación',"Observ. Técnico","Proyecto");
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Notificaciones");
            $rowNumberH = 1;
            $colH = 'A';
            foreach($heading as $h){
                $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
                $colH++;    
            }
            $maxrow=$totn+1;
            $row = 2;
            $no = 1;
            foreach($nilai as $n){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'mensajeOperador'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$n->{'mensajeTecnico'});
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'fechaInicioAtencion'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'fechaFinAtencion'});
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'tiempoAtencion'});
                
                $posicionContenidoUsuario = array_search($n->{'idUsuarioTecnico'},$usuariosArray);
                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                   
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$elemArrayUsu[0]);
                //etapa
                if ($n->{'estado'}==4) {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,"Finalizado");
                }
                if ($n->{'estado'}==3) {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,"En proceso");
                }
                if ($n->{'estado'}==2) {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,"Técnico enterado");
                }
                if ($n->{'estado'}==0) {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,"Iniciado");
                }
                //evaluacion
                if ($n->{'calificacionAtencion'} == 10) {
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,"Excelente");
                }
                if ($n->{'calificacionAtencion'} == 8) {
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,"Bien");
                }
                if ($n->{'calificacionAtencion'} == 7) {
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,"Regular");
                }
                if ($n->{'calificacionAtencion'} == 5) {
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,"Mala");
                }
                
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$n->{'observaciones_tecnico'});
                
                $posicionContenidoUsuario = array_search($n->{'idProyecto'},$arrayProyectos);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row,$posicionContenidoUsuario);  
                
                $row++;
                $no++;
            }
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            $styleArray = array(
                    'borders' => array(
                            'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                    )
            );
            $objPHPExcel->getActiveSheet()->getStyle('A1:J'.$maxrow)->applyFromArray($styleArray);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Notificaciones.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }	
    //fin exportar a excel

    function mostrarSalidasComedor() {
        if ($this->is_logged_in()){
            $url = RUTAWS.'turnos/obtener_salidas_comedor.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if ($datos->{'estado'}==1) {
                $data = array(
                    'salidas'=>$datos->{'salidas'},
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'turnos'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/adminSalidasComedor_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'salidas'=>null,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'turnos'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('turnos/adminSalidasComedor_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    //Exportar datos a Excel
    public function exportarExcelParosAdmin(){
        if ($this->is_logged_in()){
            $paros = unserialize($this->input->post("parosHiddenExcel"));
            $nilai = $paros;
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            //echo $totn;
            $heading=array('id',"Msg Operador","Msg Técnico","Estado","Not.Técnico","Not.Lider","Inicio Atención"
                ,"Fin Atención","Duración","Técnico","Solicitante","Calificación","Falla","Observ. Técnico","Proyecto");
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Paros");
            $rowNumberH = 1;
            $colH = 'A';
            foreach($heading as $h){
                $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
                $colH++;    
            }
            //$totn=$nilai->num_rows();
            $maxrow=$totn+1;
            $row = 2;
            $no = 1;
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            /****/
            $url = RUTAWS.'usuarios_turnos/obtener_usuariosturnos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataLideres = curl_exec($ch);
            $datosLideres = json_decode($dataLideres);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArrayLideres = array();
            $j = 0;
            foreach ($datosLideres->{'usuarios'} as $usuario){
                $usuariosArrayLideres[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuarioOperador'};
                $j++;
            }               

            /****/
            $url = RUTAWS.'proyectos/obtener_proyectos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataProyectos = curl_exec($ch);
            $datosProyectos = json_decode($dataProyectos);
            curl_close($ch);
            
            //Llena array Proyectos
            $arrayProyectos = array();
            $j = 0;
            foreach ($datosProyectos->{'proyectos'} as $proyecto){
                $arrayProyectos[$proyecto->{'descripcion_proyecto'}] = "".$proyecto->{'idProyecto'};
                $j++;
            }               
            
            foreach($nilai as $n){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'id'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$n->{'mensajeOperador'});
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'mensajeTecnico'});
                if ($n->{'estado'}==4) {
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"Finalizado");
                }
                if ($n->{'estado'}==3) {
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"En proceso");
                }
                if ($n->{'estado'}==2) {
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"Técnico enterado");
                }
                if ($n->{'estado'}==0) {
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"Iniciado");
                }
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'fechaEnvioNotificacionAlTecnico'});
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$n->{'fechaEnvioNotificacionAlOperador'});

                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'fechaInicioAtencion'});
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$n->{'fechaFinAtencion'});
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$n->{'tiempoAtencion'});
                //obtiene nombre tecnico
                $posicionContenidoUsuario = array_search($n->{'idUsuarioTecnico'},$usuariosArray);
                $elemArrayUsu = explode("|", $posicionContenidoUsuario);                   
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row,$elemArrayUsu[0]);      
                //obtiene nombre lider
                $posicionContenidoUsuario = array_search($n->{'idUsuarioOperador'},$usuariosArrayLideres);
                $elemArrayUsuLideres = explode("|", $posicionContenidoUsuario);
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$row,$elemArrayUsuLideres[0]);
                //calificacion
                if ($n->{'calificacionAtencion'} == 10) {
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$row,"Excelente");
                }
                if ($n->{'calificacionAtencion'} == 8) {
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$row,"Bien");
                }
                if ($n->{'calificacionAtencion'} == 7) {
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$row,"Regular");
                }
                if ($n->{'calificacionAtencion'} == 5) {
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$row,"Mala");
                }
                
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$row,$n->{'desc_falla'});
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$row,$n->{'observaciones_tecnico'});
                
                $posicionContenidoUsuario = array_search($n->{'idProyecto'},$arrayProyectos);
                $objPHPExcel->getActiveSheet()->setCellValue('O'.$row,$posicionContenidoUsuario);  
                
                //$objPHPExcel->getActiveSheet()->setCellValue('O'.$row,$n->{'idProyecto'});
                $row++;
                $no++;
            }
            //Freeze pane
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            //Cell Style
            $styleArray = array();            
            $objPHPExcel->getActiveSheet()->getStyle('A1:O'.$maxrow)->applyFromArray($styleArray);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Paros.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }	
    
    //**  Manejo de Sesiones
    function cerrarSesion() {
        $this->session->set_userdata('logueado',FALSE);
        $this->session->sess_destroy();
        $this->load->view('login_view');
    }
    
    function cerrarSesionQR() {
        $this->session->set_userdata('logueado',FALSE);
        $this->session->sess_destroy();
        $data = array('numero_parte'=> 0);
        $this->load->view('loginFromQR_view',$data);
    }

    function is_logged_in() {
        return $this->session->userdata('logueado');
    }
    //**  Fin Manejo de Sesiones
    
    
}

