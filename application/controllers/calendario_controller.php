<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Calendario_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuariosGlobal;
    private $maquinasGlobal;
    private $maquinasConActividadesGlobal;
    private $actividadesGlobal;
    
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
        $this->maquinasGlobal = $this->util_controller->cargaDatosMaquinasActivas();
        //$this->maquinasConActividadesGlobal = $this->util_controller->cargaDatosMaquinasConActividades();
        $this->actividadesGlobal = $this->util_controller->cargaDatosActividades();
        
        $this->maquinasSimple = $this->util_controller->cargaDatosMaquinasSimple();
        
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
    
    function mostrarCalendario() {
        if ($this->is_logged_in()){
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'maquinasSimple' => $this->maquinasSimple,
                'maquinas' => $this->maquinasGlobal,
                'actividades' => $this->actividadesGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => 'Calendarizar'
                    );
            
            if ($this->session->userdata('permisos') == "Administrador") {
                $this->load->view('layouts/headerAdministrador_view',$data);
            } 
            if ($this->session->userdata('permisos') == "Técnico") {
                $this->load->view('layouts/headerTecnico_view',$data);
            }             
            $this->load->view('calendarios/nuevoCalendario_view',$data);
            $this->load->view('layouts/pie_view',$data);
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

