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
            var maq = document.getElementById("actividades");
            if (maq.options[maq.selectedIndex].getAttribute("frec") != null) {
                document.getElementById('frecuencia_actividad').value = "" + maq.options[maq.selectedIndex].getAttribute("frec");
            } else {
                document.getElementById('frecuencia_actividad').value = "";
            }
        }        
        
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
                setTimeout(function(){ location.reload(); }, 3000);
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
            return true;
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
    <form onsubmit="javascript: return verificaFechas()" action="<?php echo base_url();?>index.php/mantenimiento_controller/mostrarMantenimientosAdminFiltrados" class="form-inline" method="post">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Criterios de b&uacute;squeda personalizada:</legend>
            <div class="col-sm-2">
                <select class="form-control" name="nombreMaquina" id="nombreMaquina" required autofocus>
                    <option value="">Nombre Máquina...</option>
                    <?php 
                        $elementos = array("-2");
                        foreach($maquinas2 as $fila) {
                            $v = array_search($fila->{'nombre_maquina'},$elementos);
                            if ($v == false){
                                array_push($elementos,$fila->{'nombre_maquina'});
                                echo "<option idResponsable=".$fila->{'responsable_maquina'}." codigo1=".$fila->{'nombre_maquina'}." numMaquina=".$fila->{'numero_maquina'}." value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}."</option>";
                            }
                        } 
                    ?>
                </select>
            </div> 
            <div class="col-sm-2">
                <select class="form-control" name="numero_maquina" id="numero_maquina" required>
                    <option value="">N&uacute;mero Máquina...</option>
                    <?php
                        $elementos2 = array("-2");
                        foreach($maquinas2 as $fila) {
                            $w = array_search($fila->{'numero_maquina'},$elementos2);
                            if ($w == false){
                                array_push($elementos2,$fila->{'numero_maquina'});
                                echo "<option codigo1=".$fila->{'nombre_maquina'}." value=".$fila->{'numero_maquina'}.">".$fila->{'numero_maquina'}."</option>";
                            }    
                        }    
                    ?>
                </select>       
            </div>             
            
            <div class="col-sm-2">
                <select class="form-control" name="actividades" id="actividades" onchange="muestraFrecuencia()" required>
                    <option value="">Actividad...</option>
                    <?php foreach($actividades as $fila) {
                        echo "<option codigo=".$fila->{'nombre_maquina'}." frec=".$fila->{'frecuencia'}." value=".$fila->{'idActividad'}.">".$fila->{'descripcion_actividad'}."</option>";
                    } ?>
                </select>       
            </div> 
            
            <div class="col-sm-2">
                <select class="form-control" name="responsables" id="responsables" required>
                    <option value="">Responsable ...</option>
                    <?php 
                        $elementos3 = array("-2");
                        foreach($maquinas2 as $fila) {
                            $w = array_search($fila->{'responsable_maquina'},$elementos3);
                            if ($w == false){
                                array_push($elementos3,$fila->{'responsable_maquina'});
                                echo "<option idResponsable=".$fila->{'responsable_maquina'}." value=".$fila->{'responsable_maquina'}.">".$fila->{'apusu'}." "
                                        .$fila->{'amusu'}." ".$fila->{'nomusu'}."</option>";
                            }
                        } 
                   ?>
                </select>
            </div>             

            <div class="col-sm-2">
                <select class="form-control" name="semana1" id="semana1">
                    <option value="">De...</option>
                    <?php 
                        for($i=1;$i<53;$i++) {
                            echo "<option value='".$i."'>Semana ".$i."</option>";
                        } 
                   ?>
                </select>
                &nbsp;&nbsp;
                <select class="form-control" name="semana2" id="semana2">
                    <option value="">A...</option>
                    <?php 
                        for($i=1;$i<53;$i++) {
                            echo "<option value='".$i."'>Semana ".$i."</option>";
                        } 
                   ?>
                </select>
            </div>     
            
            <div class="col-sm-2">
                <input type="text" class="form-control" id="frecuencia_actividad" name="frecuencia_actividad" 
                       placeholder="Frecuencia" disabled style="display:none;">
                <input type="submit" class="btn btn-primary" name="submit" value="Buscar" />
    </form>    
                
                <table>
                    <tr>
                        <td>
                            <form action="<?php echo base_url();?>index.php/mantenimiento_controller/exportarExcel" method="post">  
                                  <?php if (isset($mantenimientos)) { ?>
                                  <input type="hidden" name="mantenimientosHidden" value="<?php echo htmlspecialchars(serialize($mantenimientos)) ?>" />
                                  <?php } ?>
                                  <input style="padding-top: 10px;" type="image" src="<?php echo base_url(); ?>/images/sistemaicons/excelicon2.png" alt="Exportar a Excel" title="Exportar a Excel" />
                             </form>
                        </td>
                        <td>
                            <form action="<?php echo base_url();?>index.php/mantenimiento_controller/hojaEnBlanco" method="post">    
                                  <?php if (isset($mantenimientos)) { ?>
                                  <input type="hidden" name="mantenimientosHidden" value="<?php echo htmlspecialchars(serialize($mantenimientos)) ?>" />
                                  <?php } ?>
                                  <input style="padding-top: 10px;" type="image" src="<?php echo base_url(); ?>/images/sistemaicons/pdficon2.png" alt="Ver en PDF" title="Ver en PDF" />
                           </form>
                        </td>
                    </tr>
                </table>
             </div>             
     </fieldset>

    <br>
    
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Semana</th>
                    <th>Responsable</th>
                    <th>Actividad</th>
                    <th>M&aacute;quina</th>
                    <th>N&uacute;mero M&aacute;quina</th>
                    <th>Status</th>
                    <th>observaciones</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($mantenimientos)) {
                    $i=1;
                    foreach($mantenimientos as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'idFechaMantenimiento'} ?>">
                            <td><?php echo $fila->{'idFechaMantenimiento'} ?></td>
                            <td><?php echo $fila->{'fechaMantenimiento'} ?></td>
                            <td><?php echo $fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'nombre'} ?></td>
                            <td><?php echo $fila->{'descripcion_actividad'} ?></td>
                            <td><?php echo $fila->{'nombre_maquina'} ?></td>
                            <td><?php echo $fila->{'numero_maquina'} ?></td>
                            <td><?php
                                if ($fila->{'condicion_maquina'} == "NO ATENDIDA"){
                                    echo "<img src='".base_url()."/images/sistemaicons/nook.ico' alt='Pendiente' title='Pendiente' />";
                                } else {
                                    echo "<img src='".base_url()."/images/sistemaicons/ok.ico' alt='Ok' title='Ok' />";
                                } 
                                ?>
                            <td><?php echo $fila->{'observaciones_maquina'} ?></td>
                            <td>
                            <a href="actualizarMantenimiento/<?php echo $fila->{'idFechaMantenimiento'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/modificar.ico" alt="Editar" title="Editar" /></a>                            
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
                    <th>Id</th>
                    <th>Semana</th>
                    <th>Responsable</th>
                    <th>Actividad</th>
                    <th>M&aacute;quina</th>
                    <th>N&uacute;mero M&aacute;quina</th>
                    <th>Status</th>
                    <th>observaciones</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->

    <script type="text/javascript"> 
        var id1 = document.getElementById("nombreMaquina");
        var id2 = document.getElementById("actividades");
        var id3 = document.getElementById("numero_maquina");
        var id4 = document.getElementById("responsables");
        
        // Añade un evento change al elemento id1, asociado a la función cambiar()
        if (id1.addEventListener) {     // Para la mayoría de los navegadores, excepto IE 8 y anteriores
            id1.addEventListener("change", cambiar);
        } else if (id1.attachEvent) {   // Para IE 8 y anteriores
            id1.attachEvent("change", cambiar); // attachEvent() es el método equivalente a addEventListener()
        }

        function limpiarSelect() {
            var select = document.getElementById("actividades");
            var length = select.options.length;
            for (i = 0; i <= length-1; i++) {    
                id2.options[i].style.display = "none";
            }  
            
            var select2 = document.getElementById("numero_maquina");
            var length = select2.options.length;
            for (i = 0; i <= length-1; i++) {    
                id3.options[i].style.display = "none";
            }   
            
            var select3 = document.getElementById("responsables");
            var length = select3.options.length;
            for (i = 0; i <= length-1; i++) {    
                id4.options[i].style.display = "none";
            }              
            
            document.getElementById('frecuencia_actividad').value = "";
        }
        
        function selectFirstValueSelect() {  
            var select = document.getElementById("actividades");
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
            for (var i = 0; i < id2.options.length; i++) {
                if(id2.options[i].getAttribute("codigo") == id1.options[id1.selectedIndex].getAttribute("codigo1")){
                    id2.options[i].style.display = "block";
                }else{
                    id2.options[i].style.display = "none";
                }
            }  
            
            //para id3
            for (var i = 0; i < id3.options.length; i++) {
                if(id3.options[i].getAttribute("codigo1") == id1.options[id1.selectedIndex].getAttribute("codigo1")){
                    id3.options[i].style.display = "block";
                }else{
                    id3.options[i].style.display = "none";
                }
            }

            //para id4
            for (var i = 0; i < id4.options.length; i++) {
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
