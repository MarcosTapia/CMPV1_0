<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mantenimiento_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuariosGlobal;
    private $areasGlobal;
    private $maquinasGlobal;
    private $maquinasGlobal2;
    private $maquinasSimple;
    private $actividadesGlobal;
    private $mantenimientosSimple;
    private $areasSimple;

    private $usuarioHerramental;
    
    private $gatewayRest;
    
    function __construct(){
        parent::__construct();
        $this->load->model('sistema_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->error = "";
        
        //para subir imagenes
        $this->load->helper("URL", "DATE", "URI", "FORM");
        $this->load->library('upload');
        //$this->load->model('mupload_model');    
        
        //$this->sucursalesGlobal = $this->cargaDatosSucursales();
        
        //llamado a controlador util global
        $this->load->library('../controllers/util_controller');
        $this->load->library('../controllers/usuarios_controller');
        
        $this->datosEmpresaGlobal = $this->util_controller->cargaDatosEmpresa();
        $this->nombreEmpresaGlobal = $this->datosEmpresaGlobal[0]->{'nombreEmpresa'};
        $this->usuariosGlobal = $this->util_controller->cargaDatosUsuarios();
        $this->areasGlobal = $this->util_controller->cargaDatosAreas();
        $this->maquinasGlobal = $this->util_controller->cargaDatosMaquinas();
        $this->maquinasGlobal2 = $this->util_controller->cargaDatosMaquinasActivas();
        $this->actividadesGlobal = $this->util_controller->cargaDatosActividades();
        
        $this->maquinasSimple = $this->util_controller->cargaDatosMaquinasSimple();
        $this->mantenimientosSimple = $this->util_controller->cargaDatosMantenimientosSimple();
        
        $this->usuarioHerramental = $this->util_controller->obtenerUsuarioHerramental();
        $this->areasSimple = $this->util_controller->cargaDatosAreas();
        
//        $cadena = file_get_contents('bd.txt');
//        $this->gatewayRest =  $cadena;
        
    }
    
    function index(){
        $this->load->view('login_view');
    }
    
    function inicio() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                    'permisos'=>$this->session->userdata('permisos'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'opcionClickeada' => 'Inicio'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('principal_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }    
    
    function mostrarMantenimientosLista() {
        if ($this->is_logged_in()){
            $url = RUTAWS.'mantenimientos/obtener_mantenimientos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            $maquinas;
            //echo $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    $data = array(
                        'parametrosConsulta' => "",
                        'maquinas2' => $this->maquinasGlobal2,
                        'usuarios' => $this->usuariosGlobal,
                        'actividades'=> $this->actividadesGlobal,
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    if ($this->session->userdata('permisos') == "Administrador") {
                        $this->load->view('layouts/headerAdministrador_view',$data);
                        $this->load->view('mantenimientos/adminMantenimientosLista_view',$data);
                    } 
                    if ($this->session->userdata('permisos') == "Técnico") {
                        redirect('/index.php/actividades_controller/mostrarActividades');
                    }                         
                    //$this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'parametrosConsulta' => "",
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    if ($this->session->userdata('permisos') == "Administrador") {
                        $this->load->view('layouts/headerAdministrador_view',$data);
                        $this->load->view('mantenimientos/adminMantenimientosLista_view',$data);
                    } 
                    if ($this->session->userdata('permisos') == "Técnico") {
                        redirect('/index.php/actividades_controller/mostrarActividades');
                    }                     
                     $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'parametrosConsulta' => "",
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                if ($this->session->userdata('permisos') == "Administrador") {
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosLista_view',$data);
                } 
                if ($this->session->userdata('permisos') == "Técnico") {
                    redirect('/index.php/actividades_controller/mostrarActividades');
                }
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarMantenimientos() {
        if ($this->is_logged_in()){
            $url = RUTAWS.'mantenimientos/obtener_mantenimientos_simple.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            $maquinas;
            //echo $data;   
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }            

            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    
            
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    $data = array(
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'parametrosConsulta' => "",
                        'maquinas2' => $this->maquinasGlobal2,
                        'usuarios' => $this->usuariosGlobal,
                        'actividades'=> $this->actividadesGlobal,
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    if ($this->session->userdata('permisos') == "Administrador") {
                        $this->load->view('layouts/headerAdministrador_view',$data);
                        $this->load->view('mantenimientos/adminMantenimientos_view',$data);
                    } 
                    if ($this->session->userdata('permisos') == "Técnico") {
                        redirect('/index.php/actividades_controller/mostrarActividades');
                    }                         
                    //$this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'parametrosConsulta' => "",
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    if ($this->session->userdata('permisos') == "Administrador") {
                        $this->load->view('layouts/headerAdministrador_view',$data);
                        $this->load->view('mantenimientos/adminMantenimientos_view',$data);
                    } 
                    if ($this->session->userdata('permisos') == "Técnico") {
                        redirect('/index.php/actividades_controller/mostrarActividades');
                    }                     
                     $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'parametrosConsulta' => "",
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                if ($this->session->userdata('permisos') == "Administrador") {
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientos_view',$data);
                } 
                if ($this->session->userdata('permisos') == "Técnico") {
                    redirect('/index.php/actividades_controller/mostrarActividades');
                }
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function nuevoMantenimientoFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $nuevosMantenimientos = $this->input->post("calendarioFinalHidden");
                $data = array(
                    'nuevosMantenimientos' => $nuevosMantenimientos
                    );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'mantenimientos/insertar_mantenimiento.php');
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
                    $this->session->set_flashdata('correcto', "Informaci&oacute;n guardada correctamente. <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se guardó la Información, probablemente ya la guardaste antes. <br>");
                }        
                redirect('/index.php/mantenimiento_controller/mostrarMantenimientosLista'); 
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }    
    
    function actualizarMantenimiento($idFechaMantenimiento) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_por_id.php?idFechaMantenimiento='.$idFechaMantenimiento;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            if ($datos->{'estado'}==1) {
                $data = array(
                    'usuarios'=>$this->usuariosGlobal,
                    'maquinas'=>$this->maquinasGlobal,
                    'actividades'=> $this->actividadesGlobal,
                    'mantenimiento'=>$datos->{'mantenimiento'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('mantenimientos/actualizaMantenimiento_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarMantenimientoFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idFechaMantenimiento = $this->input->post("idFechaMantenimiento");
                $fechaMantenimiento = $this->input->post("fechaMantenimiento");
                $condicion_maquina = $this->input->post("condicionM");
                $idResponsable = $this->input->post("responsableMaquina");
                //$idMaquina = $this->input->post("maquinas");
                //$numero_maquina = $this->input->post("numero_maquina");
                $condicion_maquina = $this->input->post("condicion");
                //$idActividad = $this->input->post("actividades");
                $observaciones_maquina = $this->input->post("observaciones");  
                $ruta = $this->input->post("ruta");
//                echo $idFechaMantenimiento." - ".$fechaMantenimiento." - ".$idResponsable." - ".$idMaquina." - ".$condicion_maquina." - ".$observaciones_maquina;
                $data = array(
                    "idFechaMantenimiento" => $idFechaMantenimiento,
                    "fechaMantenimiento" => $fechaMantenimiento,
                    "idResponsable" => $idResponsable, 
                    "condicion_maquina" => $condicion_maquina, 
                    "observaciones_maquina" => $observaciones_maquina,
                    "ruta" => $ruta
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento.php');
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
                //echo $result;
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
                }        
                //redirect('/index.php/mantenimiento_controller/mostrarMantenimientos');
                redirect('/index.php/mantenimiento_controller/consultaMantenimientos');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function eliminarMantenimiento($idFechaMantenimiento) {
        if ($this->is_logged_in()){
            $data = array("idFechaMantenimiento" => $idFechaMantenimiento);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'mantenimientos/borrar_mantenimiento.php');
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
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro eliminado correctamente <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se eliminó el registro <br>");
            }        
            redirect('/index.php/mantenimiento_controller/mostrarMantenimientos');
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function mostrarMantenimientosMesUsuario() {
        if ($this->is_logged_in()) {
            $week;
            if ($this->input->post('submit')){
//                $ddate = $this->input->post("fechaConsulta");
//                $date = new DateTime($ddate);
//                $week = $date->format("W");   
                $week = $this->input->post("fechaConsulta");
                //echo "------> ".$week;
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                $week = $date->format("W");
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_semana_usuario.php?idUsuario='.$this->session->userdata('idUsuario')."&week=".$week;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    $data = array(
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosTecnicoMes_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosTecnicoMes_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('mantenimientos/adminMantenimientosTecnicoMes_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function mostrarMantenimientosAdminFiltrados() {
        if ($this->is_logged_in()) {
            $idMaquina = $this->input->post("nombreMaquinaHidden");
            $numero_maquina = $this->input->post("numero_maquina");
            $idActividad = $this->input->post("cbo_actividades");
            $idResponsable = $this->input->post("responsables");
            $week1 = $this->input->post("semana1");
            $week2 = $this->input->post("semana2");
            $parametrosConsulta = $idMaquina."|".$numero_maquina."|".$idActividad."|".$idResponsable."|".$week1."|".$week2."|";
            //echo "maq: ".$idMaquina." nummaq: ".$numero_maquina." actvs: ".$idActividad." resp: ".$idResponsable." sem1: ".$week1." sem2: ".$week2;
//            echo $parametrosConsulta;
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_personalizado.php?parametrosConsulta='.$parametrosConsulta;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    $data = array(
                        'parametrosConsulta' => $parametrosConsulta,
                        'maquinas2' => $this->maquinasGlobal2,
                        'usuarios' => $this->usuariosGlobal,
                        'actividades'=> $this->actividadesGlobal,
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week1' => $week1,
                        'week2' => $week2,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientos_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'parametrosConsulta' => $parametrosConsulta,
                        'maquinas2' => $this->maquinasGlobal2,
                        'usuarios' => $this->usuariosGlobal,
                        'actividades'=> $this->actividadesGlobal,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week1' => $week1,
                        'week2' => $week2,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientos_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'parametrosConsulta' => $parametrosConsulta,
                    'maquinas2' => $this->maquinasGlobal2,
                    'usuarios' => $this->usuariosGlobal,
                    'actividades'=> $this->actividadesGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('mantenimientos/adminMantenimientos_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function mostrarGraficas() {
        if ($this->is_logged_in()){
            $week;
            $idResponsable = 0;
            $idMaquina = 0;
            //echo "Azul: ".$this->input->post("responsable");
            if ($this->input->post('submit')){
                $idMaquina = $this->input->post("maquinaConsulta");
                $idResponsable = $this->input->post("responsable");
                $week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                //$week = ($date->format("W")-1)."|";
                $week = $date->format("W")."|";
                //$week = str_replace("0", "", $week);
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimientos_consulta.php?idResponsable='.$idResponsable."&week=".$week."&idMaquina=".$idMaquina;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
//            echo $data;
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    
            
            
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            //obtiene nombre del responsable di lo hay
            $idResponsableMaq = 0;
            if ($idMaquina) {
                foreach ($this->maquinasSimple as $maquina){
                    if ($idMaquina == $maquina->{'idMaquina'}) {
                        $idResponsableMaq = $maquina->{'responsable_maquina'};
                    }
                }
            }
            
            
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Graficas'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    //$this->load->view('mantenimientos/consultaMantenimientos_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosGraficas_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable,                        
                        'idResponsableMaq' => $idResponsableMaq,
                        
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    //$this->load->view('mantenimientos/consultaMantenimientos_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosGraficas_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'idMaquina' => $idMaquina,
                    'idResponsable' => $idResponsable,                        
                    'idResponsableMaq' => $idResponsableMaq,
                    
                    'maquinasSimple' => $this->maquinasSimple,
                    'usuarios' => $this->usuariosGlobal,
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => $week,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                //$this->load->view('mantenimientos/consultaMantenimientos_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosGraficas_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function mostrarMantenimientosAdminFiltradosGraficas() {
        if ($this->is_logged_in()) {
            $idMaquina = $this->input->post("nombreMaquina");
            $numero_maquina = $this->input->post("numero_maquina");
            $idActividad = $this->input->post("actividades");
            $idResponsable = $this->input->post("responsables");
            $week1 = $this->input->post("semana1");
            $week2 = $this->input->post("semana2");
            $parametrosConsulta = $idMaquina."|".$numero_maquina."|".$idActividad."|".$idResponsable."|".$week1."|".$week2."|";
            //echo "maq: ".$idMaquina." nummaq: ".$numero_maquina." actvs: ".$idActividad." resp: ".$idResponsable." sem1: ".$week1." sem2: ".$week2;
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_personalizado.php?parametrosConsulta='.$parametrosConsulta;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    $data = array(
                        'maquinas2' => $this->maquinasGlobal2,
                        'usuarios' => $this->usuariosGlobal,
                        'actividades'=> $this->actividadesGlobal,
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week1' => $week1,
                        'week2' => $week2,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosGraficas_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'maquinas2' => $this->maquinasGlobal2,
                        'usuarios' => $this->usuariosGlobal,
                        'actividades'=> $this->actividadesGlobal,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week1' => $week1,
                        'week2' => $week2,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosGraficas_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'maquinas2' => $this->maquinasGlobal2,
                    'usuarios' => $this->usuariosGlobal,
                    'actividades'=> $this->actividadesGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('mantenimientos/adminMantenimientosGraficas_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    //Exportar datos a Excel
    public function exportarExcel(){
        if ($this->is_logged_in()){
            $mantenimientos = unserialize($this->input->post("mantenimientosHidden"));
            $nilai = $mantenimientos;
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            //echo $totn;
            
            $heading=array('id','Fecha', "Responsable","Actividad","Máquina","Número Máquina","Status","Observaciones");
            $this->load->library('excel');
            //Create a new Object
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Mantenimientos");
            //Loop Heading
            $rowNumberH = 1;
            $colH = 'A';
            foreach($heading as $h){
                $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
                $colH++;    
            }
            //Loop Result
            //$totn=$nilai->num_rows();
            $maxrow=$totn+1;
            $row = 2;
            $no = 1;
            
            //Llena array Usuarios
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
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            foreach($nilai as $n){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'idFechaMantenimiento'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$n->{'fechaMantenimiento'});

                //busca en array de usuarios para desplegar el responsable
                $posicionContenidoUsuario = array_search($n->{'idResponsable'},$usuariosArray);
                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$elemArrayUsu[0]);
                
                //busca en array de actividades para desplegar la descripcion de la actividad
                $posicionContenidoActividad = array_search($n->{'idActividad'},$actividadesArray);
                $elemArrayAct = explode("|", $posicionContenidoActividad);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$elemArrayAct[0]);
                
                //busca en array de maquinas para desplegar nombre y numero de maquina
                $posicionContenidoMaquina = array_search($n->{'idMaquina'},$maquinasArray);
                $elemArrayMaq = explode("|", $posicionContenidoMaquina);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$elemArrayMaq[0]);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$elemArrayMaq[1]);
                
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'condicion_maquina'});
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$n->{'observaciones_maquina'});
                $row++;
                $no++;
            }
            //Freeze pane
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            //Cell Style
            $styleArray = array(

            );
            
            $objPHPExcel->getActiveSheet()->getStyle('A1:H'.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Mantenimientos.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }	
    //fin exportar a excel
    
    public function verManttoPdf() {
        if ($this->is_logged_in()){
            /**************** nuevo *************************/
            $parametrosConsulta = $this->input->post("parametrosConsulta");
            $mantenimientos = unserialize($this->input->post("mantenimientosHidden"));
            $titulo;
            $titulo2 = "";
            /**************** nuevo *************************/
            $titulo = 'Reporte de Mantenimientos'; 
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    
            
            
            $titulo = utf8_decode($titulo);
            require_once APPPATH.'third_party/fpdf/fpdf.php';
            if ($mantenimientos != null) {
                $nilai = $mantenimientos;
                $pdf = new FPDF();
                $pdf->AddPage('L','A4',0);
                $rutaimagen = base_url()."images/logo3.png";
                $pdf->Image($rutaimagen,25,10,33);

                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(75);
                $pdf->Cell(150,45,$titulo,0,1,'C');  
                
                if ($titulo2 != "") {
                    $pdf->Cell(75);
                    $pdf->Cell(150,-30,$titulo2,0,1,'C');  
                    $renglonTitulos = 46;
                    $renglonOriginal = 56;
                    $inicio = 30;
                } else {
                    $renglonTitulos = 36;
                    $renglonOriginal = 46;
                    $inicio = 30;
                }

                $pdf->SetFont('Times','B',12);
                $pdf->SetXY(30,$renglonTitulos);
                $pdf->Cell(20,10,"Frec.",1,1);
                $pdf->SetXY(50,$renglonTitulos);
                $pdf->Cell(40,10,"Responsable",1,1);
                $pdf->SetXY(90,$renglonTitulos);
                $pdf->Cell(50,10,"Actividad",1,1);                
                $pdf->SetXY(140,$renglonTitulos);
                $pdf->Cell(45,10,utf8_decode("Máquina"),1,1);                
                $pdf->SetXY(185,$renglonTitulos);
                $pdf->Cell(20,10,"No. Maq.",1,1);                
                $pdf->SetXY(205,$renglonTitulos);
                $pdf->Cell(15,10,"Status",1,1);                
                $pdf->SetXY(220,$renglonTitulos);
                $pdf->Cell(45,10,"Observaciones",1,1);                

                $pdf->SetFont('Times','',12);
                $renglon = 0;
                
                $cantidadRegistros = 0;
                if (isset($mantenimientos)) {
                    $cantidadRegistros = sizeof($mantenimientos);
                }
                $contRegs = 0;
                foreach ($mantenimientos as $fila){
                    $contRegs++;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    $pdf->Cell(20,10,substr($fila->{'fechaMantenimiento'},0,9),1,1);
                    $inicio = $inicio + 20;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    
                    //busca en array de usuarios para desplegar el responsable
                    $posicionContenidoUsuario = array_search($fila->{'idResponsable'},$usuariosArray);
                    $elemArrayUsu = explode("|", $posicionContenidoUsuario);                    
                    $pdf->Cell(40,10,substr($elemArrayUsu[0],0,20)."..",1,1);
                    
                    $inicio = $inicio + 40;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    
                    //busca en array de actividades para desplegar la descripcion de la actividad
                    $posicionContenidoActividad = array_search($fila->{'idActividad'},$actividadesArray);
                    $elemArrayAct = explode("|", $posicionContenidoActividad);
                    if (strlen($elemArrayAct[0]) <= 28) {
                        $pdf->Cell(50,10,utf8_decode($elemArrayAct[0]),1,1);
                    } else {
                        $pdf->Cell(50,10,utf8_decode(substr($elemArrayAct[0],0,22))."..",1,1);
                    }
                    
                    
                    $inicio = $inicio + 50;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    
                    
                    //busca en array de maquinas para desplegar nombre y numero de maquina
                    $posicionContenidoMaquina = array_search($fila->{'idMaquina'},$maquinasArray);
                    $elemArrayMaq = explode("|", $posicionContenidoMaquina);                    
                    $pdf->Cell(45,10,utf8_decode(substr($elemArrayMaq[0],0,16))."...",1,1);
                    
                    
                    $inicio = $inicio + 45;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    $pdf->Cell(20,10,$elemArrayMaq[1],1,1); //$fila->{'numero_maquina'}
                    $inicio = $inicio + 20;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    if ($fila->{'condicion_maquina'} == "NO ATENDIDA") {
                        $pdf->Cell(15,10,"NA",1,1);
                    } else {
                        $pdf->Cell(15,10,"AT",1,1);
                    }
                    $inicio = $inicio + 15;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    //$inicio = 45;
                    $pdf->Cell(45,10,utf8_decode(substr($fila->{'observaciones_maquina'},0,16)),1,1);
                    if ($renglon < 11) {
                        $renglon++;
                        $inicio = 30;
                    } else {
                        // Print centered page number
                        $pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
                        
                        if ($contRegs < $cantidadRegistros) {
                            $pdf->AddPage('L','A4',0);
                            $rutaimagen = base_url()."images/logo3.png";
                            $pdf->Image($rutaimagen,25,10,33);

                            $pdf->SetFont('Arial','B',16);
                            $pdf->Cell(75);

                            $pdf->Cell(150,45,$titulo,0,1,'C');      

                            $pdf->SetFont('Times','B',12);
                            $pdf->SetXY(30,$renglonTitulos);
                            $pdf->Cell(20,10,"Frec.",1,1);
                            $pdf->SetXY(50,$renglonTitulos);
                            $pdf->Cell(40,10,"Responsable",1,1);
                            $pdf->SetXY(90,$renglonTitulos);
                            $pdf->Cell(50,10,"Actividad",1,1);                
                            $pdf->SetXY(140,$renglonTitulos);
                            $pdf->Cell(45,10,utf8_decode("Máquina"),1,1);                
                            $pdf->SetXY(185,$renglonTitulos);
                            $pdf->Cell(20,10,"No. Maq.",1,1);                
                            $pdf->SetXY(205,$renglonTitulos);
                            $pdf->Cell(15,10,"Status",1,1);                
                            $pdf->SetXY(220,$renglonTitulos);
                            $pdf->Cell(45,10,"Observaciones",1,1);                

                            $pdf->SetFont('Times','',12);
                            $renglonOriginal = 46;
                            $renglon = 0;     $inicio = 30;                   
                        }                
                    }                    
                }      
            } else { //si no hay registros por mostrar
                $pdf = new FPDF();
                $pdf->AddPage('L','A4',0);
                $rutaimagen = base_url()."images/logo3.png";
                $pdf->Image($rutaimagen,25,10,33);

                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(75);
                $pdf->Cell(150,45,$titulo,0,1,'C');      
                // Print centered page number
                $pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');                
            }
            $pdf->Output('mantenimientos.pdf' , 'I' );
        }
    }    
    
    public function verManttoPdf2() {
        if ($this->is_logged_in()) {
            $idMaquina = $this->input->post("maquinaHidden");
            $mes = $this->input->post("mesHidden");
            $semana1 = "";
            $semana2 = "";
            $mesString = "";
            $anioString = "2021";
            switch ($mes){//verifica posicion con mes para limites de semana
                case 1:
                    $semana1 = 1;
                    $semana2 = 4;
                    $mesString = "Enero";
                    break;
                case 2:
                    $semana1 = 5;
                    $semana2 = 8;
                    $mesString = "Febrero";
                    break;
                case 3:
                    $semana1 = 9;
                    $semana2 = 13;
                    $mesString = "Marzo";
                    break;
                case 4:
                    $semana1 = 13;
                    $semana2 = 17;
                    $mesString = "Abril";
                    break;
                case 5:
                    $semana1 = 17;
                    $semana2 = 21;
                    $mesString = "Mayo";
                    break;
                case 6:
                    $semana1 = 22;
                    $semana2 = 26;
                    $mesString = "Junio";
                    break;
                case 7:
                    $semana1 = 26;
                    $semana2 = 30;
                    $mesString = "Julio";
                    break;
                case 8:
                    $semana1 = 30;
                    $semana2 = 35;
                    $mesString = "Agosto";
                    break;
                case 9:
                    $semana1 = 35;
                    $semana2 = 39;
                    $mesString = "Septiembre";
                    break;
                case 10:
                    $semana1 = 39;
                    $semana2 = 43;
                    $mesString = "Octubre";
                    break;
                case 11:
                    $semana1 = 44;
                    $semana2 = 48;
                    $mesString = "Noviembre";
                    break;
                case 12:
                    $semana1 = 48;
                    $semana2 = 52;
                    $mesString = "Diciembre";
                    break;
            }                            
            
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_por_id_maquina_semanas.php?idMaquina='.$idMaquina."&semana1=".$semana1."&semana2=".$semana2;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            $mantenimientos = $datos->{'mantenimientos'};
            
            //obtiene idResponsable
            $idResponsable;
            $idMaquina;
            foreach ($mantenimientos as $fila) {
                $idResponsable = $fila->{'idResponsable'};
                $idMaquina = $fila->{'idMaquina'};
                break;
                //echo $fila->{'idFechaMantenimiento'}."<br>";
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $act){
                $actividadesArray[$act->{'descripcion_actividad'}."|".$i."|"] = "".$act->{'idActividad'};
                $i++;
            }
            
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
            
            //Busca datos generales en Arrays
            $posicionContenidoMaquina = array_search($idMaquina,$maquinasArray);
            $elemArrayMaq = explode("|", $posicionContenidoMaquina);                
            $nombreMaquina = $elemArrayMaq[0];
            $numeroMaquina = $elemArrayMaq[1];

            $posicionContenidoUsuario = array_search($idResponsable,$usuariosArray);
            $elemArrayUsu = explode("|", $posicionContenidoUsuario);                    
            $responsable = $elemArrayUsu[0];
            $anio = "2021";
            //Busca datos generales en Arrays
            //tblTitulo2  tblPrincipal
            /***************************************************************/
            echo "<html><head><style>@media print { @page { margin: 0; } body { margin: 2cm; } }"
                ."#tblTitulo2 {border: 1px solid white;} "
                ."#tblPrincipal {  border: 1px solid black;  border-collapse: collapse; }</style></head><body>";
            
            //echo "<div>";
            /*** encabezado principal   ***/
            echo "<table id='tblEncabezado' style='font-family: Arial; font-size:14px; color:#ff0000; margin-top:-80px;'><tr><td style='color:#ff0000'>";
//            $primeraCadena = "Plan de Mantenimiento";
            $primeraCadena = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            echo $primeraCadena;
            echo "</td>";
            
            echo "<td>";
            echo "<img style='margin-left:300px; height:200px; margin-right:145px;' src='".base_url()."/images/logo3.png' />";
            echo "</td>";
            
            echo "<td style='color:black;'>";
            $otroTexto = "";
            $segundaCadena = "";
            $terceraCadena = "";
            echo $otroTexto;
            echo "</td>";
            
            echo "</tr>";
            echo "</table>";
            
            
            //echo "<td style='font-weight:bold;width:100%;text-align:center'>Reporte de mantenimiento preventivo</td>";
            $cuartaCadena1 = "Nombre de la Máquina:";
            $cuartaCadena2 = $nombreMaquina;
            $cuartaCadena3 = "No:";
            $cuartaCadena4 = $numeroMaquina;
            $cuartaCadena5 = "Responsable:";
            
            echo "<table style='font-family: Arial; font-size:14px; margin-top:-80px; margin-left:240px;'><tr>"
                    ."<td style='font-weight:bold;width:50%;text-align:center'>Reporte de mantenimiento preventivo</td></tr></table>";
            
            echo "<table id='tblTitulo2' style='font-family: Arial; font-size:14px; margin-top:10;'><tr>"
                    ."<td style='font-weight:bold;width:16%;'>".$cuartaCadena1."</td>"
                    ."<td style='width:21%;'>".$cuartaCadena2."</td>"
                    ."<td style='font-weight:bold;width:4%'>".$cuartaCadena3."</td>"
                    ."<td style='width:30%'>".$cuartaCadena4."</td>"
                    ."<td style='font-weight:bold;width:8%;'>".$cuartaCadena5."</td>"
                    ."<td style='width:35%'>".$responsable."</td>";
            echo "</tr></table>";
            
            echo "<table style='font-family: Arial; font-size:14px; margin-top:10px; margin-left:2px;'><tr>"
                    ."<td style='font-weight:bold;width:5%;'>Mes:</td>"
                    ."<td style='width:29%;'>".$mesString."</td>"
                    ."<td style='font-weight:bold;width:5%;'>Año:</td>"
                    ."<td style='width:25%;'>".$anioString."</td>"
                    ."</tr></table>";
            
            echo "<br>";
            echo "<table id='tblPrincipal' style='font-family: Arial;font-size:14px;margin-top:8px;'>"
                ."<tr style='text-align:center;font-weight:bold;'>"
                ."<td style='border: 1px solid black; border-collapse: collapse;'>Semana</td>"
                ."<td style='border: 1px solid black; border-collapse: collapse;'>Actividad</td>"
                ."<td style='border: 1px solid black; border-collapse: collapse;'>Estatus</td>"
                ."<td style='border: 1px solid black; border-collapse: collapse;'>&nbsp;Observaciones&nbsp;</td></tr>";

            //genera la tabla de actividades
            $actividadesRepetidas = array("-2");
            foreach ($mantenimientos as $fila) {
                $posicionContenidoActividad = array_search($fila->{'idActividad'},$actividadesArray);
                $elemArrayAct = explode("|", $posicionContenidoActividad);
                echo "<tr style='text-align:center;border: 1px solid black; border-collapse: collapse;'>";
                echo "<td style='text-align:left;border: 1px solid black; border-collapse: collapse;'>&nbsp;".substr($fila->{'fechaMantenimiento'},0,9)."&nbsp;</td>";
                echo "<td  style='text-align:left;border: 1px solid black; border-collapse: collapse;'>&nbsp;".$elemArrayAct[0]."&nbsp;</td>";
                if ($fila->{'condicion_maquina'} == 'ATENDIDA') {
                    echo "<td  style='text-align:left;border: 1px solid black; border-collapse: collapse;'>&nbsp;ATENDIDA&nbsp;</td>";
                } else {
                    echo "<td  style='text-align:left;border: 1px solid black; border-collapse: collapse;'></td>";
                }
                echo "<td  style='text-align:left;border: 1px solid black; border-collapse: collapse;'>&nbsp;".$fila->{'observaciones_maquina'}."&nbsp;</td></tr>";
            }
            echo "</table>";
            
            //echo "</div>";
            echo "</body></html>";
            /***************************************************************/
        } else {
            redirect($this->cerrarSesion());
        }
    }    
    
    //Fin llamada a webservices de usuarios
    /*
    //Importar desde Excel con libreria de PHPExcel
    public function importarUsersExcel(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => 'Mantenimientos'
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
    */
    
    
    function mostrarMantenimientosSemanaUsuario2() {
        if ($this->is_logged_in()) {
            $week;
            if ($this->input->post('submit')){
                $week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                //$week = ($date->format("W")-1)."|".""."|";
                $week = $date->format("W")."|".""."|";
                //$week = str_replace("0", "", $week);
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_semana_usuario_2.php?idUsuario='.$this->session->userdata('idUsuario')."&week=".$week;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
//            echo $data;
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            //obtiene dia primero y ultimo de mes
            $ddate = $fechaIngreso;
            $date = new DateTime($ddate);
            $mes = $date->format("m");            
            $anio = $date->format("Y");
            $diaIniMes = "";
            $diafinMes = "";
            switch ($mes) {
                case "01":
                    $diaIniMes = $anio."-01-01";
                    break; 
                case "02":
                    $diaIniMes = $anio."-02-01";
                    break; 
                case "03":
                    $diaIniMes = $anio."-03-01";
                    break; 
                case "04":
                    $diaIniMes = $anio."-04-01";
                    break; 
                case "05":
                    $diaIniMes = $anio."-05-01";
                    break; 
                case "06":
                    $diaIniMes = $anio."-06-01";
                    break; 
                case "07":
                    $diaIniMes = $anio."-07-01";
                    break; 
                case "08":
                    $diaIniMes = $anio."-08-01";
                    break; 
                case "09":
                    $diaIniMes = $anio."-09-01";
                    break; 
                case "10":
                    $diaIniMes = $anio."-10-01";
                    break; 
                case "11":
                    $diaIniMes = $anio."-11-01";
                    break; 
                case "12":
                    $diaIniMes = $anio."-12-01";
                    break; 
            }
            $diaFinMes = date("Y-m-t", strtotime($diaIniMes));
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'usuario_herramental'=>$this->usuarioHerramental,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerTecnico_view',$data);
//                    $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosTecnicoSemana_view_2',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'usuario_herramental'=>$this->usuarioHerramental,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerTecnico_view',$data);
//                    $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosTecnicoSemana_view_2',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => "0|",
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerTecnico_view',$data);
//                $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                $this->load->view('mantenimientos/adminMantenimientosTecnicoSemana_view_2',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarMantenimientoTecnico2($idFechaMantenimiento,$idMaquina) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_por_id_mantenimiento.php?idFechaMantenimiento='.$idFechaMantenimiento;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            if ($datos->{'estado'}==1) {
                $data = array(
                    'idUsuario'=>$this->session->userdata('idUsuario'),
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'idMaquina'=>$idMaquina,
                    'usuarios'=>$this->usuariosGlobal,
                    'maquinas'=>$this->maquinasGlobal,
                    'mantenimiento'=>$datos->{'mantenimiento'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('mantenimientos/actualizaMantenimientoTecnico_view_2',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarMantenimientoFromFormularioTecnico2() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idFechaMantenimiento = $this->input->post("idFechaMantenimiento");
                $fechaMantenimiento = $this->input->post("fechaMantenimiento");
                $observaciones_maquina = $this->input->post("observaciones");  
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y_m_d H_i_s");                 
                $nombre_archivo = $idFechaMantenimiento.".jpg";  
                //echo " ---> ".$this->input->post("imagenCargadaHidden")." <--aass".$_FILES['imagen']['name'] == "";
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen']['name'] == "") {
                    $ruta2 = $this->input->post("imagenCargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/imagenes/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."imagenes/".$nombre_archivo;
                }
                $data = array(
                    "idFechaMantenimiento" => $idFechaMantenimiento,
                    'fechaMantenimiento' => $fechaMantenimiento,
                    "observaciones_maquina" => $observaciones_maquina,
                    'ruta' => $ruta2
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_tecnico.php');
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
                redirect('/index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario2');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarMantenimientoTecnico2FromBtn($idFechaMantenimiento,$fechaMantenimiento) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            $fechaMantenimiento = "Semana ".$fechaMantenimiento." ".$fechaIngreso;
            echo $idFechaMantenimiento." - ".$fechaMantenimiento;
            $observaciones_maquina = "";  
            $nombre_archivo = "";  
            //echo " ---> ".$this->input->post("imagenCargadaHidden")." <--aass".$_FILES['imagen']['name'] == "";
            $ruta2 = "";
            $ruta = "";  
            $data = array(
                "idFechaMantenimiento" => $idFechaMantenimiento,
                'fechaMantenimiento' => $fechaMantenimiento,
                "observaciones_maquina" => $observaciones_maquina,
                'ruta' => $ruta2
                    );
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_tecnico.php');
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
            //echo $result;
            curl_close($ch);
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
            }        
            redirect('/index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario2');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarMantenimientosSemanaUsuario3() {
        if ($this->is_logged_in()) {
            $week;
            if ($this->input->post('submit')){
                $week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                //$week = ($date->format("W")-1)."|".""."|";
                $week = $date->format("W")."|".""."|";
                //$week = str_replace("0", "", $week);
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_semana_usuario_2.php?idUsuario='.$this->session->userdata('idUsuario')."&week=".$week;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
//            echo $data;
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            //obtiene dia primero y ultimo de mes
            $ddate = $fechaIngreso;
            $date = new DateTime($ddate);
            $mes = $date->format("m");            
            $anio = $date->format("Y");
            $diaIniMes = "";
            $diafinMes = "";
            switch ($mes) {
                case "01":
                    $diaIniMes = $anio."-01-01";
                    break; 
                case "02":
                    $diaIniMes = $anio."-02-01";
                    break; 
                case "03":
                    $diaIniMes = $anio."-03-01";
                    break; 
                case "04":
                    $diaIniMes = $anio."-04-01";
                    break; 
                case "05":
                    $diaIniMes = $anio."-05-01";
                    break; 
                case "06":
                    $diaIniMes = $anio."-06-01";
                    break; 
                case "07":
                    $diaIniMes = $anio."-07-01";
                    break; 
                case "08":
                    $diaIniMes = $anio."-08-01";
                    break; 
                case "09":
                    $diaIniMes = $anio."-09-01";
                    break; 
                case "10":
                    $diaIniMes = $anio."-10-01";
                    break; 
                case "11":
                    $diaIniMes = $anio."-11-01";
                    break; 
                case "12":
                    $diaIniMes = $anio."-12-01";
                    break; 
            }
            $diaFinMes = date("Y-m-t", strtotime($diaIniMes));
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'usuario_herramental'=>$this->usuarioHerramental,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'usuario_herramental'=>$this->usuarioHerramental,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => "0|",
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarMantenimientoTecnico3FromBtn($idFechaMantenimiento,$fechaMantenimiento) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            $fechaMantenimiento = "Semana ".$fechaMantenimiento." ".$fechaIngreso;
            echo $idFechaMantenimiento." - ".$fechaMantenimiento;
            $observaciones_maquina = "";  
            $nombre_archivo = "";  
            //echo " ---> ".$this->input->post("imagenCargadaHidden")." <--aass".$_FILES['imagen']['name'] == "";
            $ruta2 = "";
            $ruta = "";  
            $data = array(
                "idFechaMantenimiento" => $idFechaMantenimiento,
                'fechaMantenimiento' => $fechaMantenimiento,
                "observaciones_maquina" => $observaciones_maquina,
                'ruta' => $ruta2
                    );
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_tecnico.php');
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
            //echo $result;
            curl_close($ch);
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
            }        
            redirect('/index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario3');
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarMantenimientoTecnico3($idFechaMantenimiento,$idMaquina) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_por_id_mantenimiento.php?idFechaMantenimiento='.$idFechaMantenimiento;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            if ($datos->{'estado'}==1) {
                $data = array(
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'idMaquina'=>$idMaquina,
                    'usuarios'=>$this->usuariosGlobal,
                    'maquinas'=>$this->maquinasGlobal,
                    'mantenimiento'=>$datos->{'mantenimiento'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('mantenimientos/actualizaMantenimientoTecnico_view_3',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarMantenimientoFromFormularioTecnico3() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idFechaMantenimiento = $this->input->post("idFechaMantenimiento");
                $fechaMantenimiento = $this->input->post("fechaMantenimiento");
                $observaciones_maquina = $this->input->post("observaciones");  
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y_m_dH_i_s");                 
                $nombre_archivo = $idFechaMantenimiento.".jpg";  
                //echo " ---> ".$this->input->post("imagenCargadaHidden")." <--aass".$_FILES['imagen']['name'] == "";
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen']['name'] == "") {
                    $ruta2 = $this->input->post("imagenCargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/imagenes/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."imagenes/".$nombre_archivo;
                }
                $data = array(
                    "idFechaMantenimiento" => $idFechaMantenimiento,
                    'fechaMantenimiento' => $fechaMantenimiento,
                    "observaciones_maquina" => $observaciones_maquina,
                    'ruta' => $ruta2
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_tecnico.php');
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
                //echo $result;
                curl_close($ch);
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
                }        
                redirect('/index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario3');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarConsultasMantenimientosTecnico(){
        if ($this->is_logged_in()){
            $week;
            if ($this->input->post('submit')){
                $week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                //$week = ($date->format("W")-1)."|".""."|";
                $week = $date->format("W")."|".""."|";
                //$week = str_replace("0", "", $week);
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_semana_usuario_2.php?idUsuario='.$this->session->userdata('idUsuario')."&week=".$week;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
//            echo $data;
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            //obtiene dia primero y ultimo de mes
            $ddate = $fechaIngreso;
            $date = new DateTime($ddate);
            $mes = $date->format("m");            
            $anio = $date->format("Y");
            $diaIniMes = "";
            $diafinMes = "";
            switch ($mes) {
                case "01":
                    $diaIniMes = $anio."-01-01";
                    break; 
                case "02":
                    $diaIniMes = $anio."-02-01";
                    break; 
                case "03":
                    $diaIniMes = $anio."-03-01";
                    break; 
                case "04":
                    $diaIniMes = $anio."-04-01";
                    break; 
                case "05":
                    $diaIniMes = $anio."-05-01";
                    break; 
                case "06":
                    $diaIniMes = $anio."-06-01";
                    break; 
                case "07":
                    $diaIniMes = $anio."-07-01";
                    break; 
                case "08":
                    $diaIniMes = $anio."-08-01";
                    break; 
                case "09":
                    $diaIniMes = $anio."-09-01";
                    break; 
                case "10":
                    $diaIniMes = $anio."-10-01";
                    break; 
                case "11":
                    $diaIniMes = $anio."-11-01";
                    break; 
                case "12":
                    $diaIniMes = $anio."-12-01";
                    break; 
            }
            $diaFinMes = date("Y-m-t", strtotime($diaIniMes));
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'usuario_herramental'=>$this->usuarioHerramental,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerTecnico_view',$data);
//                    $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientosTecnico_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'usuario_herramental'=>$this->usuarioHerramental,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerTecnico_view',$data);
//                    $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientosTecnico_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => "0|",
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerTecnico_view',$data);
//                $this->load->view('mantenimientos/adminMantenimientosTecnicoSemanaLista_view',$data);
                $this->load->view('mantenimientos/consultaMantenimientosTecnico_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarMantenimientosSemanaUsuario4() {
        if ($this->is_logged_in()) {
            $week;
            if ($this->input->post('submit')){
                $week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                //$week = ($date->format("W")-1)."|".""."|";
                $week = $date->format("W")."|".""."|";
                //$week = str_replace("0", "", $week);
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_semana_usuario_2.php?idUsuario='.$this->session->userdata('idUsuario')."&week=".$week;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
//            echo $data;
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            $datos = json_decode($data);
            curl_close($ch);
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            //obtiene dia primero y ultimo de mes
            $ddate = $fechaIngreso;
            $date = new DateTime($ddate);
            $mes = $date->format("m");            
            $anio = $date->format("Y");
            $diaIniMes = "";
            $diafinMes = "";
            switch ($mes) {
                case "01":
                    $diaIniMes = $anio."-01-01";
                    break; 
                case "02":
                    $diaIniMes = $anio."-02-01";
                    break; 
                case "03":
                    $diaIniMes = $anio."-03-01";
                    break; 
                case "04":
                    $diaIniMes = $anio."-04-01";
                    break; 
                case "05":
                    $diaIniMes = $anio."-05-01";
                    break; 
                case "06":
                    $diaIniMes = $anio."-06-01";
                    break; 
                case "07":
                    $diaIniMes = $anio."-07-01";
                    break; 
                case "08":
                    $diaIniMes = $anio."-08-01";
                    break; 
                case "09":
                    $diaIniMes = $anio."-09-01";
                    break; 
                case "10":
                    $diaIniMes = $anio."-10-01";
                    break; 
                case "11":
                    $diaIniMes = $anio."-11-01";
                    break; 
                case "12":
                    $diaIniMes = $anio."-12-01";
                    break; 
            }
            $diaFinMes = date("Y-m-t", strtotime($diaIniMes));
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $data['usuario_herramental'] = $this->util_controller->verificaUsuarioHerramental($this->session->userdata('idUsuario'));
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientosTecnico_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $data['usuario_herramental'] = $this->util_controller->verificaUsuarioHerramental($this->session->userdata('idUsuario'));
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientosTecnico_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'diaIniMes' => $diaIniMes,'diaFinMes' => $diaFinMes,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => "0|",
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $data['usuario_herramental'] = $this->util_controller->verificaUsuarioHerramental($this->session->userdata('idUsuario'));
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('mantenimientos/consultaMantenimientosTecnico_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarMantenimientoTecnico4FromBtn($idFechaMantenimiento,$fechaMantenimiento) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            $fechaMantenimiento = "Semana ".$fechaMantenimiento." ".$fechaIngreso;
            echo $idFechaMantenimiento." - ".$fechaMantenimiento;
            $observaciones_maquina = "";  
            $nombre_archivo = "";  
            //echo " ---> ".$this->input->post("imagenCargadaHidden")." <--aass".$_FILES['imagen']['name'] == "";
            $ruta2 = "";
            $ruta = "";  
            $data = array(
                "idFechaMantenimiento" => $idFechaMantenimiento,
                'fechaMantenimiento' => $fechaMantenimiento,
                "observaciones_maquina" => $observaciones_maquina,
                'ruta' => $ruta2
                    );
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_tecnico.php');
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
            //echo $result;
            curl_close($ch);
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
            }        
            redirect('/index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario4');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarMantenimientoTecnico4($idFechaMantenimiento,$idMaquina) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_por_id_mantenimiento.php?idFechaMantenimiento='.$idFechaMantenimiento;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            if ($datos->{'estado'}==1) {
                $data = array(
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'idMaquina'=>$idMaquina,
                    'usuarios'=>$this->usuariosGlobal,
                    'maquinas'=>$this->maquinasGlobal,
                    'mantenimiento'=>$datos->{'mantenimiento'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('mantenimientos/actualizaMantenimientoTecnico_view_4',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarMantenimientoFromFormularioTecnico4() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idFechaMantenimiento = $this->input->post("idFechaMantenimiento");
                $fechaMantenimiento = $this->input->post("fechaMantenimiento");
                $observaciones_maquina = $this->input->post("observaciones");  
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y_m_dH_i_s");                 
                $nombre_archivo = $idFechaMantenimiento.".jpg";  
                //echo " ---> ".$this->input->post("imagenCargadaHidden")." <--aass".$_FILES['imagen']['name'] == "";
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen']['name'] == "") {
                    $ruta2 = $this->input->post("imagenCargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/imagenes/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."imagenes/".$nombre_archivo;
                }
                $data = array(
                    "idFechaMantenimiento" => $idFechaMantenimiento,
                    'fechaMantenimiento' => $fechaMantenimiento,
                    "observaciones_maquina" => $observaciones_maquina,
                    'ruta' => $ruta2
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_tecnico.php');
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
                //echo $result;
                curl_close($ch);
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
                }        
                redirect('/index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario4');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarMantenimientoVerificacion($idFechaMantenimiento,$accion) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if ($accion == 1) {
                $accion = "Verificada ".$fechaMantenimiento." ".$fechaIngreso;
            } else {
                $accion = "";
            }
            $data = array(
                "idFechaMantenimiento" => $idFechaMantenimiento,
                "accion" => $accion
                    );
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_verificacion.php');
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
            //echo $result;
            curl_close($ch);
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
            }        
            redirect('/index.php/mantenimiento_controller/consultaMantenimientos');
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarMantenimientoVerificacionLista($idFechaMantenimiento,$accion) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if ($accion == 1) {
                $accion = "Verificada ".$fechaMantenimiento." ".$fechaIngreso;
            } else {
                $accion = "";
            }
            $data = array(
                "idFechaMantenimiento" => $idFechaMantenimiento,
                "accion" => $accion
                    );
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_verificacion.php');
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
            //echo $result;
            curl_close($ch);
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
            }        
            redirect('/index.php/mantenimiento_controller/mostrarMantenimientosLista');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function operacionesMasivas(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|".$maquina->{'responsable_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    
            
            $data = array(
                'usuariosGlobal' => $this->usuariosGlobal,
                'usuariosArray' => $usuariosArray,
                'maquinasArray' => $maquinasArray,
                'mantenimientosSimple' => $this->mantenimientosSimple,
                'maquinasSimple' => $this->maquinasSimple,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'sucursal' => $this->session->userdata('sucursal'),
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '7'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('mantenimientos/operacionesMasivasMantenimiento_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function eliminarMantenimientoMasivo(){
        if ($this->is_logged_in()){
            $manttoElim = explode("|", $this->input->post("maquinaOrigen2"));
            $idMaqElim = $manttoElim[0];
            $idRespElim = $manttoElim[1];
            echo "idMaqElim: ".$idMaqElim." ".$idRespElim;
            $data = array("idMaqElim" => $idMaqElim, 'idRespElim' => $idRespElim);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'mantenimientos/borrar_mantenimiento_masivo.php');
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
            redirect('/index.php/mantenimiento_controller/operacionesMasivas');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarMantenimientoMasivo(){
        if ($this->is_logged_in()){
            $arrayMaquinaOrigen = explode("|", $this->input->post("maquinaOrigen"));
            $idMaquina = $arrayMaquinaOrigen[0];
            $idResponsable = $arrayMaquinaOrigen[1]; 
            $idResponsableNuevo = $this->input->post("maquinaDestino");
            //echo "Estoy en actualizar idMaquina: ".$idMaquina." idResponsable: ".$idResponsable." idResponsableNuevo: ".$idResponsableNuevo;
            
            $data = array("idMaquina" => $idMaquina, 'idResponsable' => $idResponsable, 'idResponsableNuevo' => $idResponsableNuevo);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'mantenimientos/actualizar_mantenimiento_masivo.php');
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
            //echo $result;
            curl_close($ch);
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Mantenimiento actualizado correctamente. <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se actualizó el registro. <br>");
            }        
            redirect('/index.php/mantenimiento_controller/operacionesMasivas');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function consultaMantenimientos(){
        if ($this->is_logged_in()){
            $week;
            $idResponsable = 0;
            $idMaquina = 0;
            if ($this->input->post('submit')){
                $idMaquina = $this->input->post("maquinaConsulta");
                $idResponsable = $this->input->post("responsable");
                $week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                //$week = ($date->format("W")-1)."|";
                $week = $date->format("W")."|";
                ////$week = str_replace("0", "", $week);
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimientos_consulta.php?idResponsable='.$idResponsable."&week=".$week."&idMaquina=".$idMaquina;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
//            echo $data;
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
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
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientos_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientos_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'idMaquina' => $idMaquina,
                    'idResponsable' => $idResponsable, 
                    'idResponsableMaq' => $idResponsableMaq,
                    
                    'maquinasSimple' => $this->maquinasSimple,
                    'usuarios' => $this->usuariosGlobal,
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => $week,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('mantenimientos/consultaMantenimientos_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function consultaMantenimientosHistorico(){
        if ($this->is_logged_in()){
            $week;
            $anio;
            $idResponsable = 0;
            $idMaquina = 0;
            if ($this->input->post('submit')){
                $idMaquina = $this->input->post("maquinaConsulta");
                $idResponsable = $this->input->post("responsable");
                $week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
                $anio = $this->input->post("anio");
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                //$week = ($date->format("W")-1)."|";
                $week = $date->format("W")."|";
                //$week = str_replace("0", "", $week);
                $anio = "2021";
            }
            $tabla = "mantenimientos";
            switch($anio) {
                case '2020':
                    $tabla = $tabla."_".$anio;
                    break;
                case '2021':
                    break;
                case '2022':
                    $tabla = $tabla."_".$anio;
                    break;
                case '2023':
                    $tabla = $tabla."_".$anio;
                    break;
                case '2024':
                    $tabla = $tabla."_".$anio;
                    break;
                case '2025':
                    $tabla = $tabla."_".$anio;
                    break;
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimientos_consultaHistorico.php?idResponsable='.$idResponsable."&week=".$week."&idMaquina=".$idMaquina."&tabla=".$tabla;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
//            echo $data;
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
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
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        'anio' => $anio,
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientosHistorial_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        'anio' => $anio,
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientosHistorial_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'idMaquina' => $idMaquina,
                    'idResponsable' => $idResponsable, 
                    'idResponsableMaq' => $idResponsableMaq,
                    'anio' => $anio,
                    'maquinasSimple' => $this->maquinasSimple,
                    'usuarios' => $this->usuariosGlobal,
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => $week,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('mantenimientos/consultaMantenimientosHistorial_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    public function creaFormatoActividades($idMaquina) {
        if ($this->is_logged_in()) {
            $url = RUTAWS.'mantenimientos/obtener_mantenimiento_por_id_maquina.php?idMaquina='.$idMaquina;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);

            //obtiene idResponsable
            $idResponsable;
            foreach ($datos->{'mantenimientos'} as $fila) {
                $idResponsable = $fila->{'idResponsable'};
                break;
                //echo $fila->{'idFechaMantenimiento'}."<br>";
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $act){
                $actividadesArray[$act->{'descripcion_actividad'}."|".$i."|"] = "".$act->{'idActividad'};
                $i++;
            }
            
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
            
            //Busca datos generales en Arrays
            $posicionContenidoMaquina = array_search($idMaquina,$maquinasArray);
            $elemArrayMaq = explode("|", $posicionContenidoMaquina);                
            $nombreMaquina = $elemArrayMaq[0];
            $numeroMaquina = $elemArrayMaq[1];

            $posicionContenidoUsuario = array_search($idResponsable,$usuariosArray);
            $elemArrayUsu = explode("|", $posicionContenidoUsuario);                    
            $responsable = $elemArrayUsu[0];
            $anio = "2021";
            //Busca datos generales en Arrays
            //tblTitulo2  tblPrincipal
            /***************************************************************/
            echo "<html><head><style>@media print { @page { margin: 0; } body { margin: 2cm; } }"
                ."#tblTitulo2 {border: 1px solid white;} "
                ."#tblPrincipal {  border: 1px solid black;  border-collapse: collapse; }</style></head><body>";
            
            //echo "<div>";
            /*** encabezado principal   ***/
            echo "<table id='tblEncabezado' style='font-family: Arial; font-size:14px; color:#ff0000; margin-top:-80px;'><tr><td style='color:#ff0000'>";
            $primeraCadena = "Plan de Mantenimiento";
            echo $primeraCadena;
            echo "</td>";
            
            echo "<td>";
            echo "<img style='margin-left:180px; height:250px; margin-right:145px;' src='".base_url()."/images/logo3.png' />";
            echo "</td>";
            
            echo "<td style='color:black;'>";
            $otroTexto = "<br><br>Doc -No: ACA_K_SH_3710_159_ES";
            $segundaCadena = "Revisión: 01 de 08.01.2021";
            $terceraCadena = "Estatus: Aprobado";
            echo $otroTexto."<br>".$segundaCadena."<br>".$terceraCadena;
            echo "</td>";
            
            echo "</tr>";
            echo "</table>";

            /*
            /*** Fin encabezado principal   ***/
            
            
            $cuartaCadena1 = "Nombre de la Máquina:";
            $cuartaCadena2 = $nombreMaquina;
            $cuartaCadena3 = "Año:";
            $cuartaCadena4 = "2021";
            $cuartaCadena5 = "Número de Máquina:";
            
            echo "<table id='tblTitulo2' style='font-family: Arial; font-size:14px; margin-top:-40;'><tr>"
                    ."<td style='font-weight:bold;width:17%;'>".$cuartaCadena1."</td>"
                    ."<td style='width:30%;'>".$cuartaCadena2."</td>"
                    ."<td style='font-weight:bold;width:4%'>".$cuartaCadena3."</td>"
                    ."<td style='width:26%'>".$cuartaCadena4."</td>"
                    ."<td style='font-weight:bold;width:15%;'>".$cuartaCadena5."</td>"
                    ."<td >".$numeroMaquina."</td>";
            echo "</tr></table>";
            
            echo "<br>";
            echo "<table id='tblPrincipal' style='font-family: Arial;font-size:14px;'><tr style='text-align:center;font-weight:bold;'><td style='border: 1px solid black;  border-collapse: collapse;'>Actividades</td>"
                ."<td style='border: 1px solid black;  border-collapse: collapse;'>Enero</td><td style='border: 1px solid black;  border-collapse: collapse;'>Febrero</td>"
                ."<td style='border: 1px solid black;  border-collapse: collapse;'>Marzo</td><td style='border: 1px solid black;  border-collapse: collapse;'>Abril</td>"
                ."<td style='border: 1px solid black;  border-collapse: collapse;'>Mayo</td><td style='border: 1px solid black;  border-collapse: collapse;'>Junio</td>"
                ."<td style='border: 1px solid black;  border-collapse: collapse;'>Julio</td><td style='border: 1px solid black;  border-collapse: collapse;'>Agosto</td>"
                ."<td style='border: 1px solid black;  border-collapse: collapse;'>Septiembre</td><td style='border: 1px solid black;  border-collapse: collapse;'>Octubre</td>"
                ."<td style='border: 1px solid black;  border-collapse: collapse;'>Noviembre</td><td style='border: 1px solid black;  border-collapse: collapse;'>Diciembre</td></tr>";

            //genera la tabla de actividades
            $actividadesRepetidas = array("-2");
                            $enero = "";
                            $febrero = "";
                            $marzo = "";
                            $abril = "";
                            $mayo = "";
                            $junio = "";
                            $julio = "";
                            $agosto = "";
                            $septiembre = "";
                            $octubre = "";
                            $noviembre = "";
                            $diciembre = "";
            foreach ($datos->{'mantenimientos'} as $fila) {
                $posicionContenidoActividad = array_search($fila->{'idActividad'},$actividadesArray);
                $elemArrayAct = explode("|", $posicionContenidoActividad);
                $v = array_search($elemArrayAct[0],$actividadesRepetidas);
                if ($v == false){
                    array_push($actividadesRepetidas,$elemArrayAct[0]);    
                    echo "<tr style='text-align:center;border: 1px solid black;  border-collapse: collapse;'><td  style='text-align:left;border: 1px solid black;  border-collapse: collapse;'>".$elemArrayAct[0]."</td>";
                    
                    //busca semanas en mantenimientos
                    $renglon = 1;
                    foreach ($datos->{'mantenimientos'} as $fila2) {
                        if ($fila->{'idActividad'} == $fila2->{'idActividad'}){ //si concuerda la posicion en el dataset
                            switch (trim(substr($fila2->{'fechaMantenimiento'}, 7, 2))){//verifica posicion con mes en array
                                case 1:
                                    $enero = $enero.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 2:
                                    $enero = $enero.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 3:
                                    $enero = $enero.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 4:
                                    $enero = $enero.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 5:
                                    $febrero = $febrero.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 6:
                                    $febrero = $febrero.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 7:
                                    $febrero = $febrero.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 8:
                                    $febrero = $febrero.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 9:
                                    $marzo = $marzo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 10:
                                    $marzo = $marzo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 11:
                                    $marzo = $marzo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 12:
                                    $marzo = $marzo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 13:
                                    $marzo = $marzo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 14:
                                    $abril = $abril.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 15:
                                    $abril = $abril.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 16:
                                    $abril = $abril.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 17:
                                    $abril = $abril.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 18:
                                    $mayo = $mayo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 19:
                                    $mayo = $mayo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 20:
                                    $mayo = $mayo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 21:
                                    $mayo = $mayo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 22:
                                    //$mayo = $mayo.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    $junio = $junio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 23:
                                    $junio = $junio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 24:
                                    $junio = $junio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 25:
                                    $junio = $junio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 26:
                                    $junio = $junio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 27:
                                    $julio = $julio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 28:
                                    $julio = $julio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 29:
                                    $julio = $julio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 30:
                                    $julio = $julio.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 31:
                                    $agosto = $agosto.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 32:
                                    $agosto = $agosto.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 33:
                                    $agosto = $agosto.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 34:
                                    $agosto = $agosto.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 35:
                                    $agosto = $agosto.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 36:
                                    $septiembre = $septiembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 37:
                                    $septiembre = $septiembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 38:
                                    $septiembre = $septiembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 39:
                                    $septiembre = $septiembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 40:
                                    $octubre = $octubre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 41:
                                    $octubre = $octubre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 42:
                                    $octubre = $octubre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 43:
                                    $octubre = $octubre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 44:
                                    $noviembre = $noviembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 45:
                                    $noviembre = $noviembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 46:
                                    $noviembre = $noviembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 47:
                                    $noviembre = $noviembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 48:
                                    $noviembre = $noviembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 49:
                                    $diciembre = $diciembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 50:
                                    $diciembre = $diciembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 51:
                                    $diciembre = $diciembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                                case 52:
                                    $diciembre = $diciembre.trim(substr($fila2->{'fechaMantenimiento'}, 7, 2)).",";
                                    break;
                            }                            
                        }
                    }
                    echo "<td style='border: 1px solid black;  border-collapse: collapse;'>".trim($enero,",")."</td><td style='border: 1px solid black;  border-collapse: collapse;'>".trim($febrero,",")
                            ."</td><td style='border: 1px solid black;  border-collapse: collapse;'>".trim($marzo,",")."</td><td style='border: 1px solid black;  border-collapse: collapse;'>".trim($abril,",")
                            ."</td><td style='border: 1px solid black;  border-collapse: collapse;'>".trim($mayo,",")."</td><td style='border: 1px solid black;  border-collapse: collapse;'>"
                            .trim($junio,",")."</td><td style='border: 1px solid black;  border-collapse: collapse;'>".trim($julio,",")."</td><td style='border: 1px solid black;  border-collapse: collapse;'>"
                            .trim($agosto,",")."</td><td style='border: 1px solid black;  border-collapse: collapse;'>".trim($septiembre,",")."</td><td style='border: 1px solid black;  border-collapse: collapse;'>".trim($octubre,",")."</td><td>"
                            .trim($noviembre,",")."</td><td style='border: 1px solid black;  border-collapse: collapse;'>".trim($diciembre,",")."</td></tr>";
                    $enero = "";
                    $febrero = "";
                    $marzo = "";
                    $abril = "";
                    $mayo = "";
                    $junio = "";
                    $julio = "";
                    $agosto = "";
                    $septiembre = "";
                    $octubre = "";
                    $noviembre = "";
                    $diciembre = "";
                }
            }
            echo "</table>";
            
            //echo "</div>";
            echo "</body></html>";
            /***************************************************************/
        } else {
            redirect($this->cerrarSesion());
        }
    }    
    
    function consultaMantenimientosOtros(){
        if ($this->is_logged_in()){
            $week;
            $idResponsable = 0;
            $idMaquina = 0;
            if ($this->input->post('submit')){
                $idMaquina = $this->input->post("maquinaConsulta");
                $idResponsable = $this->input->post("responsable");
                $week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
            } else {
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fechaIngreso = $dt->format("Y-m-d");                 
                $ddate = $fechaIngreso;
                $date = new DateTime($ddate);
                //$week = ($date->format("W")-1)."|";
                $week = $date->format("W")."|";
                //$week = str_replace("0", "", $week);
            }
            $url = RUTAWS.'mantenimientos/obtener_mantenimientos_consulta.php?idResponsable='.$idResponsable."&week=".$week."&idMaquina=".$idMaquina;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            
//            echo $data;
            //Llena array Maquinas
            $maquinasArray = array();
            foreach ($this->maquinasSimple as $maquina){
                $maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
            }
            
            //Llena array Actividades
            $actividadesArray = array();
            $i = 0;
            foreach ($this->actividadesGlobal as $actividad){
                $actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
                $i++;
            }
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
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
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            if (isset($datos->{'estado'})) {
                if ($datos->{'estado'}==1) {
                    //consulta original
                    $data = array(
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'mantenimientos'=>$datos->{'mantenimientos'},
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );
                    $this->load->view('layouts/headerOtros_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientosOtros_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'idMaquina' => $idMaquina,
                        'idResponsable' => $idResponsable, 
                        'idResponsableMaq' => $idResponsableMaq,
                        
                        'maquinasSimple' => $this->maquinasSimple,
                        'usuarios' => $this->usuariosGlobal,
                        'usuariosArray' => $usuariosArray,
                        'maquinasArray' => $maquinasArray,
                        'actividadesArray' => $actividadesArray,
                        'idUsuario' => $this->session->userdata('idUsuario'),
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'week' => $week,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => 'Mantenimientos'
                            );                
                    $this->load->view('layouts/headerOtros_view',$data);
                    $this->load->view('mantenimientos/consultaMantenimientosOtros_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'idMaquina' => $idMaquina,
                    'idResponsable' => $idResponsable, 
                    'idResponsableMaq' => $idResponsableMaq,
                    
                    'maquinasSimple' => $this->maquinasSimple,
                    'usuarios' => $this->usuariosGlobal,
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'actividadesArray' => $actividadesArray,
                    'idUsuario' => $this->session->userdata('idUsuario'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'week' => $week,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Mantenimientos'
                        );                
                $this->load->view('layouts/headerOtros_view',$data);
                $this->load->view('mantenimientos/consultaMantenimientosOtros_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }
	
    function consultaMantenimientosPublico(){
		$week;
		$idResponsable = 0;
		$idMaquina = 0;
		if ($this->input->post('submit')){
			$idMaquina = $this->input->post("maquinaConsulta");
			$idResponsable = $this->input->post("responsable");
			$week = $this->input->post("semana1")."|".$this->input->post("semana2")."|";
		} else {
			$dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
			$fechaIngreso = $dt->format("Y-m-d");                 
			$ddate = $fechaIngreso;
			$date = new DateTime($ddate);
			//$week = ($date->format("W")-1)."|";
			$week = $date->format("W")."|";
			//$week = str_replace("0", "", $week);
		}
		$url = RUTAWS.'mantenimientos/obtener_mantenimientos_consulta.php?idResponsable='.$idResponsable."&week=".$week."&idMaquina=".$idMaquina;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		
//            echo $data;
		//Llena array Maquinas
		$maquinasArray = array();
		foreach ($this->maquinasSimple as $maquina){
			$maquinasArray[$maquina->{'nombre_maquina'}."|".$maquina->{'numero_maquina'}."|"] = $maquina->{'idMaquina'};
		}
		
		//Llena array Actividades
		$actividadesArray = array();
		$i = 0;
		foreach ($this->actividadesGlobal as $actividad){
			$actividadesArray[$actividad->{'descripcion_actividad'}."|".$i."|"] = "".$actividad->{'idActividad'};
			$i++;
		}
		
		//Llena array Usuarios
		$usuariosArray = array();
		$j = 0;
		foreach ($this->usuariosGlobal as $usuario){
			$usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
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
		$dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
		$fechaIngreso = $dt->format("Y-m-d H:i:s"); 
		
		//crea campos de sesion
		$this->session->set_userdata('nombre', "WeWire");
		$this->session->set_userdata('permisos', "Otros");
		$this->session->set_userdata('usuario', "WeWire");					
		$this->session->set_userdata('clave', "999");					
		$this->session->set_userdata('idUsuario', 63);
		$this->session->set_userdata('logueado', TRUE);
		
		if (isset($datos->{'estado'})) {
			if ($datos->{'estado'}==1) {
				//consulta original
				$data = array(
					'idMaquina' => $idMaquina,
					'idResponsable' => $idResponsable, 
					'idResponsableMaq' => $idResponsableMaq,
					
					'maquinasSimple' => $this->maquinasSimple,
					'usuarios' => $this->usuariosGlobal,
					'usuariosArray' => $usuariosArray,
					'maquinasArray' => $maquinasArray,
					'actividadesArray' => $actividadesArray,
					'idUsuario' => $this->session->userdata('idUsuario'),
					'mantenimientos'=>$datos->{'mantenimientos'},
					'usuarioDatos' => $this->session->userdata('nombre'),
					'fecha' => $fechaIngreso,
					'week' => $week,
					'nombre_Empresa'=>$this->nombreEmpresaGlobal,
					'permisos' => $this->session->userdata('permisos'),
					'opcionClickeada' => 'Mantenimientos'
						);
                                $this->load->view('layouts/headerOtros_view',$data);
				$this->load->view('mantenimientos/consultaMantenimientosOtros_view',$data);
				$this->load->view('layouts/pie_view',$data);
			} else {
				$data = array(
					'idMaquina' => $idMaquina,
					'idResponsable' => $idResponsable, 
					'idResponsableMaq' => $idResponsableMaq,
					
					'maquinasSimple' => $this->maquinasSimple,
					'usuarios' => $this->usuariosGlobal,
					'usuariosArray' => $usuariosArray,
					'maquinasArray' => $maquinasArray,
					'actividadesArray' => $actividadesArray,
					'idUsuario' => $this->session->userdata('idUsuario'),
					'usuarioDatos' => $this->session->userdata('nombre'),
					'fecha' => $fechaIngreso,
					'week' => $week,
					'nombre_Empresa'=>$this->nombreEmpresaGlobal,
					'permisos' => $this->session->userdata('permisos'),
					'opcionClickeada' => 'Mantenimientos'
						);                
                                $this->load->view('layouts/headerOtros_view',$data);
				$this->load->view('mantenimientos/consultaMantenimientosOtros_view',$data);
				$this->load->view('layouts/pie_view',$data);
			} 
		} else {
			$data = array(
				'idMaquina' => $idMaquina,
				'idResponsable' => $idResponsable, 
				'idResponsableMaq' => $idResponsableMaq,
				
				'maquinasSimple' => $this->maquinasSimple,
				'usuarios' => $this->usuariosGlobal,
				'usuariosArray' => $usuariosArray,
				'maquinasArray' => $maquinasArray,
				'actividadesArray' => $actividadesArray,
				'idUsuario' => $this->session->userdata('idUsuario'),
				'usuarioDatos' => $this->session->userdata('nombre'),
				'fecha' => $fechaIngreso,
				'week' => $week,
				'nombre_Empresa'=>$this->nombreEmpresaGlobal,
				'permisos' => $this->session->userdata('permisos'),
				'opcionClickeada' => 'Mantenimientos'
					);                
                        $this->load->view('layouts/headerOtros_view',$data);
			$this->load->view('mantenimientos/consultaMantenimientosOtros_view',$data);
			$this->load->view('layouts/pie_view',$data);
		} 
    }
    
    function mostrarNotificacionesTecnico() {
        $idUsuario = $this->session->userdata('idUsuario');
        $tipoUsuario = 2;
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'turnos/obtener_turno_por_idUsuario.php?idUsuario='.$idUsuario;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataTurno = curl_exec($ch);
            $datosTurno = json_decode($dataTurno);
            curl_close($ch);
            
            $url = RUTAWS.'notificaciones/obtener_notificaciones_tecnico_general.php?idUsuarioTecnico='.$idUsuario;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            //si no hay notificaciones
            if ($datos->{'estado'} == 2) {
                echo "<script>window.history.back();</script>";
            } 
            
            $url = RUTAWS.'usuarios_turnos/obtener_usuariosturnos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataLideres = curl_exec($ch);
            $datosLideres = json_decode($dataLideres);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($datosLideres->{'usuarios'} as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuarioOperador'};
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
                    'idUsuario' => $this->session->userdata('idUsuario'),                            
                    'turno'=>$datosTurno->{'turno'},
                    'areasArray' => $areasArray,
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('turnos/adminNotificacionesTecnico_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'notificaciones'=>null,
                    'proyectos'=>$datosProyectos->{'proyectos'},
                    'idUsuario' => $this->session->userdata('idUsuario'),                            
                    'turno'=>$datosTurno->{'turno'},
                    'areasArray' => $areasArray,
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('turnos/adminNotificacionesTecnico',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    //Exportar datos a Excel
    public function exportarExcelTecnico(){
        if ($this->is_logged_in()){
            $url = RUTAWS.'usuarios_turnos/obtener_usuariosturnos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataLideres = curl_exec($ch);
            $datosLideres = json_decode($dataLideres);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($datosLideres->{'usuarios'} as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuarioOperador'};
                $j++;
            }             
            
            $idUsuarioTecnico = $this->session->userdata('idUsuario');
            $url = RUTAWS.'notificaciones/obtener_notificaciones_tecnico_general.php?idUsuarioTecnico='.$idUsuarioTecnico;
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
            $heading=array('Descripción de Falla','Respuesta del Técnico','Inicio Atención','Fin Atención','Duración'
                ,'Lider','Status','Calificación',"Observ. Técnico","Proyecto");
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
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'mensajeOperador'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$n->{'mensajeTecnico'});
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'fechaInicioAtencion'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'fechaFinAtencion'});
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'tiempoAtencion'});
                
                $posicionContenidoUsuario = array_search($n->{'idUsuarioOperador'},$usuariosArray);
                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                   
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$elemArrayUsu[0]);
                
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

    function agregarComentario($idNotificacion) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'notificaciones/obtener_notificacion_por_id.php?id='.$idNotificacion;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            $url = RUTAWS.'usuarios_turnos/obtener_usuariosturnos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataLideres = curl_exec($ch);
            $datosLideres = json_decode($dataLideres);
            curl_close($ch);
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($datosLideres->{'usuarios'} as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuarioOperador'};
                $j++;
            }    
            $idUsuario = $this->session->userdata('idUsuario');
            if ($datos->{'estado'}==1) {
                $data = array(
                    'idUsuario' => $idUsuario,
                    'usuariosArray' => $usuariosArray,
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'usuarios'=>$this->usuariosGlobal,
                    'maquinas'=>$this->maquinasGlobal,
                    'notificacion'=>$datos->{'notificacion'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'notificaciones'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('turnos/actualizaNotificacionTecnico_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    

    function actualizarObservacionFromFormularioTecnico() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idNotificacion = $this->input->post("idNotificacion");
                $obsTecnico = $this->input->post("obsTecnico");
                $data = array(
                    "idNotificacion" => $idNotificacion,
                    "obsTecnico" => $obsTecnico
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'notificaciones/actualizar_notificacion_observaciones_tecnico.php');
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
                //echo $result;
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
                }        
                //redirect('/index.php/mantenimiento_controller/mostrarMantenimientos');
                redirect('/index.php/mantenimiento_controller/mostrarNotificacionesTecnico');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarTurnosSupervisor() {
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
                $data = array(
                    'turnos'=>$datos->{'turnos'},
                    'idUsuario'=>$this->session->userdata('idUsuario'),
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'adminTecnicos'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('turnos/adminTurnosSupervisor_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'turnos'=>null,
                    'idUsuario'=>$this->session->userdata('idUsuario'),
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'areasArray' => $areasArray,
                    'usuariosArray' => $usuariosArray,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'adminTecnicos'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('turnos/adminTurnosSupervisor_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarTurnoSupervisor($idTurno) {
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
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'idUsuario'=>$this->session->userdata('idUsuario'),
                    'areas' => $this->areasSimple,
                    'turno'=>$datos->{'turno'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );
                $this->load->view('layouts/headerTecnico_view',$data);
                $this->load->view('turnos/actualizaTurnoSupervisor_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarTurnoFromFormularioSupervisor() {
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
                redirect('/index.php/mantenimiento_controller/mostrarTurnosSupervisor');
            }
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

    function is_logged_in() {
        return $this->session->userdata('logueado');
    }
    //**  Fin Manejo de Sesiones
    

}
