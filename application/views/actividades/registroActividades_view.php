<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
    
  
    <script>
        var actividadesArray = [];
        
        function cambiaAActividad(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                document.getElementById("descripcion_actividad").focus();
            }            
        }
               
        function agregarDatoTabla(){
            var descripcion_actividad = document.getElementById("descripcion_actividad").value;
            var e = document.getElementById("nombreMaquina");
            var nombreMaquina = e.options[e.selectedIndex].text;
            var frecuencia = document.getElementById("frecuencia").value;
            if ((descripcion_actividad == "") || (nombreMaquina == "") || (frecuencia == "")) {
                alert("Completa los campos");
                return;
            }
            var table = document.getElementById("tablaActividades");
            var noRenglones = table.rows.length;   
            var row = table.insertRow(noRenglones);
            row.id = noRenglones;
            var cell0 = row.insertCell(0);
            var cell1 = row.insertCell(1);
            var cell2 = row.insertCell(2);
            var cell3 = row.insertCell(3);
            var cell4 = row.insertCell(4);                

            cell0.innerHTML = row.id;
            cell1.innerHTML = descripcion_actividad;
            cell2.innerHTML = nombreMaquina;    
            cell3.innerHTML = frecuencia; 
            cell4.innerHTML = "<a href='#'>" +
                        "<img src='<?php echo base_url(); ?>/images/sistemaicons/modificar.ico' " +
                        "alt='Editar' title='Editar' onclick='editarRenglon(" + row.id + ")'/></a> &nbsp;&nbsp;" +
                        "<a href='#'><img src='<?php echo base_url(); ?>/" +
                        "images/sistemaicons/borrar2.ico' alt='Borrar' title='Borrar' onclick='javascript: return borraRenglon(" + row.id + ")'/></a>";     
            document.getElementById('nombreMaquina').disabled = true;
            document.getElementById("descripcion_actividad").value = "";
            document.getElementById("descripcion_actividad").focus();
        }        
        
        function agregarDatoTablaEnter(e){   
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                if (document.getElementById("frecuencia").value != "") { 
                    var descripcion_actividad = document.getElementById("descripcion_actividad").value;
                    var e = document.getElementById("nombreMaquina");
                    var nombreMaquina = e.options[e.selectedIndex].text;
                    var frecuencia = document.getElementById("frecuencia").value;
                    if ((descripcion_actividad == "") || (nombreMaquina == "") || (frecuencia == "")) {
                        alert("Completa los campos");
                        return;
                    }
                    var table = document.getElementById("tablaActividades");
                    var noRenglones = table.rows.length;   
                    var row = table.insertRow(noRenglones);
                    row.id = noRenglones;
                    var cell0 = row.insertCell(0);
                    var cell1 = row.insertCell(1);
                    var cell2 = row.insertCell(2);
                    var cell3 = row.insertCell(3);
                    var cell4 = row.insertCell(4);                

                    cell0.innerHTML = row.id;
                    cell1.innerHTML = descripcion_actividad;
                    cell2.innerHTML = nombreMaquina;    
                    cell3.innerHTML = frecuencia; 
                    cell4.innerHTML = "<a href='#'>" +
                                "<img src='<?php echo base_url(); ?>/images/sistemaicons/modificar.ico' " +
                                "alt='Editar' title='Editar' onclick='editarRenglon(" + row.id + ")'/></a> &nbsp;&nbsp;" +
                                "<a href='#'><img src='<?php echo base_url(); ?>/" +
                                "images/sistemaicons/borrar2.ico' alt='Borrar' title='Borrar' onclick='javascript: return borraRenglon(" + row.id + ")'/></a>";     
                    document.getElementById('nombreMaquina').disabled = true;
                    document.getElementById("descripcion_actividad").value = "";
                    document.getElementById("descripcion_actividad").focus();
                } else {
                    document.getElementById("frecuencia").focus();
                }
            }    
        }      
        
        function borraRenglon(idRow){
            var r = confirm("¿Realmente deseas borrar?");
            if (r) {  
                var row = document.getElementById(idRow);
                row.parentNode.removeChild(row);
            }    
            return false;
        }        
        
        function editarRenglon(idRow){
            document.getElementById("descripcion_actividad").value = document.getElementById("tablaActividades").rows[idRow].cells[1].innerHTML;
            document.getElementById("frecuencia").value = document.getElementById("tablaActividades").rows[idRow].cells[3].innerHTML;
            var row = document.getElementById(idRow);
            row.parentNode.removeChild(row);
        } 
        
        function armaCadenaActividades() {
            var table = document.getElementById("tablaActividades");
            var noRenglones = table.rows.length;
            if (noRenglones < 2) {
                alert("No existen valores en la tabla");
                return false;
            }
            
            //arma cadena de actividades a enviar para guardado
            var actividadadesStr = "";
            var actividadadesBlobal = "";
            for(var i=1;i<noRenglones;i++) {
                actividadadesStr = actividadadesStr + document.getElementById("tablaActividades").rows[i].cells[1].innerHTML + "|";
                actividadadesStr = actividadadesStr + document.getElementById("tablaActividades").rows[i].cells[3].innerHTML + "|";
                actividadadesStr = actividadadesStr + document.getElementById("tablaActividades").rows[i].cells[2].innerHTML + "|";
                actividadadesBlobal = actividadadesBlobal + actividadadesStr + "@@@";
                actividadadesStr = "";
            }
            document.getElementById('actividadesHidden').value = actividadadesBlobal;
            document.getElementById('maquinaHidden').value = document.getElementById("nombreMaquina").value;  
            //alert(actividadadesBlobal);
            //libera local storage 
            localStorage.removeItem(capturaTotal);
            //libera local storage total
            //localStorage.clear();
            return true;
        }
        
        function mensaje() {
            if (document.getElementById('registroCorrecto').innerHTML != "") {
                setTimeout(function(){ location.reload(); }, 1000);
            }
            llena_arreglo_actividades();
            muestraValoresArreglo();
        }
        
        function muestraValoresArreglo() {
            for (j=0; j < actividadesArray.length; j++) {
                alert(actividadesArray[j]);
            }
        }
        
        function borraTablaActCargadas() {
            var table = document.getElementById("tbl_actividades_cargadas");
            var noRenglones = table.rows.length;
            if (noRenglones < 2) {
                return false;
            }
            for(var i=1;i<noRenglones;i++) {
                var row = document.getElementById(i);
                row.parentNode.removeChild(row);              
            }
        }
        
        function muestraActividades(){
            borraTablaActCargadas();
            
            //valor de maquina a mostrar en la  table
            var e = document.getElementById("nombreMaquina");
            var nomMaq = e.options[e.selectedIndex].innerHTML;
            
            for (j=0; j < actividadesArray.length; j++) {
                var celdas = actividadesArray[j].split("|");
                if (celdas[0] == nomMaq) {
                    var table = document.getElementById("tbl_actividades_cargadas");
                    var noRenglones = table.rows.length;   
                    var row = table.insertRow(noRenglones);
                    row.id = noRenglones;
                    var cell0 = row.insertCell(0);
                    var cell1 = row.insertCell(1);
                    var cell2 = row.insertCell(2);
                    
                    cell0.innerHTML = celdas[0];
                    cell1.innerHTML = celdas[1];
                    cell2.innerHTML = celdas[2];    
                }
            }
        }
        
        function llena_arreglo_actividades() {
            var table = document.getElementById("tbl_actividades_cargadas");
            var noRenglones = table.rows.length;
            if (noRenglones < 2) {
                //alert("No existen valores en la tabla");
                return false;
            }
            
            
            for(var i=1;i<noRenglones - 1;i++) {
                actividadesArray[i - 1] =  document.getElementById("tbl_actividades_cargadas").rows[i].cells[0].innerHTML 
                        + "|" + document.getElementById("tbl_actividades_cargadas").rows[i].cells[1].innerHTML
                        + "|" + document.getElementById("tbl_actividades_cargadas").rows[i].cells[2].innerHTML
                        + "|";
            }
            //Para captura de pantalla
            regresaCaptura();
        }
        
    </script>
