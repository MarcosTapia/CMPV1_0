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
                    <form onsubmit="javascript:return verificaCampos();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/usuarioturno_controller/actualizarUsuarioFromFormulario" method="post">
                        <h4>Actualizaci&oacute;n de Usuarios</h4>
                        <input type="hidden" name="idUsuarioOperador" id="idUsuarioOperador" value="<?php echo $usuario->{'idUsuarioOperador'}; ?>" />
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="usuario">Usuario*:</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $usuario->{'usuario'}; ?>" placeholder="Usuario" required autofocus>
                          </div>					  
                        </div>  

                        <div class="form-group">
                          <input type="hidden" name="claveAnt" value="<?php echo $usuario->{'clave'}?>" />  
                          <label class="control-label col-sm-4" for="clave">Nueva Clave:</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="clave" name="clave" placeholder="Nueva Clave">
                          </div>					  
                        </div> 

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="tipoUsuario">Tipo de  Usuario*:</label>
                            <div class="col-sm-8">
                            <div class="input-group">
                                <select class="form-control" name="tipoUsuario" id="tipoUsuario" required>
                                    <option value="">Seleccionar uno...</option>
                                    <?php if ($usuario->{'permisos'} == "Supervisor") { ?>
                                        <option value="Supervisor" selected>Supervisor</option>
                                    <?php } else { ?>
                                        <option value="Supervisor">Supervisor</option>
                                    <?php } ?>
                                    <?php if ($usuario->{'permisos'} == "Lider") { ?>
                                        <option value="Lider" selected>Lider</option>
                                    <?php } else { ?>
                                        <option value="Lider">Lider</option>
                                    <?php } ?>
                                </select>
                            </div>					  
                            </div>					  
                        </div> 

                        <div class="form-group">
                          <label class="control-label col-sm-4" for="nombre">Nombre(s):</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario->{'nombre'}; ?>" placeholder="Nombre" required>
                          </div>					  
                        </div> 

                        <div class="form-group">
                          <label class="control-label col-sm-4" for="apellido_paterno">Apellido Paterno:</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo $usuario->{'apellido_paterno'}; ?>" placeholder="Apellido Paterno" required>
                          </div>					  
                        </div> 

                        <div class="form-group">
                          <label class="control-label col-sm-4" for="apellido_materno">Apellido Materno:</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo $usuario->{'apellido_materno'}; ?>" placeholder="Apellido Materno" required>
                          </div>					  
                        </div> 
                        
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="idArea">Area*:</label>
                            <div class="col-sm-8">
                            <div class="input-group">
                                <select class="form-control" name="idArea" id="idArea" required>
                                    <option value="">Seleccionar uno...</option>
                                    <?php foreach ($areas as $fila) {
                                        if ($fila->{'idArea'} == $usuario->{'idArea'}) {
                                            echo "<option value='".$fila->{'idArea'}."' selected>".$fila->{'descripcion'}."</option>";
                                        } else {
                                            echo "<option value='".$fila->{'idArea'}."'>".$fila->{'descripcion'}."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>					  
                            </div>					  
                        </div> 
                        

                        <div class="form-group">
                            <center>
                            <?php $submitBtn = array('class' => 'btn btn-primary',  
                                'value' => 'GUARDAR', 'name'=>'submit'); 
                            echo form_submit($submitBtn); ?>
                            
                            <a href="<?php echo base_url();?>index.php/usuarioturno_controller/mostrarUsuariosTurnos">
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