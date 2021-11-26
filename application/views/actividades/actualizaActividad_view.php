<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
</head>
<body>      
    <div class="container"> <!--class="container-fluid" -->
            <form onsubmit="javascript:return verificaCampos();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/actividades_controller/actualizarActividadFromFormulario" method="post">
            <div class="row-fluid">
                <br><br><br>
                <h4>Actualizaci&oacute;n de Actividad</h4>  
                <input type="hidden" name="idActividad" id="idActividad" value="<?php echo $actividad->{'idActividad'}; ?>" />

                <div class="col-sm-2">
                    <label class="control-label" for="descripcion_actividad">Descripci&oacute;n*:</label>
                </div>                           
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="descripcion_actividad" name="descripcion_actividad" value="<?php echo $actividad->{'descripcion_actividad'}; ?>" placeholder="Descripci&oacute;n" required autofocus>
                </div>                           
            </div>	
                
            <div class="row-fluid">
                <div class="col-sm-2">
                    <br>
                    <label class="control-label col-sm-2" for="nombreMaquina">M&aacute;quina*:</label>
                </div>                           
                <div class="col-sm-10">
                    <br>
                    <!--  
                    <select class="form-control col-sm-10 col-md-10 col-lg-10" name="nombreMaquina" id="nombreMaquina" disabled>
                    -->
                    <select class="form-control col-sm-10 col-md-10 col-lg-10" name="nombreMaquina" id="nombreMaquina">
                        <option value="">Máquina...</option>
                        <?php 
                            $elementos = array("-2");
                            foreach($maquinas as $fila) {
                                $v = array_search($fila->{'nombre_maquina'},$elementos);
                                if ($v == false){                        
                                    array_push($elementos,$fila->{'nombre_maquina'});
                                    if ($actividad->{'nombre_maquina'} == $fila->{'nombre_maquina'}) {
                                        echo "<option value='".$fila->{'nombre_maquina'}."' selected>".$fila->{'nombre_maquina'}."</option>";
                                    } else {
                                        echo "<option value='".$fila->{'nombre_maquina'}."'>".$fila->{'nombre_maquina'}."</option>";
                                    }
                                }
                            } 
                        ?>
                    </select>                
                </div>                           
            </div>	

            <div class="row-fluid">
                <div class="col-sm-2">
                    <br>
                    <label class="control-label col-sm-2 col-md-2 col-lg-2" for="frecuencia">Frecuencia*:</label>
                </div>                           
                <div class="col-sm-10">
                    <br>
                    <select class="form-control col-sm-10 col-md-10 col-lg-10" name="frecuencia" id="frecuencia">
                        <?php if ($actividad->{'frecuencia'} == "") { ?>
                        <option value="" selected>Seleccionar Frecuencia</option>
                        <?php } else { ?>
                            <option value="">Seleccionar Frecuencia</option>
                        <?php } ?>                      
                        
                        <?php if ($actividad->{'frecuencia'} == "Semanal") { ?>
                            <option value="Semanal" selected>Semanal</option>
                        <?php } else { ?>
                        <option value="Semanal">Semanal</option>
                        <?php } ?>  

                        <?php if ($actividad->{'frecuencia'} == "Mensual") { ?>
                        <option value="Mensual" selected>Mensual</option>
                        <?php } else { ?>
                            <option value="Mensual">Mensual</option>
                        <?php } ?>  

                        <?php if ($actividad->{'frecuencia'} == "Trimestral") { ?>
                            <option value="Trimestral" selected>Trimestral</option>
                        <?php } else { ?>
                            <option value="Trimestral">Trimestral</option>
                        <?php } ?>  

                        <?php if ($actividad->{'frecuencia'} == "Semestral") { ?>
                            <option value="Semestral" selected>Semestral</option>
                        <?php } else { ?>
                            <option value="Semestral">Semestral</option>
                        <?php } ?>  
                        
                        <?php if ($actividad->{'frecuencia'} == "Anual") { ?>
                            <option value="Anual" selected>Anual</option>
                        <?php } else { ?>
                            <option value="Anual">Anual</option>
                        <?php } ?>  
                            
                        <?php if ($actividad->{'frecuencia'} == "Ciclos") { ?>
                            <option value="Ciclos" selected>Ciclos</option>
                        <?php } else { ?>
                            <option value="Ciclos">Ciclos</option>
                        <?php } ?>                          
                        
                    </select>
                    <br><br><br><br>
                </div>                           
            </div>	
               
            <div class="row-fluid">
                <div class="col-sm-1">
                    <br>
                </div>                           
                <div class="col-sm-11">
                    <br>
                    <center>
                    <?php $submitBtn = array('class' => 'btn btn-primary
                    ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                    echo form_submit($submitBtn); ?>
                    
                    &nbsp;&nbsp;
                    
                    <a href="<?php echo base_url();?>index.php/actividades_controller/mostrarActividades">
                    <button type="button" class="btn btn-success">REGRESAR</button>
                    </a>
                    </center>
                </div>                           
            </div>	

            </form>
	</div>
</body>
</html>