<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Soluciones_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuariosGlobal;
    private $areasGlobal;
    
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
                    'opcionClickeada' => '0'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('principal_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarSolucionesPorIdFalla($idFalla) {
        if ($this->is_logged_in()){
            //trae el registro de la falla
            $url = RUTAWS.'fallas/obtener_falla_por_id.php?idFalla='.$idFalla;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataParaFotos = curl_exec($ch);
            $datosFalla = json_decode($dataParaFotos);
            curl_close($ch);
            
            $url = RUTAWS.'soluciones/obtener_soluciones_por_id_falla.php?idFalla='.$idFalla;
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
                    'soluciones'=>$datos->{'soluciones'},
                    'idUsuario' => $this->session->userdata('idUsuario'),                  
                    'falla' => $datosFalla->{'falla'},
                    'idFalla' => $idFalla,    
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Fallas',
                    'usuario_herramental' => $this->util_controller->verificaUsuarioHerramental($this->session->userdata('idUsuario'))
                        );
                if ($this->session->userdata('permisos')=="Administrador") {
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('soluciones/adminSolucionesPorFalla_view',$data);
                }
                if ($this->session->userdata('permisos')=="Técnico") {
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('soluciones/adminSolucionesPorFallaTecnico_view',$data);
                }
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'soluciones'=>null,
                    'falla' => $datosFalla->{'falla'},
                    'idFalla' => $idFalla,    
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Fallas',
                    'usuario_herramental' => $this->util_controller->verificaUsuarioHerramental($this->session->userdata('idUsuario'))
                        );
                if ($this->session->userdata('permisos')=="Administrador") {
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('soluciones/adminSolucionesPorFalla_view',$data);
                }
                if ($this->session->userdata('permisos')=="Técnico") {
                    $this->load->view('layouts/headerTecnico_view',$data);
                    $this->load->view('soluciones/adminSolucionesPorFallaTecnico_view',$data);
                }
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoSolucion($idFalla) {
        if ($this->is_logged_in()){
            //trae el registro de la falla
            $url = RUTAWS.'fallas/obtener_falla_por_id.php?idFalla='.$idFalla;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataParaFotos = curl_exec($ch);
            $datos = json_decode($dataParaFotos);
            curl_close($ch);
            
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'falla' => $datos->{'falla'},
                'idUsuario' => $this->session->userdata('idUsuario'),                  
                'descripcionFalla' => $datos->{'falla'}->{'descripcionFalla'},
                'idFalla' => $idFalla,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'sucursal' => $this->session->userdata('sucursal'),
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
            $this->load->view('soluciones/nuevoSolucion_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function verSolucion($idSolucion,$idFalla) {
        if ($this->is_logged_in()){
            //trae el registro de la solucion
            $url = RUTAWS.'soluciones/obtener_solucion_por_id.php?idSolucion='.$idSolucion;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataParaFotos = curl_exec($ch);
            $datos = json_decode($dataParaFotos);
            curl_close($ch);
            
            //trae el registro de la falla
            $url = RUTAWS.'fallas/obtener_falla_por_id.php?idFalla='.$idFalla;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataParaFotos = curl_exec($ch);
            $datosFalla = json_decode($dataParaFotos);
            curl_close($ch);
            
            
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'falla' => $datosFalla->{'falla'},
                'solucion' => $datos->{'solucion'},
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'sucursal' => $this->session->userdata('sucursal'),
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
            $this->load->view('soluciones/actualizaSolucion_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function nuevoSolucionFromFormulario($idFalla) {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                //obtiene ultimo registro insertado de las fallas para generar el nombre de la referencia de foto
                $url = RUTAWS.'soluciones/obtener_ultimoid_soluciones.php';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $dataLastId = curl_exec($ch);
                $datosLastId = json_decode($dataLastId);
                curl_close($ch);     

                $nuevoId = 0;
                if ($datosLastId->{'estado'} == 1) {
                    $nuevoId = $datosLastId->{'soluciones'}[0]->{'idSolucion'} + 1;
                    //$nombre_archivo_Foto_antes = ($datosLastId->{'fallas'}[0]->{'idFalla'} + 1)."_foto_antes.jpg";  
                } else {
                    $nuevoId = 1;            
                }            

                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fecha = $dt->format("Y_m_d_H_i_s");
                $evidenciaSolucion = "";
                
                //recibe las fotos
                $contFotos = 1;
                //foto1
                $nombre_archivo = "solucion".$contFotos."_".$nuevoId."_".$fecha.".jpg";  
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen']['name'] == "") {
                    $ruta2 = $this->input->post("imagenCargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaSolucion = $evidenciaSolucion.$nombre_archivo."|";
                    $contFotos++;
                }
                
                //foto2
                $nombre_archivo = "solucion".$contFotos."_".$nuevoId."_".$fecha.".jpg";  
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen2']['name'] == "") {
                    $ruta2 = $this->input->post("imagen2CargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen2']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaSolucion = $evidenciaSolucion.$nombre_archivo."|";
                    $contFotos++;
                }
                
                //foto3
                $nombre_archivo = "solucion".$contFotos."_".$nuevoId."_".$fecha.".jpg";  
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['imagen3']['name'] == "") {
                    $ruta2 = $this->input->post("imagen3CargadaHidden");
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['imagen3']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaSolucion = $evidenciaSolucion.$nombre_archivo."|";
                    $contFotos++;
                }

                //archivo
                $tipoArchivo = explode(".",$_FILES['archivo']['name']);
                $nombre_archivo = "solucion".$contFotos."_".$nuevoId."_".$fecha.".".$tipoArchivo[sizeof($tipoArchivo) - 1];
                $ruta2 = "";
                $ruta = "";  
                if ($_FILES['archivo']['name'] == "") {
                    $ruta2 = "";
                } else {
                    $ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/evidenciasfallas_soluciones/".$nombre_archivo;
                    move_uploaded_file($_FILES['archivo']['tmp_name'],$ruta);                
                    $ruta2 = "".base_url()."evidenciasfallas_soluciones/".$nombre_archivo;
                    $evidenciaSolucion = $evidenciaSolucion.$nombre_archivo."|";
                    $contFotos++;
                }
                
                //arma datos de guardado
                $descripcionSolucion = $this->input->post("descripcionSolucion");  
                $idUsuario = $this->session->userdata('idUsuario');
                
                $fecha = $dt->format("Y-m-d H:i:s");
                //echo $descripcionSolucion."------>".$evidenciaSolucion."------->".$idUsuario."------>".$fecha;
                
                $data = array(
                    "descripcionSolucion" => $descripcionSolucion,
                    'evidenciaSolucion' => $evidenciaSolucion,
                    "idUsuario" => $idUsuario,
                    "idFalla" => $idFalla,
                    'fecha' => $fecha
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'soluciones/insertar_solucion.php');
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
                //redirect('/index.php/fallas_controller/mostrarFallas');
                //redirect($this->mostrarSolucionesPorIdFalla($idFalla));
                redirect('/index.php/soluciones_controller/mostrarSolucionesPorIdFalla/'.$idFalla);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function eliminarSolucion($idSolucion,$idFalla) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            //trae el registro a eliminar para borrar sus evidencias
            $url = RUTAWS.'soluciones/obtener_solucion_por_id.php?idSolucion='.$idSolucion;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataParaFotos = curl_exec($ch);
            $datosSolucion = json_decode($dataParaFotos);
            curl_close($ch);
            
            if ($datosSolucion->{'estado'}==1) {
                $fotosArray = explode("|",$datosSolucion->{'solucion'}->{'evidenciaSolucion'});
                foreach($fotosArray as $value) {
                    $project = explode('/', $_SERVER['REQUEST_URI'])[1];
                    $rutaArchivo = $_SERVER['DOCUMENT_ROOT']."/".$project."/evidenciasfallas_soluciones/".$value;
                    if (file_exists($rutaArchivo)){
                        unlink($rutaArchivo);
                    }
                }
            } else {
                $this->session->set_flashdata('correcto', "Error. No se eliminó el registro <br>");
                redirect('/index.php/soluciones_controller/mostrarSolucionesPorIdFalla/'.$idFalla);
            }
            $data = array("idSolucion" => $idSolucion);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'soluciones/borrar_solucion.php');
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
            redirect('/index.php/soluciones_controller/mostrarSolucionesPorIdFalla/'.$idFalla);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
//    function mostrarAreas() {
//        if ($this->is_logged_in()){
//            $url = RUTAWS.'areas/obtener_areas.php';
//            $ch = curl_init($url);
//            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $data = curl_exec($ch);
//            $datos = json_decode($data);
//            curl_close($ch);
//            $areas;
//            //echo $data;
//            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
//            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
//            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
//            if ($datos->{'estado'}==1) {
//                $data = array('areas'=>$datos->{'areas'},
//                    'usuarioDatos' => $this->session->userdata('nombre'),
//                    'fecha' => $fechaIngreso,
//                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
//                    'permisos' => $this->session->userdata('permisos'),
//                    'opcionClickeada' => '7'
//                        );
//                $this->load->view('layouts/headerAdministrador_view',$data);
//                $this->load->view('areas/adminAreas_view',$data);
//                $this->load->view('layouts/pie_view',$data);
//            } else {
//                $this->load->view('layouts/headerAdministrador_view',$data);
//                $this->load->view('principal_view',$data);
//                $this->load->view('layouts/pie_view',$data);
//            } 
//        } else {
//            redirect($this->cerrarSesion());
//        }
//    }
//
//    function actualizarArea($idArea) {
//        if ($this->is_logged_in()){
//            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
//            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
//            $url = RUTAWS.'areas/obtener_area_por_id.php?idArea='.$idArea;
//            $ch = curl_init($url);
//            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $data = curl_exec($ch);
//            $datos = json_decode($data);
//            curl_close($ch);
//            if ($datos->{'estado'}==1) {
//                $data = array(
//                    'area'=>$datos->{'area'},
//                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
//                    'usuarioDatos' => $this->session->userdata('nombre'),
//                    'fecha' => $fechaIngreso,
//                    'permisos' => $this->session->userdata('permisos'),
//                    'opcionClickeada' => '7'
//                        );
//                $this->load->view('layouts/headerAdministrador_view',$data);
//                $this->load->view('areas/actualizaArea_view',$data);
//                $this->load->view('layouts/pie_view',$data);
//            } else {
//                echo "error";
//            }
//        } else {
//            redirect($this->cerrarSesion());
//        }
//    }
//
//    function actualizarAreaFromFormulario() {
//        if ($this->is_logged_in()){
//            if ($this->input->post('submit')){
//                $idArea = $this->input->post("idArea");
//                $descripcion = $this->input->post("descripcion");
//                $data = array(
//                    "idArea"=>  $idArea,
//                    "descripcion" => $descripcion
//                    );  
//                $data_string = json_encode($data);
//                $ch = curl_init(RUTAWS.'areas/actualizar_area.php');
//                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                    'Content-Type: application/json',
//                    'Content-Length: ' . strlen($data_string))
//                );
//                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//                $result = curl_exec($ch);
//                curl_close($ch);
//                $resultado = json_decode($result, true);
//                if ($resultado['estado']==1) {
//                    $this->session->set_flashdata('correcto', "Registro actualizado correctamente <br>");
//                } else {
//                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
//                }        
//                redirect('/index.php/areas_controller/mostrarAreas');
//            }
//        } else {
//            redirect($this->cerrarSesion());
//        }
//    }
//
//    /*
//    //Importar desde Excel con libreria de PHPExcel
//    public function importarUsersExcel(){
//        if ($this->is_logged_in()){
//            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
//            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
//            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal,
//                'usuarioDatos' => $this->session->userdata('nombre'),
//                'fecha' => $fechaIngreso,
//                'permisos' => $this->session->userdata('permisos'),
//                'opcionClickeada' => '7'
//                );
//            $this->load->view('layouts/header_view',$data);
//            $this->load->view('usuarios/importarUsersFromExcel_view',$data);
//            $this->load->view('layouts/pie_view',$data);
//        } else {
//            redirect($this->cerrarSesion());
//        }
//    }        
//
//    //Importar desde Excel con libreria de PHPExcel
//    public function importarExcel(){
//        if ($this->is_logged_in()){
//            //Cargar PHPExcel library
//            $this->load->library('excel');
//            $name   = $_FILES['excel']['name'];
//            $tname  = $_FILES['excel']['tmp_name'];
//            $obj_excel = PHPExcel_IOFactory::load($tname);       
//            $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
//            $arr_datos = array();
//            $result;
//            foreach ($sheetData as $index => $value) {            
//                if ( $index != 1 ){
//                    $arr_datos = array(
//                            'usuario' => $value['A'],
//                            'clave' => $value['B'],
//                            'permisos' => $value['C'],
//                            'nombre' => $value['D'],
//                            'apellido_paterno' => $value['E'],
//                            'apellido_materno' => $value['F'],
//                            'telefono_casa' => $value['G'],
//                            'telefono_celular' => $value['H'],
//                            'idSucursal' => $value['I']
//                    ); 
//                    foreach ($arr_datos as $llave => $valor) {
//                        $arr_datos[$llave] = $valor;
//                    }
//                    //$this->db->insert('usuarios',$arr_datos);
//
//                    //Llamada de ws para insertar
//                    $data_string = json_encode($arr_datos);
//                    $ch = curl_init(RUTAWS.'usuarios/insertar_usuario.php');
//                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                        'Content-Type: application/json',
//                        'Content-Length: ' . strlen($data_string))
//                    );
//                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//                    //execute post
//                    $result = curl_exec($ch);
//                    //close connection
//                    curl_close($ch);
//                    //echo $result;
//                } 
//            }
//            $resultado = json_decode($result, true);
//            if ($resultado['estado']==1) {
//                $this->session->set_flashdata('correcto', "Registro guardado <br>");
//            } else {
//                $this->session->set_flashdata('correcto', "Error. No se guardó el registro <br>");
//            }        
//            redirect('/usuarios_controller/mostrarUsuarios');
//        } else {
//            redirect($this->cerrarSesion());
//        }
//    }        
//    //Fin Importar desde Excel con libreria de PHPExcel
//    
//    //Exportar datos a Excel
//    public function exportarExcel(){
//        if ($this->is_logged_in()){
//            //llamadod de ws
//            # An HTTP GET request example
//            $url = RUTAWS.'usuarios/obtener_usuarios.php';
//            $ch = curl_init($url);
//            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $data = curl_exec($ch);
//            $datos = json_decode($data);
//            curl_close($ch);
//            //fin llamado de ws
//            //$id=$this->uri->segment(3);
//            $nilai=$datos->{'usuarios'};
//            $totn = 0;
//            foreach($nilai as $h){
//                $totn = $totn + 1;
//            }
//            $heading=array('USUARIO','CLAVE','PERMISOS','NOMBRE','AP.PATERNO','AP.MATERNO','TEL.CASA','CELULAR','SUCURSAL');
//            $this->load->library('excel');
//            //Create a new Object
//            $objPHPExcel = new PHPExcel();
//            $objPHPExcel->getActiveSheet()->setTitle("Empleados");
//            //Loop Heading
//            $rowNumberH = 1;
//            $colH = 'A';
//            foreach($heading as $h){
//                $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
//                $colH++;    
//            }
//            //Loop Result
//            //$totn=$nilai->num_rows();
//            $maxrow=$totn+1;
//            $row = 2;
//            $no = 1;
//            foreach($nilai as $n){
//                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'usuario'});
//                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,"1");
//                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'permisos'});
//                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'nombre'});
//                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'apellido_paterno'});
//                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$n->{'apellido_materno'});
//                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'telefono_casa'});
//                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$n->{'telefono_celular'});
//                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$n->{'descripcionSucursal'});
//                $row++;
//                $no++;
//            }
//            //Freeze pane
//            $objPHPExcel->getActiveSheet()->freezePane('A2');
//            //Cell Style
//            $styleArray = array(
//                    'borders' => array(
//                            'allborders' => array(
//                                    'style' => PHPExcel_Style_Border::BORDER_THIN
//                            )
//                    )
//            );
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I'.$maxrow)->applyFromArray($styleArray);
//            //Save as an Excel BIFF (xls) file
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
//            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Disposition: attachment;filename="Empleados.xls"');
//            header('Cache-Control: max-age=0');
//            $objWriter->save('php://output');
//            exit();
//        } else {
//            redirect($this->cerrarSesion());
//        }
//    }	
//    //fin exportar a excel
//    */
    
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