</head>
<body onload="llena_arreglo_actividades();">
    <?php //echo "<script>llena_arreglo_actividades();</script>"; ?>
    <div class="container-fluid"> 
        <div class="row-fluid">
            <br><br>            
            <?php 
                $correcto = $this->session->flashdata('correcto');
                if ($correcto) { ?>
            <span id="registroCorrecto" style="color:blue;"><?= $correcto ?></span>
            <?php } ?>
            <br><br><br><br>
            <h4>Registro de Actividades</h4>
            
                    <div class="form-group">
                        <br>
                        <label class="control-label col-sm-2" for="nombreMaquina">M&aacute;quina:</label>
                      <div class="col-sm-10">
                          <select onchange="muestraActividades()" class="form-control" name="nombreMaquina" id="nombreMaquina" required autofocus>
                                <option value="">Máquina...</option>
                                <?php
                                    $elementos2 = array("-2");
                                    foreach($maquinas as $fila) {
                                        $w = array_search($fila->{'nombre_maquina'},$elementos2);
                                        if ($w == false){
                                            array_push($elementos2,$fila->{'nombre_maquina'});
                                            echo "<option value=".$fila->{'nombre_maquina'}.">".$fila->{'nombre_maquina'}."</option>";
                                        }    
                                    } 
                                ?>
                            </select>
                      </div>					  
                    </div> 
            
                    <div class="form-group">
                        <br><br>
                      <label class="control-label col-sm-2" for="descripcion_actividad">Descripci&oacute;n:</label>
                      <div class="col-sm-10">
                          <textarea class="form-control" rows="3" id="descripcion_actividad" class="form-control" onkeypress="agregarDatoTablaEnter(event)" placeholder="Descripción Actividad" required></textarea>
                          <br>                       
                      </div>					  
                    </div>             

                    
                    <div class="form-group">
                      
                      <label class="control-label col-sm-2" for="frecuencia">Frecuencia :</label>
                      <div class="col-sm-10">
                            <select class="form-control" name="frecuencia" id="frecuencia" required>
                                <option value="">Seleccionar Frecuencia</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Trimestral">Trimestral</option>
                                <option value="Semestral">Semestral</option>
                                <option value="Anual">Anual</option>
                                <option value="Ciclos">Ciclos</option>
                            </select>
                          <br><br>
                      </div>					  
                    </div>             

                    <div class="form-group">
                      <br>  
                      <div class="col-sm-3">
                            <input type="button" class="btn btn-primary" value="AGREGAR" onclick="agregarDatoTabla()" />
                      </div>	
                      <div class="col-sm-3">
                            <form onsubmit="javascript:return armaCadenaActividades()" 
                                  action="<?php echo base_url();?>index.php/actividades_controller/nuevoActividadesFromFormulario" method="post">
                                <input type="hidden" name="actividadesHidden" id="actividadesHidden" /> 
                                <input type="hidden" name="maquinaHidden" id="maquinaHidden" /> 
                                <input type="submit" name="submit" class="btn btn-primary" value="GUARDAR" />
                            </form>
                      </div>	
                      <div class="col-sm-3">
                            <a href="<?php echo base_url();?>index.php/actividades_controller/mostrarActividades">
                            <button type="button" class="btn btn-primary">REGRESAR</button>
                            </a>    
                      </div>	                        
                      <div class="col-sm-3">
                          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal" 
                                  onclick="guardaCapturaTemporal();">Ayuda</button>
                          <br><br><br><br>
                      </div>	                        
                    </div>             
        </div>
        
        <div class="row-fluid">
            <h3 style="text-align:center">Actividades por Guardar:</h3>
            <div class="table-responsive">
                <table class="table table-hover table-dark" id="tablaActividades">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Descripción Actividad</th>
                      <th scope="col">M&aacute;quina</th>
                      <th scope="col">Frecuencia</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div>
     
        </div>   
        
        <div class="row-fluid">
            <br><br>
            <h3 style="text-align: center">Actividades cargadas previamente</h3>
            <div class="table-responsive">     
                <table class="table table-dark" border="1" id="tbl_actividades_cargadas">
                    <thead>
                        <tr>
                            <th>M&aacute;quina</th>
                            <th>Actividad</th>
                            <th>Frecuencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($actividades) {
                            $i=1;
                            foreach($actividades as $fila) { 
                                ?>
                                <tr id="<?php echo $i; ?>">
                                    <td><?php echo $fila->{'nombre_maquina'} ?></td>
                                    <td><?php echo $fila->{'descripcion_actividad'} ?></td>
                                    <td><?php echo $fila->{'frecuencia'} ?></td>
                                </tr>
                              <?php 
                              $i++;  
                            }   
                        }
                        ?>
                    </tbody>
                </table>
                
                <?php 
                 echo "<script>
                     if (actividadesArray.length <= 0) {
                        llena_arreglo_actividades();
                     }
                </script>";
                ?>                
            </div>            
        </div>
    </div>
    
