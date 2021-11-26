<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="distributor" content="Global" />
    <meta itemprop="contentRating" content="General" />
    <meta name="robots" content="All" />
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
  
    <style type="text/css" title="currentStyle">
            @import "<?php echo base_url();?>media/css/demo_page.css";
            @import "<?php echo base_url();?>media/css/demo_table.css";
    </style>
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
        function muestraFrecuencia(){
            var maq = document.getElementById("cbo_actividades");
            if (maq.options[maq.selectedIndex].getAttribute("frec") != null) {
                document.getElementById('frecuencia_actividad').value = "" + maq.options[maq.selectedIndex].getAttribute("frec");
            } else {
                document.getElementById('frecuencia_actividad').value = "";
            }
        }        
        
        var maquinasArray = [];
        
        $(document).ready(function() {
                $('#example').dataTable( {
                        "sPaginationType": "full_numbers"
                } );
        } );

        function preguntar() {
            var conf = confirm("¿Seguro que quieres eliminar?");
            if (conf == false) {
                return false;
            } else {
                return true;
            }
        }
        
        function mensaje() {
            if (document.getElementById('registroCorrecto').innerHTML != "") {
                setTimeout(function(){ location.reload(); }, 50);
            }
        }
        
        function verificaFechas(){
            if (document.getElementById('semana2').value != "") {
                if (document.getElementById('semana1').value == "") {
                    alert("Si es solo una fecha selecciona la fecha 1");
                    return false;
                }                
            }
            if (document.getElementById('semana1').value != "") {
                if (document.getElementById('semana2').value != "") {
                    var fecha1 = parseInt(document.getElementById('semana1').value); 
                    var fecha2 = parseInt(document.getElementById('semana2').value);
                    if (fecha1 > fecha2) {
                        alert("La fecha 1 debe ser mayor a la fecha 2");
                        return false;
                    }
                }                
            }
            asignaDatosFinales();
            return true;
        }
        
        function asignaDatosFinales(){
            var f = document.getElementById("numero_maquina");
            var numMaq = "";
            if (f.options[f.selectedIndex].value != "") {
                for(var k=0;k<maquinasArray.length;k++) {
                    if (f.options[f.selectedIndex].value == maquinasArray[k][1]) {
                        document.getElementById('nombreMaquinaHidden').value = maquinasArray[k][0];
                        break;
                    }
                } 
            } else {
                document.getElementById('nombreMaquinaHidden').value = document.getElementById("nombreMaquina").value;
            }
        }
        
        function llenaArrayMaquinas(){
            var maqs = document.getElementById("nombreMaquinaTemp");
            for (var i = 0; i < maqs.options.length; i++) {
                var idMaq  = maqs.options[i].getAttribute("value");
                var numMaq = maqs.options[i].getAttribute("numMaquina");
                maquinasArray.push( [ idMaq, numMaq] );
            }   
        }     
        
        window.onhashchange = function() {
            llenaArrayMaquinas();
        }        
    </script>
     
    <style>
        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
                    box-shadow:  0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
        }        
    </style>
