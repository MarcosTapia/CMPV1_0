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
            <div class="col-sm-6">
                <form onsubmit="javascript:return verificaCampos();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/usuarioturno_controller/nuevoUsuarioFromFormulario" method="post">
                    <br>
                    <h4>Alta de Usuario</h4>
                    <div class="form-group">
                      <label class="control-label col-sm-4" for="usuario">Usuario*:</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required autofocus>
                      </div>					  
                    </div>  

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="clave">Clave*:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="clave" name="clave" placeholder="Clave" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="nombre">Nombre*:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="apellido_paterno">Apellido Paterno:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido Paterno" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="apellido_materno">Apellido Materno:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Apellido Materno" required>
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="tipoUsuario">Tipo de  Usuario*:</label>
                        <div class="col-sm-8">
                        <div class="input-group">
                            <select class="form-control" name="tipoUsuario" id="tipoUsuario" required>
                                <option value="">Seleccionar uno...</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Lider">Lider</option>
                            </select>
                        </div>					  
                        </div>					  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="idArea">Area*:</label>
                        <div class="col-sm-8">
                        <div class="input-group">
                            <select class="form-control" name="idArea" id="idArea" required>
                                <option value="">Seleccionar uno...</option>
                                <?php foreach ($areas as $fila) {
                                    echo "<option value='".$fila->{'idArea'}."'>".$fila->{'descripcion'}."</option>";
                                }
                                ?>
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

                        <a href="<?php echo base_url();?>index.php/usuarioturno_controller/mostrarUsuariosTurnos">
                        <button type="button" class="btn btn btn-success">REGRESAR</button>
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