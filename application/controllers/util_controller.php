<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Util_controller extends CI_Controller {
    private $gatewayRest;

    function __construct(){
        parent::__construct();
//        $cadena = file_get_contents(base_url/'bd.txt');
//        $this->gatewayRest =  $cadena;
    }
    
    function verificaUsuarioHerramental($idUsuario){
        //obtiene el usuario herramental
        $url = RUTAWS.'usuarios_herramentales/obtener_usuario_por_id_herramentales.php?idUsuario='.$idUsuario;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataHerram = curl_exec($ch);
        $datosHerra = json_decode($dataHerram);
        curl_close($ch);
        if ($datosHerra->{'estado'}==1) {
            return $datosHerra->{'usuario_herramental'};
        } else {
            return null;
        }        
    }
    
    function cargaDatosEmpresa() {
        $url2 = RUTAWS.'datosempresa/obtener_datosempresas.php';
        $ch2 = curl_init($url2);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $data2 = curl_exec($ch2);
        $datos = json_decode($data2);
        if ($datos->{'estado'}==1) {
            return $datos->{'datosEmpresas'};
        } else {
            return null;
        }
    }
    
    function cargaDatosUsuarios() {
        $url = RUTAWS.'usuarios/obtener_usuarios.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'usuarios'};
        } else {
            return null;
        }
    } 

    function cargaDatosUsuariosHerramentales() {
        $url = RUTAWS.'usuarios_herramentales/obtener_usuarios_herramentales.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'usuarios_herramentales'};
        } else {
            return null;
        }
    } 
    
    function cargaDatosAplicadores() {
        $url = RUTAWS.'aplicadores/obtener_aplicadores.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'aplicadores'};
        } else {
            return null;
        }
    } 
    
    function cargaDatosAplicadoresSinMovimientos() {
        $url = RUTAWS.'aplicadores/obtener_aplicadores_sin_movimientos.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'aplicadores'};
        } else {
            return null;
        }
    } 
    
    function cargaDatosMaquinasSimple() {
        $url = RUTAWS.'maquinaria/obtener_maquinas_simple.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'maquinas'};
        } else {
            return null;
        }
    } 
    
    function cargaDatosMantenimientosSimple() {
        $url = RUTAWS.'mantenimientos/obtener_mantenimientos_simple.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'mantenimientos'};
        } else {
            return null;
        }
    } 

    function cargaDatosMaquinas() {
        $url = RUTAWS.'maquinaria/obtener_maquinas.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'maquinas'};
        } else {
            return null;
        }
    } 
    
    function cargaDatosCategorias() {
        $url = RUTAWS.'categorias/obtener_categorias.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'categorias'};
        } else {
            return null;
        }
    } 
    
    
    function cargaDatosMaquinasActivasConActividades() {
        $url = RUTAWS.'maquinaria/obtener_maquinas_activas_con_actividades.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'maquinas'};
        } else {
            return null;
        }
    }     

    function cargaDatosMaquinasActivas() {
        $url = RUTAWS.'maquinaria/obtener_maquinas_activas.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'maquinas'};
        } else {
            return null;
        }
    }     
    
    function cargaDatosAreas() {
        $url = RUTAWS.'areas/obtener_areas.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'areas'};
        } else {
            return null;
        }
    }      

    function cargaDatosProveedores() {
        $url = RUTAWS.'proveedores/obtener_proveedores.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'proveedores'};
        } else {
            return null;
        }
    }

    function cargaDatosActividades() {
        $url = RUTAWS.'actividades/obtener_actividades.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'actividades'};
        } else {
            return null;
        }
    }    

    function obtenerUsuarioHerramental(){
        $url = RUTAWS.'usuarios_herramentales/obtener_usuario_por_id_herramentales.php?idUsuario='. $this->session->userdata('idUsuario');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataHerram = curl_exec($ch);
        $datosHerra = json_decode($dataHerram);
        curl_close($ch);
        if ($datosHerra->{'estado'}==1) {
            return $datosHerra->{'usuario_herramental'};
        } else {
            return null;
        }
    }   
    
    function obtenerUsuariosOperadores(){
        $url = RUTAWS.'usuarios_operadores/obtener_usuariosoperadores.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==1) {
            return $datos->{'usuarios'};
        } else {
            return null;
        }
    }     
    
    
    function index(){
        $this->load->view('login_view');
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

