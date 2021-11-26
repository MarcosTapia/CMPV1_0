<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script>
        function validaCantidadSalida(){
            if (parseInt(document.getElementById('stock').value) < parseInt(document.getElementById('cantidad').value)) {
                alert("Cantidad solicitada supera al stock, ajusta la cantidad");
                return false;
            }
            var cantidad = parseInt(document.getElementById('cantidad').value);
            if ( cantidad <= 0) {
                alert("Cantidad no válida, debe se un numero mntero mayor a 0");
                return false;
            }
        }
    </script>
</head>
<body>     
    <div class="container">
        <div class="row-fluid">
            <div class="col-sm-1">
            </div>		
            <div class="col-sm-8">
                <form class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/inventariotoolcrib_controller/actualizarInventarioFromFormularioQR" method="post">
                    <br>
                    <h4>Salida de Inventario</h4>
                    <br>
                    <input type="hidden" name="idInventario" id="idInventario" value="<?php echo $inventario->{'idInventario'}; ?>" />
                    <div class="form-group">
                      <label class="control-label col-sm-3" for="numParte">Número Parte*:</label>
                      <div class="col-sm-9">
                          <input readonly type="text" value="<?php echo $inventario->{'numero_parte'}; ?>" class="form-control" id="numParte" name="numParte" placeholder=Número de Parte" required >
                      </div>					  
                    </div>  

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="descripcion">Descripción*:</label>
                        <div class="col-sm-9" disabled>
                            <input readonly type="text" value="<?php echo $inventario->{'descripcion'}; ?>" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" required>
                        </div>					  
                    </div> 
                    
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="ubicacion">Ubicación*:</label>
                        <div class="col-sm-9">
                            <input readonly type="text" value="<?php echo $inventario->{'ubicacion'}; ?>"  class="form-control" id="ubicacion" name="ubicacion" placeholder="Ubicación" required>
                        </div>					  
                    </div> 
                    
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="stock">Stock*:</label>
                        <div class="col-sm-9">
                            <input readonly type="number" value="<?php echo $inventario->{'stock'}; ?>"  class="form-control" id="stock" name="stock" placeholder="Stock" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="cantidad">Cantidad Solicitada*:</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad Solicitada" required autofocus>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="maquina">Máquina Destino*:</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control" name="maquina" id="maquina" required>
                                    <option value="">Seleccionar uno...</option>
                                    <?php foreach($maquinasSimple as $fila) {
                                        if ($inventario->{'idMaquina'} == $fila->{'idMaquina'}) {
                                           echo "<option value=".$fila->{'idMaquina'}." selected>".$fila->{'nombre_maquina'}." ".$fila->{'numero_maquina'}."</option>";
                                        } else {
                                           echo "<option value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}." ".$fila->{'numero_maquina'}."</option>";
                                        }
                                    } ?>
                                </select>
                            </div>					  
                        </div>					  
                    </div> 

                    <div class="form-group">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type='submit' name='submit' class='btn btn-primary' value = 'REGISTRAR' onclick="javascript: return validaCantidadSalida()" /> 
                    </div>
                    
                </form>
            </div>	
            <div class="col-sm-2">		
            </div>		
        </div>
    </div>
</body>
</html>