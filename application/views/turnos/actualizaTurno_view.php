<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script>
        function muestraHoras() {
            var horaEntrada = document.getElementById('hora_entrada').value;
            var horaSalida = document.getElementById('hora_salida').value;
            //alert(horaEntrada + " " + horaSalida);
            return true;
        }
    </script>
    
</head>
<body>     
    <div class="container">
        <div class="row-fluid">
            <div class="col-sm-1">		
            </div>		
            <div class="col-sm-6">
                <form onsubmit="javascript:return muestraHoras();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/turnos_controller/actualizarTurnoFromFormulario" method="post">
                    <br>
                    <input type="hidden" name="idTurno" value="<?php echo $turno->{'idTurno'}; ?>" />
                    <h4>Edición de turno</h4>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="usuario">Usuario*:</label>
                        <div class="col-sm-9">
                        <div class="input-group">
                            <select class="form-control" name="idUsuario" id="idUsuario" required>
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($usuarios as $fila) {
                                    if ($fila->{'idUsuario'} == $turno->{'idUsuario'}) {
                                        echo "<option value=".$fila->{'idUsuario'}." selected>".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'nombre'}."</option>";
                                    } else {
                                        echo "<option value=".$fila->{'idUsuario'}.">".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'nombre'}."</option>";
                                    }
                                } ?>
                            </select>
                        </div>					  
                        </div>					  
                    </div> 

                    <div class="form-group">
                      <label class="control-label col-sm-3" for="hora_entrada">Hora Entrada*:</label>
                      <div class="col-sm-9">
                          <input type="time" id="hora_entrada" name="hora_entrada" min="00:00" max="23:59" value="<?php echo $turno->{'hora_entrada'}; ?>" required>
                      </div>					  
                    </div>  

                    <div class="form-group">
                      <label class="control-label col-sm-3" for="hora_salida">Hora Salida*:</label>
                      <div class="col-sm-9">
                          <input type="time" id="hora_salida" name="hora_salida" min="00:00" max="23:59" value="<?php echo $turno->{'hora_salida'}; ?>" required>
                      </div>					  
                    </div>  
                    	
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="idArea1">Area 1*:</label>
                        <div class="col-sm-9">
                        <div class="input-group">
                            <select class="form-control" name="idArea1" id="idArea1" required>
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($areas as $fila) {
                                    if ($turno->{'idArea1'} == $fila->{'idArea'}) { 
                                        echo "<option value=".$fila->{'idArea'}." selected>".$fila->{'descripcion'}."</option>";
                                    } else {
                                        echo "<option value=".$fila->{'idArea'}.">".$fila->{'descripcion'}."</option>";
                                    } 
                                } ?>
                            </select>
                        </div>					  
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="idArea2">Area 2*:</label>
                        <div class="col-sm-9">
                        <div class="input-group">
                            <select class="form-control" name="idArea2" id="idArea2" >
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($areas as $fila) {
                                    if ($turno->{'idArea2'} == $fila->{'idArea'}) {
                                        echo "<option value=".$fila->{'idArea'}." selected>".$fila->{'descripcion'}."</option>";
                                    } else {
                                        echo "<option value=".$fila->{'idArea'}.">".$fila->{'descripcion'}."</option>";
                                    } 
                                } ?>
                            </select>
                        </div>					  
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="idArea3">Area 3*:</label>
                        <div class="col-sm-9">
                        <div class="input-group">
                            <select class="form-control" name="idArea3" id="idArea3" >
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($areas as $fila) {
                                    if ($turno->{'idArea3'} == $fila->{'idArea'}) {
                                        echo "<option value=".$fila->{'idArea'}." selected>".$fila->{'descripcion'}."</option>";
                                    } else {
                                        echo "<option value=".$fila->{'idArea'}.">".$fila->{'descripcion'}."</option>";
                                    } 
                                } ?>
                            </select>
                        </div>					  
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="idArea4">Area 4*:</label>
                        <div class="col-sm-9">
                        <div class="input-group">
                            <select class="form-control" name="idArea4" id="idArea4" >
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($areas as $fila) {
                                    if ($turno->{'idArea4'} == $fila->{'idArea'}) {
                                        echo "<option value=".$fila->{'idArea'}." selected>".$fila->{'descripcion'}."</option>";
                                    } else {
                                        echo "<option value=".$fila->{'idArea'}.">".$fila->{'descripcion'}."</option>";
                                    } 
                                } ?>
                            </select>
                        </div>					  
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="idArea5">Area 5*:</label>
                        <div class="col-sm-9">
                        <div class="input-group">
                            <select class="form-control" name="idArea5" id="idArea5" >
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($areas as $fila) {
                                    if ($turno->{'idArea5'} == $fila->{'idArea'}) { 
                                        echo "<option value=".$fila->{'idArea'}." selected>".$fila->{'descripcion'}."</option>";
                                    } else {
                                        echo "<option value=".$fila->{'idArea'}.">".$fila->{'descripcion'}."</option>";
                                    } 
                                } ?>
                            </select>
                        </div>					  
                        </div>					  
                    </div> 
                    
                    <br>
                    <div class="form-group">
                        <center>
                            <input type="submit" name="submit" class="btn btn-primary" value="GUARDAR" />

                        <a href="<?php echo base_url();?>index.php/turnos_controller/mostrarTurnos">
                        <button type="button" class="btn btn btn-success">REGRESAR</button>
                        </a>
                        </center>
                    </div> 
                </form>
            </div>	
            <div class="col-sm-5">		
            </div>		
        </div>
    </div>
</body>
</html>