<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
</head>
<body>      
    <div class="container"> <!--class="container-fluid" -->
        <div class="row-fluid">
            <div class="col-sm-1">		
            </div>		
            <div class="col-sm-6">
                <form onsubmit="javascript:return verificaCampos();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/layoutmaqstatus_controller/nuevoLugarFromFormulario" method="post">
                    <br>
                    <h4>Alta de Máquina - Ubicación</h4>
                    <br>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="idMaquina">Máquina*:</label>
                        <div class="col-sm-10">
                        <div class="input-group">
                            <select class="form-control" name="idMaquina" id="idMaquina" required>
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($maquinas as $fila) {
                                    $palabra1 = "Tableros Dimensionales";
                                    $palabra2 = "Tableros Auxiliares";
                                    if ((strpos($fila->{'nombre_maquina'}, $palabra1) !== false) || (strpos($fila->{'nombre_maquina'}, $palabra2) !== false)) {
                                        //echo "Word Found!";
                                    } else{
                                        echo "<option codigo1='".$fila->{'idMaquina'}."' value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}." ".$fila->{'numero_maquina'}."</option>";
                                    }                                      
                                } ?>
                            </select>
                        </div>					  
                        </div>					  
                    </div>   
                    
                    <div class="form-group">
                      <label class="control-label col-sm-2" for="control">Control:</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" id="control" name="control" placeholder="Ingresa el nombre del control en el layout" required autofocus>
                      </div>					  
                    </div> 
                    
                    <br><br>
                    <div class="form-group">
                        <center>
                        <?php $submitBtn = array('class' => 'btn btn-primary
                        ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                        echo form_submit($submitBtn); ?>

                        <a href="<?php echo base_url();?>index.php/layoutmaqstatus_controller/mostrarlugares">
                        <button type="button" class="btn btn-success">REGRESAR</button>
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