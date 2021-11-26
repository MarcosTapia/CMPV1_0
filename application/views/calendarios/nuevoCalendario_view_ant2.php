<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    
     <?php 
     $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
     $fechaIngreso = $dt->format("Y-m-d");      
     //echo "---->>>  ".$fechaIngreso; 
     ?>
    <script>
        $( function() {
          $( "#datepicker" ).datepicker({
             showWeek: true,
          });            
          $( "#datepicker" ).datepicker();
        } );
        
        var maquinasArray = [];

        function muestraFrecuencia(){
            var maq = document.getElementById("cboActividades");
            if (maq.options[maq.selectedIndex].getAttribute("frec") != null) {
                document.getElementById('frecuencia_actividad').value = "" + maq.options[maq.selectedIndex].getAttribute("frec");
                if (document.getElementById('frecuencia_actividad').value != null) {
                    cambiaNumeroSemanas(document.getElementById('frecuencia_actividad').value);
                }
            } else {
                document.getElementById('frecuencia_actividad').value = "";
            }
        } 
        
        function cambiaNumeroSemanas(frecuencia) {
            var cantidadSemanas = 0;
            switch (frecuencia.toLowerCase()) {
                case 'semanal': 
                    cantidadSemanas = 53;
                    break;
                case 'mensual': 
                    cantidadSemanas = 53;
                    break;
                case 'trimestral': 
                    cantidadSemanas = 53;
                    break;                    
                case 'semestral': 
                    cantidadSemanas = 53;
                    break;
                case 'anual':     
                    cantidadSemanas = 53;
                    break;   
                case 'ciclos':     
                    cantidadSemanas = 53;
                    break;   
            }
            
            //Agrega semanas
            var table = document.getElementById("tblCalendarioFinal");
            table.innerHTML = "";
            
            //inserta el encabezado
            var row = table.insertRow(0);
            row.id = 0;
            var cell0 = row.insertCell(0);
            var cell1 = row.insertCell(1);
            cell0.innerHTML = "Semana";
            cell0.style.fontSize = "x-large";
            cell0.backgroundColor="#CCC";
            cell1.innerHTML = "Selección";
            cell1.style.fontSize = "x-large";

            var noRenglones;   
            row;
            for (i=0; i<cantidadSemanas; i++) {
                noRenglones = table.rows.length;   
                row = table.insertRow(noRenglones);
                row.id = noRenglones;
                var cell0 = row.insertCell(0);
                var cell1 = row.insertCell(1);
                cell0.innerHTML = row.id;
                
                if (frecuencia.toLowerCase() == "semanal") {
                    cell1.innerHTML = "<input type='checkbox' id='chk"+row.id+"' class='custom-control-input' checked>";
                } else {
                    cell1.innerHTML = "<input type='checkbox' id='chk"+row.id+"' class='custom-control-input'>";
                }
                document.getElementById(row.id).style.border = "3px solid #0000FF";
            }
            
            table.style.border = "3px solid #0000FF";
            //table.classList.add("table table-bordered");
        }
        
        function armaCalendarioFinal() {
            var table = document.getElementById("tblCalendarioFinal");
            var noRenglones = table.rows.length;
            if (noRenglones < 2) {
                alert("No existen valores en la tabla");
                return false;
            }
            
            //arma cadena de cboActividades a enviar para guardado
            var cadenaFinalMantenimientosStr = "";
            var cadenaFinalMantenimientosGlobal = "";
            
            var x = document.getElementById("nombreMaquina").selectedIndex;
            var y = document.getElementById("nombreMaquina").options;
            var idResponsable = y[x].getAttribute("idResponsable");
            //var idMaquina = y[x].getAttribute("value");
            var idMaquina;
            
            var f = document.getElementById("numero_maquina");
            for(var k=0;k<maquinasArray.length;k++) {
                if (f.options[f.selectedIndex].text == maquinasArray[k][1]) {
                    idMaquina = maquinasArray[k][0];
                }
            }            

            var x1 = document.getElementById("nombreMaquina").selectedIndex;
            var y1 = document.getElementById("nombreMaquina").options;
            var idActividad = document.getElementById("cboActividades").value;
            //alert("" + idActividad);
            //alert(document.getElementById("numero_maquina").selectedIndex);
            //alert("" + document.getElementById('numero_maquina').options[0].getAttribute("numMaquina"));
            for(var i=1;i<noRenglones;i++) {
                 if (document.getElementById("chk" + i).checked == true) {
                    cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + "Semana " + i + "|";
                    cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + idResponsable + "|"; 
                    cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + idMaquina + "|"; 
                    cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + "NO ATENDIDA" + "|"; 
                    cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + "" + "|"; 
                    cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + idActividad + "|"; 
                    
                    cadenaFinalMantenimientosGlobal = cadenaFinalMantenimientosGlobal + cadenaFinalMantenimientosStr + "@@@";
                    cadenaFinalMantenimientosStr = "";

                 }
            }
            document.getElementById('calendarioFinalHidden').value = cadenaFinalMantenimientosGlobal;    
            //alert(document.getElementById('calendarioFinalHidden').value);
            return true;
        }
        
        function llenaArrayMaquinas(){
            var maqs = document.getElementById("nombreMaquinaTemp");
            for (var i = 0; i < maqs.options.length; i++) {
                var idMaq  = maqs.options[i].getAttribute("value");
                var numMaq = maqs.options[i].getAttribute("numMaquina");
                maquinasArray.push( [ idMaq, numMaq] );
            }   
        }        
    </script>
