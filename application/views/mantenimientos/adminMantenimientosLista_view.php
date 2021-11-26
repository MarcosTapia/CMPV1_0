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
    
    <script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function(){
            jQuery.ajax({
                    url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_data_simple.php",
                    cache: false,
                    success: function(response){
                        //alert(response);
                        $('#treeview').treeview({data: response});
                        $('#treeview').treeview('collapseAll', { silent: true });
                    },
                    error: function(response){
                        alert("Error");
                    }
            });	
        });   
        
        function consultaActividades(idMaquina){
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_dataConsultaActividades.php?idMaquina="+idMaquina,
                cache: false,
                success: function(response){
                    //alert(response);
                    $('#treeviewConsulta').treeview({data: response});
                },
                error: function(response){
                    alert("Error");
                }
            });	        
        }
        
        function mensaje() {
            if (document.getElementById('registroCorrecto').innerHTML != "") {
                setTimeout(function(){ location.reload(); }, 50);
            }
        }
        
        function muestraInfo(divAMostrar,idMaquina,nombreMaquina,obj){
            switch (divAMostrar){
                case 1:
                    document.getElementById("nombreMaquina").innerHTML = "Máquina: " + nombreMaquina;
                    document.getElementById("numeroMaquina").innerHTML = "Número: " + obj.innerHTML;
                    document.getElementById("rowTreeview").style.display = "none";
                    document.getElementById("rowConsulta").style.display = "block";
                    document.getElementById("rowTituloConsulta").style.display = "block";
                    if (idMaquina != 0) {
                        consultaActividades(idMaquina);
                    }                    
                    break;
                case 2:
                    document.getElementById("treeviewConsulta").innerHTML = ""
                    document.getElementById("treeviewConsultaSemanas").innerHTML = ""
                    	                    
                    document.getElementById("rowTreeview").style.display = "block";
                    document.getElementById("rowConsulta").style.display = "none";
                    document.getElementById("rowTituloConsulta").style.display = "none";
                    break;                    
            }
            //document.getElementById("treeview").CollapseAll();
        }
        
        function muestraInfoSemanas(idActividad,idMaquina){
            document.getElementById('consultaSems').style.display = "block";
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_dataConsultaSemanas.php?idActividad=" + idActividad + "&idMaquina=" + idMaquina,
                cache: false,
                success: function(response){
                    $('#treeviewConsultaSemanas').treeview({data: response,enableLinks: true});
                },
                error: function(response){
                    alert("Error");
                }
            });	        
        }
        
        function verificar(idFechaMantenimiento,accion) {
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_dataVerificar.php?idFechaMantenimiento=" + idFechaMantenimiento + "&accion=" + accion,
                cache: false,
                success: function(response){            
                    //$('#treeviewConsultaSemanas').treeview({data: response,enableLinks: true});
                    alert("Actualización exitosa");
                    document.getElementById('consultaSems').style.display = "none";
                },
                error: function(response){
                    alert("Error");
                }
            });	               
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
        <div class="row" id="rowTreeview">	
            <div class="col-md-12" id="treeview"></div>	
	</div>	

        <div class="row" id="rowTituloConsulta" style="display:none">
            <div class="col-md-12">
                <div class="col-md-3">
                    <a href="#" onclick="muestraInfo(2,0,'',this)" style="color:blue"> Regresar a la Consulta</a>
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
        <?php echo "<script>mensaje();</script>"; ?>
    </div>    
    
</body>	
</html>