</body>


    <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onkeydown="focusToClose(event);" href="#" target="_self"><span class="text">&times;</button>
            <h4 class="modal-title">
                <img src="<?php echo base_url(); ?>/images/sistemaicons/help1.ico" />
                <img src="<?php echo base_url(); ?>/images/sistemaicons/help2.ico" />
                Como generar Actividades
            </h4>
          </div>
          <div class="modal-body">
            <p>Cuando ingresas a esta opción se muestran por defecto todas las actividades cargadas </p>
            <p>como  se ve en la siguiente imagen: </p>
            <br>
            <img src="<?php echo base_url(); ?>/images/ayuda1.png" />
            <br>
            <p>Debes comenzar por seleccionar la máquina a la cual deseas agregarle actividades,</p>
            <p>despu&eacute;s llena el campo descripción y por ultimo selecciona la frecuencia.</p>
            <p>Puedes observar en la siguiente imagen la ubicación de cada uno de los controles</p>
            <p>mencionados anteriormente</p>
            <br>
            <img src="<?php echo base_url(); ?>/images/ayuda2.png" />
            <br><br>
            <p>Al terminar de hacer tu captura presiona el botón agregar para que se vayan</p>
            <p>guardando de manera temporal cada una de las actividades que vayas agregando.</p>
            <p>Cuando termines de hacer toda tu captura podrás guardar de manera definitiva</p>
            <p>toda la información pulsando el botón Guardar.</p>
            <br>
            <p>Es importante mencionar que una vez que hayas agregado tu primera actividad</p>
            <p>de manera temporal se bloqueará el combo de selección de máquina para evitar</p>
            <p>posibles errores de captura. Es decir cada registro de actividades es por</p>
            <p>máquina pero sólo se hará el proceso una sola ocasión para todas las máquinas</p>
            <p>del mismo nombre.</p> 
            <p>Tambien puedes regresar a la pantalla donde de Admnistraci&oacute;n de actividades</p>
            <p>presionando el botón regresar.</p>
            <br>
            <p>Por último debes observar que en la primera ocasión que seleccionaste una</p>
            <p>máquina se filtró la información en la tabla de Actividades guardadas </p>
            <p>previamente para que puedas verificar si la captura que estás realizando</p>
            <p>no se ha guardado previamente.</p>
            <br>
            <p>Nota,. Para cualquier aclaración o duda dirigirse al personal indicado.</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
  </div>

  
  
   <script>
        //var captura = [];                       
        function guardaCapturaTemporal(){
            var e = document.getElementById("nombreMaquina");
            var nomMaq = e.options[e.selectedIndex].innerHTML;            
            var table = document.getElementById("tablaActividades");
            var noRenglones = table.rows.length;
            
            var capturaParcial = "";
            var capturaTotal = "";
            if (noRenglones >= 2) {
                for(var i=1;i<noRenglones;i++) {
                    capturaParcial = document.getElementById("tablaActividades").rows[i].cells[0].innerHTML 
                            + "|" + document.getElementById("tablaActividades").rows[i].cells[1].innerHTML
                            + "|" + document.getElementById("tablaActividades").rows[i].cells[2].innerHTML
                            + "|" + document.getElementById("tablaActividades").rows[i].cells[3].innerHTML
                    capturaTotal = capturaTotal + capturaParcial + "@@@";
                    capturaParcial = "";
                }
            }
            
            // Check browser support
            if (typeof(Storage) !== "undefined") {
              // Store
              localStorage.setItem("capturaTotal", capturaTotal);
            } else {
              alert("Sorry, your browser does not support Web Storage...");
            }            
            
            
        }
        
        function regresaCaptura(){
            var regresoCaptura = localStorage.getItem("capturaTotal");
            var capturaTotalArray = regresoCaptura.split("@@@");
            var capturaParcialArray = [];
            for (var i=0; i < capturaTotalArray.length - 1; i++) {
                capturaParcialArray = capturaTotalArray[i].split("|");
            }
            //selecciona la maquina en la que se habia quedado
            var cont = 0;
            Array.from(document.querySelector("#nombreMaquina").options).forEach(function(option_element) {
                let option_text = option_element.text;
                if (option_text == capturaParcialArray[2]) {
                    document.getElementById('nombreMaquina').selectedIndex = cont;
                    return;
                }
                cont++;
            });
            localStorage.removeItem("capturaTotal");
            muestraActividades();
            for (var i=0; i < capturaTotalArray.length - 1; i++) {
                capturaParcialArray = capturaTotalArray[i].split("|");
                var table = document.getElementById("tablaActividades");
                var noRenglones = table.rows.length;   
                var row = table.insertRow(noRenglones);
                row.id = noRenglones;
                var cell0 = row.insertCell(0);
                var cell1 = row.insertCell(1);
                var cell2 = row.insertCell(2);
                var cell3 = row.insertCell(3);
                var cell4 = row.insertCell(4);                

                cell0.innerHTML = capturaParcialArray[0];
                cell1.innerHTML = capturaParcialArray[1];
                cell2.innerHTML = capturaParcialArray[2];    
                cell3.innerHTML = capturaParcialArray[3]; 
                cell4.innerHTML = "<a href='#'>" +
                            "<img src='<?php echo base_url(); ?>/images/sistemaicons/modificar.ico' " +
                            "alt='Editar' title='Editar' onclick='editarRenglon(" + row.id + ")'/></a> &nbsp;&nbsp;" +
                            "<a href='#'><img src='<?php echo base_url(); ?>/" +
                            "images/sistemaicons/borrar2.ico' alt='Borrar' title='Borrar' onclick='javascript: return borraRenglon(" + row.id + ")'/></a>";     
            }
            //alert(capturaTotalArray.length);
            if (capturaTotalArray.length > 1) {
                document.getElementById('nombreMaquina').disabled = true;
            }
        }
        
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
            location.reload();
        });
  </script> 
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>   

</html>