</head>
<body onload="mensaje()">
<div class="container">
<div class="row">
<div class="col-md-12">
    <?php 
        $correcto = $this->session->flashdata('correcto');
        if ($correcto) { ?>
    <span id="registroCorrecto" style="color:blue;"><?= $correcto ?></span>
    <?php } ?>
    <br>
    
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Semana</th>
                    <th>Responsable</th>
                    <th>Actividad</th>
                    <th>M&aacute;quina</th>
                    <th>N&uacute;mero M&aacute;quina</th>
                    <th>Status&nbsp;&nbsp;<?php echo "<img src='".base_url()."/images/sistemaicons/soporte.ico' "
                            ."alt='Soporte. Con ## se muestran los mantenimientos pendientes y con @@ los realizados.' "
                            ."title='Soporte. Con ## se muestran los mantenimientos pendientes y con @@ los realizados.' />";?>
                    </th>
                    <th>observaciones</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Acci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($mantenimientos)) {
                    $i=1;
                    foreach($mantenimientos as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'idFechaMantenimiento'} ?>">
                            <td><?php echo $fila->{'fechaMantenimiento'}; ?></td>
                            <?php 
                                $posicionContenidoUsuario = array_search($fila->{'idResponsable'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                                
                                $posicionContenidoMaquina = array_search($fila->{'idMaquina'},$maquinasArray);
                                $elemArrayMaq = explode("|", $posicionContenidoMaquina);
                                
                                $posicionContenidoActividad = array_search($fila->{'idActividad'},$actividadesArray);
                                $elemArrayAct = explode("|", $posicionContenidoActividad);
                                
                            ?>
                            <td><?php echo $elemArrayUsu[0]; ?></td>
                            
                            <td><?php echo $elemArrayAct[0];; ?></td>
                            <td><?php echo $elemArrayMaq[0]; ?></td>
                            <td><?php echo $elemArrayMaq[1]; ?></td>
                            <td><?php
                                if ($fila->{'condicion_maquina'} == "NO ATENDIDA"){
                                    echo "<p style='opacity: 0; color: transparent;font-size:1px;'>##</p> <img src='".base_url()."/images/sistemaicons/nook.ico' alt='Pendiente' title='Pendiente' />";
                                } else {
                                    echo "<p style='opacity: 0; color: transparent;font-size:1px;'>@@</p> <img src='".base_url()."/images/sistemaicons/ok.ico' alt='Ok' title='Ok' />";
                                } 
                                ?>
                            <td><?php echo $fila->{'observaciones_maquina'} ?></td>
                            <td>
                            <?php if ($fila->{'verificada'} == ""){ ?>
                                <a href="actualizarMantenimientoVerificacion/<?php echo $fila->{'idFechaMantenimiento'} ?>/1"><img src="<?php echo base_url(); ?>/images/sistemaicons/persona.ico" alt="Por verificar" title="Por verificar" /></a>
                            <?php } else { ?>
                                <a href="actualizarMantenimientoVerificacion/<?php echo $fila->{'idFechaMantenimiento'} ?>/0"><img src="<?php echo base_url(); ?>/images/sistemaicons/ojobuscar.ico" alt="Verificada" title="Verificada" /></a>
                            <?php } ?>
                            &nbsp;
                            <a href="actualizarMantenimiento/<?php echo $fila->{'idFechaMantenimiento'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/modificar.ico" alt="Editar" title="Editar" /></a>
                            &nbsp;
                            <a href="eliminarMantenimiento/<?php echo $fila->{'idFechaMantenimiento'} ?>/<?php echo $fila->{'idFechaMantenimiento'} ?>"  onclick="javascript: return preguntar()"><img src="<?php echo base_url(); ?>/images/sistemaicons/borrar2.ico" alt="Borrar" title="Borrar" /></a>
                            </td>
                        </tr>
                      <?php                   
                      $i++;  
                    }   
                } 
                echo "<script>mensaje();</script>";
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Semana</th>
                    <th>Responsable</th>
                    <th>Actividad</th>
                    <th>M&aacute;quina</th>
                    <th>N&uacute;mero M&aacute;quina</th>
                    <th>Status</th>
                    <th>observaciones</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Acci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->

    <script type="text/javascript"> 
        var id1 = document.getElementById("nombreMaquina");
        var id2 = document.getElementById("cbo_actividades");
        var id3 = document.getElementById("numero_maquina");
        var id4 = document.getElementById("responsables");
        
        // Añade un evento change al elemento id1, asociado a la función cambiar()
        if (id1.addEventListener) {     // Para la mayoría de los navegadores, excepto IE 8 y anteriores
            id1.addEventListener("change", cambiar);
        } else if (id1.attachEvent) {   // Para IE 8 y anteriores
            id1.attachEvent("change", cambiar); // attachEvent() es el método equivalente a addEventListener()
        }

        function limpiarSelect() {
            var select = document.getElementById("cbo_actividades");
            
            var length = select.options.length;
            for (i = 1; i <= length-1; i++) {    
                id2.options[i].style.display = "none";
            }  
            
            
            var select2 = document.getElementById("numero_maquina");
            var length = select2.options.length;
            for (i = 1; i <= length-1; i++) {    
                id3.options[i].style.display = "none";
            }   
            
            var select3 = document.getElementById("responsables");
            var length = select3.options.length;
            for (i = 1; i <= length-1; i++) {    
                id4.options[i].style.display = "none";
            }              
            
            document.getElementById('frecuencia_actividad').value = "";
        }
        
        function selectFirstValueSelect() {  
            var select = document.getElementById("cbo_actividades");
            var length = select.options.length;
            for (i = 0; i <= length-1; i++) {   
                var style = window.getComputedStyle(select.options[i]);
                if (style.display !== 'none') {
                    select.options.selectedIndex = i;
                    document.getElementById('frecuencia_actividad').value = "" + select.options[select.selectedIndex].getAttribute("frec");
                    break;
                }
            }  
            
            var select2 = document.getElementById("numero_maquina");
            var length = select2.options.length;
            for (i = 0; i <= length-1; i++) {   
                var style2 = window.getComputedStyle(select2.options[i]);
                if (style2.display !== 'none') {
                    select2.options.selectedIndex = i;
                    break;
                }
            }    
            
            var select3 = document.getElementById("responsables");
            var length = select3.options.length;
            for (i = 0; i <= length-1; i++) {   
                var style2 = window.getComputedStyle(select3.options[i]);
                if (style2.display !== 'none') {
                    select3.options.selectedIndex = i;
                    break;
                }
            }             
        }        
        
        function cambiar() {
            limpiarSelect();
            //para actividades
            for (var i = 1; i < id2.options.length; i++) {
                if(id2.options[i].getAttribute("codigo") == id1.options[id1.selectedIndex].getAttribute("codigo1")){
                    id2.options[i].style.display = "block";
                }else{
                    id2.options[i].style.display = "none";
                }
            }  
            
            //para id3 numero_maquina
            for (var i = 1; i < id3.options.length; i++) {
                if(id3.options[i].getAttribute("codigo1") == id1.options[id1.selectedIndex].getAttribute("codigo1")){
                    id3.options[i].style.display = "block";
                }else{
                    id3.options[i].style.display = "none";
                }
            }

            //para id4 responsables
            for (var i = 1; i < id4.options.length; i++) {
                if(id4.options[i].getAttribute("idResponsable") == id1.options[id1.selectedIndex].getAttribute("idResponsable")){
                    id4.options[i].style.display = "block";
                }else{
                    id4.options[i].style.display = "none";
                }
            }

            id2.value = "";
            id3.value = "";
            id4.value = "";
            selectFirstValueSelect();
        }
        cambiar();
    </script>

</body>	
</html>
