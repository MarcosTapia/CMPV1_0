<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script>
            function automatico(opcion) {
                if (opcion==1) {
                    document.getElementById('hora').disabled = false;
                } else {
                    document.getElementById('hora').disabled = true;
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
            <form class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/configuracion_controller/actualizaFromFormulario" method="post">
                <h4>Configuraci&oacute;n del Sistema</h4>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="nombre_Empresa">Nombre:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de la Instituci&oacute;n o Empresa">
                    </div>					  
                </div>                    
				
        	<div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa el email">
                    </div>
		</div>
				
		<div class="form-group">
                    <label class="control-label col-sm-2" for="asunto">Asunto a Enviar:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto a Enviar por Email ">
                    </div>					  
		</div>                    
				
                <div class="form-group">
                        <label for="mensaje">Mensaje a enviar:</label>
                        <textarea class="form-control" rows="5" id="mensaje" name="mensaje"></textarea>
                </div>
                
                <table>
                        <tr>
                            <td>
                                <div class="form-group">        
                                  <div class="col-sm-offset-2 col-sm-10">
                                        <?php $submitBtn = array('class' => 'btn btn-xs btn-success
                                        ',  'value' => 'Procesar', 'name'=>'submit'); 
                                        echo form_submit($submitBtn); ?>
                                  </div>
                                </div>							
                            </td>
                            <td>
                                <div class="form-group">        
                                  <div class="col-sm-offset-2 col-sm-10">
                                        <a href="<?php echo base_url();?>index.php/login_controller/inicio">
                                        <button type="button" class="btn btn-xs btn-danger">Regresar</button>
                                        </a>
                                  </div>
                                </div>
                            </td>
                        </tr>
                </table>
            </form>
	</div>		
            <div class="col-sm-5">		
            </div>		
        </div>
    </div>
</body>
</html>