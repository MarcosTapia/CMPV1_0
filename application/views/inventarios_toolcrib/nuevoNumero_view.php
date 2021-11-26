<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
</head>
<body>     
    <div class="container">
        <div class="row-fluid">
            <div class="col-sm-1">		
            </div>		
            <div class="col-sm-8">
                <form class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/inventariotoolcrib_controller/nuevoNumeroFromFormulario" method="post">
                    <br>
                    <h4>Alta de Número</h4>
                    <br>
                    <div class="form-group">
                      <label class="control-label col-sm-3" for="numSAP">Número SAP*:</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="numSAP" name="numSAP" placeholder="SAP" required autofocus>
                      </div>					  
                    </div>  
                    
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="maquina">Máquina*:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="maquina" name="maquina" placeholder="Máquina" required autofocus>
                        </div>					  
                    </div> 
                    
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="descripcion">Descripción*:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                      <label class="control-label col-sm-3" for="numParte">Número Parte*:</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="numParte" name="numParte" placeholder=Número de Parte" required autofocus>
                      </div>					  
                    </div>  

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="ubicacion">Ubicación*:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ubicación" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="stock">Stock*:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="stock" name="stock" placeholder="Stock" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="minimo">Cantidad Mínima:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="minimo" name="minimo" placeholder="Cantidad Mínima" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="maximo">Cantidad Máxima:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="maximo" name="maximo" placeholder="Cantidad Máxima" required>
                        </div>					  
                    </div> 
                    
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="proveedor">Proveedor*:</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control" name="proveedor" id="proveedor" required>
                                    <option value="">Seleccionar uno...</option>
                                    <?php foreach($proveedores as $fila) {  
                                        echo "<option value=".$fila->{'idProveedor'}.">".$fila->{'nombre_empresa'}."</option>";
                                    } ?>
                                </select>
                            </div>					  
                        </div>					  
                    </div> 
                    
                    <br>
                    <div class="form-group">
                        <center>
                        <?php $submitBtn = array('class' => 'btn btn-primary
                        ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                        echo form_submit($submitBtn); ?>

                        <a href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/mostrarInventario">
                        <button type="button" class="btn btn btn-success">REGRESAR</button>
                        </a>
                        </center>
                    </div> 
                </form>
            </div>	
            <div class="col-sm-2">		
            </div>		
        </div>
    </div>
</body>
</html>