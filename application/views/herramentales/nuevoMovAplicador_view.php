<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script>
        function validaNumDigitosCiclos() {
            var numero = document.getElementById("ciclos").value;
            if (numero.length >= 7) {
                alert("El número sólo debe tener 7 digitos como máximo.");
                return false;
            } else {
                return true;
            }
        }
    </script>
</head>
<body>      
    <div class="container"> <!--class="container-fluid" -->
        <div class="row-fluid">
            <div class="col-sm-1">		
            </div>		
            <div class="col-sm-6">
                <form onsubmit="javascript:return validaNumDigitosCiclos();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/herramentales_controller/nuevoMovAplicadorFromFormulario" method="post">
                    <br>
                    <h4>Inicio de Movimientos de Aplicador</h4>
                    <br>
                    <div class="form-group">
                      <label class="control-label col-sm-2" for="aplicador">Aplicador*:</label>
                      <div class="col-sm-10">
                            <select class="form-control" name="aplicador" id="aplicador" required>
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($aplicadores as $fila) {
                                    echo "<option value=".$fila->{'idAplicador'}.">".$fila->{'aplicador'}."</option>";
                                } ?>
                            </select>
                      </div>					  
                    </div>                     
                    
                    <div class="form-group">
                      <label class="control-label col-sm-2" for="ciclos">Ciclos*:</label>
                      <div class="col-sm-10">
                          <input type="number" class="form-control" id="ciclos" name="ciclos" placeholder="Ingresa el número de Ciclos Inicial" required >
                      </div>					  
                    </div> 

                    <br><br>
                    <div class="form-group">
                        <center>
                        <?php $submitBtn = array('class' => 'btn btn-primary
                        ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                        echo form_submit($submitBtn); ?>

                        <a href="<?php echo base_url();?>index.php/herramentales_controller/funcioninicialAdministradorAplicadores">
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