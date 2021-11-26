    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Proveedores_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $proveedoresGlobal;
    
    private $historicoProveedoresGlobal;

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
        $this->load->model('mupload_model');
        
        //llamado a controlador util global
        $this->load->library('../controllers/util_controller');
        
        $this->datosEmpresaGlobal = $this->util_controller->cargaDatosEmpresa();
        $this->nombreEmpresaGlobal = $this->datosEmpresaGlobal[0]->{'nombreEmpresa'};
        $this->proveedoresGlobal = $this->util_controller->cargaDatosProveedores();
        
//        $cadena = file_get_contents('bd.txt');
//        $this->gatewayRest =  $cadena;        
    }

    function index(){
        $this->load->view('login_view');
    }
    
    function regresa() {
        echo "error";
    }
    
    function mostrarProveedores() {
        if ($this->is_logged_in()){
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($this->proveedoresGlobal)) {            
                if (count($this->proveedoresGlobal) > 0) {
                    $data = array('proveedores'=>$this->proveedoresGlobal,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'sucursal' => $this->session->userdata('sucursal'),
                        'permisos' => $this->session->userdata('permisos'),
                        'historicoPreciosProveedores' => $this->historicoProveedoresGlobal,
                        'opcionClickeada' => '5'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('proveedores/adminProveedores_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('proveedores/adminProveedores_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('proveedores/adminProveedores_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }                 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoProveedor() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '5'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('proveedores/nuevoProveedor_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoProveedorFromFormulario(){
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $nombre_empresa = $this->input->post("nombre_empresa");
                $direccion_empresa = $this->input->post("direccion_empresa");
                $nombre_contacto = $this->input->post("nombre_contacto");
                $email_contacto = $this->input->post("email_contacto");
                $numero_telefonico = $this->input->post("numero_telefonico");                

                $data = array(
                    "nombre_empresa" => $nombre_empresa,
                    "direccion_empresa" => $direccion_empresa,
                    "nombre_contacto" => $nombre_contacto,
                    "email_contacto" => $email_contacto,
                    "numero_telefonico" => $numero_telefonico
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'proveedores/insertar_proveedor.php');
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
                redirect('/index.php/proveedores_controller/mostrarProveedores');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarProveedor($idProveedor) {
        if ($this->is_logged_in()){
            $url = RUTAWS.'proveedores/obtener_proveedor_por_id.php?idProveedor='.$idProveedor;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if ($datos->{'estado'}==1) {
                $data = array(
                    'proveedor'=>$datos->{'proveedor'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '5'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('proveedores/actualizaProveedor_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarProveedorFromFormulario(){
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idProveedor = $this->input->post("idProveedor");
                $nombre_empresa = $this->input->post("nombre_empresa");
                $direccion_empresa = $this->input->post("direccion_empresa");
                $nombre_contacto = $this->input->post("nombre_contacto");
                $email_contacto = $this->input->post("email_contacto");
                $numero_telefonico = $this->input->post("numero_telefonico");               

                $data = array(
                    "idProveedor" => $idProveedor,
                    "nombre_empresa" => $nombre_empresa,
                    "direccion_empresa" => $direccion_empresa,
                    "nombre_contacto" => $nombre_contacto,
                    "email_contacto" => $email_contacto,
                    "numero_telefonico" => $numero_telefonico
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'proveedores/actualizar_proveedor.php');
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
                echo $result;
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro actualizado exitosamente. <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
                }  
                redirect('/index.php/proveedores_controller/mostrarProveedores');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function eliminarProveedor($idProveedor) {
        if ($this->is_logged_in()){
            $data = array("idProveedor" => $idProveedor);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'proveedores/borrar_proveedor.php');
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
                $this->session->set_flashdata('correcto', "Registro Eliminado correctamente. <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se eliminó el registro <br>");
            }              
            redirect('/index.php/proveedores_controller/mostrarProveedores');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    //Importar desde Excel con libreria de PHPExcel
    public function importarProveedoresExcel(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'sucursal' => $this->session->userdata('sucursal'),
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '5'
                );
            $this->load->view('layouts/header_view',$data);
            $this->load->view('proveedores/importarProveedoresFromExcel_view',$data);
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
            foreach ($sheetData as $index => $value) {            
                if ( $index != 1 ){
                    $arr_datos = array(
                            'empresa' => $value['A'],
                            'nombre' => $value['B'],
                            'apellidos' => $value['C'],
                            'telefono_casa' => $value['D'],
                            'telefono_celular' => $value['E'],
                            'direccion1' => $value['F'],
                            'direccion2' => $value['G'],
                            'rfc' => $value['H'],
                            'email' => $value['I'],
                            'ciudad' => $value['J'],
                            'estado' => $value['K'],
                            'cp' => $value['L'],
                            'pais' => $value['M'],
                            'comentarios' => $value['N'],
                            'noCuenta' => $value['O']
                    ); 
                    foreach ($arr_datos as $llave => $valor) {
                        $arr_datos[$llave] = $valor;
                    }
                    //$this->db->insert('usuarios',$arr_datos);

                    //Llamada de ws para insertar
                    $data_string = json_encode($arr_datos);
                    $ch = curl_init(RUTAWS.'proveedores/insertar_proveedor.php');
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
            $this->mostrarProveedores();
        } else {
            redirect($this->cerrarSesion());
        }
    }        
    //Fin Importar desde Excel con libreria de PHPExcel
    
    //Exportar datos a Excel
    public function exportarProveedorExcel(){
        if ($this->is_logged_in()){
            //llamadod de ws
            # An HTTP GET request example
            $url = RUTAWS.'proveedores/obtener_proveedores.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //fin llamado de ws
            $id=$this->uri->segment(3);
    //        $nilai=$this->login_model->obtieneUsuarios();
            $nilai=$datos->{'proveedores'};
    //        if (isset($datos->{'usuarios'})) {
    //            foreach($nilai as $h){
    //                echo "azul";
    //            }
    //        }
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            $heading=array('Empresa','Nombre','Apellidos','Telefono_casa',
                'Telefono_celular','Direccion1','Direccion2','RFC',
                'Email','Ciudad','Estado','CP','País','Comentarios','No. Cuenta');
            $this->load->library('excel');
            //Create a new Object
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Proveedores");

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
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'empresa'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$n->{'nombre'});
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'apellidos'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'telefono_casa'});
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'telefono_celular'});
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$n->{'direccion1'});
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'direccion2'});
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$n->{'rfc'});
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$n->{'email'});
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row,$n->{'ciudad'});
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$row,$n->{'estado'});
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$row,$n->{'cp'});
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$row,$n->{'pais'});
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$row,$n->{'comentarios'});
                $objPHPExcel->getActiveSheet()->setCellValue('O'.$row,$n->{'noCuenta'});
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
            $objPHPExcel->getActiveSheet()->getStyle('A1:O'.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Proveedores.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }	
    //fin exportar a excel
    
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

