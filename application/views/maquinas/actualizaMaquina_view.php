<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>      
    <div class="container"> <!--class="container-fluid" -->
            <div class="row-fluid">
                <div class="col-sm-9">
                    <form onsubmit="javascript:return verificaCampos();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/maquinaria_controller/actualizarMaquinaFromFormulario" method="post">
                        <br>
                        <h4>Actualizaci&oacute;n de M&aacute;quina</h4>
                        <input type="hidden" name="idMaquina" id="idMaquina" value="<?php echo $maquina->{'idMaquina'}; ?>" />
                        <div class="form-group">
                        <label class="control-label col-sm-2" for="numeromaq">N&uacute;mero*:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="numeromaq" name="numeromaq" value="<?php echo $maquina->{'numero_maquina'}; ?>" placeholder="N&uacute;mero de M&aacute;quina" required autofocus>
                        </div>					  
                        </div>  

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nombremaq">Nombre*:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nombremaq" name="nombremaq" value="<?php echo $maquina->{'nombre_maquina'}; ?>" placeholder="Nombre de la M&aacute;quina" required>
                            </div>					  
                        </div> 

                        <div class="form-group">
                        <label class="control-label col-sm-2" for="responsables">Responsable*:</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <select class="form-control" name="responsables" id="responsables" required>
                                    <option value="">Seleccionar uno...</option>
                                    <?php foreach($usuarios as $fila) {
                                        if ($maquina->{'responsable_maquina'} == $fila->{'idUsuario'}) {
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
                            <label class="control-label col-sm-2" for="areas">&Aacute;rea*:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select class="form-control" name="areas" id="areas" required>
                                        <option value="">Seleccionar uno...</option>
                                        <?php foreach($areas as $fila) {
                                            if ($maquina->{'idArea'} == $fila->{'idArea'}) {
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
                            <label class="control-label col-sm-2" for="status">Status:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select class="form-control" name="status" id="status" required>
                                        <option value="">Seleccionar uno...</option>
                                        <?php 
                                            if ($maquina->{'status'} == 1) {
                                                echo "<option value='1' selected>ACTIVA</option>";
                                            } else {
                                                echo "<option value='1'>ACTIVA</option>";
                                            }
                                            if ($maquina->{'status'} == 0) {
                                                echo "<option value='0' selected>INACTIVA</option>";
                                            } else {
                                                echo "<option value='0'>INACTIVA</option>";
                                            }                                        
                                        ?>
                                    </select>
                                </div>					  
                            </div>					  
                        </div>                                                 

                        <div class="form-group">
                          <label class="control-label col-sm-2" for="proveedores">Proveedor*:</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                                <select class="form-control" name="proveedores" id="proveedores" required>
                                    <option value="">Seleccionar uno...</option>
                                    <?php foreach($proveedores as $fila) {
                                        if ($maquina->{'idProveedor'} == $fila->{'idProveedor'}) {
                                            echo "<option value=".$fila->{'idProveedor'}." selected>".$fila->{'nombre_empresa'}."</option>";
                                        } else {
                                            echo "<option value=".$fila->{'idProveedor'}.">".$fila->{'nombre_empresa'}."</option>";
                                        }                                    
                                    } ?>
                                </select>
                            </div>					  
                          </div>					  
                        </div>
                        
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="categorias">Categoría*:</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                                <select class="form-control" name="categorias" id="categorias" required>
                                    <option value="">Seleccionar uno...</option>
                                    <?php foreach($categorias as $fila) {
                                        if ($maquina->{'idCategoria'} == $fila->{'idCategoria'}) {
                                            echo "<option value=".$fila->{'idCategoria'}." selected>".$fila->{'descripcion_categoria'}."</option>";
                                        } else {
                                            echo "<option value=".$fila->{'idCategoria'}.">".$fila->{'descripcion_categoria'}."</option>";
                                        }                                    
                                    } ?>
                                </select>
                            </div>					  
                          </div>					  
                        </div>
                        
                        
                        <div class="form-group">
                            <br>
                            <center>
                            <?php $submitBtn = array('class' => 'btn btn-primary
                            ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                            echo form_submit($submitBtn); ?>
                            
                            <a href="<?php echo base_url();?>index.php/maquinaria_controller/mostrarMaquinas">
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
</body>
</html>