<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fallas_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuariosGlobal;
    private $areasGlobal;
    private $maquinasSimple;
    
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
        
        $this->maquinasSimple = $this->util_controller->cargaDatosMaquinasSimple();
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
                    'opcionClickeada' => '0'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('principal_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarFallas() {
        if ($this->is_logged_in()){
            $idUsuario = $this->session->userdata('idUsuario');
            $url = RUTAWS.'fallas/obtener_fallas.php';
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
            if ($datos->{'estado'}==1) {
                $data = array(
                    'fallas'=>$datos->{'fallas'},
                    'idUsuario'=>$idUsuario,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Fallas',
                    'usuario_herramental' => $this->util_controller->verificaUsuarioHerramental($this->session->userdata('idUsuario'))
                        );
                if ($this->session->userdata('permisos')=="Administrador") {
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('fallas/adminFallas_view',$data);
                }
                if ($this->session->userdata('permisos')=="Técnico") {
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('fallas/adminFallasTecnico_view',$data);
                }
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'fallas'=>null,
                    'idUsuario'=>$idUsuario,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Fallas',  
                    'usuario_herramental' => $this->util_controller->verificaUsuarioHerramental($this->session->userdata('idUsuario'))
                        );
                if ($this->session->userdata('permisos')=="Administrador") {
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('fallas/adminFallas_view',$data);
                }
                if ($this->session->userdata('permisos')=="Técnico") {
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('fallas/adminFallasTecnico_view',$data);
                }
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoFalla() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'idUsuario'=>$this->session->userdata('idUsuario'),
                'maquinasSimple'=> $this->maquinasSimple,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'sucursal' => $this->session->userdata('sucursal'),
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => 'Fallas'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('fallas/nuevoFalla_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoFallaFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                //obtiene ultimo registro insertado de las fallas para generar el nombre de la referencia de foto
                $url = RUTAWS.'fallas/obtener_ultimoid_fallas.php';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $dataLastId = curl_exec($ch);
                $datosLastId = json_decode($dataLastId);
                curl_close($ch);     

                $nuevoId = 0;
                if ($datosLastId->{'estado'} == 1) {
                    $nuevoId = $datosLastId->{'fallas'}[0]->{'idFalla'} + 1;
                    //$nombre_archivo_Foto_antes = ($datosLastId->{'fallas'}[0]->{'idFalla'} + 1)."_foto_antes.jpg";  
                } else {
                    $nuevoId = 1;            
                }            

                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fecha = $dt->format("Y_m_d_H_i_s");
                $evidenciaFalla = "";
                
                //recibe las fotos
                $contFotos = 1;
                //foto1
                $nombre_archivo = "falla".$contFotos."_".$nuevoId."_".$fecha.".jpg";  
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen']['name'] == "") {
                    $ruta2 = $this->input->post("imagenCargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaFalla = $evidenciaFalla.$nombre_archivo."|";
                    $contFotos++;
                }

                //foto2
                $nombre_archivo = "falla".$contFotos."_".$nuevoId."_".$fecha.".jpg";  
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen2']['name'] == "") {
                    $ruta2 = $this->input->post("imagen2CargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen2']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaFalla = $evidenciaFalla.$nombre_archivo."|";
                    $contFotos++;
                }
                
                //foto3
                $nombre_archivo = "falla".$contFotos."_".$nuevoId."_".$fecha.".jpg";  
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen3']['name'] == "") {
                    $ruta2 = $this->input->post("imagen3CargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen3']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaFalla = $evidenciaFalla.$nombre_archivo."|";
                    $contFotos++;
                }

                //foto4
                $nombre_archivo = "falla".$contFotos."_".$nuevoId."_".$fecha.".jpg";  
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen4']['name'] == "") {
                    $ruta2 = $this->input->post("imagen4CargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen4']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaFalla = $evidenciaFalla.$nombre_archivo."|";
                    $contFotos++;
                }
                
                //foto5
                $nombre_archivo = "falla".$contFotos."_".$nuevoId."_".$fecha.".jpg";  
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen5']['name'] == "") {
                    $ruta2 = $this->input->post("imagen5CargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen5']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaFalla = $evidenciaFalla.$nombre_archivo."|";
                    $contFotos++;
                }
                
                //arma datos de guardado
                $descripcionFalla = $this->input->post("descripcionFalla");  
                $idMaquina = $this->input->post("maquina");  
                $idUsuario = $this->session->userdata('idUsuario');
                //'idUsuario' => $this->session->userdata('idUsuario'),
                
                $fecha = $dt->format("Y-m-d H:i:s");
                echo $descripcionFalla."------>".$evidenciaFalla."------->".$idUsuario."------>".$idMaquina."-------->".$fecha;
                
                $data = array(
                    "descripcionFalla" => $descripcionFalla,
                    'evidenciaFalla' => $evidenciaFalla,
                    "idUsuario" => $idUsuario,
                    'idMaquina' => $idMaquina,
                    'fecha' => $fecha
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'fallas/insertar_falla.php');
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
                    $this->session->set_flashdata('correcto', "Registro guardado correctamente <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se guardó el registro <br>");
                }        
                redirect('/index.php/fallas_controller/mostrarFallas');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarFalla($idFalla) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'fallas/obtener_falla_por_id.php?idFalla='.$idFalla;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            if ($datos->{'estado'}==1) {
                $data = array(
                    'maquinasSimple'=> $this->maquinasSimple,
                    'falla'=>$datos->{'falla'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Fallas',
                    'usuario_herramental' => $this->util_controller->verificaUsuarioHerramental($this->session->userdata('idUsuario'))
                        );
                if ($this->session->userdata('permisos')=="Administrador") {
                    $this->load->view('layouts/headerAdministrador_view',$data);
                }
                if ($this->session->userdata('permisos')=="Técnico") {
                    $this->load->view('layouts/headerTecnico_view',$data);
                }
                $this->load->view('fallas/actualizaFalla_view',$data);
                $this->load->view('layouts/pie_view',$data);
                
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function eliminarFalla($idFalla) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            //trae el registro a eliminar para borrar sus evidencias de fallas
            $url = RUTAWS.'fallas/obtener_falla_por_id.php?idFalla='.$idFalla;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataParaFotos = curl_exec($ch);
            $datos = json_decode($dataParaFotos);
            curl_close($ch);
            
            //trae el registro a eliminar para borrar sus evidencias de soluciones
            $url = RUTAWS.'soluciones/obtener_soluciones_por_id_falla.php?idFalla='.$idFalla;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataParaSoluciones = curl_exec($ch);
            $datosSolucion = json_decode($dataParaSoluciones);
            curl_close($ch);
            //echo $datosSolucion->{'estado'};
            //echo "<br>".$datosSolucion->{'soluciones'}[0]->{'evidenciaSolucion'};
            
            if ($datos->{'estado'}==1) {
                //elimina archivos de fallas
                $fotosArray = explode("|",$datos->{'falla'}->{'evidenciaFalla'});
                foreach($fotosArray as $value) {
                    $project = explode('/', $_SERVER['REQUEST_URI'])[1];
                    $rutaArchivo = $_SERVER['DOCUMENT_ROOT']."/".$project."/evidenciasfallas_soluciones/".$value;
                    if (file_exists($rutaArchivo)){
                        unlink($rutaArchivo);
                    }
                }
                
                //elimina archivos de soluciones
                if ($datosSolucion->{'estado'} == 1) {
                    foreach($datosSolucion->{'soluciones'} as $fila){
                        echo "<br>".$fila->{'evidenciaSolucion'};
                        $fotosArraySoluciones = explode("|",$fila->{'evidenciaSolucion'});
                        foreach($fotosArraySoluciones as $value) {
                            $project = explode('/', $_SERVER['REQUEST_URI'])[1];
                            $rutaArchivo = $_SERVER['DOCUMENT_ROOT']."/".$project."/evidenciasfallas_soluciones/".$value;
                            if (file_exists($rutaArchivo)){
                                unlink($rutaArchivo);
                            }
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('correcto', "Error. No se eliminó el registro <br>");
                redirect('/index.php/fallas_controller/mostrarFallas');
            }
            
            //para saber el tipo de borrado si es en cascada o normal solo en la tabla
            $tipoborrado = 0; //1 para borrado en cascada, 2 para borrado solo de la falla sin las soluciones
            if ($datosSolucion->{'estado'} == 1) {
                $tipoborrado = 1;
            } else {
                $tipoborrado = 2;
            }
            $data = array("idFalla" => $idFalla,"tipoborrado" => $tipoborrado);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'fallas/borrar_falla.php');
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
            echo $result;
            curl_close($ch);
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro eliminado correctamente <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se eliminó el registro <br>");
            }        
            redirect('/index.php/fallas_controller/mostrarFallas');
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

