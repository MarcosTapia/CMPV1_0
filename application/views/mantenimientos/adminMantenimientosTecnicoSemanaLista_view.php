<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <title>Consulta de Mantenimientos</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/treeview_mantenimientos/bootstrap-treeview.min.css" type="text/css" media="all">
    <script src="<?php echo base_url(); ?>/treeview_mantenimientos/bootstrap-treeview.min.js"></script>
    <!--
    <script src="<?php echo base_url(); ?>/treeview_mantenimientos/script.js"></script>    
    -->
    
    <style>
        *, *.before, *:after {
            box-sizing:border-box;
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
    
    
    <?php 
        //obtiene el primer dia de la semana se hace para bloquear que solo la semana actual se pueda calificar
        //se cambia en la consulta por diaIniMes
        $firstday = date('Y-m-d', strtotime("this week")); 
    ?>   
    <script type="text/javascript" charset="utf-8">
        var idUsuario = '<?php echo $idUsuario; ?>';
        //var diaIniMes = '<?php echo $diaIniMes; ?>';
        var diaIniMes = '<?php echo $firstday; ?>';
        var diaFinMes = '<?php echo $diaFinMes; ?>';
        jQuery(document).ready(function(){

            jQuery.ajax({
                url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_data_tecnico.php?idUsuario=" + idUsuario + '&diaIniMes=' + diaIniMes + '&diaFinMes='+diaFinMes,
                cache: false,
                success: function(response){
                    //document.write(response);
                    //alert(response);
                    $('#treeview').treeview({data: response});
                    $('#treeview').treeview('collapseAll', { silent: true });
                },
                error: function(response){
                    alert("Error");
                }
            });	
        });   
        
        function consultaActividades(idMaquina,fechaMantenimiento,nombreMaquina,numeroMaquina){
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_dataConsultaActividadesTecnico.php?idMaquina="+idMaquina+"&idResponsable="+idUsuario+"&fechaMantenimiento=" + fechaMantenimiento,
                cache: false,
                success: function(response){
                    $('#treeviewConsulta').treeview({data: response});
                },
                error: function(response){
                    alert("Error");
                }
            });	        
        }
        
        function mensaje() {
            if (document.getElementById('registroCorrecto').innerHTML != "") {
                setTimeout(function(){ location.reload(); }, 1000);
            }
        }
        
        function muestraInfo(divAMostrar,idMaquina,nombreMaquina,fechaMantenimiento,obj){
            switch (divAMostrar){
                case 1:
                    document.getElementById("nombreMaquina").innerHTML = nombreMaquina + " - " + obj.innerHTML;
                    document.getElementById("rowTreeview").style.display = "none";
                    document.getElementById("rowConsulta").style.display = "block";
                    
                    document.getElementById("registroModificacionActividad").style.display = "none";
                    
                    document.getElementById("rowTituloConsulta").style.display = "block";
                    if (nombreMaquina != 0) {
                        consultaActividades(idMaquina,fechaMantenimiento,nombreMaquina,obj.innerHTML);
                    }                    
                    break;
                case 2:
                    document.getElementById("treeviewConsulta").innerHTML = ""
                    document.getElementById("treeviewConsultaSemanas").innerHTML = ""
                    	                    
                    document.getElementById("rowTreeview").style.display = "block";
                    document.getElementById("rowConsulta").style.display = "none";
                    
                    document.getElementById("registroModificacionActividad").style.display = "none";
                    
                    document.getElementById("rowTituloConsulta").style.display = "none";
                    break;                    
            }
            //document.getElementById("treeview").CollapseAll();
        }
        
        function muestraInfoSemanas(idActividad,idMaquina,fechaMantenimiento){
            document.getElementById('treeviewConsultaSemanas').style.display = "block";
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_dataConsultaStatusActTecnico.php?idActividad=" + idActividad + "&idResponsable=" + idUsuario + "&fechaMantenimiento=" + fechaMantenimiento + "&idMaquina=" + idMaquina,
                cache: false,
                success: function(response){            
                    $('#treeviewConsultaSemanas').treeview({data: response,enableLinks: true});
                },
                error: function(response){
                    alert("Error");
                }
            });	        
        }
        
        function manttoRapido(idFechaMantenimiento){
            //alert('Actualiza Rapido: ' + idFechaMantenimiento);
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_dataManttoRapidoTecnico.php?idFechaMantenimiento=" + idFechaMantenimiento,
                cache: false,
                success: function(response){            
                    //$('#treeviewConsultaSemanas').treeview({data: response,enableLinks: true});
                    alert("Actualización exitosa");
                    document.getElementById('treeviewConsultaSemanas').style.display = "none";
                },
                error: function(response){
                    alert("Error");
                }
            });	        
        }
        
        function atender(idMantenimiento,semana,observaciones,urlImagen){
            //document.getElementById("numeroMaquina").innerHTML = "Número: " + obj.innerHTML;
            document.getElementById("rowTreeview").style.display = "none";
            document.getElementById("rowTituloConsulta").style.display = "none";
            document.getElementById("rowConsulta").style.display = "none";
            document.getElementById("registroModificacionActividad").style.display = "block";
            document.getElementById("tituloPrincipal").innerHTML = "";
            
            document.getElementById("idFechaMantenimiento").value = idMantenimiento;
            document.getElementById("fechaMantenimiento").value = semana;
            if ((observaciones != "0") && (urlImagen != "0")){
                document.getElementById("observaciones").value = observaciones;
                document.getElementById("imagenCargada").src = urlImagen.replace("localhost", "192.168.98.200");
                if (urlImagen != "") {
                    document.getElementById("imagenCargadaHidden").value = urlImagen.replace("localhost", "192.168.98.200");;
                } else {
                    document.getElementById("imagenCargadaHidden").value = "";
                }
            } 
            
            
            //alert(observaciones + " - " + urlImagen);

            //http://192.168.98.200/index.php/mantenimiento_controller/actualizarMantenimientoTecnico/
            //document.getElementById('atender'+idMantenimiento).href="http://192.168.98.200/cmpv1_0/index.php/mantenimiento_controller/actualizarMantenimientoTecnico/" + idMantenimiento; 
            //alert(document.getElementById('atender'+idMantenimiento).href);        
        }
        
        function revisaUrlFoto(){
//            alert(document.getElementById('imagenCargadaHidden').value);

//            if (document.getElementById('imagenCargadaHidden').value != ""){
//                var arrayUrlImg = document.getElementById("imagenCargadaHidden").value.split("/");
//                document.getElementById("foto").value = arrayUrlImg[arrayUrlImg.length - 1];                
//                alert("YA HABIA FOTO CARGADA");
//            }
            return true;
        }
    </script>
     
