<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Configuracion_controller extends CI_Controller {
    private $categoriasGlobal;
    private $sucursalesGlobal;
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $sistemaGlobal;
    
    //permisos campos inventario
    private $permisosCamposInventarioGlobal;
    private $permisosCamposVentasGlobal;
    private $permisosCamposComprasGlobal;
    private $permisosCamposConsultasGlobal;
    private $permisosCamposProveedoresGlobal;
    private $permisosCamposClientesGlobal;
    private $permisosCamposEmpleadosGlobal;
    private $permisosCamposEmpresaGlobal;

    private $gatewayRest;
    
    function __construct(){
        parent::__construct();
        $this->load->model('sistema_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->error = "";
        
        //para subir imagenes
        $this->load->helper("URL", "DATE", "URI", "FORM");
        $this->load->library('../controllers/util_controller');
        
        //valores globales de categorias y datos de Empresa
        $this->datosEmpresaGlobal = $this->util_controller->cargaDatosEmpresa();
        $this->nombreEmpresaGlobal = $this->datosEmpresaGlobal[0]->{'nombreEmpresa'};
        
//        $cadena = file_get_contents('bd.txt');
//        $this->gatewayRest =  $cadena;
        
    }

    function index(){
        $this->load->view('login_view');
    }
    
    function actualizarDatosEmpresa() {
        if ($this->is_logged_in()){
            $url2 = RUTAWS.'datosempresa/obtener_datosempresa_por_id.php?idEmpresa=1';
            $ch2 = curl_init($url2);
            curl_setopt($ch2, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            $data2 = curl_exec($ch2);
            $datos2 = json_decode($data2);
            curl_close($ch2);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array('categorias'=>$this->categoriasGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'datosEmpresa'=>$datos2->{'datosEmpresa'},
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'opcionClickeada' => '8',
                'permisos' => $this->session->userdata('permisos'));
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('configuracion/actualizaDatosEmpresa_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarDatosEmpresaFromFormulario(){
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idEmpresa = $this->input->post("idEmpresa");
                $nombreEmpresa = $this->input->post("nombreEmpresa");
                $rfcEmpresa = $this->input->post("rfcEmpresa");
                $direccionEmpresa = $this->input->post("direccionEmpresa");
                $emailEmpresa = $this->input->post("emailEmpresa");
                $telEmpresa = $this->input->post("telEmpresa");
                $cpEmpresa = $this->input->post("cpEmpresa");
                $ciudadEmpresa = $this->input->post("ciudadEmpresa");
                $estadoEmpresa = $this->input->post("estadoEmpresa");
                $paisEmpresa = $this->input->post("paisEmpresa");

                $data = array("idEmpresa" => $idEmpresa, 
                "nombreEmpresa" => $nombreEmpresa,
                "rfcEmpresa" => $rfcEmpresa,
                "direccionEmpresa" => $direccionEmpresa,
                "emailEmpresa" => $emailEmpresa,
                "telEmpresa" => $telEmpresa,
                "cpEmpresa" => $cpEmpresa,
                "ciudadEmpresa" => $ciudadEmpresa,
                "estadoEmpresa" => $estadoEmpresa,
                "paisEmpresa" => $paisEmpresa
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'datosempresa/actualizar_datosempresa.php');
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
                redirect('/index.php/configuracion_controller/actualizarDatosEmpresa');
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

