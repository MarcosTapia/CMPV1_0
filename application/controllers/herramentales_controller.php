<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Herramentales_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuariosGlobal;
    private $areasGlobal;
    
    private $aplicadores;
    private $aplicadoresSinMovimientos;
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
        
        $this->aplicadores = $this->util_controller->cargaDatosAplicadores();
        $this->aplicadoresSinMovimientos = $this->util_controller->cargaDatosAplicadoresSinMovimientos();
        $this->usuarioHerramental = $this->util_controller->obtenerUsuarioHerramental();
        
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
            $this->load->view('layouts/headerTecnico_view',$data);
            $this->load->view('principal_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function funcioninicial(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                    'idUsuario'=>$this->session->userdata('idUsuario'),
                    'aplicadores' => $this->aplicadores,
                    'usuario_herramental'=>$this->usuarioHerramental,
                    'permisos'=>$this->session->userdata('permisos'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'opcionClickeada' => 'Herramental'
                );
            $this->load->view('layouts/headerTecnico_view',$data);
            if ($this->usuarioHerramental->{'tipo_herramental'} == "Aplicador") {
                $this->load->view('herramentales/nuevaOperacionHerramental_view',$data);
            }
            if ($this->usuarioHerramental->{'tipo_herramental'} == "Molde") {
                $this->load->view('herramentales/nuevaOperacionHerramentalMolde_view',$data);
            }
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function funcioninicialAdministradorAplicadores(){
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                    'aplicadores' => $this->aplicadores,
                    'permisos'=>$this->session->userdata('permisos'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'opcionClickeada' => 'Herramental'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('herramentales/adminAplicadores_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function funcioninicialAdministradorMoldes(){
        if ($this->is_logged_in()){
            echo "En construcción....";
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function nuevoAplicador() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '7'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('herramentales/nuevoAplicador_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function creaMovimientoInicial() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'aplicadores' => $this->aplicadoresSinMovimientos,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '7'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('herramentales/nuevoMovAplicador_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function nuevoAplicadorFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $maquina = $this->input->post("maquina");
                $aplicador = $this->input->post("aplicador");
                $fabricante = $this->input->post("fabricante");
                $noParteAplicador = $this->input->post("noParteAplicador");
                $noParteTerminal = $this->input->post("noParteTerminal");
                $noParteTerminalInterno = $this->input->post("noParteTerminalInterno");
                $cliente = $this->input->post("cliente");
                $noCiclos = $this->input->post("noCiclos");
                 
                $data = array(
                    "maquina" => $maquina,
                    "aplicador" => $aplicador,
                    "fabricante" => $fabricante,
                    "noParteAplicador" => $noParteAplicador,
                    "noParteTerminal" => $noParteTerminal,
                    "noParteTerminalInterno" => $noParteTerminalInterno,
                    "cliente" => $cliente,
                    "noCiclos" => $noCiclos
                    );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'aplicadores/insertar_aplicador.php');
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
                redirect('/index.php/herramentales_controller/funcioninicialAdministradorAplicadores'); 
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function nuevoMovAplicadorFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idAplicador = $this->input->post("aplicador");
                $ciclos = ltrim($this->input->post("ciclos"),"0");
                $idUsuario = 1;
                $data = array(
                    "idAplicador" => $idAplicador,
                    "ciclos" => $ciclos,
                    "idUsuario" => $idUsuario
                    );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'aplicadores/insertar_mov_aplicador.php');
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
                //echo #result;
                curl_close($ch);
                $resultado = json_decode($result, true);
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro guardado <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se guardó el registro <br>");
                }        
                redirect('/index.php/herramentales_controller/funcioninicialAdministradorAplicadores'); 
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarAplicador($idAplicador) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $url = RUTAWS.'aplicadores/obtener_aplicador_por_id.php?idAplicador='.$idAplicador;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            if ($datos->{'estado'}==1) {
                $data = array(
                    'aplicador'=>$datos->{'aplicador'},
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('herramentales/actualizaAplicador_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarAplicadorFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idAplicador = $this->input->post("idAplicador");
                $maquina = $this->input->post("maquina");
                $aplicador = $this->input->post("aplicador");
                $fabricante = $this->input->post("fabricante");
                $noParteAplicador = $this->input->post("noParteAplicador");
                $noParteTerminal = $this->input->post("noParteTerminal");
                $noParteTerminalInterno = $this->input->post("noParteTerminalInterno");
                $cliente = $this->input->post("cliente");
                $noCiclos = $this->input->post("noCiclos");
                 
                $data = array(
                    "maquina" => $maquina,
                    "aplicador" => $aplicador,
                    "fabricante" => $fabricante,
                    "noParteAplicador" => $noParteAplicador,
                    "noParteTerminal" => $noParteTerminal,
                    "noParteTerminalInterno" => $noParteTerminalInterno,
                    "cliente" => $cliente,
                    "noCiclos" => $noCiclos,
                    "idAplicador" => $idAplicador
                    );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'aplicadores/actualizar_aplicador.php');
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
                redirect('/index.php/herramentales_controller/funcioninicialAdministradorAplicadores');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function eliminarAplicador($idAplicador) {
        if ($this->is_logged_in()){
            $data = array("idAplicador" => $idAplicador);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'aplicadores/borrar_aplicador.php');
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
            redirect('/index.php/herramentales_controller/funcioninicialAdministradorAplicadores');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function movimientosAplicador($idAplicador){
        $url = RUTAWS.'aplicadores/obtener_movimientos_aplicador_por_id.php?idAplicador='.$idAplicador;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        
        if ($datos->{'estado'}==2) {
            //$this->session->set_flashdata('correcto', "Error. No existen movimientos para este aplicador. <br>");
            //redirect('/index.php/herramentales_controller/funcioninicialAdministradorAplicadores');
            echo "<script>alert('No existen movimientos para este aplicador.');window.history.back();</script>";
        } else {
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                    'usuariosArray' => $usuariosArray,
                    'movimientos_aplicadores' => $datos->{'movimientos_aplicador'},
                    'permisos'=>$this->session->userdata('permisos'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'opcionClickeada' => 'Herramental'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('herramentales/adminMovimientosAplicadores_view',$data);
            $this->load->view('layouts/pie_view',$data);
        }
    }
    
    // Telegram function which you can call
    function telegram($msg) {
        // Set your Bot ID and Chat ID.
        $telegrambot='1591382343:AAGTgrUiF8j0AF-SQu6pxf-06nVmnCHjTX0';
        $telegramchatid=1539275351;

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
        redirect('/index.php/herramentales_controller/funcioninicialAdministradorAplicadores');
    }
    
    function enviaCorreo() {
        $numeroRequisicion = rand();
        $to_email = "mtapia_soto@hotmail.com";
        $subject = "Seguimiento de Requisición";
        
        $mensaje = '<html>'.
	'<head><title>Mensaje desde Sistema Toolcrib</title></head>'.
	'<body><br><h1>Requisición No: '.$numeroRequisicion.'</h1></body>'.
	'</html>';

        $body = $mensaje;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";        
        $headers .= "From: Matservices";
        if (mail($to_email, $subject, $body, $headers)) {
            $this->session->set_flashdata('correcto', "Mensaje enviado. <br>");
        } else {
            $this->session->set_flashdata('correcto', "Mensaje no enviado. <br>");
        }        
        redirect('/index.php/herramentales_controller/funcioninicialAdministradorAplicadores');
    }

    function enviaWhatsapp(){
        $data = [
            'phone' => '524425952059', // Receivers phone
            'body' => 'Mensaje enviado desde sistema Toolcrib', // Message
        ];
        $json = json_encode($data); // Encode data to JSON
        // URL for request POST /message
        // https://eu125.chat-api.com/instance216572/ and token zihhtqnu769c6i1r
        $token = 'zihhtqnu769c6i1r';
        $instanceId = '216572';
        $url = 'https://api.chat-api.com/instance'.$instanceId.'/message?token='.$token;
        // Make a POST request
        $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        // Send a request
        $result = file_get_contents($url, false, $options);
        $datos = json_decode($result);
        if ($datos->{'sent'}==1) {
            $this->session->set_flashdata('correcto', "Mensaje enviado.<br>");
        } else {
            $this->session->set_flashdata('correcto', "Error. No se envió el mensaje. <br>");
        }        
        redirect('/index.php/herramentales_controller/funcioninicialAdministradorAplicadores');
    }

    
    //Exportar datos a Excel
    public function exportarExcel(){
        if ($this->is_logged_in()){
            $movsAplicadores = unserialize($this->input->post("movsAplicadoresHidden"));
            $nilai = $movsAplicadores;
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            //echo $totn;
            
            $heading=array('Aplicador','Responsable','Fecha', "Ciclos","Tipo Movimiento","Observaciones Movimiento","Observaciones Mantenimiento","Estatus");
            $this->load->library('excel');
            //Create a new Object
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Movimientos de Aplicador");
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
            
            //Llena array Aplicadores
            $aplicadoresArray = array();
            $j = 0;
            foreach ($this->aplicadores as $aplicador){
                $aplicadoresArray[$aplicador->{'aplicador'}."|".$j."|"] = "".$aplicador->{'idAplicador'};
                $j++;
            }    
            
            foreach($nilai as $n){
                //busca en array de usuarios para desplegar el responsable
                $posicionContenidoAplicador = array_search($n->{'idAplicador'},$aplicadoresArray);
                $elemArrayAplic = explode("|", $posicionContenidoAplicador);
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$elemArrayAplic[0]);
                
                //busca en array de usuarios para desplegar el responsable
                $posicionContenidoUsuario = array_search($n->{'idUsuario'},$usuariosArray);
                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$elemArrayUsu[0]);
                
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'fecha'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'ciclos'});
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'tipoMovimiento'});
                
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$n->{'observacionesTipoMov'});
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'observacionesMantto'});
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$n->{'status'});
                
                $row++;
                $no++;
            }
            //Freeze pane
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            //Cell Style
            $styleArray = array(

            );
            
            $objPHPExcel->getActiveSheet()->getStyle('A1:E'.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Movimientos Aplicador.xls"');
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
            /**************** nuevo ************************/
            $parametrosConsulta = $this->input->post("parametrosConsulta");
            $movimientos = unserialize($this->input->post("movimientosHidden"));
            $titulo;
            $titulo2 = "";
            /**************** nuevo *************************/
            $titulo = 'Movimientos de Aplicador'; 
            
            //Llena array Usuarios
            $usuariosArray = array();
            $j = 0;
            foreach ($this->usuariosGlobal as $usuario){
                $usuariosArray[$usuario->{'apellido_paterno'}." ".$usuario->{'apellido_materno'}." ".$usuario->{'nombre'}."|".$j."|"] = "".$usuario->{'idUsuario'};
                $j++;
            }    

            //Llena array Aplicadores
            $aplicadoresArray = array();
            $j = 0;
            foreach ($this->aplicadores as $aplicador){
                $aplicadoresArray[$aplicador->{'aplicador'}."|".$j."|"] = "".$aplicador->{'idAplicador'};
                $j++;
            }    
            
            $titulo = utf8_decode($titulo);
            require_once APPPATH.'third_party/fpdf/fpdf.php';
            if ($movimientos != null) {
                $nilai = $movimientos;
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
                $pdf->Cell(20,10,"Aplicador",1,1);
                $pdf->SetXY(50,$renglonTitulos);
                $pdf->Cell(40,10,"Responsable",1,1);
                $pdf->SetXY(90,$renglonTitulos);
                $pdf->Cell(45,10,"Fecha",1,1);                
                $pdf->SetXY(135,$renglonTitulos);
                //$pdf->Cell(45,10,utf8_decode("Máquina"),1,1);                
                $pdf->Cell(25,10,"Ciclos",1,1);                
                $pdf->SetXY(160,$renglonTitulos);
                $pdf->Cell(20,10,"Tipo Mov.",1,1);                
                $pdf->SetXY(180,$renglonTitulos);
                $pdf->Cell(35,10,"Obs. Mov",1,1);                
                $pdf->SetXY(215,$renglonTitulos);
                $pdf->Cell(45,10,"Obs. Mantto",1,1);                

                $pdf->SetFont('Times','',12);
                $renglon = 0;
                
                $cantidadRegistros = 0;
                if (isset($movimientos)) {
                    $cantidadRegistros = sizeof($movimientos);
                }
                $contRegs = 0;
                foreach ($movimientos as $fila){
                    $contRegs++;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    
                    //busca en array de usuarios para desplegar el responsable
                    $posicionContenidoAplicador = array_search($fila->{'idAplicador'},$aplicadoresArray);
                    $elemArrayAplic = explode("|", $posicionContenidoAplicador);
                    $pdf->Cell(20,10,$elemArrayAplic[0],1,1);
                    
                    $inicio = $inicio + 20;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    
                    //busca en array de usuarios para desplegar el responsable
                    $posicionContenidoUsuario = array_search($fila->{'idUsuario'},$usuariosArray);
                    $elemArrayUsu = explode("|", $posicionContenidoUsuario);                    
                    $pdf->Cell(40,10,substr($elemArrayUsu[0],0,20)."..",1,1);
                    
                    $inicio = $inicio + 40;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    $pdf->Cell(45,10,$fila->{'fecha'},1,1);
                    
                    
                    $inicio = $inicio + 45;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    $pdf->Cell(25,10,$fila->{'ciclos'},1,1);
                    
                    
                    $inicio = $inicio + 25;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    $pdf->Cell(20,10,$fila->{'tipoMovimiento'},1,1); 
                    $inicio = $inicio + 20;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    $pdf->Cell(35,10, utf8_decode(substr($fila->{'observacionesTipoMov'},0,16))."..",1,1);
                    
                    
                    $inicio = $inicio + 35;
                    $pdf->SetXY($inicio,$renglonOriginal + ($renglon * 10));
                    //$inicio = 45;
                    $pdf->Cell(45,10,utf8_decode(substr($fila->{'observacionesMantto'},0,18))."..",1,1);
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
                            $pdf->Cell(20,10,"Aplicador",1,1);
                            $pdf->SetXY(50,$renglonTitulos);
                            $pdf->Cell(40,10,"Responsable",1,1);
                            $pdf->SetXY(90,$renglonTitulos);
                            $pdf->Cell(45,10,"Fecha",1,1);                
                            $pdf->SetXY(135,$renglonTitulos);
                            $pdf->Cell(25,10,"Ciclos",1,1);                
                            $pdf->SetXY(160,$renglonTitulos);
                            $pdf->Cell(20,10,"Tipo Mov.",1,1);                
                            $pdf->SetXY(180,$renglonTitulos);
                            $pdf->Cell(35,10,"Obs. Mov",1,1);                
                            $pdf->SetXY(215,$renglonTitulos);
                            $pdf->Cell(45,10,"Obs. Mantto",1,1);                

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

