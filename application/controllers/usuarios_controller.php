<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usuarios_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuarios_herramentales;
    private $usuarioHerramental;
    
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
        
//        $cadena = file_get_contents('bd.txt');
//        $this->gatewayRest =  $cadena;
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
                    'idUsuario'=>$this->session->userdata('idUsuario'),
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'permisos'=>$this->session->userdata('permisos'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
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

    function verificaUsuario() {
        $usuario = $this->input->post("usuario");
        //$clave = md5($this->input->post("clave"));
        $clave = $this->input->post("clave");
        $data = array(
            "usuario" => $usuario, 
            "clave" => $clave
                );
        $data_string = json_encode($data);
        $ch = curl_init(RUTAWS.'usuarios/verifica_usuario2.php');
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
        $datos = json_decode($result);
        if ($datos->{'estado'}==1) {
            $idUsuario = $datos->{'usuario'}->{'idUsuario'};
            $usuario = $datos->{'usuario'}->{'usuario'};
            $clave = $datos->{'usuario'}->{'clave'};
            $permisos = $datos->{'usuario'}->{'permisos'};
            $nombre = $datos->{'usuario'}->{'nombre'};
            $apellido_paterno = $datos->{'usuario'}->{'apellido_paterno'};
            $apellido_materno = $datos->{'usuario'}->{'apellido_materno'};
            //crea campos de sesion
            $this->session->set_userdata('nombre', $nombre." ".$apellido_paterno." ".$apellido_materno);
            $this->session->set_userdata('permisos', $permisos);
            $this->session->set_userdata('usuario', $usuario);					
            $this->session->set_userdata('clave', $clave);					
            $this->session->set_userdata('idUsuario', $idUsuario);
            $this->session->set_userdata('logueado', TRUE);
            //Asigna el nombre de la sucursal para mostrarlo en el sistema
            $sucursalDescripcion = "";
       
            //Fin Asigna el nombre de la sucursal para mostrarlo en el sistema
            $this->session->set_userdata('sucursal',$sucursalDescripcion);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            //$this->session->set_userdata('last_activity', $fechaIngreso);
            //$this->session->set_userdata('fechaIngreso', $fechaIngreso);
            
            //fin crea campos de sesion
            $data = array(
                    'idUsuario'=>$idUsuario,
                    'usuario'=>$usuario,'clave'=>$clave,
                    'permisos'=>$permisos,'nombre'=>$nombre,
                    'apellido_paterno'=>$apellido_paterno,
                    'apellido_materno'=>$apellido_materno,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'opcionClickeada' => 'Inicio'
                );
            if ($permisos == "Administrador") {
                $this->load->view('layouts/headerAdministrador_view',$data);
            } else {
                $data['usuario_herramental'] = $this->util_controller->verificaUsuarioHerramental($idUsuario);
                if ($permisos == "Técnico") {
                    $this->load->view('layouts/headerTecnico_view',$data);
                } 
                if ($permisos == "Otros") {
                    $this->load->view('layouts/headerOtros_view',$data);
                } 
            }
            $this->load->view('principal_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            $this->session->set_flashdata('correcto', "Error. Vuelva a intentarlo.");
            redirect($this->index());
        }
    }
    
    function verificaUsuarioOperador($usuario,$clave) {
        $data = array(
            "usuario" => $usuario, 
            "clave" => $clave
                );
        $data_string = json_encode($data);
        $ch = curl_init(RUTAWS.'usuarios/verifica_usuario_operador.php');
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
        $datos = json_decode($result);
        if ($datos->{'estado'}==1) {
            $idUsuarioOperador = $datos->{'usuario'}->{'idUsuarioOperador'};
            $nombre = $datos->{'usuario'}->{'nombre'};
            $apellido_paterno = $datos->{'usuario'}->{'apellido_paterno'};
            $apellido_materno = $datos->{'usuario'}->{'apellido_materno'};
            $this->session->set_userdata('nombre', $nombre." ".$apellido_paterno." ".$apellido_materno);
            $this->session->set_userdata('permisos', $permisos);
            $this->session->set_userdata('idUsuarioOperador', $idUsuarioOperador);
            $this->session->set_userdata('logueado', TRUE);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                    'idUsuarioOperador'=>$idUsuarioOperador,
                    'usuario'=>$usuario,'clave'=>$clave,
                    'permisos'=>$this->session->userdata('permisos'),'nombre'=>$nombre,
                    'apellido_paterno'=>$apellido_paterno,
                    'apellido_materno'=>$apellido_materno,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'opcionClickeada' => 'Atencion'
                );
            $this->load->view('layouts/headerOperador_view',$data);
            $this->load->view('turnos/atencion_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            $this->session->set_flashdata('correcto', "Error. Vuelva a intentarlo.");
            redirect($this->index());
        }
    }
    
    function mostrarUsuarios() {
        if ($this->is_logged_in()){
            $url = RUTAWS.'usuarios/obtener_usuarios.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            $usuarios;
            $i=0;
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if ($datos->{'estado'}==1) {
                $data = array('usuarios'=>$datos->{'usuarios'},
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('usuarios/adminUsers_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('principal_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarUsuario($idUsuario) {
        if ($this->is_logged_in()) {
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'usuarios/obtener_usuario_por_id.php?idUsuario='.$idUsuario;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            if ($datos->{'estado'}==1) {
                $data = array(
                    'usuario'=>$datos->{'usuario'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('usuarios/actualizaUsuario_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarUsuarioFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                //LLamadfo de WS
                $permisos = $this->input->post('tipoUsuario');
                $idUsuario = $this->input->post("idUsuario");
                $usuario = $this->input->post("usuario");
                $clave = $this->input->post("clave");
                $claveAnt = $this->input->post("claveAnt");
                if ($clave == "") {
                    $clave = $claveAnt;
                } else {
                    $clave = $this->input->post("clave");
                }
                $nombre = $this->input->post("nombre");
                $apellido_paterno = $this->input->post("apellido_paterno");
                $apellido_materno = $this->input->post("apellido_materno");
                $data = array("idUsuario" => $idUsuario, 
                    "usuario" => $usuario, 
                    "clave" => $clave, 
                    "permisos" => $permisos, 
                    "nombre" => $nombre, 
                    "apellido_paterno" => $apellido_paterno, 
                    "apellido_materno" => $apellido_materno
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'usuarios/actualizar_usuario.php');
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
                redirect('/index.php/usuarios_controller/mostrarUsuarios');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function eliminarUsuario($idUsuario) {
        if ($this->is_logged_in()){
            $data = array("idUsuario" => $idUsuario);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'usuarios/borrar_usuario.php');
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
            redirect('/index.php/usuarios_controller/mostrarUsuarios');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function nuevoUsuario() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'sucursal' => $this->session->userdata('sucursal'),
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '7'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('usuarios/nuevoUsuario_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoUsuarioFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
//                $usuario = str_replace(" ", "", $this->input->post("usuario"));
                $usuario = $this->input->post("usuario");
                $clave = $this->input->post("clave");
                $nombre = $this->input->post("nombre");
                $apellido_paterno = $this->input->post("apellido_paterno");
                $apellido_materno = $this->input->post("apellido_materno");
                $permisos = $this->input->post("tipoUsuario");

                $data = array(
                    "usuario" => $usuario, 
                    "clave" => $clave, 
                    "permisos" => $permisos, 
                    "nombre" => $nombre, 
                    "apellido_paterno" => $apellido_paterno, 
                    "apellido_materno" => $apellido_materno
                        );
                $data_string = json_encode($data);
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
                $result = curl_exec($ch);
                curl_close($ch);
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro guardado <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se guardó el registro <br>");
                }        
                redirect('/index.php/usuarios_controller/mostrarUsuarios');
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
    
    function verificaUsuarioQR($usuario,$clave) {
        $usuario = str_replace("%20"," ",$usuario);
        $usuario = str_replace("_"," ",$usuario);
        $data = array(
            "usuario" => $usuario, 
            "clave" => $clave
                );
        $data_string = json_encode($data);
        $ch = curl_init(RUTAWS.'usuarios/verifica_usuario2.php');
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
        $datos = json_decode($result);
        if ($datos->{'estado'}==1) {
            $idUsuario = $datos->{'usuario'}->{'idUsuario'};
            $usuario = $datos->{'usuario'}->{'usuario'};
            $clave = $datos->{'usuario'}->{'clave'};
            $permisos = $datos->{'usuario'}->{'permisos'};
            $nombre = $datos->{'usuario'}->{'nombre'};
            $apellido_paterno = $datos->{'usuario'}->{'apellido_paterno'};
            $apellido_materno = $datos->{'usuario'}->{'apellido_materno'};
            $this->session->set_userdata('nombre', $nombre." ".$apellido_paterno." ".$apellido_materno);
            $this->session->set_userdata('permisos', $permisos);
            $this->session->set_userdata('usuario', $usuario);					
            $this->session->set_userdata('clave', $clave);					
            $this->session->set_userdata('idUsuario', $idUsuario);
            $this->session->set_userdata('logueado', TRUE);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            echo "<script>window.location.replace('https://192.168.98.200/cmpv1_0/index.php/usuarios_controller/scaneo');</script>";
        } else {
            echo "no esta el usuario";
            //$this->session->set_flashdata('correcto', "Error. Vuelva a intentarlo.");
            //redirect($this->index());
        }
    }
    
    function scaneo(){
        if ($this->is_logged_in()){
            $this->load->view('inventarios_toolcrib/camara_view');
        } else {
            redirect($this->cerrarSesion());
        }        
    }
    
    function confirmarCaptura($mensaje){
        $data = array('mensaje'=>$mensaje);
        if ($this->is_logged_in()){
            $this->load->view('inventarios_toolcrib/confirmarcaptura_view',$data);
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

