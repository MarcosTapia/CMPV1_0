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
                    <form class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/proveedores_controller/actualizarProveedorFromFormulario" method="post">
                        <h4>Actualizaci&oacute;n de Proveedor</h4>
                        <input type="hidden" name="idProveedor" id="idProveedor" value="<?php echo $proveedor->{'idProveedor'}; ?>" />

                        <div class="form-group">
                          <label class="control-label col-sm-2" for="nombre_empresa">Nombre:</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="<?php echo $proveedor->{'nombre_empresa'}; ?>" placeholder="Nombre Empresa" required autofocus>
                          </div>					  
                        </div> 

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="direccion_empresa">Direcci&oacute;n:</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="direccion_empresa" name="direccion_empresa" value="<?php echo $proveedor->{'direccion_empresa'}; ?>" placeholder="Direcci&oacute;n" required>
                          </div>					  
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nombre_contacto">Contacto:</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="nombre_contacto" name="nombre_contacto"  value="<?php echo $proveedor->{'nombre_contacto'}; ?>" placeholder="Contacto" required>
                          </div>					  
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email_contacto">Email:</label>
                          <div class="col-sm-10">
                              <input type="email" class="form-control" id="email_contacto" name="email_contacto"  value="<?php echo $proveedor->{'email_contacto'}; ?>" placeholder="Email" required>
                          </div>					  
                        </div> 

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="numero_telefonico">Tel&eacute;fono:</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="numero_telefonico" name="numero_telefonico"  value="<?php echo $proveedor->{'numero_telefonico'}; ?>" placeholder="Tel&eacute;fono" required>
                          </div>					  
                        </div> 

                        <div class="form-group">
                            <center>
                            <?php $submitBtn = array('class' => 'btn btn-primary
                            ',  'value' => 'Procesar', 'name'=>'submit'); 
                            echo form_submit($submitBtn); ?>
                            
                            <a href="<?php echo base_url();?>index.php/proveedores_controller/mostrarProveedores">
                            <button type="button" class="btn btn-success">Regresar</button>
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