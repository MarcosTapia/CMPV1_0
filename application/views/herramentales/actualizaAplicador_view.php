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
                    <form class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/herramentales_controller/actualizarAplicadorFromFormulario" method="post">
                        <br>
                        <h4>Actualizaci&oacute;n de Aplicador</h4>
                        <input type="hidden" name="idAplicador" id="idAplicador" value="<?php echo $aplicador->{'idAplicador'}; ?>" />
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="maquina">Máquina(s)*:</label>
                          <div class="col-sm-10">
                              <input type="text" value="<?php echo $aplicador->{'noMaquina'}; ?>" class="form-control" id="maquina" name="maquina" placeholder="Máquina(s)" required autofocus>
                          </div>					  
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="aplicador">Aplicador*:</label>
                          <div class="col-sm-10">
                              <input type="text" value="<?php echo $aplicador->{'aplicador'}; ?>" class="form-control" id="aplicador" name="aplicador" placeholder="Aplicador" required >
                          </div>					  
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="fabricante">Fabricante*:</label>
                          <div class="col-sm-10">
                              <input type="text" value="<?php echo $aplicador->{'fabricante'}; ?>" class="form-control" id="fabricante" name="fabricante" placeholder="Fabricante" required >
                          </div>					  
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="noParteAplicador">NoParte Aplicador*:</label>
                          <div class="col-sm-10">
                              <input type="text" value="<?php echo $aplicador->{'no_parte_aplicador'}; ?>" class="form-control" id="noParteAplicador" name="noParteAplicador" placeholder="NoParte Aplicador" required >
                          </div>					  
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="noParteTerminal">NoParte Terminal*:</label>
                          <div class="col-sm-10">
                              <input type="text" value="<?php echo $aplicador->{'no_parte_terminal'}; ?>" class="form-control" id="noParteTerminal" name="noParteTerminal" placeholder="NoParte Terminal" required >
                          </div>					  
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="noParteTerminalInterno">NoParte Terminal Interno*:</label>
                          <div class="col-sm-10">
                              <input type="text" value="<?php echo $aplicador->{'no_parte_terminal_interno'}; ?>" class="form-control" id="noParteTerminalInterno" name="noParteTerminalInterno" placeholder="NoParte Terminal Interno" required >
                          </div>					  
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="cliente">Cliente*:</label>
                          <div class="col-sm-10">
                              <input type="text" value="<?php echo $aplicador->{'cliente'}; ?>" class="form-control" id="cliente" name="cliente" placeholder="Cliente" required >
                          </div>					  
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-2" for="noCiclos">No Ciclos*:</label>
                          <div class="col-sm-10">
                              <input type="text" value="<?php echo $aplicador->{'no_ciclos'}; ?>" class="form-control" id="noCiclos" name="noCiclos" placeholder="No Ciclos" required >
                          </div>					  
                        </div> 
                        
                        <br>
                        <div class="form-group">
                            <?php $submitBtn = array('class' => 'btn btn-primary
                            ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                            echo form_submit($submitBtn); ?>
                            
                            <a href="<?php echo base_url();?>index.php/herramentales_controller/funcioninicialAdministradorAplicadores">
                            <button type="button" class="btn btn-success">REGRESAR</button>
                            </a>
                        </div>
                    </form>
                </div>	
                <div class="col-sm-3">		
                </div>		
            </div>
	</div>
</body>
</html>