<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Técnicos en Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
  
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
	
    <script type="text/javascript" charset="utf-8">
        function enviarSolicitud(idUsuario,idUsuarioOperador,tipoUsuario){
            var idMaquina = document.getElementById("maquina").value;
            var falla = document.getElementById("falla").value;
            var idFalla = 310;
            var descripcionFalla = "";
            var idProyecto;
            
            //valida info campos
            if (idMaquina=="") {
                alert("Debes seleccionar una máquina");
                return;
            }
            
            if ((document.getElementById("fallanotificacion").value == "") && (falla == "")) {
                alert("Debes registrar una falla");
                return;
            }
            
            if (document.getElementById("fallanotificacion").value != "") {
                idFalla = document.getElementById("fallanotificacion").value;
            } else {
                descripcionFalla = falla;
            }
            
            var prioridad = "normal";
            if ((tipoUsuario == "Supervisor") && (document.getElementById('prioridad').value == "")) {
                alert("Debes seleccionar una prioridad");
                return;
            } 
            if (tipoUsuario == "Supervisor") {
                prioridad = document.getElementById("prioridad").value;                
            }
            
            if (document.getElementById("proyecto").value == "") {
                alert("Debes seleccionar un proyecto");
                return;
            } else {
                idProyecto = document.getElementById("proyecto").value;
            }
            //Fin valida info campos

            var notificacion = "";
            notificacion = idUsuario + "|" + idUsuarioOperador + "|" +  idMaquina + "|" + idFalla + "|" + prioridad + "|" + descripcionFalla + "|" + idProyecto + "|";
            document.getElementById('txtEnviaSolicitud').innerHTML = 'Solicitando atención... Espere un momento.';
                 
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/grabaNotificacionParaTecnico1.php?notificacion=" + notificacion,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    if (response != 0) {
                        document.getElementById('idNotificacion').value = response;
                        document.getElementById('btnIniciaDialogo').disabled = true;
                        document.getElementById('txtEnviaSolicitud').innerHTML = 'Mensaje enviado.';
                    }
                    if (idUsuario == 1) { //despues de levantar la notificacion hay que regresar a las atenciones (notificacion encolada)
                        window.history.back();
                    }
                },
                error: function(response){
                    alert("Error");
                }
            });	
        }

        function iniciacronometro(claveTecnico) {
            var claveTecnicoAtencion = prompt("Para continuar introduce la clave del Técnico");
            if (claveTecnicoAtencion != claveTecnico) {
                alert("No coinciden las claves, no se puede continuar la atención.");
                return;
            }
            
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/iniciaCronometroAtencion.php?idNotificacion=" + document.getElementById('idNotificacion').value,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    if (response != 0) {
                        alert("Inicia tiempo de atención");
                        document.getElementById('btnIniciaAtencion').disabled = true;
                        document.getElementById('btnRegresar').disabled = false;
                        document.getElementById('btnFinAtencion').disabled = false;
                    }
                },
                error: function(response){
                    alert("Error");
                }
            });	
        }
        
        function finalizaAtencion(){
            //verifica campos
            if (document.getElementById('calificacion').value == "") {
                alert("Debes seleccionar una calificación");
                return;
            }
            
            var uu = "<?php echo $user;?>";
            var uc = "<?php echo $pass;?>";
            var ruta = "http://192.168.98.200/cmpv1_0//index.php/usuarioturno_controller/verificaUsuarioOperador/" + uu + "/" + uc;
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/terminaCronometroAtencion.php?idNotificacion=" 
                        + document.getElementById('idNotificacion').value + "&calificacionAtencion=" + document.getElementById('calificacion').value
                        + "&comentarios=" + document.getElementById('observaciones').value,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    if (response != 0) {
                        alert("Fin de la atención. Seguimos a tus órdenes.");
                        window.location.replace(ruta);
                    }
                },
                error: function(response){
                    alert("Error");
                }
            });	
        }
        
        function regresar() {
            var conf = confirm("¿Realmente deseas salir de la página?. Recuerda que puedes continuar con la solicitud desde el panel ordenes en curso. ");
            if (conf == false) {
                return false;
            } else {
                jQuery.ajax({
                    url: "<?php echo base_url(); ?>/consultas_ajax/pausa_nva_solicitud.php?idNotificacion=" + document.getElementById('idNotificacion').value,
                    cache: false,
                    contentType: "text/html; charset=UTF-8",
                    success: function(response){
                        var uu = "<?php echo $user;?>";
                        var uc = "<?php echo $pass;?>";
                        var ruta = "http://192.168.98.200/cmpv1_0//index.php/usuarioturno_controller/verificaUsuarioOperador/" + uu + "/" + uc;
                        window.location.replace(ruta);
                    },
                    error: function(response){
                        alert("Error");
                    }
                });	                
            }   
        }
        
        function verificaSeleccion() {
            if (document.getElementById("fallanotificacion").value != "") {
                document.getElementById("falla").disabled = true;
            } else {
                document.getElementById("falla").disabled = false;
            }
        }
    </script>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">
    <h3 style="text-align: center" style='background: #00cc00'>Solicitud de atención </h3>
    <h4 style="text-align: center" style='background: #00cc00'>Técnico: <?php echo $nombreUsuario; ?></h4>
    <div class="panel panel-default" style="border: 2px solid blue">
        <div class="panel-heading"><h4>Paso1.- Llena completamente la información</h4></div>
        <input type='hidden' name="idNotificacion" id="idNotificacion" />
        <div class="panel-body">
            <label class="control-label h5 text-success" for="maquina">Selecciona el nombre de la Máquina:</label>
            <select class="form-control" name="maquina" id="maquina" required autofocus>
                <option value="">Nombre Máquina...</option>
                <?php 
                    foreach($maquinasSimple as $fila) {
                        if ($fila->{'status'} == 1) {
                            $palabra1 = "Tableros Dimensionales";
                            $palabra2 = "Tableros Auxiliares";
                            if ((strpos($fila->{'nombre_maquina'}, $palabra1) !== false) || (strpos($fila->{'nombre_maquina'}, $palabra2) !== false)) {
                            } else{
                                echo "<option codigo1='".$fila->{'idMaquina'}."' value='".$fila->{'idMaquina'}."'>".$fila->{'nombre_maquina'}." - ".$fila->{'numero_maquina'}."</option>";
                            }
                        }
                    } 
                ?>
            </select>
            
            <label class="control-label h5 text-success" for="fallanotificacion">Falla a reportar:</label>
            <select class="form-control" onchange="verificaSeleccion();" name="fallanotificacion" id="fallanotificacion" required>
                <option value="">Falla...</option>
                <?php 
                    foreach($fallasNotificaciones as $filaN) {
                        echo "<option codigo2='".$filaN->{'idMaquina'}."' value=".$filaN->{'idFallaNotificacion'}.">".$filaN->{'descripcion'}."</option>";
                    } 
                ?>
            </select>
            
            <div class="form-group">
                <label class="control-label h5 text-success" for="falla">Otra falla no mostrada:</label>
                <textarea class="form-control" rows="5" id="falla" name="falla" placeholder="Ingresa la falla a reportar"></textarea>
            </div> 
            
            <?php if ($permisos == "Supervisor") { ?>
            <label class="control-label h5 text-success" for="prioridad">Selecciona la prioridad:</label>
            <select class="form-control" name="prioridad" id="prioridad" required>
                <option value="">Prioridad...</option>
                <option value="alta">Alta</option>
                <option value="media">Media</option>
                <option value="normal">Normal</option>
            </select>        
            <?php } ?>
            <br>

            <label class="control-label h5 text-success" for="proyecto">Proyecto:</label>
            <select class="form-control" name="proyecto" id="proyecto" required>
                <option value="">Proyecto...</option>
                <?php 
                    foreach($proyectos as $filaN) {
                        echo "<option value=".$filaN->{'idProyecto'}.">".$filaN->{'descripcion_proyecto'}."</option>";
                    } 
                ?>
            </select>
            <br><br>
            
            <div class="form-group">
                <table>
                    <tr>
                        <td>
                            <input id="btnIniciaDialogo"   type="button" class="btn btn-primary" value="Enviar solicitud" onclick="enviarSolicitud(<?php echo $idUsuario.",".$idUsuarioOperador.",'".$permisos."'"; ?>)" />
                            <!--
                            <a class="btn btn-primary" href="<?php echo base_url();?>index.php/turnos_controller/enviaTelegram">Enviar solicitud por Telegram</a>
                            -->
                        </td>
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td>
                            <p id="txtEnviaSolicitud" style="margin-top: 10px;"></p>
                        </td>
                    </tr>
                </table>
            </div>             
        </div>
        <div class='panel-footer'>
        </div>
    </div>
    
    <div class="panel panel-default" style="border: 2px solid blue">
        <div class="panel-heading"><h4>Paso2.- Presiona el botón inicio atención</h4></div>
        <div class="panel-body">
            <div class="form-group">
                <input id="btnIniciaAtencion" type="button" class="btn btn-primary" value="Inicio Atención" onclick="iniciacronometro('<?php echo $usuario->{'clave'}; ?>');"  disabled />
                <!--
                <input id="btnRegresar"   type="button" class="btn btn-primary" value="Notificar nueva falla" onclick="javascript: return regresar()" disabled />
                -->
            </div> 
        </div>
        <div class='panel-footer'>
        </div>
    </div>
    
    <div class="panel panel-default" style="border: 2px solid blue">
        <div class="panel-heading"><h4>Paso3.- Califica la atención.</h4></div>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label h5 text-success" for="maquina">Calificación de la atención:</label>
                <select class="form-control" name="calificacion" id="calificacion" required autofocus>
                    <option value="">Selecciona una...</option>
                    <option value="10">Excelente</option>
                    <option value="8">Bien</option>
                    <option value="7">Regular</option>
                    <option value="5">Mala</option>
                </select>            
                <div class="form-group">
                    <label class="control-label h5 text-success" for="observaciones">Observaciones:</label>
                    <textarea class="form-control" rows="5" id="observaciones" name="observaciones" placeholder="Observaciones"></textarea>
                </div> 
                <input id='btnFinAtencion' onclick="finalizaAtencion();" type="button" class="btn btn-primary" value="Fin Atención" />
            </div> 
        </div>
        <div class='panel-footer'>
        </div>
    </div>
    
    <audio id="mySound" src="<?php echo base_url(); ?>respuesta_tecnico.wav"></audio>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->

    <!-- Para notificaciones -->
    <script>
          var idUsuarioOperador = '<?php echo $idUsuarioOperador;?>';
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Fin Para notificaciones -->

    <script>
        $(document).ready(function(){
            <!-- Para notificaciones -->
            function load_unseen_notification() {
                //alert(idUsuarioOperador);
                $.ajax({
                  url:"<?php echo base_url(); ?>fetchLider.php",
                  method:"POST",
                  data:{idUsuarioOperador:idUsuarioOperador},
                  //dataType:"json",
                  success:function(data) {
                    var obj = jQuery.parseJSON(data);
                    //if ((obj.notification != -1) && (idUsuarioOperador != 1)) { //
                    if (obj.notification != -1) {
                        document.getElementById('txtEnviaSolicitud').innerHTML = obj.notification + "\n";
                        document.getElementById('mySound').play();
  
                        document.getElementById('btnIniciaAtencion').disabled = false;
                    }
                  },
                  error:function(data) {
                    document.getElementById('txtEnviaSolicitud').innerHTML = "";
                    //alert("Error");
                  }
                });
            }
            
            load_unseen_notification();
            
            setInterval(function(){ 
              load_unseen_notification();;
            }, 5000);
            <!-- Para notificaciones -->
            
            $("#send-btn").on("click", function(){
                $value = $("#data").val();
                $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ $value +'</p></div></div>';
                $(".form").append($msg);
                $("#data").val('');
                $.ajax({
                    url: 'message.php',
                    type: 'POST',
                    data: 'text='+$value,
                    success: function(result){
                        $replay = '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>'+ result +'</p></div></div>';
                        $(".form").append($replay);
                        $(".form").scrollTop($(".form")[0].scrollHeight);
                    }
                });
            });
			
            $('#data').keypress(function(e) {
                if (e.which == 13) {
                    $value = $("#data").val();
                    $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ $value +'</p></div></div>';
                    $(".form").append($msg);
                    $("#data").val('');
                    $.ajax({
                        url: 'message.php',
                        type: 'POST',
                        data: 'text='+$value,
                        success: function(result){
                                $replay = '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>'+ result +'</p></div></div>';
                                $(".form").append($replay);
                                $(".form").scrollTop($(".form")[0].scrollHeight);
                        }
                    });
                }
            });
			
        });
        
    </script>
    
    <script type="text/javascript"> 
        var id1 = document.getElementById("maquina");
        var id2 = document.getElementById("fallanotificacion");
        var t = 0;
        
        // Añade un evento change al elemento id1, asociado a la función cambiar()
        if (id1.addEventListener) {     // Para la mayoría de los navegadores, excepto IE 8 y anteriores
            id1.addEventListener("change", cambiar);
        } else if (id1.attachEvent) {   // Para IE 8 y anteriores
            id1.attachEvent("change", cambiar); // attachEvent() es el método equivalente a addEventListener()
        }
        
        function limpiarSelect() {
            t=0;
            id2.size=t;
            for (var i=0; i < id2.options.length;i++) {    
                id2.options[i].style.display = "none";
            }
        }
        
        function selectFirstValueSelect() {  
            for (var i=0; i<=id2.options.length - 1;i++) {   
                var style2 = window.getComputedStyle(id2.options[i]);
                if (style2.display !== 'none') {
                    id2.options.selectedIndex = i;
                    break;
                }
            }
        }        
        
        function cambiar() {
            limpiarSelect();
            id2.options[0].style.display = "block";
            var k = 0;
            while(k<id2.options.length){
                //console.log(id2.options[k].getAttribute('codigo1') == id1.options[id1.selectedIndex].getAttribute('codigo1'));
                var valor1 = id1.options[id1.selectedIndex].getAttribute('codigo1');
                var valor2 = id2.options[k].getAttribute('codigo2');
                if (valor1 == valor2) {
                    t++
                    console.log("karlita" + valor1 + " - " + valor2);
                    id2.size=t;
                    id2.options[k].style.display = "block";
                }
                k++;
            }

            selectFirstValueSelect();
        }
        cambiar();
    </script>
    
    
</body>	
</html>