</head>
<body onload="mensaje()">
    <div class="container">
        <?php 
            $correcto = $this->session->flashdata('correcto');
            if ($correcto) { ?>
                <span id="registroCorrecto" style="color:blue;"><?= $correcto ?></span>
        <?php } ?>
        
        <p style="color:white;font-size:15px">aaaaaaaaaaaaaa</p>
        
        <?php 
        $ddate = $fecha;
        $date = new DateTime($ddate);
        $mes = $date->format("m");
        $anio = $date->format("Y");
        
        switch ($mes) {
            case "01":
                $mes = "Enero"; 
                break; 
            case "02":
                $mes = "Febrero"; 
                break; 
            case "03":
                $mes = "Marzo"; 
                break; 
            case "04":
                $mes = "Abril"; 
                break; 
            case "05":
                $mes = "Mayo"; 
                break; 
            case "06":
                $mes = "Junio"; 
                break; 
            case "07":
                $mes = "Julio"; 
                break; 
            case "08":
                $mes = "Agosto"; 
                break; 
            case "09":
                $mes = "Septiembre"; 
                break; 
            case "10":
                $mes = "Octubre"; 
                break; 
            case "11":
                $mes = "Noviembre"; 
                break; 
            case "12":
                $mes = "Diciembre"; 
                break; 
        }
        //se cambia por restriccion de samenas
        //echo "<h4 id='tituloPrincipal' style='color:blue;text-align:center;'> Mantenimientos correspondientes al mes de: ".$mes."</h4><br>"; 
        $semanaArray = explode("|",$week);
        echo "<h4 id='tituloPrincipal' style='color:blue;text-align:center;'> Mantenimientos de la Semana. ".$semanaArray[0]."</h4><br>"; 
    ?>
              
                
        <div class="row" id="rowTreeview">	
            <div class="col-md-12" id="treeview"></div>	
	</div>	

        <div class="row" id="rowTituloConsulta" style="display:none">
            <div class="col-md-12">
                <div class="col-md-3">
                    <a href="#" onclick="muestraInfo(2,0,'','',this)" style="color:blue"> Regresar a la Consulta</a>
                </div>
                <div class="col-md-6 text-center">
                    <br>
                    <p id='nombreMaquina' style="font-size: 20px; color:blue;"></p>
                </div>
                <div class="col-md-3 text-left">
                    <br>
                    <p id='numeroMaquina' style="font-size: 20px; color:blue;"></p>
                </div>                
            </div>
	</div>	
                
        <div class="row" id="rowConsulta" style="display:none">
            <div class="col-md-12">
                <div class="col-md-6" id="consultaActsSems">
                    <div id="treeviewConsulta"></div>	
                </div>

                <div class="col-md-6" id="consultaSems">
                    <div id="treeviewConsultaSemanas"></div>	
                </div>
            </div>
	</div>	
        
        <script>mensaje();</script>

        <div class="row" id="registroModificacionActividad" style="display:none">	
            <div class="col-md-12">
                
                
                
                
                <form onsubmit='javascript: return revisaUrlFoto()' enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/mantenimiento_controller/actualizarMantenimientoFromFormularioTecnico" method="post">
                    
                    <?php //echo $mantenimiento->{'idFechaMantenimiento'}; ?>
                    <?php //echo $mantenimiento->{'fechaMantenimiento'}; ?>
                    <?php //echo $mantenimiento->{'observaciones_maquina'}; ?>
                    <?php //echo $mantenimiento->{'urlImagen'}; ?>
                    
                    <input type="hidden" name="idFechaMantenimiento" id="idFechaMantenimiento" value="" />
                    <input type="hidden" name="fechaMantenimiento" id="fechaMantenimiento" value="" />
                    <input type="hidden" name="imagenCargadaHidden" id="imagenCargadaHidden" value="" />
                    <br>
                    <div class="form-group">
                        <label class="control-label h4 text-center text-success" for="observaciones">Observaciones</label>
                        <textarea class="form-control" rows="5" id="observaciones" name="observaciones"></textarea>
                    </div> 

                    <div class="form-group">
                        <div class="col-sm-2">                          
                        </div>
                        <div class="col-sm-4">
                            <img src="" id='imagenCargada' width="200">
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
                            
                            
                            
                            <a style="margin-left: 30px" href="<?php echo base_url();?>index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario">
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
