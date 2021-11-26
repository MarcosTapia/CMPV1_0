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
            $("#foto2").change(function() {
                readURL2(this);  
            });   
            $("#foto3").change(function() {
                readURL3(this);  
            });   
            $("#foto4").change(function() {
                readURL4(this);  
            });   
            $("#foto5").change(function() {
                readURL5(this);  
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
        
        function readURL2(input) {
          if (input.files && input.files[0]) {
            var reader2 = new FileReader();
            reader2.onload = function(e) {
              $('#visualizador2').attr('src', e.target.result);
            }
            reader2.readAsDataURL(input.files[0]); // convert to base64 string
          }
        }

        function readURL3(input) {
          if (input.files && input.files[0]) {
            var reader3 = new FileReader();
            reader3.onload = function(e) {
              $('#visualizador3').attr('src', e.target.result);
            }
            reader3.readAsDataURL(input.files[0]); // convert to base64 string
          }
        }

        function readURL4(input) {
          if (input.files && input.files[0]) {
            var reader4 = new FileReader();
            reader4.onload = function(e) {
              $('#visualizador4').attr('src', e.target.result);
            }
            reader4.readAsDataURL(input.files[0]); // convert to base64 string
          }
        }

        function readURL5(input) {
          if (input.files && input.files[0]) {
            var reader5 = new FileReader();
            reader5.onload = function(e) {
              $('#visualizador5').attr('src', e.target.result);
            }
            reader5.readAsDataURL(input.files[0]); // convert to base64 string
          }
        }
    </script>
    
    
</head>
<body>      
    <div class="container"> <!--class="container-fluid" -->
        <div class="row">
            <div class="col-sm-12">
                <br>
                <p style="font-size:17px;"><b>Nueva solución para la falla:</b> <ins><?php echo $descripcionFalla ?></ins>. <b>Máquina: </b><ins><?php echo $falla->{'nombre_maquina'}." ".$falla->{'numero_maquina'} ?></ins></p>
            </div>		
        </div>		
        <div class="row-fluid">
            <div class="col-sm-1">		
            </div>		
            <div class="col-sm-6">
                <form enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/soluciones_controller/nuevoSolucionFromFormulario/<?php echo $idFalla; ?>" method="post">
                    <input type='hidden' name="idFallaHdn" value='<?php echo $idFalla ?>' />
                    <br>
                    <div class="form-group">
                      <label class="control-label col-sm-2" for="descripcionSolucion">Soluci&oacute;n:</label>
                      <div class="col-sm-10">
                          <textarea class="form-control" rows="5" id="descripcionSolucion" name="descripcionSolucion"></textarea>
                      </div>					  
                    </div> 
                    
                    <div class="form-group">
                        <br>
                        <p style="font-weight: bold;font-size: 16px;text-align: center;">Foto(s) de Evidencia:</p>
                        <br>
                        <div class="col-sm-6">
                            <input type='hidden' name='imagenCargadaHidden' />
                            <img src="" width="200" id="visualizador" />
                            <h4>Vista previa:</h4>
                        </div>
                        <div class="col-sm-6">
                            <div id="Selector">
                                <input type="file" name="imagen" id="foto" class="SubirFoto" accept="image/*" capture="camera" />
                                <label for="foto"><figure><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Cargar una foto...</span></label>
                             </div>
                        </div>
                    </div>    
                    
                    <div class="form-group">
                        <br><br><br>
                        <div class="col-sm-6">
                            <input type='hidden' name='imagen2CargadaHidden' />
                            <img src="" width="200" id="visualizador2" />
                            <h4>Vista previa:</h4>
                        </div>
                        <div class="col-sm-6">
                            <div id="Selector">
                                <input type="file" name="imagen2" id="foto2" class="SubirFoto" accept="image/*" capture="camera" />
                                <label for="foto2"><figure><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Cargar una foto...</span></label>
                             </div>
                        </div>
                    </div>    
                    
                    <div class="form-group">
                        <br><br><br>
                        <div class="col-sm-6">
                            <input type='hidden' name='imagen3CargadaHidden' />
                            <img src="" width="200" id="visualizador3" />
                            <h4>Vista previa:</h4>
                        </div>
                        <div class="col-sm-6">
                            <div id="Selector">
                                <input type="file" name="imagen3" id="foto3" class="SubirFoto" accept="image/*" capture="camera" />
                                <label for="foto3"><figure><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Cargar una foto...</span></label>
                             </div>
                        </div>
                    </div>    
                    
                    <div class="form-group">
                        <h4>Archivo: </h4>
                        <br>
                        <div class="form-group">
                          <label for="exampleFormControlFile1">Selecciona el archivo: </label>
                          <input type="file" class="form-control-file" multiple id="archivo" name="archivo">
                        </div>        
                    </div>    

                    <br><br>
                    <div class="form-group">
                        <center>
                        <?php $submitBtn = array('class' => 'btn btn-primary
                        ',  'value' => 'GUARDAR', 'name'=>'submit'); 
                        echo form_submit($submitBtn); ?>

                        <a href="<?php echo base_url();?>index.php/soluciones_controller/mostrarSolucionesPorIdFalla/<?php echo $idFalla ?>">
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