<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    
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
        $(document).ready(function() {
            $("#foto").change(function() {
                readURL(this);  
            });   
        } );
        
        function readURL(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
              $('#visualizador').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
          }
        }
    </script>
</head>
<body>      
    <div class="container"> <!--class="container-fluid" -->
        <div class="row-fluid">
            <br>
            <h4 class="text-center">Reporte del Mantenimiento</h4>
        </div>
        <div class="row-fluid">
            <div class="col-sm-4">		
                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label" for="nombreUsuario">Responsable:</label>
                        <input type="text" class="form-control bg-danger" id="nombreUsuario" name="nombreUsuario" 
                               value="<?php echo $usuarioDatos; ?>" disabled="true">
                    </div>					  
                </div>                       
            </div>		
            <div class="col-sm-4">
                <div class="form-group">
                    <div class="input-group">
                      <label class="control-label" for="maquinas">M&aacutequina:</label>
                        <?php 
                            $nombreMaquina = "";
                            foreach($maquinas as $fila) {
                            if ($idMaquina == $fila->{'idMaquina'}) {
                                $nombreMaquina = $fila->{'nombre_maquina'};
                            }
                        } ?>
                        <input type="text" class="form-control" 
                               id="nombreMaquina" name="nombreMaquina" 
                               value="<?php echo $nombreMaquina; ?>" disabled="true">
                    </div>					  
                </div>   

            </div>	
            <div class="col-sm-4">		
                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label" for="actividad">Actividad:</label>
                        <input type="text" class="form-control" 
                               id="actividad" name="actividad" 
                               value="<?php echo $mantenimiento->{'descripcion_actividad'}; ?>" disabled="true">
                    </div>					  
                </div>                      
            </div>		
        </div>
        
        <div class="row-fluid">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/mantenimiento_controller/actualizarMantenimientoFromFormularioTecnico3" method="post">
                    <input type="hidden" name="idFechaMantenimiento" id="idFechaMantenimiento" value="<?php echo $mantenimiento->{'idFechaMantenimiento'}; ?>" />
                    <input type="hidden" name="fechaMantenimiento" id="fechaMantenimiento" value="<?php echo $mantenimiento->{'fechaMantenimiento'}; ?>" />
                    <br>
                    <div class="form-group">
                        <label class="control-label h4 text-center text-success" for="observaciones">Observaciones</label>
                        <textarea class="form-control" rows="5" id="observaciones" name="observaciones"><?php echo $mantenimiento->{'observaciones_maquina'}; ?></textarea>
                    </div> 

                    <div class="form-group">
                        <div class="col-sm-2">                          
                        </div>
                        <div class="col-sm-4">
                            <input type='hidden' name='imagenCargadaHidden' value='<?php echo $mantenimiento->{'urlImagen'}; ?>' />
                            <img src="<?php echo $mantenimiento->{'urlImagen'}; ?>" width="200" id="visualizador" />
                            <h4>Vista previa:</h4>
                        </div>
                        <div class="col-sm-4">
                            <div id="Selector">
                                <input type="file" name="imagen" id="foto" class="SubirFoto" accept="image/*" capture="camera" />
                                <label for="foto"><figure><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Cargar una foto...</span></label>
                             </div>
                        <div class="col-sm-2">                          
                        </div>
                    </div>    
                    
                    <br><br>
                    <div class="form-group">
                        <div class="col-sm-4">                          
                        </div>
                        <div class="col-sm-8">
                            <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php $submitBtn = array('class' => 'btn btn-primary
                                        ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                                        echo form_submit($submitBtn); ?>    
                            
                            
                            
                            <a style="margin-left: 30px" href="<?php echo base_url();?>index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario3">
                                        <button type="button" class="btn btn-success">REGRESAR</button>
                                        </a>                           
                        </div>					  
                    </div> 
                </form>
            </div>		
        </div>

    </div>
</body>
</html>