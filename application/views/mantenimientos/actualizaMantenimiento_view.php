<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script>
        function myFunction() {
            document.getElementById("observaciones").value = "<?php echo $mantenimiento->{'observaciones_maquina'}; ?>";
        }
    </script>
</head>
<body onload="myFunction()">      
    <div class="container"> <!--class="container-fluid" -->
        <div class="row-fluid">
            <div class="col-sm-9">
                <form class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/mantenimiento_controller/actualizarMantenimientoFromFormulario" method="post">
                    <br>
                    <h4>Actualizaci&oacute;n de Mantenimiento</h4>
                    <input type="hidden" name="idFechaMantenimiento" id="idFechaMantenimiento" value="<?php echo $mantenimiento->{'idFechaMantenimiento'}; ?>" />
                    <br>
                    <div class="form-group">                        
                        <label class="control-label col-sm-2" for="fechaMantenimiento">Semana*:</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <select class="form-control" name="fechaMantenimiento" id="fechaMantenimiento" disabled>
                                    <option value="">Semana...</option>
                                    <?php 
                                    for($i=1; $i<54; $i++) {
                                        $semana = "Semana ".$i;
                                        if ($mantenimiento->{'fechaMantenimiento'} == $semana) {
                                            echo "<option value='".$semana."' selected>".$semana."</option>";
                                        } else {
                                            echo "<option value='".$semana."'>".$semana."</option>";
                                        }
                                    } ?>
                                </select>
                            </div>
                         </div>
                    </div>                      
                    <div class="form-group">
                      <label class="control-label col-sm-2" for="responsables">Responsable*:</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                            <select class="form-control" name="responsableMaquina" id="responsableMaquina" disabled>
                                <option value="">Seleccionar uno...</option>
                                <?php 
                                    $elementos = array("-2");
                                    foreach($usuarios as $fila) {
//                                        $v = array_search($fila->{'idUsuario'},$elementos);
//                                        if ($v == false){
//                                            array_push($elementos,$fila->{'idUsuario'});
                                            if ($fila->{'permisos'} != "Administrador") {
                                                if ($mantenimiento->{'idResponsable'} == $fila->{'idUsuario'}) {                                            
                                                    echo "<option value=".$fila->{'idUsuario'}." selected>".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'apellido_materno'}.$fila->{'nombre'}."</option>";
                                                } else {
                                                    echo "<option value=".$fila->{'idUsuario'}.">".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'nombre'}."</option>";
                                                }
                                            }
