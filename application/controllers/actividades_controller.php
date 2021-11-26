<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Actividades_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuariosGlobal;
    private $areasGlobal;
    private $maquinasGlobal;
    private $actividadesGlobal;
	
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
        $this->actividadesGlobal = $this->util_controller->cargaDatosActividades();
        
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
    
    function mostrarActividades() {
        if ($this->is_logged_in()){
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'actividades' => $this->actividadesGlobal,
                'maquinas' => $this->maquinasGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => 'Actividades'
                    );    
            if ($this->session->userdata('permisos') == "Administrador") {
                $this->load->view('layouts/headerAdministrador_view',$data);
            } 
            if ($this->session->userdata('permisos') == "Técnico") {
                $this->load->view('layouts/headerTecnico_view',$data);
            } 
            $this->load->view('actividades/adminActividades_view',$data);
            $this->load->view('layouts/pie_view',$data);            
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function nuevoActividades() {
          if ($this->is_logged_in()){
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'actividades' => $this->actividadesGlobal,
                 'maquinas' => $this->maquinasGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => 'Actividades'
                    );      
            if ($this->session->userdata('permisos') == "Administrador") {
                $this->load->view('layouts/headerAdministrador_view',$data);
            } 
            if ($this->session->userdata('permisos') == "Técnico") {
                $this->load->view('layouts/headerTecnico_view',$data);
            } 
            $this->load->view('actividades/registroActividades_view',$data);
            $this->load->view('layouts/pie_view',$data);            
        } else {
            redirect($this->cerrarSesion());
        }      
    }

    function nuevoActividadesFromFormulario() {
        if ($this->is_logged_in()){
                            $mensaje = "";
            if ($this->input->post('submit')){
                $actividadesGlobales = $this->input->post("actividadesHidden");
                $data = array(
                "actividadesGlobales" => $actividadesGlobales
                );
                //Inserto las actividades
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'actividades/insertar_actividades.php');
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
                $resultado1 = json_decode($result, true);
                if ($resultado1['estado']==1) {
                    $mensaje = "Los actividades se guardadon correctamente <br>";
                } else {
                    $mensaje = "Error. No se guardarón los registros de actividades <br>";
                } 
                $this->session->set_flashdata('correcto', $mensaje);
                redirect('/index.php/actividades_controller/mostrarActividades');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarActividad($idActividad) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'actividades/obtener_actividad_por_id.php?idActividad='.$idActividad;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            if ($datos->{'estado'}==1) {
                $data = array(
                    'maquinas' => $this->maquinasGlobal,
                    'actividad'=>$datos->{'actividad'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => 'Actividades'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('actividades/actualizaActividad_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $this->session->set_flashdata('correcto', "Error");
                redirect('/index.php/actividades_controller/mostrarActividades');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarActividadFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idActividad = $this->input->post("idActividad");
                $descripcion_actividad = $this->input->post("descripcion_actividad");
                $frecuencia = $this->input->post("frecuencia");
                $nombre_maquina = $this->input->post("nombreMaquina");
                $data = array(
                    "idActividad"=>  $idActividad,
                    "descripcion_actividad" => $descripcion_actividad,
                    "frecuencia" => $frecuencia,
                    "nombre_maquina" => $nombre_maquina
                    );  
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'actividades/actualizar_actividad.php');
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
                redirect('/index.php/actividades_controller/mostrarActividades');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }    
    
    function eliminarActividad($idActividad) {
        if ($this->is_logged_in()){
            $data = array("idActividad" => $idActividad);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'actividades/borrar_actividad.php');
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
            redirect('/index.php/actividades_controller/mostrarActividades');
        } else {
            redirect($this->cerrarSesion());
        }
    }    
    
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
                'opcionClickeada' => 'Actividades'
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
    
    //Exportar datos a Excel
    public function exportarExcel(){
        if ($this->is_logged_in()){
            //llamadod de ws
            # An HTTP GET request example
            $url = RUTAWS.'usuarios/obtener_usuarios.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //fin llamado de ws
            //$id=$this->uri->segment(3);
            $nilai=$datos->{'usuarios'};
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            $heading=array('USUARIO','CLAVE','PERMISOS','NOMBRE','AP.PATERNO','AP.MATERNO','TEL.CASA','CELULAR','SUCURSAL');
            $this->load->library('excel');
            //Create a new Object
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Empleados");
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
            foreach($nilai as $n){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'usuario'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,"1");
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'permisos'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'nombre'});
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'apellido_paterno'});
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$n->{'apellido_materno'});
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'telefono_casa'});
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$n->{'telefono_celular'});
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$n->{'descripcionSucursal'});
                $row++;
                $no++;
            }
            //Freeze pane
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            //Cell Style
            $styleArray = array(
                    'borders' => array(
                            'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                    )
            );
            $objPHPExcel->getActiveSheet()->getStyle('A1:I'.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Empleados.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }	
    //fin exportar a excel
    */
    
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

