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
            //Fin valida info campos

            var notificacion = "";
            notificacion = idUsuario + "|" + idUsuarioOperador + "|" +  idMaquina + "|" + idFalla + "|" + prioridad + "|" + descripcionFalla + "|";
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
                },
                error: function(response){
                    alert("Error");
                }
            });	
        }


        function iniciacronometro(idUsuarioTecnicoActual,pausado,claveTecnico,tecnicos) {
            var idUsuarioTecnico;
            if (pausado=="0") {
                idUsuarioTecnico = idUsuarioTecnicoActual;
                
                //verifica cve del tecnico original
                var claveTecnicoAtencion = prompt("Para continuar introduce la clave del Técnico");
                if (claveTecnicoAtencion != claveTecnico) {
                    alert("No coinciden las claves, no se puede continuar la atención.");
                    return;
                }
            } else {
                var idNvoTecnico = document.getElementById("tecnicos").value;
                //verifica si se selecciono un tecnico
                if (idNvoTecnico == "") {
                    var confirmarUsuarioOriginal = confirm("¿Es el Técnico asignado originalmente (Aceptar-Si, Cancelar -No)?.");
                    if (confirmarUsuarioOriginal == false) {
                        alert("Debes seleccionar un Técnico.");
                        return;
                    } else {
                        idUsuarioTecnico = idUsuarioTecnicoActual;
                        
                        //verifica cve del tecnico original
                        var claveTecnicoAtencion = prompt("Para continuar introduce la clave del Técnico");
                        if (claveTecnicoAtencion != claveTecnico) {
                            alert("No coinciden las claves, no se puede continuar la atención.");
                            return;
                        }
                    }
                } else {
                    if (idUsuarioTecnicoActual == idNvoTecnico) {
                        idUsuarioTecnico = idUsuarioTecnicoActual;
                        
                        //verifica cve del tecnico original
                        var claveTecnicoAtencion = prompt("Para continuar introduce la clave del Técnico");
                        if (claveTecnicoAtencion != claveTecnico) {
                            alert("No coinciden las claves, no se puede continuar la atención.");
                            return;
                        }
                    } else {
                        var conf = confirm("¿Deseas asignar la notificación original al nuevo Técnico (Aceptar-Si, Cancelar -No)?");
                        if (conf == false) {
                            
                            //verifica cve del tecnico original
                            var claveTecnicoAtencion = prompt("Para continuar introduce la clave del nuevo Técnico");
                            var tecnicosArray = tecnicos.split("|");
                            for (var i=0; i<tecnicosArray.length; i++) {
                                var tecnicoActualArray = tecnicosArray[i].split(",");
                                //si es el mismo id
                                if (idUsuarioTecnico == tecnicoActualArray[0]) {
                                    //comprueba la clave introducida
                                    if (claveTecnicoAtencion != tecnicoActualArray[1]) {
                                        alert("No coinciden las claves, no se puede continuar la atención.");
                                        return;
                                    }
                                }                                
                            }
                            
                            //asigna al anterior el id para que la notificaion quede igual
                            idUsuarioTecnico = idUsuarioTecnicoActual;
                        } else {
                            idUsuarioTecnico = idNvoTecnico;
                            
                            //verifica cve del tecnico original
                            var claveTecnicoAtencion = prompt("Para continuar introduce la clave del nuevo Técnico");
                            var tecnicosArray = tecnicos.split("|");
                            for (var i=0; i<tecnicosArray.length; i++) {
                                var tecnicoActualArray = tecnicosArray[i].split(",");
                                //si es el mismo id
                                if (idUsuarioTecnico == tecnicoActualArray[0]) {
                                    //comprueba la clave introducida
                                    if (claveTecnicoAtencion != tecnicoActualArray[1]) {
                                        alert("No coinciden las claves, no se puede continuar la atención.");
                                        return;
                                    }
                                }                                
                            }
                            
                            jQuery.ajax({
                                url: "<?php echo base_url(); ?>/consultas_ajax/cambiar_notificacion_nuevo_tecnico.php?idNotificacion=" 
                                        + document.getElementById('idNotificacion').value + "&idUsuarioTecnico=" + idUsuarioTecnico,
                                cache: false,
                                contentType: "text/html; charset=UTF-8",
                                success: function(response){
                                    if (response != 0) {
                                    }
                                },
                                error: function(response){
                                    alert("Error");
                                }
                            });	                            
                        }                
                    }
                }
            }
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/iniciaCronometroAtencion.php?idNotificacion=" 
                        + document.getElementById('idNotificacion').value + "&idUsuarioTecnico=" + idUsuarioTecnico,
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
        
        function finalizaAtencion(idNotificacion){
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
                        + idNotificacion + "&calificacionAtencion=" + document.getElementById('calificacion').value
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
    <h3 style="text-align: center" style='background: #00cc00'>Cierre de atención</h3>
    <input type="hidden" name="idNotificacion" id="idNotificacion" value="<?php echo $idNotificacion; ?>" />
    <?php if ($etapa == 1) { ?>
    <div class="panel panel-default" style="border: 2px solid blue">
        <div class="panel-heading"><h4>Paso2.- Presiona el botón inicio atención</h4></div>
        <div class="panel-body">
            <div class="form-group">
                <?php 
                    $clavesTecnicos = "";
                    foreach ($usuarios as $fila) {
                        if ($fila->{'permisos'}=="Técnico") {
                            $clavesTecnicos .= $fila->{'idUsuario'}.",".$fila->{'clave'}."|";
                        }
                    }
                ?>
                
                <?php 
                    $idArea1TecnicoActual = 0;
                    $idArea2TecnicoActual = 0;
                    $idArea3TecnicoActual = 0;
                    $idArea4TecnicoActual = 0;
                    $idArea5TecnicoActual = 0;
                    
                    foreach($turnos as $fila){
                        if ($notificacion->{'idUsuarioTecnico'} == $fila->{'idUsuario'}) {
                            $idArea1TecnicoActual = $fila->{'idArea1'};
                            $idArea2TecnicoActual = $fila->{'idArea2'};
                            $idArea3TecnicoActual = $fila->{'idArea3'};
                            $idArea4TecnicoActual = $fila->{'idArea4'};
                            $idArea5TecnicoActual = $fila->{'idArea5'};
                        }                        
                    }
                    //echo "Area1: ".$idArea1TecnicoActual." Area2: ".$idArea2TecnicoActual." Area3: ".$idArea3TecnicoActual." Area4: ".$idArea4TecnicoActual." Area5: ".$idArea5TecnicoActual;
                ?>
                
                <?php if ($notificacion->{'pausado'} != 0) { ?>
                <label style="margin-top: -5px;" class="control-label h5 text-success" for="tecnicos">Técnicos disponibles:</label>
                <select class="form-control" name="tecnicos" id="tecnicos">
                    <option value="">Selecciona uno...</option>
                    <?php 
                    foreach($turnos as $fila){
                        //si esta disponible verifico si es su area
                        $mostrado = 0;
                        if ($fila->{'status'} == 0) {
                            //si no es el area general y es igual a alguna de las areas del tecnico actual muestro el tecnico
                            //area1
                            if ($fila->{'idArea1'} != 17) {
                                if (($fila->{'idArea1'} == $idArea1TecnicoActual) || ($fila->{'idArea1'} == $idArea2TecnicoActual) || ($fila->{'idArea1'} == $idArea3TecnicoActual) || ($fila->{'idArea1'} == $idArea4TecnicoActual) || ($fila->{'idArea1'} == $idArea5TecnicoActual)) {
                                    echo "<option value='".$fila->{'idUsuario'}."'>".$fila->{'nombre'}." ".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}."</option>";
                                    $mostrado = 1;                                
                                }
                            }
                            
                            //area2
                            if ($mostrado == 0) {
                                if ($fila->{'idArea2'} != 17) {
                                    if (($fila->{'idArea2'} == $idArea1TecnicoActual) || ($fila->{'idArea2'} == $idArea2TecnicoActual) || ($fila->{'idArea2'} == $idArea3TecnicoActual) || ($fila->{'idArea2'} == $idArea4TecnicoActual) || ($fila->{'idArea2'} == $idArea5TecnicoActual)) {
                                        echo "<option value='".$fila->{'idUsuario'}."'>".$fila->{'nombre'}." ".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}."</option>";
                                        $mostrado = 1;                                
                                    }
                                }
                            }

                            //area3
                            if ($mostrado == 0) {
                                if ($fila->{'idArea3'} != 17) {
                                    if (($fila->{'idArea3'} == $idArea1TecnicoActual) || ($fila->{'idArea3'} == $idArea2TecnicoActual) || ($fila->{'idArea3'} == $idArea3TecnicoActual) || ($fila->{'idArea3'} == $idArea4TecnicoActual) || ($fila->{'idArea3'} == $idArea5TecnicoActual)) {
                                        echo "<option value='".$fila->{'idUsuario'}."'>".$fila->{'nombre'}." ".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}."</option>";
                                        $mostrado = 1;                                
                                    }
                                }
                            }
                            
                            //area4
                            if ($mostrado == 0) {
                                if ($fila->{'idArea4'} != 17) {
                                    if (($fila->{'idArea4'} == $idArea1TecnicoActual) || ($fila->{'idArea4'} == $idArea2TecnicoActual) || ($fila->{'idArea4'} == $idArea3TecnicoActual) || ($fila->{'idArea4'} == $idArea4TecnicoActual) || ($fila->{'idArea4'} == $idArea5TecnicoActual)) {
                                        echo "<option value='".$fila->{'idUsuario'}."'>".$fila->{'nombre'}." ".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}."</option>";
                                        $mostrado = 1;                                
                                    }
                                }
                            }
                            
                            //area5
                            if ($mostrado == 0) {
                                if ($fila->{'idArea5'} != 17) {
                                    if (($fila->{'idArea5'} == $idArea1TecnicoActual) || ($fila->{'idArea5'} == $idArea2TecnicoActual) || ($fila->{'idArea5'} == $idArea3TecnicoActual) || ($fila->{'idArea5'} == $idArea4TecnicoActual) || ($fila->{'idArea5'} == $idArea5TecnicoActual)) {
                                        echo "<option value='".$fila->{'idUsuario'}."'>".$fila->{'nombre'}." ".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}."</option>";
                                        $mostrado = 1;                                
                                    }
                                }
                            }
                            
                        }
                    }
                    ?>
                </select>                    
                <br><br>                
                <?php } ?>
                <?php 
                //echo $notificacion->{'id'}."<br>";
                //echo $turnos[0]->{'idUsuario'}."<br>";
                //echo $usuario->{'clave'}."<br>";
                ?>
                <input id="btnIniciaAtencion" type="button" class="btn btn-primary" value="Inicio Atención" onclick="iniciacronometro('<?php echo $notificacion->{'idUsuarioTecnico'}; ?>','<?php echo $notificacion->{'pausado'}; ?>','<?php echo $usuario->{'clave'}; ?>','<?php echo $clavesTecnicos; ?>');" />
                <input id="btnRegresar"   type="button" class="btn btn-primary" value="Notificar nueva falla" onclick="javascript: return regresar()" disabled />
            </div> 
        </div>
        <div class='panel-footer'>
        </div>
    </div>
    <?php } ?>
    
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
                <input id='btnFinAtencion' onclick="finalizaAtencion('<?php echo $idNotificacion; ?>');" type="button" class="btn btn-primary" value="Fin Atención" />
            </div> 
        </div>
        <div class='panel-footer'>
        </div>
    </div>
    
    
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