</head>
<body>      
    <div class="container">
        <div class="row-fluid">
            <center><h3>Parámetros Generales</h3></center>
            <div class="col-sm-3">
                <select class="form-control" name="nombreMaquina" id="nombreMaquina" required autofocus>
                    <option value="">Nombre Máquina...</option>
                    <?php 
                        $elementos = array("-2");
                        foreach($maquinas as $fila) {
                            $v = array_search($fila->{'nombre_maquina'},$elementos);
                            if ($v == false){
                                array_push($elementos,$fila->{'nombre_maquina'});
                                echo "<option idResponsable=".$fila->{'responsable_maquina'}." codigo1='".$fila->{'nombre_maquina'}."' numMaquina=".$fila->{'numero_maquina'}." value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}."</option>";
                            }
                        } 
                    ?>
                </select>
                
                <select class="form-control" name="nombreMaquinaTemp" id="nombreMaquinaTemp" style="display:none">
                    <?php 
                        echo "<script>maquinasArray = [];</script>";
                        foreach($maquinas as $fila) {
                            echo "<option idResponsable=".$fila->{'responsable_maquina'}." codigo1='".$fila->{'nombre_maquina'}."' numMaquina=".$fila->{'numero_maquina'}." value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}."</option>";
                        } 
                    ?>
                </select>
                <script>llenaArrayMaquinas();</script>
            </div> 
            <div class="col-sm-3">
                <select class="form-control" name="numero_maquina" id="numero_maquina" required>
                    <option value="">N&uacute;mero de Máquina...</option>
                    <?php
                        $elementos2 = array("-2");
                        foreach($maquinas as $fila) {
                            $w = array_search($fila->{'numero_maquina'},$elementos2);
                            if ($w == false){
                                array_push($elementos2,$fila->{'numero_maquina'});
                                echo "<option codigo1='".$fila->{'nombre_maquina'}."' value=".$fila->{'idMaquina'}.">".$fila->{'numero_maquina'}."</option>";
                            }    
                        }    
                    ?>
                </select>       
            </div>             
            
            <div class="col-sm-3">
                <select class="form-control" name="cboActividades" id="cboActividades" onchange="muestraFrecuencia()" required>
                    <option value="">Actividad...</option>
                    <?php foreach($actividades as $fila) {
                        echo "<option codigo='".$fila->{'nombre_maquina'}."' frec=".$fila->{'frecuencia'}." value=".$fila->{'idActividad'}.">".$fila->{'descripcion_actividad'}."</option>";
                    } ?>
                </select>       
            </div> 
            
            <div class="col-sm-3">
                <input type="text" class="form-control" id="frecuencia_actividad" name="frecuencia_actividad" 
                       placeholder="Frecuencia" disabled required>
            </div>             
        </div>    
            
            
        <div class="row-fluid">
            <br><br>
            <center><h2>FECHAS</h2></center>
            <div class="col-md-3 col-sm-3 col-xs-3" >
                <br>
                <form onsubmit="javascript: return armaCalendarioFinal()" action="<?php echo base_url();?>index.php/mantenimiento_controller/nuevoMantenimientoFromFormulario" method="post">
                    <input type="hidden" name="calendarioFinalHidden" id="calendarioFinalHidden" />
                    <input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
                </form>
                <br><br>
                Calendario: <div id="datepicker" style="position: fixed;">
                </div>  
                <br><br><br><br><br><br><br><br><br><br><br><br>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1">
            </div>            
            <div class="col-md-8 col-sm-8 col-xs-8">
                <table class="table table-bordered" name="tblCalendarioFinal" id="tblCalendarioFinal">
                  <thead>
                    <tr>
                      <th scope="col">Semana</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
            </div>
        </div>
    </div>
        
    <script type="text/javascript"> 
        var id1 = document.getElementById("nombreMaquina");
        var id2 = document.getElementById("cboActividades");
        var id3 = document.getElementById("numero_maquina");
        
        // Añade un evento change al elemento id1, asociado a la función cambiar()
        if (id1.addEventListener) {     // Para la mayoría de los navegadores, excepto IE 8 y anteriores
            id1.addEventListener("change", cambiar);
        } else if (id1.attachEvent) {   // Para IE 8 y anteriores
            id1.attachEvent("change", cambiar); // attachEvent() es el método equivalente a addEventListener()
        }
        
        function limpiarSelect() {
            var select = document.getElementById("cboActividades");
            var length = select.options.length;
            for (i = 0; i <= length-1; i++) {    
                id2.options[i].style.display = "none";
            }   
            var select2 = document.getElementById("numero_maquina");
            var length = select2.options.length;
            for (i = 0; i <= length-1; i++) {    
                id3.options[i].style.display = "none";
            }   
            document.getElementById('frecuencia_actividad').value = "";
        }
        
        function selectFirstValueSelect() {  
            var select = document.getElementById("cboActividades");
            var length = select.options.length;
            for (i = 0; i <= length-1; i++) {   
                var style = window.getComputedStyle(select.options[i]);
                if (style.display !== 'none') {
                    select.options.selectedIndex = i;
                    //alert(select.options[select.selectedIndex].getAttribute("frec"));
                    document.getElementById('frecuencia_actividad').value = "" + select.options[select.selectedIndex].getAttribute("frec");
                    cambiaNumeroSemanas(document.getElementById('frecuencia_actividad').value);                    
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
        }        
        
        function cambiar() {
            limpiarSelect();
            //para cboActividades
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
            
            id2.value = "";
            id3.value = "";
            selectFirstValueSelect();
        }
        cambiar();
    </script>
    
</body>
</html>