<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
        *, *.before, *:after {
            box-sizing:border-box;
        }
        
        body {
            font-family: Avenir, 'Helvetica Neue', 'Lato', 'Segoe UI', Helvetica, Arial, sans-serif;
        }
        
        #Selector {
            width: 200px;
            margin: 0 auto;
        }
        
        .SubirFoto {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
            line-height: normal;
            font-size: 100%;
            margin:0px;
        }
        
        .SubirFoto + label {
            font-size: 1.2rem;
            font-weight: bold;
            color: #d3394c;
            display: inline-block;
            text-overflow: ellipsis;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            padding: 0.6rem 1.2rem;
            cursor: pointer;
        }

        .SubirFoto:focus + label,
        .SubirFoto + label:hover {
            color: orange;
            outline: 1px dotted #000;
            fill: orange;
        }
        
        .SubirFoto + label figure {
            width: 100%;
            height: 100%;
            fill: #f1e5e6;
            border-radius: 50%;
            background-color: #d3394c;
            display: block;
            padding: 20px;
            margin: 0 auto 10px;
        }
        
        .SubirFoto + label:hover figure {
            background:orange;
        }
        
        inputfile + label svg {
            vertical-align: middle;
            width: 100%;
            height: 100%;
            fill: #f1e5e6;
        }
    </style>
    
    <script>
    </script>
