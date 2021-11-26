<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->
<head>  	
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <link rel="shortcut icon" href="favicon.png">
    <!-- Para favicon -->
    <link href="<?php echo base_url(); ?>/images/favicon.ico" rel="icon" type="image/x-icon" />    
    
    <title>Ingreso al Sistema</title>
    <link href="<?php echo base_url(); ?>/css/bootstrap.css" rel="stylesheet">
    
    <script>
        function mensaje() {
            if (document.getElementById('registroCorrecto').innerHTML != "") {
                setTimeout(function(){ location.reload(); }, 1000);
            }
        }  
        
        function pausar(intervalo) {
            var w=0;
            setTimeout(function(){ w++; }, intervalo);
        }
        
        function activa(seleccion) {
            <?php $localIP = getHostByName(getHostName()); ?>
            var ipLocal = '<?php echo $localIP; ?>';
            var rootApps = "http://" + ipLocal +":8080/";
            
            
            var ipAplic1 = '192.168.98.200';
            var ipAplic2 = '192.168.96.199';
            var ipAplic3 = '192.168.98.200';
            var ipAplic4 = '192.168.98.200';
            var ipAplic5 = '192.168.98.200';
            
            var dirMantto = "http://" + ipAplic1 + "/cmpv1_0/index.php/usuarios_controller/verificaUsuario";
            var dirCPIWeb = "http://" + ipAplic2 + "/cpiwebv1_0/index.php/usuarios_controller/verificaUsuario";
            var dirTSK = "http://" + ipAplic3 + "/tskWebv1_0/index.php/usuarios_controller/verificaUsuario";
            var dirOperador = "http://" + ipAplic3 + "/tskWebv1_0/index.php/usuarios_controller/verificaUsuarioOperador";
            var dirCalidad = "http://" + ipAplic3 + "/continuousImprovementsv1_0/index.php/usuarios_controller/verificaUsuario";

            let ruta1 = document.getElementById('mantto').getAttribute('src');
            var splitRuta1 = ruta1.split("/");            

            var ruta2 = document.getElementById('almacen').getAttribute('src');
            var splitRuta2 = ruta2.split("/");            
            
            var ruta3 = document.getElementById('tsk').getAttribute('src');
            var splitRuta3 = ruta3.split("/");            
            
            var ruta5 = document.getElementById('calidad').getAttribute('src');
            var splitRuta5 = ruta5.split("/");   
            
            if (seleccion == 1) {
                if (splitRuta1[splitRuta1.length - 1] == "mantenimiento_opaco.png") {
                    ruta1 = ruta1.replace("mantenimiento_opaco.png", "mantenimiento.png");
                    
                    //verifica imagenes cargadas de las otras aplicaciones
                    if (splitRuta2[splitRuta2.length - 1] == "almacen.png") {
                        ruta2 = ruta2.replace("almacen.png", "almacen_opaco.png");
                    }    
                    if (splitRuta3[splitRuta3.length - 1] == "tsk.png") {
                        ruta3 = ruta3.replace("tsk.png", "tsk_opaco.png");
                    }    
                    if (splitRuta5[splitRuta5.length - 1] == "calidad.png") {
                        ruta5 = ruta5.replace("calidad.png", "calidad_opaco.png");
                    }    
                    
                    document.getElementById('mantto').src = ruta1;
                    document.getElementById('almacen').src = ruta2;
                    document.getElementById('tsk').src = ruta3;
                    document.getElementById('calidad').src = ruta5;
                    document.getElementById("loginPrincipal").action = dirMantto;
                } else {
                    ruta1 = ruta1.replace("mantenimiento.png", "mantenimiento_opaco.png");
                    
                    //verifica imagenes cargadas de las otras aplicaciones
                    if (splitRuta2[splitRuta2.length - 1] == "almacen.png") {
                        ruta2 = ruta2.replace("almacen.png", "almacen_opaco.png");
                    }    
                    if (splitRuta3[splitRuta3.length - 1] == "tsk.png") {
                        ruta3 = ruta3.replace("tsk.png", "tsk_opaco.png");
                    }    
                    if (splitRuta5[splitRuta5.length - 1] == "calidad.png") {
                        ruta5 = ruta5.replace("calidad.png", "calidad_opaco.png");
                    }    
                    
                    document.getElementById('mantto').src = ruta1;
                    document.getElementById('almacen').src = ruta2;
                    document.getElementById('tsk').src = ruta3;
                    document.getElementById('calidad').src = ruta5;
                    document.getElementById("loginPrincipal").action = dirMantto;
                }
            }
            
            if (seleccion == 2) {
                if (splitRuta2[splitRuta2.length - 1] == "almacen_opaco.png") {
                    ruta2 = ruta2.replace("almacen_opaco.png", "almacen.png");
                    
                    //verifica imagenes cargadas de las otras aplicaciones
                    if (splitRuta1[splitRuta1.length - 1] == "mantenimiento.png") {
                        ruta1 = ruta1.replace("mantenimiento.png", "mantenimiento_opaco.png");
                    }    
                    if (splitRuta3[splitRuta3.length - 1] == "tsk.png") {
                        ruta3 = ruta3.replace("tsk.png", "tsk_opaco.png");
                    }    
                    if (splitRuta5[splitRuta5.length - 1] == "calidad.png") {
                        ruta5 = ruta5.replace("calidad.png", "calidad_opaco.png");
                    }    
                    
                    document.getElementById('mantto').src = ruta1;
                    document.getElementById('almacen').src = ruta2;
                    document.getElementById('tsk').src = ruta3;
                    document.getElementById('calidad').src = ruta5;
                    document.getElementById("loginPrincipal").action = dirCPIWeb;
                } else {
                    ruta2 = ruta2.replace("almacen.png", "almacen_opaco.png");
                    
                    //verifica imagenes cargadas de las otras aplicaciones
                    if (splitRuta1[splitRuta1.length - 1] == "mantenimiento.png") {
                        ruta1 = ruta1.replace("mantenimiento.png", "mantenimiento_opaco.png");
                    }    
                    if (splitRuta3[splitRuta3.length - 1] == "tsk.png") {
                        ruta3 = ruta3.replace("tsk_opaco.png", "tsk_opaco.png");
                    }                     
                    if (splitRuta5[splitRuta5.length - 1] == "calidad.png") {
                        ruta5 = ruta5.replace("calidad.png", "calidad_opaco.png");
                    }    
                    document.getElementById('mantto').src = ruta1;
                    document.getElementById('almacen').src = ruta2;
                    document.getElementById('tsk').src = ruta3;
                    document.getElementById('calidad').src = ruta5;
                    document.getElementById("loginPrincipal").action = dirCPIWeb;
                }
            }
            
            if (seleccion == 3) {
                if (splitRuta3[splitRuta3.length - 1] == "tsk_opaco.png") {
                    ruta3 = ruta3.replace("tsk_opaco.png", "tsk.png");
                    
                    //verifica imagenes cargadas de las otras aplicaciones
                    if (splitRuta1[splitRuta1.length - 1] == "mantenimiento.png") {
                        ruta1 = ruta1.replace("mantenimiento.png", "mantenimiento_opaco.png");
                    }    
                    if (splitRuta2[splitRuta2.length - 1] == "almacen.png") {
                        ruta2 = ruta2.replace("almacen.png", "almacen_opaco.png");
                    }   
                    if (splitRuta5[splitRuta5.length - 1] == "calidad.png") {
                        ruta2 = ruta2.replace("calidad.png", "calidad_opaco.png");
                    }                        
                    
                    document.getElementById('mantto').src = ruta1;
                    document.getElementById('almacen').src = ruta2;
                    document.getElementById('tsk').src = ruta3;
                    document.getElementById('calidad').src = ruta5;
                    document.getElementById("loginPrincipal").action = dirTSK;
                } else {
                    ruta3 = ruta3.replace("tsk.png", "tsk_opaco.png");
                    
                    //verifica imagenes cargadas de las otras aplicaciones
                    if (splitRuta1[splitRuta1.length - 1] == "mantenimiento.png") {
                        ruta1 = ruta1.replace("mantenimiento.png", "mantenimiento_opaco.png");
                    }    
                    if (splitRuta2[splitRuta2.length - 1] == "almacen.png") {
                        ruta2 = ruta2.replace("almacen.png", "almacen_opaco.png");
                    }    
                    if (splitRuta5[splitRuta5.length - 1] == "calidad.png") {
                        ruta2 = ruta2.replace("calidad.png", "calidad_opaco.png");
                    }                        
                    
                    document.getElementById('mantto').src = ruta1;
                    document.getElementById('almacen').src = ruta2;
                    document.getElementById('tsk').src = ruta3;
                    document.getElementById('calidad').src = ruta5;
                    document.getElementById("loginPrincipal").action = dirTSK;
                }
            }
            
            if (seleccion == 5) {
                if (splitRuta5[splitRuta5.length - 1] == "calidad_opaco.png") {
                    ruta5 = ruta5.replace("calidad_opaco.png", "calidad.png");

                    //verifica imagenes cargadas de las otras aplicaciones
                    if (splitRuta1[splitRuta1.length - 1] == "mantenimiento.png") {
                        ruta1 = ruta1.replace("mantenimiento.png", "mantenimiento_opaco.png");
                    }    
                    if (splitRuta2[splitRuta2.length - 1] == "almacen.png") {
                        ruta2 = ruta2.replace("almacen.png", "almacen_opaco.png");
                    }    
                    if (splitRuta3[splitRuta3.length - 1] == "tsk.png") {
                        ruta3 = ruta3.replace("tsk.png", "tsk_opaco.png");
                    }    
                    
                    document.getElementById('mantto').src = ruta1;
                    document.getElementById('almacen').src = ruta2;
                    document.getElementById('tsk').src = ruta3;
                    document.getElementById('calidad').src = ruta5;
                    document.getElementById("loginPrincipal").action = dirCalidad;
                } else {
                    ruta5 = ruta5.replace("calidad.png", "calidad_opaco.png");
                    
                    //verifica imagenes cargadas de las otras aplicaciones
                    if (splitRuta1[splitRuta1.length - 1] == "mantenimiento.png") {
                        ruta1 = ruta1.replace("mantenimiento.png", "mantenimiento_opaco.png");
                    }    
                    if (splitRuta2[splitRuta2.length - 1] == "almacen.png") {
                        ruta2 = ruta2.replace("almacen.png", "almacen_opaco.png");
                    }    
                    if (splitRuta3[splitRuta3.length - 1] == "tsk.png") {
                        ruta3 = ruta3.replace("tsk.png", "tsk_opaco.png");
                    }    
                    
                    document.getElementById('mantto').src = ruta1;
                    document.getElementById('almacen').src = ruta2;
                    document.getElementById('tsk').src = ruta3;
                    document.getElementById('calidad').src = ruta5;
                    document.getElementById("loginPrincipal").action = dirCalidad;
                }
            }
            
            
            
        }
        
        function verificaSeleccion(){
            var continua = false;
            
            var ruta1 = document.getElementById('mantto').getAttribute('src');
            var ruta2 = document.getElementById('almacen').getAttribute('src');
            var ruta3 = document.getElementById('tsk').getAttribute('src');
            var ruta5 = document.getElementById('calidad').getAttribute('src');
            
            var splitRuta1 = ruta1.split("/");            
            var splitRuta2 = ruta2.split("/");            
            var splitRuta3 = ruta3.split("/");            
            var splitRuta5 = ruta5.split("/");     
            
            if ((splitRuta1[splitRuta1.length - 1] == "mantenimiento_opaco.png") && 
                (splitRuta2[splitRuta2.length - 1] == "almacen_opaco.png") && 
                (splitRuta5[splitRuta5.length - 1] == "calidad_opaco.png")
                ){
                alert("Debes elegir una aplicación");
                continua = false;
            } else {
                if (splitRuta2[splitRuta2.length - 1] == "almacen.png") {
                    move();
                }
                continua = true;
            }
            return continua;
        }
        
        //para progress bar
        var i = 0;
        function move() {
          if (document.getElementById('statusBDHidden').value == 0) {
                var avance = document.getElementById('avanceHidden').value;
                if (i == 0) {
                  i = 1;
                  var elem = document.getElementById("myBar");
                  document.getElementById('lblBarra').style.display = 'block';
                  var width = 0;
                  var id = setInterval(frame, 150);
                  function frame() {
                    if (width >= Math.floor(parseFloat(avance))) {
                      clearInterval(id);
//                      pausar(2000);
                      i = 0;
//                      move();
                    } else {
                      width++;
                      elem.style.width = width + "%";
                      elem.innerHTML = width + "%";
                    }
                  }
                }
          }
        }    
        
        function verificaDatos(){
            var usuario = document.getElementById("usuario").value;
            var clave = document.getElementById("clave").value;
            if ((usuario =="") || (clave =="")){
                alert("Debes ingresar tu clave y password.");
                document.getElementById('urlOperador').setAttribute('href', "#");
                document.getElementById("usuario").focus();
                return;
            } else {
                var enlace = "<?php echo base_url()?>/index.php/usuarioturno_controller/verificaUsuarioOperador/" + usuario + "/" + clave;
                document.getElementById('urlOperador').setAttribute('href', enlace);
            }
        }
        
        function advertenciaRetardo(){
            var ruta = "<?php echo base_url(); ?>images/menubar/tsk_opaco.png";
            if (document.getElementById('tsk').src != ruta) {
                document.getElementById('tsk').src = ruta;
                document.getElementById('mensajeCargaTsk').style.display = "block";
                document.getElementById('aplicTskEnlace').disabled = true;
            }
        }
        
    </script>
    
    
    <style>
        /* Login Form */
        .login-form input{
            display: block;
            margin:0 auto 15px;
            width:70%;
            background: #d6d6d6;    
            border:2px solid #cc0000;
            color:black;
            padding: 8px;
            
        }
        /* Login Button */
        .btn.btn-red {
            width: 120px;
            display:block;
            margin: 20px auto 20px;
            color: white;
            text-transform:uppercase ;	
            text-shadow: 1px 2px 2px #c44c4c;
            background: #e75a5a; 
            border: 1px solid #008FFF;
            -webkit-box-shadow: inset 0 1px 2px #ff8c8c;
            -moz-box-shadow: inset 0 1px 2px #ff8c8c;
            box-shadow: inset 0 1px 2px #ff8c8c;
            -webkit-transition: background .5s ease-in-out;
            -moz-transition: background .5s ease-in-out;
            -o-transition: background .5s ease-in-out;
            transition: background .5s ease-in-out;
            
        }

        .btn.btn-red:hover {	 
            background: #d94444; 
        } 
        .btn.btn-red.btn-reset{
            width: 180px;
        }
        
        #myBar {
          width: 0%;
          height: 30px;
          background-color: #4CAF50;
          text-align: center; /* To center it horizontally (if you want) */
          line-height: 30px; /* To center it vertically */
          color: white;
        }        
    </style>