//                                        }
                                    } ?>
                            </select>
                        </div>					  
                      </div>					  
                    </div>                       

                    <div class="form-group">
                      <label class="control-label col-sm-2" for="maquinas">M&aacutequina*:</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                            <select class="form-control" name="maquinas" id="maquinas" disabled>
                                <option value="">Seleccionar uno...</option>
                                <?php
                                    $elementos2 = array("-2");
                                    foreach($maquinas as $fila) {
//                                        $v = array_search($fila->{'nombre_maquina'},$elementos2);
//                                        if ($v == false){
//                                            array_push($elementos2,$fila->{'nombre_maquina'});
//                                            if ($mantenimiento->{'idMaquina'} == $fila->{'idMaquina'}) {
//                                                echo "<option codigo1=".$fila->{'nombre_maquina'}." value=".$fila->{'idMaquina'}." selected>".$fila->{'nombre_maquina'}."</option>";
//                                            } else {
//                                                echo "<option codigo1=".$fila->{'nombre_maquina'}." value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}."</option>";
//                                            }
//                                        }

                                            if ($mantenimiento->{'idMaquina'} == $fila->{'idMaquina'}) {
                                                echo "<option codigo1=".$fila->{'nombre_maquina'}." value=".$fila->{'idMaquina'}." selected>".$fila->{'nombre_maquina'}."</option>";
                                            } else {
                                                echo "<option codigo1=".$fila->{'nombre_maquina'}." value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}."</option>";
                                            }
                                        
                                } ?>
                            </select>
                        </div>					  
                      </div>					  
                    </div>   
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="numero_maquina">N&uacute;mero de M&aacutequina*:</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                            <select class="form-control" name="numero_maquina" id="numero_maquina" disabled>
                                <option value="">Seleccionar uno...</option>
                                <?php
                                    $elementos = array("-2");
                                    foreach($maquinas as $fila) {
                                    if ($mantenimiento->{'idMaquina'} == $fila->{'idMaquina'}) {
                                        echo "<option codigo1=".$fila->{'nombre_maquina'}." value=".$fila->{'numero_maquina'}." selected>".$fila->{'numero_maquina'}."</option>";
                                    } else {
                                        echo "<option codigo1=".$fila->{'nombre_maquina'}." value=".$fila->{'numero_maquina'}.">".$fila->{'numero_maquina'}."</option>";
                                    }
                                } ?>
                            </select>
                        </div>					  
                      </div>					  
                    </div>                       

                    <div class="form-group">
                      <label class="control-label col-sm-2" for="condicion">Condici&oacute;n*:</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                            <select class="form-control" name="condicionM" id="condicionM" disabled>
                                <?php 
                                    if ($mantenimiento->{'condicion_maquina'} == "") {
                                        echo "<option value='' selected>Seleccionar uno...</option>";
                                    } else {
                                        echo "<option value=''>Seleccionar uno...</option>";
                                    }
                                    
                                    if ($mantenimiento->{'condicion_maquina'} == "ATENDIDA") {
                                        echo "<option value='ATENDIDA' selected>ATENDIDA</option>";
                                    } else {
                                        echo "<option value='ATENDIDA'>ATENDIDA</option>";
                                    }
                                
                                    if ($mantenimiento->{'condicion_maquina'} == "NO ATENDIDA") {
                                        echo "<option value='NO ATENDIDA' selected>NO ATENDIDA</option>";
                                    } else {
                                        echo "<option value='NO ATENDIDA'>NO ATENDIDA</option>";
                                    }
                                ?>
                            </select>
                        </div>					  
                      </div>					  
                    </div>                      

                    <div class="form-group">
                      <label class="control-label col-sm-2" for="actividades">Actividad*:</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                            <select class="form-control" name="actividades" id="actividades" disabled>
                                <option value="">Actividad ...</option>
                                <?php 
                                    foreach($actividades as $fila) {
                                        if ($mantenimiento->{'idActividad'} == $fila->{'idActividad'}) {
                                            echo "<option codigo=".$fila->{'nombre_maquina'}." value=".$fila->{'idActividad'}." selected>".$fila->{'descripcion_actividad'}."</option>";
                                        } else {
                                            echo "<option codigo=".$fila->{'nombre_maquina'}." value=".$fila->{'idActividad'}.">".$fila->{'descripcion_actividad'}."</option>";
                                        }
                                } ?>
                            </select>
                        </div>					  
                      </div>					  
                    </div>   
                    
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="observaciones">Observaciones*:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" id="observaciones" name="observaciones"><?php echo $mantenimiento->{'observaciones_maquina'}; ?></textarea>
                        </div>					  
                    </div> 
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="observaciones">Vista previa de Evidencia:</label>
                        <div class="col-sm-10">
                            <img src="<?php echo $mantenimiento->{'urlImagen'}; ?>" width="200">
                        </div>					  
                    </div>    
                    
                    <input type="hidden" name="ruta" id="ruta" value="<?php echo $mantenimiento->{'urlImagen'}; ?>" />
                    
                    <br><br>
                    <div class="form-group">
                        <center>
                        <?php $submitBtn = array('class' => 'btn btn-primary
                                    ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                                    echo form_submit($submitBtn); ?>        
                        <!--   
                        <a href="<?php echo base_url();?>index.php/mantenimiento_controller/mostrarMantenimientos">
                                    <button type="button" class="btn btn-success">REGRESAR</button>
                                    </a>                           
                        -->
                        <a href="<?php echo base_url();?>index.php/mantenimiento_controller/consultaMantenimientos">
                                    <button type="button" class="btn btn-success">REGRESAR</button>
                                    </a>                           
                        </center>
                    </div> 

                </form>
              </div>	
              <div class="col-sm-3">		
              </div>		
        </div>
    </div>
    
    
    <script type="text/javascript"> //nombreMaquina   actividades
        var id1 = document.getElementById("maquinas");
        var id2 = document.getElementById("actividades");
        var id3 = document.getElementById("numero_maquina");
        
        // Añade un evento change al elemento id1, asociado a la función cambiar()
        if (id1.addEventListener) {     // Para la mayoría de los navegadores, excepto IE 8 y anteriores
            id1.addEventListener("change", cambiar);
        } else if (id1.attachEvent) {   // Para IE 8 y anteriores
            id1.attachEvent("change", cambiar); // attachEvent() es el método equivalente a addEventListener()
        }

        function limpiarSelect() {
            var select = document.getElementById("actividades");
            var length = select.options.length;
            for (i = 0; i <= length-1; i++) {    
                id2.options[i].style.display = "none";
            }  
            
            var select2 = document.getElementById("numero_maquina");
            var length = select2.options.length;
            for (i = 0; i <= length-1; i++) {    
                id3.options[i].style.display = "none";
            }   
            
            //document.getElementById('fechaMantenimiento').value = "";
        }
        
        function selectFirstValueSelect() {  
            var select = document.getElementById("actividades");
            var length = select.options.length;
            for (i = 0; i <= length-1; i++) {   
                var style = window.getComputedStyle(select.options[i]);
                if (style.display !== 'none') {
                    select.options.selectedIndex = i;
                    //document.getElementById('fechaMantenimiento').value = "" + select.options[select.selectedIndex].getAttribute("frec");
                    break;
                }
            }  
            
            var select2 = document.getElementById("numero_maquina");
            var length = select2.options.length;
            for (i = 0; i <= length-1; i++) {   
                var style2 = window.getComputedStyle(select2.options[i]);
                if (style2.display !== 'none') {
                    select2.options.selectedIndex = i;
                    break;
                }
            }             
        }        
        
        function cambiar() {
            limpiarSelect();
            //para actividades
            for (var i = 0; i < id2.options.length; i++) {
                if(id2.options[i].getAttribute("codigo") == id1.options[id1.selectedIndex].getAttribute("codigo1")){
                    id2.options[i].style.display = "block";
                }else{
                    id2.options[i].style.display = "none";
                }
            }  
            
            //para id3
            for (var i = 0; i < id3.options.length; i++) {
                if(id3.options[i].getAttribute("codigo1") == id1.options[id1.selectedIndex].getAttribute("codigo1")){
                    id3.options[i].style.display = "block";
                }else{
                    id3.options[i].style.display = "none";
                }
            }
            
            id2.value = "";
            id3.value = "";
            selectFirstValueSelect();
        }
        
        cambiar();
    </script>
    
</body>
</html>