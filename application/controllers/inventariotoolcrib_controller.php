    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inventariotoolcrib_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $proveedoresGlobal;
    private $maquinasSimple;
    private $usuariosGlobal;
    private $maquinasGlobal;
    
    private $historicoProveedoresGlobal;
    
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
        $this->load->model('mupload_model');
        
        //llamado a controlador util global
        $this->load->library('../controllers/util_controller');
        
        $this->datosEmpresaGlobal = $this->util_controller->cargaDatosEmpresa();
        $this->nombreEmpresaGlobal = $this->datosEmpresaGlobal[0]->{'nombreEmpresa'};
        $this->proveedoresGlobal = $this->util_controller->cargaDatosProveedores();
        $this->maquinasSimple = $this->util_controller->cargaDatosMaquinasSimple();
        
        $this->maquinasGlobal = $this->util_controller->cargaDatosMaquinas();
        
        $this->usuariosGlobal = $this->util_controller->cargaDatosUsuarios();
        
//        $cadena = file_get_contents('bd.txt');
//        $this->gatewayRest =  $cadena;
        
    }
    
    function index(){
        $this->load->view('login_view');
    }
    
    function regresa() {
        echo "error";
    }
    
    function mostrarInventario() {
        if ($this->is_logged_in()){
            $url = RUTAWS.'inventariotoolcrib/obtener_inventarios.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($datos->{'estado'})) {            
                if ($datos->{'estado'}==1) {
                    $data = array('inventarios'=>$datos->{'inventarios'},'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'sucursal' => $this->session->userdata('sucursal'),
                        'permisos' => $this->session->userdata('permisos'),
                        'historicoPreciosProveedores' => $this->historicoProveedoresGlobal,
                        'opcionClickeada' => '5'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('inventarios_toolcrib/adminInventario_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'inventarios'=>null,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('inventarios_toolcrib/adminInventario_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } 
            } else {
                $data = array(
                    'inventarios'=>null,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );                
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('inventarios_toolcrib/adminInventario_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoNumero() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                'maquinasSimple'=> $this->maquinasSimple,
                'proveedores'=>$this->proveedoresGlobal,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '5'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('inventarios_toolcrib/nuevoNumero_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoNumeroFromFormulario(){
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $numSAP = $this->input->post("numSAP");
                $maquina = $this->input->post("maquina");
                $descripcion = $this->input->post("descripcion");
                $numParte = $this->input->post("numParte");
                $ubicacion = $this->input->post("ubicacion");
                $stock = $this->input->post("stock");
                $minimo = $this->input->post("minimo");
                $maximo = $this->input->post("maximo");
                $proveedor = $this->input->post("proveedor");
                $data = array(
                    'numSAP' =>$numSAP,
                    "maquina" =>$maquina,
                    "descripcion" => $descripcion,
                    "numParte" => $numParte,
                    "ubicacion" => $ubicacion,
                    "stock" => $stock,
                    "minimo" => $minimo,
                    "maximo" => $maximo,
                    "idProveedor" => $proveedor
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'inventariotoolcrib/insertar_inventario.php');
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
                redirect('/index.php/inventariotoolcrib_controller/mostrarInventario');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function actualizarInventario($idInventario) {
        if ($this->is_logged_in()){
            $url = RUTAWS.'inventariotoolcrib/obtener_inventario_por_id.php?idInventario='.$idInventario;
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
                    'inventario'=>$datos->{'inventario'},
                    'maquinasSimple'=> $this->maquinasSimple,
                    'proveedores'=>$this->proveedoresGlobal,
                            
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '5'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('inventarios_toolcrib/actualizaInventario_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                echo "error";
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function actualizarInventarioFromFormulario(){
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idInventario = $this->input->post("idInventario");
                $numSAP = $this->input->post("numSAP");
                $maquina = $this->input->post("maquina");
                $descripcion = $this->input->post("descripcion");
                $numParte = $this->input->post("numParte");
                $ubicacion = $this->input->post("ubicacion");
                $stock = $this->input->post("stock");
                $minimo = $this->input->post("minimo");
                $maximo = $this->input->post("maximo");
                $proveedor = $this->input->post("proveedor");
                $stockIni = $this->input->post("stockIni"); 
                $cantidad = $stock - $stockIni;                 
                $data = array(
                    'idInventario' =>$idInventario,
                    'numSAP' =>$numSAP,
                    "maquina" =>$maquina,
                    "descripcion" => $descripcion,
                    "numParte" => $numParte,
                    "ubicacion" => $ubicacion,
                    "stock" => $stock,
                    "minimo" => $minimo,
                    "maximo" => $maximo,
                    "idProveedor" => $proveedor,
                    "idUsuario" => $this->session->userdata('idUsuario'),
                    "cantidad" => $cantidad                    
                        );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'inventariotoolcrib/actualizar_inventario.php');
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
                    $this->session->set_flashdata('correcto', "Registro actualizado exitosamente. <br>");
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro <br>");
                }  
                redirect('/index.php/inventariotoolcrib_controller/mostrarInventario');
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function eliminarParteInventario($idInventario) {
        if ($this->is_logged_in()){
            $data = array("idInventario" => $idInventario);
            $data_string = json_encode($data);
            $ch = curl_init(RUTAWS.'inventariotoolcrib/borrar_parte.php');
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
            echo $result;
            $resultado = json_decode($result, true);
            if ($resultado['estado']==1) {
                $this->session->set_flashdata('correcto', "Registro Eliminado correctamente. <br>");
            } else {
                $this->session->set_flashdata('correcto', "Error. No se eliminó el registro <br>");
            }              
            redirect('/index.php/inventariotoolcrib_controller/mostrarInventario');
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function bajaFromQR($numero_parte){
        if ($this->is_logged_in()){
            $url = RUTAWS.'inventariotoolcrib/obtener_inventario_por_numero_parte.php?numero_parte='.$numero_parte;
            $ch = curl_init($url);  
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            //echo $data;
            $datos = json_decode($data);
            curl_close($ch);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if ($datos->{'estado'}==1) {
                $data = array(
                    'inventario'=>$datos->{'inventario'},
                    'maquinasSimple'=> $this->maquinasSimple,
                    'proveedores'=>$this->proveedoresGlobal,
                            
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '5'
                        );
                $this->load->view('inventarios_toolcrib/bajaFromQR_view',$data);
            } else {
                echo "error";
            }
        } else {
            $data = array('numero_parte'=>$numero_parte);
            $this->load->view('loginFromQR_view',$data);
        }
    }
    
    function actualizarInventarioFromFormularioQR(){
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                $idUsuario = $this->session->userdata('idUsuario');
                $numParte = $this->input->post("numParte");
                $cantidad = $this->input->post("cantidad");
                $stock = $this->input->post("stock");
                $idInventario = $this->input->post("idInventario");
                $idMaquina = $this->input->post("maquina");
                //nuevo stock
                $stock = $stock - $cantidad;
                //echo "idUsuario: ".$idUsuario." numParte: ".$numParte." cantidad: ".$cantidad." stock: ".$stock." idInventario: ".$idInventario." idMaquina: ".$idMaquina;
                $data = array(
                    "idUsuario" => $idUsuario,
                    'idInventario' => $idInventario,
                    'cantidad' => $cantidad,
                    'idMaquina' =>$idMaquina,
                    "stock" => $stock);
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'inventariotoolcrib/actualizar_inventarioQR.php');
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
                $mensaje = 0;
                if ($resultado['estado']==1) {
                    $this->session->set_flashdata('correcto', "Registro actualizado exitosamente. <br>");
                    $mensaje = 1;
                } else {
                    $this->session->set_flashdata('correcto', "Error. No se actualizó el registro. <br>");
                    $mensaje = 0;
                }  
                redirect('/index.php/usuarios_controller/confirmarCaptura/'.$mensaje);
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarMovimientos(){
        if ($this->is_logged_in()){
            $url = RUTAWS.'inventariotoolcrib/obtener_movimientos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($datos->{'estado'})) {            
                if ($datos->{'estado'}==1) {
                    $data = array(
                        'movimientos'=>$datos->{'movimientos'},
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('inventarios_toolcrib/adminMovimientos_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'movimientos'=>null,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('inventarios_toolcrib/adminMovimientos_view',$data);
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
                $this->load->view('inventarios_toolcrib/adminMovimientos_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }                 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarMovimientosPorCodigo($idInventario){
        //trae el detalle del numero de parte
        $url = RUTAWS.'inventariotoolcrib/obtener_inventario_por_id.php?idInventario='.$idInventario;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data1 = curl_exec($ch);
        $datos1 = json_decode($data1);
        curl_close($ch);
        
        //llama a todos los movimientos del numero de parte
        $url = RUTAWS.'inventariotoolcrib/obtener_movimientos_noparte_por_id.php?idInventario='.$idInventario;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $datos = json_decode($data);
        curl_close($ch);
        if ($datos->{'estado'}==2) {
            echo "<script>alert('No existen movimientos para este número de parte.');window.history.back();</script>";
        } else {
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
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                    'inventario'=>$datos1->{'inventario'},
                    'usuariosArray' => $usuariosArray,
                    'maquinasArray' => $maquinasArray,
                    'movimientos' => $datos->{'movimientos_noparte'},
                    'permisos'=>$this->session->userdata('permisos'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'opcionClickeada' => 'Herramental'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('inventarios_toolcrib/adminMovimientosIndividual_view',$data);
            $this->load->view('layouts/pie_view',$data);
        }
    }
    
    function mostrarAlertas(){
        if ($this->is_logged_in()){
            $url = RUTAWS.'inventariotoolcrib/obtener_alertas.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            $data;
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if (isset($datos->{'estado'})) {            
                if ($datos->{'estado'}==1) {
                    $data = array('alertas'=>$datos->{'alertas'},'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'sucursal' => $this->session->userdata('sucursal'),
                        'permisos' => $this->session->userdata('permisos'),
                        'historicoPreciosProveedores' => $this->historicoProveedoresGlobal,
                        'opcionClickeada' => '5'
                            );
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('inventarios_toolcrib/adminAlertas_view',$data);
                    $this->load->view('layouts/pie_view',$data);
                } else {
                    $data = array(
                        'alertas' => null,
                        'usuarioDatos' => $this->session->userdata('nombre'),
                        'fecha' => $fechaIngreso,
                        'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                        'permisos' => $this->session->userdata('permisos'),
                        'opcionClickeada' => '7'
                            );                
                    $this->load->view('layouts/headerAdministrador_view',$data);
                    $this->load->view('inventarios_toolcrib/adminAlertas_view',$data);
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
                $this->load->view('inventarios_toolcrib/adminMovimientos_view',$data);
                $this->load->view('layouts/pie_view',$data);
            }                 
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarBot(){
        $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
        $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
        //crea campos de sesion
        $this->session->set_userdata('nombre', "WeWire");
        $this->session->set_userdata('permisos', "Otros");
        $this->session->set_userdata('usuario', "WeWire");					
        $this->session->set_userdata('clave', "999");					
        $this->session->set_userdata('idUsuario', 63);
        $this->session->set_userdata('logueado', TRUE);
        $data = array(
            'idUsuario' => $this->session->userdata('idUsuario'),
            'usuarioDatos' => $this->session->userdata('nombre'),
            'fecha' => $fechaIngreso,
            'nombre_Empresa'=>$this->nombreEmpresaGlobal,
            'permisos' => $this->session->userdata('permisos'),
            'opcionClickeada' => 'bot'
        );
        $this->load->view('layouts/headerBot_view',$data);
        $this->load->view('inventarios_toolcrib/bot_view',$data);
        $this->load->view('layouts/pie_view',$data);
    }
    
    
    function TelegramBot(){
        $this->load->view('inventarios_toolcrib/telegram_view',$data);
    }
    
    // Telegram function which you can call
    //A matservices
//    function telegram($msg) {
//        // Set your Bot ID and Chat ID.
//        $telegrambot='1591382343:AAGTgrUiF8j0AF-SQu6pxf-06nVmnCHjTX0';
//        $telegramchatid=1539275351;
//
//        //global $telegrambot,$telegramchatid;
//
//        $url='https://api.telegram.org/bot'.$telegrambot.'/sendMessage';$data=array('chat_id'=>$telegramchatid,'text'=>$msg);
//        $options=array('http'=>array('method'=>'POST','header'=>"Content-Type:application/x-www-form-urlencoded\r\n",'content'=>http_build_query($data),),);
//        $context=stream_context_create($options);
//        $result=file_get_contents($url,false,$context);
//        return $result;
//    }
    
    function telegram($msg) {
        // Set your Bot ID and Chat ID.
        //https://api.telegram.org/bot
        //1676714028:AAH0aWRKqqhVIauq-5M6B7Fqz0AundeBdT0/getUpdates        
        $telegrambot='1676714028:AAH0aWRKqqhVIauq-5M6B7Fqz0AundeBdT0';
        $telegramchatid=1539275351;
//        $telegrambot='1591382343:AAGTgrUiF8j0AF-SQu6pxf-06nVmnCHjTX0';
//        $telegramchatid=1539275351;

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
        redirect('/index.php/inventariotoolcrib_controller/mostrarInventario');
    }
    
    function enviaCorreoGmail() {
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
        $headers .= "From: ToolCrib";
        if (mail($to_email, $subject, $body, $headers)) {
            $this->session->set_flashdata('correcto', "Mensaje enviado. <br>");
        } else {
            $this->session->set_flashdata('correcto', "Mensaje no enviado. <br>");
        }        
        redirect('/index.php/inventariotoolcrib_controller/mostrarInventario');
    }
    
    function enviaCorreoEmpresa() {
	echo "<h3 style='background:#ccc;background-color:#ccc;color:blue;'>Espere mientras se envía la información. </h3>";
        $list = array (
          array("origen", "destino" ,"clave", "contenido", "asunto", "attachment"),
          array("m.soto@coroplast.net", "marcostapia623@gmail.com", "Mexico2020", "Informe de piezas por surtir","Piezas por surtir BBB","info.pdf")
        );
        $file = fopen("C:\\xampp\\htdocs\\correoempresa\\configura_correo.csv","w");
        foreach ($list as $line) {
          fputcsv($file, $line);
        }
        fclose($file);
        $this->session->set_flashdata('correcto', "Operación finalizada. <br>");
        echo "<script>window.location.replace('http://192.168.98.200/correoempresa/aaa.php');</script>";
        
//        $archivoConfigura = "C:\\xampp\\htdocs\\correoempresa\\configura_correo.csv";
//        $resultado = "C:\\xampp\\htdocs\\correoempresa\\resultadoenviocorreo.csv";
//        $ejecutable = "C:\\xampp\\htdocs\\correoempresa\\correo2.exe";
//        //$archivoConfigura = FCPATH."correoempresa/configura_correo.csv";
//        if (file_exists($archivoConfigura)) {
//            //$resultado = FCPATH."correoempresa/resultadoenviocorreo.csv";
//            if (file_exists($resultado)) {
//                //$ejecutable = FCPATH."correoempresa/correo2.exe";
//                if (file_exists($ejecutable)) {
//                    echo exec($ejecutable);
//                    $linea = 0;
//                    $archivoResultado = fopen($resultado, "r");
//                    while (($datos = fgetcsv($archivoResultado, ",")) == true) {
//                      $num = count($datos);
//                      $linea++;
//                      for ($columna = 0; $columna < $num; $columna++) {
//                          if ($datos[$columna] == "ok"){
//                              $this->session->set_flashdata('correcto', "Mensaje enviado. <br>");
//                          }
//                          if ($datos[$columna] == "error"){
//                              $this->session->set_flashdata('correcto', "Mensaje no enviado. <br>");
//                          }
//                      }
//                    }
//                    fclose($archivoResultado);
//                } else {
//                    $this->session->set_flashdata('correcto', "Mensaje no enviado. <br>");
//                }
//            } else {
//                $this->session->set_flashdata('correcto', "Mensaje no enviado. <br>");
//            }
//        } else {
//            $this->session->set_flashdata('correcto', "Mensaje no enviado. <br>");
//        }
//        redirect('/index.php/inventariotoolcrib_controller/mostrarInventario');
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
            $url = RUTAWS.'inventariotoolcrib/obtener_inventarios.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            //$data;
            $nilai=$datos->{'inventarios'};
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            $heading=array('Sap','Máquina','Descripción','Número de Parte','Ubicación','Stock','Cantidad Mínima','Cantidad Máxima','Proveedor');
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Inventario");
            $rowNumberH = 1;
            $colH = 'A';
            foreach($heading as $h){
                $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
                $colH++;    
            }
            
            //proveedores
            //Llena array Actividades
            $proveedoresArray = array();
            $i = 0;
            foreach ($this->proveedoresGlobal as $proveedor){
                $proveedoresArray[$proveedor->{'nombre_empresa'}."|".$i."|"] = "".$proveedor->{'idProveedor'};
                $i++;
            }            
            
            $maxrow=$totn+1;
            $row = 2;
            $no = 1;
            foreach($nilai as $n){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'sap'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$n->{'maquina'});
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'descripcion'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'numero_parte'});
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'ubicacion'});
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$n->{'stock'});
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'cantidad_minima'});
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$n->{'cantidad_maxima'});
                
                //busca en array de actividades para desplegar la descripcion de la actividad
                $posicionContenidoProveedor = array_search($n->{'idProveedor'},$proveedoresArray);
                $elemArrayProv = explode("|", $posicionContenidoProveedor);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$elemArrayProv[0]);                
                $row++;
                $no++;
            }
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            $styleArray = array(
            );
            $objPHPExcel->getActiveSheet()->getStyle('A1:I'.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Inventario.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $objWriter->save('php://output');            
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }	
    
    //Exportar datos a Excel de numeros de parte por surtir
    public function exportarExcelSurtir(){
        if ($this->is_logged_in()){
            $url = RUTAWS.'inventariotoolcrib/obtener_alertas.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            //$data;
            $nilai=$datos->{'alertas'};
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            $heading=array('Sap','No Parte','Descripción','Stock','Cantidad Mínima','Cantidad Máxima','Proveedor');
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Por surtir");
            $rowNumberH = 1;
            $colH = 'A';
            foreach($heading as $h){
                $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
                $colH++;    
            }
            
            $maxrow=$totn+1;
            $row = 2;
            $no = 1;
            foreach($nilai as $n){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'sap'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$n->{'numero_parte'});
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'descripcion'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'stock'});
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'cantidad_minima'});
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$n->{'cantidad_maxima'});
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'nombre_empresa'});
                $row++;
                $no++;
            }
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            $styleArray = array(
            );
            $objPHPExcel->getActiveSheet()->getStyle('A1:G'.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Surtir.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $objWriter->save('php://output');            
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }	
    
    //Exportar datos a Excel de numeros de parte por surtir
    public function exportarExcelMovimientos(){
        if ($this->is_logged_in()){
            $url = RUTAWS.'inventariotoolcrib/obtener_movimientos.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            $data;
            $nilai=$datos->{'movimientos'};
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            $heading=array('Usuario','Fecha','Parte','Cantidad','Máquina','Movimiento');
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Movimientos");
            $rowNumberH = 1;
            $colH = 'A';
            foreach($heading as $h){
                $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
                $colH++;    
            }
            $maxrow=$totn+1;
            $row = 2;
            $no = 1;
            foreach($nilai as $n){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'apellido_paterno'}." ".$n->{'apellido_materno'}." ".$n->{'nombre'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$n->{'fecha'});
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'numero_parte'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'cantidad'});
                if ($n->{'descripcion_de_maquina'} == "") { 
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'nombre_maquina'});
                } else {
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'descripcion_de_maquina'});
                }
              
                if ($n->{'cantidad'} < 0) {
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,'BAJA');
                } else {
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,'ALTA');
                }
                $row++;
                $no++;
            }
            
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            $styleArray = array(
            );
            $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Movimientos.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $objWriter->save('php://output');            
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }

    
/*    
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