</head>
<body onload="mensaje()">
    <div class="container" 
         <?php  ?>
        <div class="row" style="margin-top: -45px;">
            <div class="col-sm-12">
                   
                   <center>
                   <a href="#">
                       <br><br>
                       <img class="img-responsive" src="<?php echo base_url(); ?>/images/WEWIRELOGO.png" alt="Logo de la Empresa" />
                   </a>
                   </center>
                   <h3 style="text-align: center; margin-top: -30px;margin-left: 20px;color:#666666">Data Management Portal</h3>
            </div>
        </div>
        <div class="row">
            <br><br>
            <div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4" style="border: 5px solid #008FFF;border-radius: 10px;margin-top: -30px;">
                <div>
                    <div>
                        <br>
                        <h4 style="font-weight:bold;text-align:center;">INGRESO AL SISTEMA</h4>
                        <br>
                        <center>
                            
                            
                            <img id="mantto" onclick="activa(1)" src="<?php echo base_url(); ?>images/menubar/mantenimiento_opaco.png" alt="Mantenimientos" title="Mantenimientos" />
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <img id="almacen" onclick="activa(2)" src="<?php echo base_url(); ?>images/menubar/almacen_opaco.png" alt="Almacén" title="Almacén" />
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <!--
                            <img id="tsk" onclick="activa(3)" src="<?php echo base_url(); ?>images/menubar/tsk_opaco.png" alt="TSK" title="TSK" />
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <img id="tsk" onclick="activa(3)" src="<?php echo base_url(); ?>images/menubar/bot6.jpg" alt="Ayuda Toolcrib" title="Ayuda Toolcrib" />
                            -->
                            
                            <a id="aplicTskEnlace" href="http://192.168.98.200/tskWebv1_0/index.php/usuarios_controller/mostrarTskAdministradorLibre">
                                <img id="tsk" onclick="advertenciaRetardo()" src="<?php echo base_url(); ?>images/menubar/tsk_opaco.png" alt="TSK" title="TSK" />
                            </a>
                            
                            &nbsp;&nbsp;&nbsp;
                            <a href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/mostrarBot">
                            <img id="tsk" src="<?php echo base_url(); ?>images/menubar/bot4_a.png" alt="Ayuda Toolcrib" title="Ayuda Toolcrib" />
                            </a>
                            <!--
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <img id="operador" onclick="activa(4)" src="<?php echo base_url(); ?>images/menubar/soporte_tecnico1.jpg" alt="Soporte Técnico" title="Soporte Técnico" />
                            -->
                            
                            &nbsp;&nbsp;
                            <img id="calidad" onclick="activa(5)" src="<?php echo base_url(); ?>images/menubar/calidad_opaco.png" alt="Mejora Continua" title="Mejora Continua" />
                            
                        </center>
                    </div> 
                    <hr />
                    <div class="login-form">
                        <div>
                            <?php 
                                $correcto = $this->session->flashdata('correcto');
                                if ($correcto) { ?>
                            <span id="registroCorrecto" style="text-align: center;color:#ff6666;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $correcto ?></span><br><br>
                            <?php 
                            } ?>
                        </div>
                        <form id="loginPrincipal" onsubmit="javascript: return verificaSeleccion()" action="" method="post"  >
                            <input type="text" id="usuario" name="usuario" autocomplete="false" placeholder="Nombre de Usuario" required autofocus/> 
                            <br>
                            <input type="password" id="clave" name="clave" placeholder="Contraseña" required/>
                            <br>
                            <button type="submit" class="btn btn-red" style="background: #008FFF">Ingresar</button> 
                            <br>
			    <a style="margin-top:-5px;" class='btn btn-success center-block' href="<?php echo base_url(); ?>index.php/mantenimiento_controller/consultaMantenimientosPublico">Consulta de Mantenimientos</a>
                            <br>
                            
			    <a id='urlOperador' onclick="verificaDatos();" style="margin-top:-5px;" class='btn btn-success center-block' href="#">Solicitud de Técnico</a>
                            <br>
                            <p id="mensajeCargaTsk" style="background: #ccc; background-color: #ccc; color: #c44c4c; font-size: 16px; font-weight: bold; display: none;">Por favor espere mientras se carga la información. </p>
                        </form>	
                        <?php echo "<script> mensaje(); </script>"; ?>;
                    </div> 	
                </div>
                <br>
            </div>
        </div>
    </div>
</body>
</html>
