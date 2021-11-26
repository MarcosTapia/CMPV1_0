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
                    <form onsubmit="javascript:return verificaCampos();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/usuarios_controller/actualizarUsuarioFromFormulario" method="post">
                        <h4>Actualizaci&oacute;n de Usuarios</h4>
                        <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $usuario->{'idUsuario'}; ?>" />
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="usuario">Usuario*:</label>
                          <div class="col-sm-10">
                                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $usuario->{'usuario'}; ?>" placeholder="Usuario" required autofocus>
                          </div>					  
                        </div>  

                        <div class="form-group">
                          <input type="hidden" name="claveAnt" value="<?php echo $usuario->{'clave'}?>" />  
                          <label class="control-label col-sm-2" for="clave">Nueva Clave:</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="clave" name="clave" placeholder="Nueva Clave">
                          </div>					  
                        </div> 

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="tipoUsuario">Tipo de  Usuario*:</label>
                            <div class="col-sm-10">
                            <div class="input-group">
                                <select class="form-control" name="tipoUsuario" id="tipoUsuario" required>
                                    <option value="">Seleccionar uno...</option>
                                    <?php if ($usuario->{'permisos'} == "Administrador") { ?>
                                    <option value="Administrador" selected>Administrador</option>
                                    <?php } else { ?>
                                        <option value="Administrador">Administrador</option>
                                    <?php } ?>
                                    <?php if ($usuario->{'permisos'} == "Técnico") { ?>
                                    <option value="Técnico" selected>Técnico</option>
                                    <?php } else { ?>
                                        <option value="Técnico">Técnico</option>
                                    <?php } ?>
                                        
                                    <?php if ($usuario->{'permisos'} == "Otros") { ?>
                                        <option value="Otros" selected>Otros</option>
                                    <?php } else { ?>
                                        <option value="Otros">Otros</option>
                                    <?php } ?>
                                        
                                        
                                </select>
                            </div>					  
                            </div>					  
                        </div> 

                        <div class="form-group">
                          <label class="control-label col-sm-2" for="nombre">Nombre(s):</label>
                          <div class="col-sm-10">
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario->{'nombre'}; ?>" placeholder="Nombre" required>
                          </div>					  
                        </div> 

                        <div class="form-group">
                          <label class="control-label col-sm-2" for="apellido_paterno">Apellido Paterno:</label>
                          <div class="col-sm-10">
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo $usuario->{'apellido_paterno'}; ?>" placeholder="Apellido Paterno" required>
                          </div>					  
                        </div> 

                        <div class="form-group">
                          <label class="control-label col-sm-2" for="apellido_materno">Apellido Materno:</label>
                          <div class="col-sm-10">
                                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo $usuario->{'apellido_materno'}; ?>" placeholder="Apellido Materno" required>
                          </div>					  
                        </div> 

                        <div class="form-group">
                            <center>
                            <?php $submitBtn = array('class' => 'btn btn-primary',  
                                'value' => 'GUARDAR', 'name'=>'submit'); 
                            echo form_submit($submitBtn); ?>
                            
                            <a href="<?php echo base_url();?>index.php/usuarios_controller/mostrarUsuarios">
                            <button type="button" class="btn btn-success">REGRESAR</button>
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