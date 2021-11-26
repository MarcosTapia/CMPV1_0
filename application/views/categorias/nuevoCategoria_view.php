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
                <form onsubmit="javascript:return verificaCampos();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/categorias_controller/nuevoCategoriaFromFormulario" method="post">
                    <br>
                    <h4>Alta de Categoría</h4>
                    <br>
                    <div class="form-group">
                      <label class="control-label col-sm-2" for="descripcion_categoria">Descripci&oacute;n:</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" id="descripcion_categoria" name="descripcion_categoria" placeholder="Descripci&oacute;n del &Aacute;rea" required autofocus>
                      </div>					  
                    </div> 
                    <br><br>
                    <div class="form-group">
                        <center>
                        <?php $submitBtn = array('class' => 'btn btn-primary
                        ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                        echo form_submit($submitBtn); ?>

                        <a href="<?php echo base_url();?>index.php/categorias_controller/mostrarCategorias">
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