</head>
<body>      
    <div class="container"> <!--class="container-fluid" -->
        <div class="row-fluid">
            <br>
            <h4 class="text-center">Detalle de la Notificación</h4>
        </div>
        
        <div class="row-fluid">
            <div class="col-sm-12">	
                <br>
                <div class="col-sm-3">		
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="notificacionATecnico">Aviso a Técnico:</label>
                            <input type="text" class="form-control bg-danger" id="notificacionATecnico" name="notificacionATecnico" value="<?php echo $notificacion->{'fechaEnvioNotificacionAlTecnico'}; ?>" disabled="true">
                        </div>					  
                    </div>                       
                </div>		

                <div class="col-sm-3">		
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="notificacionALider">Respuesta Técnico:</label>
                            <input type="text" class="form-control bg-danger" id="notificacionALider" name="notificacionALider" value="<?php echo $notificacion->{'fechaEnvioNotificacionAlOperador'}; ?>" disabled="true">
                        </div>					  
                    </div>                       
                </div>		
                
                <div class="col-sm-3">		
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="inicioAtencion">Inicio Atención:</label>
                            <input type="text" class="form-control bg-danger" id="inicioAtencion" name="inicioAtencion" value="<?php echo $notificacion->{'fechaInicioAtencion'}; ?>" disabled="true">
                        </div>					  
                    </div>                       
                </div>		

                <div class="col-sm-3">		
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="FinAtencion">Fin Atención:</label>
                            <input type="text" class="form-control bg-danger" id="FinAtencion" name="FinAtencion" value="<?php echo $notificacion->{'fechaFinAtencion'}; ?>" disabled="true">
                        </div>					  
                    </div>                       
                </div>		
            </div>
        </div>
        
        <div class="row-fluid">
            <div class="col-sm-12">	
                <div class="col-sm-3">		
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="TiempoAtencion">Tiempo Atención:</label>
                            <input type="text" class="form-control bg-danger" id="TiempoAtencion" name="TiempoAtencion" value="<?php echo $notificacion->{'tiempoAtencion'}; ?>" disabled="true">
                        </div>					  
                    </div>                       
                </div>		

                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="input-group">
                          <label class="control-label" for="lider">Lider/Supervisor:</label>
                            <?php 
                                $posicionContenidoUsuario = array_search($notificacion->{'idUsuarioOperador'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                                $lider = $elemArrayUsu[0];
                            ?>
                            <input type="text" class="form-control" id="lider" name="lider" value="<?php echo $lider; ?>" disabled>
                        </div>					  
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="input-group">
                          <label class="control-label" for="maquinas">M&aacutequina:</label>
                            <?php 
                                $nombreMaquina = "";
                                foreach($maquinas as $fila) {
                                    if ($notificacion->{'idMaqui'} == $fila->{'idMaquina'}) {
                                        $nombreMaquina = $fila->{'nombre_maquina'};
                                    }
                                } ?>
                            <input type="text" class="form-control" id="nombreMaquina" name="nombreMaquina" value="<?php echo $nombreMaquina; ?>" disabled>
                        </div>					  
                    </div>
                </div>
                
                <div class="col-sm-3">		
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="status">Estatus:</label>
                            <?php if ($notificacion->{'mensajeOperador'} == 4) { ?>
                            <input type="text" class="form-control" id="status" name="status" value="Finalizado" disabled>
                            <?php } ?>

                            <?php if ($notificacion->{'mensajeOperador'} == 3) { ?>
                            <input type="text" class="form-control" id="status" name="status" value="En proceso" disabled>
                            <?php } ?>

                            <?php if ($notificacion->{'mensajeOperador'} == 2) { ?>
                            <input type="text" class="form-control" id="status" name="status" value="Técnico enterado" disabled>
                            <?php } ?>

                            <?php if ($notificacion->{'mensajeOperador'} == 0) { ?>
                            <input type="text" class="form-control" id="status" name="status" value="Iniciado" disabled>
                            <?php } ?>
                        </div>					  
                    </div>                      
                </div>		
            </div>
        </div>
        
        <div class="row-fluid">
            <div class="col-sm-12">	
                <div class="col-sm-3">		
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="comentariosLider">Comentarios Lider/Supervisor:</label>
                            <textarea class="form-control" id="comentariosLider" rows="3" disabled><?php echo $notificacion->{'comentarios'}; ?></textarea>
                        </div>					  
                    </div>                      
                </div>		
                
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="msgOperador">Mensaje Operador:</label>
                            <textarea class="form-control" id="msgOperador" rows="3" disabled><?php echo $notificacion->{'mensajeOperador'}; ?></textarea>
                        </div>					  
                    </div>                      
                </div>		

                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="msgTecnico">Mensaje Técnico:</label>
                            <textarea class="form-control" id="msgTecnico" rows="3" disabled><?php echo $notificacion->{'mensajeTecnico'}; ?></textarea>
                        </div>					  
                    </div>                      
                </div>		

                <div class="col-sm-3">		
                    <div class="form-group">
                        <div class="input-group">
                            <label class="control-label" for="calificacion">Calificación Atención:</label>
                            <?php if ($notificacion->{'calificacionAtencion'} == 10) { ?>
                            <input type="text" class="form-control" id="calificacion" name="calificacion" value="Excelente" disabled>
                            <?php } ?>

                            <?php if ($notificacion->{'calificacionAtencion'} == 8) { ?>
                            <input type="text" class="form-control" id="calificacion" name="calificacion" value="Bien" disabled>
                            <?php } ?>

                            <?php if ($notificacion->{'calificacionAtencion'} == 7) { ?>
                            <input type="text" class="form-control" id="calificacion" name="calificacion" value="Regular" disabled>
                            <?php } ?>

                            <?php if ($notificacion->{'calificacionAtencion'} == 5) { ?>
                            <input type="text" class="form-control" id="calificacion" name="calificacion" value="Mala" disabled>
                            <?php } ?>
                        </div>					  
                    </div>                       
                </div>		
                
            </div>
        </div>
        
        <div class="row-fluid">
            <div class="col-sm-12">	
                <div class="col-sm-12">	
                    <form class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/mantenimiento_controller/actualizarObservacionFromFormularioTecnico" method="post">
                        <input type="hidden" name="idNotificacion" value="<?php echo $notificacion->{'id'}; ?>" />
                        <div class="form-group">
                            <center>
                            <label class="control-label" for="obsTecnico">Observaciones del Técnico:</label>
                            <br>
                            <textarea class="form-control" id="obsTecnico" name="obsTecnico" rows="3"><?php echo $notificacion->{'observaciones_tecnico'}; ?></textarea>
                            </center>
                        </div>                      
                        <br><br>
                        <div class="form-group">
                            <center>
                            <?php $submitBtn = array('class' => 'btn btn-primary',  'value' => 'GUARDAR', 'name'=>'submit'); echo form_submit($submitBtn); ?>    
                            <a style="margin-left: 30px" href="<?php echo base_url();?>index.php/mantenimiento_controller/mostrarNotificacionesTecnico">
                                        <button type="button" class="btn btn-success">REGRESAR</button></a>
                           </center>
                        </div> 
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>
</html>