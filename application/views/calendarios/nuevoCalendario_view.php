<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    
    
    <style>
    body {font-family: Arial, Helvetica, sans-serif;}

    /* The Modal (background) */
    .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1; /* Sit on top */
      padding-top: 100px; /* Location of the box */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
    }

    /* The Close Button */
    .close {
      color: #aaaaaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }
    </style>    
    
    
    
     <?php 
     $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
     $fechaIngreso = $dt->format("Y-m-d");      
     //echo "---->>>  ".$fechaIngreso; 
     ?>
    <script>
        $( function() {
          $( "#datepicker" ).datepicker({
             showWeek: true,
             firstDay: 1
          });            
          //$( "#datepicker" ).datepicker();
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
            var cantidadSemanas = 53;
            
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
        
        function verificaActsVsFrec() {
                var continuar = true;
                var valorFrec = document.getElementById("frecuencia_actividad").value;
                var select = document.getElementById("cboActividades");
                var length = select.options.length;
                var j=1;
                for (i = 0; i < length-1; i++) {   
                    var style = window.getComputedStyle(select.options[i]);
                    if (style.display !== 'none') {
                        if (select.options[i].selected) {
                            var frecActCbo = select.options[i].getAttribute("frec");                        
                            if (frecActCbo != valorFrec) {
                                alert("La frecuencia de las actividades no coincide con la frecuencia seleccionada, verifica la actividad " + j);
                                continuar = false;
                                break;
                            }
                        }
                    }                         
                }
                return continuar;
        }
        
        function armaCalendarioFinal() {
            //verifica frecuencias de actividades contra la establecida
            if (!verificaActsVsFrec()) {
                return false;
            }   
            
            //verifica que haya renglones seleccionados
            var table = document.getElementById("tblCalendarioFinal");
            var noRenglones = table.rows.length;
            if (noRenglones < 2) {
                alert("No existen valores en la tabla");
                return false;
            }
            
			
            //verifica cantidad de fechas seleccionadas de acuerdo a tipo de frecuencia
            var valorFrec = document.getElementById("frecuencia_actividad").value;
            var renglonesSeleccionados = 0;
            if (valorFrec != "null") {
                for(var i=1;i<noRenglones;i++) {
                    if (document.getElementById("chk" + i).checked == true) {
                        renglonesSeleccionados++;
                    }
                }    
                var continuar = false;
                switch (valorFrec) {
                    case "Mensual":
                            if (renglonesSeleccionados != 12) {
                                alert("Debes seleccionar 12 fechas, seleccionaste: " + renglonesSeleccionados);
                                return false;
                            }
                            break;
                    case "Trimestral":
                            if (renglonesSeleccionados != 4) {
                                alert("Debes seleccionar 4 fechas, seleccionaste: " + renglonesSeleccionados);
                                return false;
                            }
                            break;
                    case "Semestral":
                            if (renglonesSeleccionados != 2) {
                                alert("Debes seleccionar 2 fechas, seleccionaste: " + renglonesSeleccionados);
                                return false;
                            }
                            break;          
                    case "Anual":
                            if (renglonesSeleccionados != 1) {
                                alert("Debes seleccionar 1 fechas, seleccionaste: " + renglonesSeleccionados);
                                return false;
                            }
                            break;                               
                }
            } else {
                alert("No hay frecuencia seleccionada");
                return false;
            }
            //fin verifica cantidad de acuerdo a tipo de frecuencia

            //arma cadena de cboActividades a enviar para guardado
            var cadenaFinalMantenimientosStr = "";
            var cadenaFinalMantenimientosGlobal = "";
            
            var idResponsable;
            var idMaquina;
            
            var f = document.getElementById("numero_maquina");
            var nombreMaqSelect = document.getElementById("nombreMaquina");
            var nombreMaqXY = nombreMaqSelect.options[nombreMaqSelect.selectedIndex].getAttribute("codigo1");
			var j1=0
            for(var k=0;k<maquinasArray.length;k++) {
                var maqsArrayTemp = maquinasArray[k].split("|");
                if ((f.options[f.selectedIndex].text == maqsArrayTemp[1]) &&
                    (nombreMaqXY == maqsArrayTemp[3]) ){
                    idMaquina = maqsArrayTemp[0];
                    idResponsable = maqsArrayTemp[2];
                }
            }
            var x1 = document.getElementById("nombreMaquina").selectedIndex;
            var y1 = document.getElementById("nombreMaquina").options;
            
            var idActividad = document.getElementById("cboActividades").value;
            
            for(var i=1;i<noRenglones;i++) {
                 if (document.getElementById("chk" + i).checked == true) {
                    var select = document.getElementById("cboActividades");
                    var length = select.options.length;
                    for (k = 0; k < length; k++) {   
                        var style = window.getComputedStyle(select.options[k]);
                        if (style.display !== 'none') {
                            if (select.options[k].selected) {
                                var idActTemp = select.options[k].getAttribute("value"); 
                                
                                cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + "Semana " + i + "|";
                                cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + idResponsable + "|"; 
                                cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + idMaquina + "|"; 
                                cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + "NO ATENDIDA" + "|"; 
                                cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + "" + "|"; 
                                cadenaFinalMantenimientosStr = cadenaFinalMantenimientosStr + idActTemp + "|"; 

                                cadenaFinalMantenimientosGlobal = cadenaFinalMantenimientosGlobal + cadenaFinalMantenimientosStr + "@@@";
                                cadenaFinalMantenimientosStr = "";
                            }
                        }                         
                    }            
                 }
            }
            
            document.getElementById('calendarioFinalHidden').value = cadenaFinalMantenimientosGlobal;    
            //alert(document.getElementById('calendarioFinalHidden').value);
            return true;
        }
        
        var arregloDeMaquinas = new Array();
        function llenaArrayMaquinas(){
            var maqs = document.getElementById("nombreMaquinaTemp1");
            for (var i = 0; i < maqs.options.length; i++) {
                var idMaq  = maqs.options[i].getAttribute("value");
                var numMaq = maqs.options[i].getAttribute("numMaquina");
                var idResponsable1 = maqs.options[i].getAttribute("idResponsable");
                var nomMaq1 = maqs.options[i].text;
                var renglon = idMaq + "|" + numMaq + "|" + idResponsable1 + "|" + nomMaq1 + "|";
                maquinasArray[i] = renglon;
            }   
        }    
        
    </script>
</head>
<body>      
    <div class="container">
        <div class="row-fluid">
            <center><h3>Parámetros Generales</h3></center>
            <div class="col-sm-4">
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
                
                <select class="form-control" name="nombreMaquinaTemp1" id="nombreMaquinaTemp1" style="display:none">
                    <?php 
                        echo "<script>maquinasArray = [];</script>";
                        foreach($maquinasSimple as $fila) {
                            echo "<option idResponsable=".$fila->{'responsable_maquina'}." numMaquina='".$fila->{'numero_maquina'}."' value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}."</option>";
                        } 
                    ?>
                </select>
                <script>llenaArrayMaquinas();</script>
            </div> 
            <div class="col-sm-4">
                <select class="form-control" name="numero_maquina" id="numero_maquina" required>
                    <option value="">N&uacute;mero de Máquina...</option>
                    <?php
                        $elementos2 = array("-2");
                        foreach($maquinas as $fila) {
                            $w = array_search($fila->{'nombre_maquina'}.$fila->{'numero_maquina'},$elementos2);
                            if ($w == false){
                                array_push($elementos2,$fila->{'nombre_maquina'}.$fila->{'numero_maquina'});
                                echo "<option codigo1='".$fila->{'nombre_maquina'}."' value=".$fila->{'idMaquina'}.">".$fila->{'numero_maquina'}."</option>";
                            }    
                        }    
                    ?>
                </select>       
            </div>             
            <div class="col-sm-4">
                <input type="text" class="form-control" id="frecuencia_actividad" name="frecuencia_actividad" 
                       placeholder="Frecuencia" disabled required>
            </div>           
       </div>
            
        <br>
       <div class="row-fluid"> 
            <p style="color: blue;font-size: 16px;font-weight: bold"> &nbsp;&nbsp; &nbsp;&nbsp;Actividades
            <div class="col-sm-12">
                <select style="height: 150px;" multiple class="form-control" name="cboActividades" id="cboActividades" onchange="muestraFrecuencia()" required>
                    <option value="">Actividad...</option>
                    <?php foreach($actividades as $fila) {
                        echo "<option codigo='".$fila->{'nombre_maquina'}."' frec=".$fila->{'frecuencia'}." value=".$fila->{'idActividad'}.">".$fila->{'descripcion_actividad'}."</option>";
                    } ?>
                </select>       
            </div> 
            </p>
        </div>    
            
            
        <div class="row-fluid">
            <br><br><br><br>
            <center><h2>FECHAS</h2></center>
            <div class="col-md-4">
                <table class="table"><tr><td>
                <form onsubmit="javascript: return armaCalendarioFinal()" action="<?php echo base_url();?>index.php/mantenimiento_controller/nuevoMantenimientoFromFormulario" method="post">
                    <input type="hidden" name="calendarioFinalHidden" id="calendarioFinalHidden" />
                    <input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
                </form>                        
                </td>
                <td>
                <button class="btn btn-primary" id="myBtn" onclick="verificaActividades();">Ver Actividades</button>
                </td></tr></table>
                
                <div id="datepicker" style="position: fixed;">
                </div>  
            </div>
            <div class="col-md-1">
            </div>            
            <div class="col-md-7">
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
    
    
    <div id="myModal" class="modal">
      <div class="modal-content">
        <p class="close">&times;</p>
        <br>
        <h3 id='tituloActBusq'></h3>
        <table class="table table-hover" id="tblActsBusq">            
        </table>
      </div>
    </div>    
    
<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
    /* Obtiene datos para la consulta */
    var idResponsable;
    var idMaquina;
    
    var f = document.getElementById("numero_maquina");
    var nombreMaqSelect = document.getElementById("nombreMaquina");
    var nombreMaqXY = nombreMaqSelect.options[nombreMaqSelect.selectedIndex].getAttribute("codigo1");
    var numMaqElegido =  f.options[f.selectedIndex].text;
    
    var select = document.getElementById("cboActividades");
    var idActTemp = select.options[select.selectedIndex].getAttribute("value");
    
    for(var k=0;k<maquinasArray.length;k++) {
        var maqsArrayTemp = maquinasArray[k].split("|");
        if ((f.options[f.selectedIndex].text == maqsArrayTemp[1]) &&
            (nombreMaqXY == maqsArrayTemp[3]) ){
            idMaquina = maqsArrayTemp[0];
            idResponsable = maqsArrayTemp[2];
        }
    }
    /* Obtiene datos para la consulta */
    
    if ((idMaquina == null) || (idResponsable == null)){
        alert("Seleccionada una máquina o verifica el responsable de esa máquina.");
        return;
    } else {
        /* Realiza la consulta ajax */
        var opcion = prompt("Consulta General: 1, Consulta por Actividad: 2","");
        if ((opcion == null) || (opcion == "")) {
            alert("Debes elegir un tipo de consulta");
            return;
        }
        
        jQuery.ajax({
            url: "<?php echo base_url(); ?>/consultas_ajax/consultaActividadesEnMantenimientos.php?idMaquina="+idMaquina+"&idResponsable="+idResponsable+"&idActTemp="+idActTemp+"&opcion="+opcion,
            cache: false,
            contentType: "text/html; charset=UTF-8",
            success: function(response){
                document.getElementById('tituloActBusq').innerHTML = "Actividades encontradas. Máquina: " + nombreMaqXY + " Número: " + numMaqElegido;
                //Agrega info a Tabla
                var table = document.getElementById("tblActsBusq");
                table.innerHTML = "";
                //inserta el encabezado
                var row = table.insertRow(0);
                row.id = 0;
                var cell0 = row.insertCell(0);
                var cell1 = row.insertCell(1);
                cell0.innerHTML = "Semana";
                cell0.style.fontSize = "x-large";
                cell0.backgroundColor="#CCC";
                cell1.innerHTML = "Actividad";
                cell1.style.fontSize = "x-large";

                var noRenglones;
                row;
                var arrayActs = response.split("@@");
                for (i=0; i<(arrayActs.length) - 1; i++) {
                    var renglonArray = arrayActs[i].split("|");
                    //reemplaza por acentos
                    renglonArray[1] = renglonArray[1].replace("\\u00e1","á");
                    renglonArray[1] = renglonArray[1].replace("\\u00e9","é");
                    renglonArray[1] = renglonArray[1].replace("\\u00ed","í");
                    renglonArray[1] = renglonArray[1].replace("\\u00f3","ó");
                    renglonArray[1] = renglonArray[1].replace("\\u00f3","ó");
                    renglonArray[1] = renglonArray[1].replace("\\u00fa","ú");
                    renglonArray[1] = renglonArray[1].replace("\\u00c1","Á");
                    renglonArray[1] = renglonArray[1].replace("\\u00c9","É");
                    renglonArray[1] = renglonArray[1].replace("\\u00cd","Í");
                    renglonArray[1] = renglonArray[1].replace("\\u00d3","Ó");
                    renglonArray[1] = renglonArray[1].replace("\\u00da","Ú");
                    renglonArray[1] = renglonArray[1].replace("\\u00f1","ñ");
                    renglonArray[1] = renglonArray[1].replace("\\u00d1","Ñ");

                    noRenglones = table.rows.length;   
                    row = table.insertRow(noRenglones);
                    row.id = noRenglones;
                    var cell0 = row.insertCell(0);
                    var cell1 = row.insertCell(1);
                    cell0.innerHTML = renglonArray[0];
                    cell1.innerHTML = renglonArray[1];

                    //document.getElementById(row.id).style.border = "3px solid #0000FF";
                }
                table.style.border = "3px solid red";
                
                
                //$('#treeviewConsulta').treeview({data: response});
                modal.style.display = "block";
            },
            error: function(response){
                alert("Error");
            }
        });	  
        document.getElementById('tituloActBusq').innerHTML = "";
    }
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>    
    
    
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