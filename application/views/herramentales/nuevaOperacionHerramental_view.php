<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">

<style>    

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
<script>
    function consultaAjaxPromesa(idAplicador) {
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/consultaUltimoGolpesAplicadorById.php?idAplicador="+idAplicador,
                cache: false,
                success: function(response){
                    resolve(response);
                },
                error: function(error){
                    console.log("llegaaaaaaaaaa");
                    reject(error);
                },
            })
        });
    }
    
    function validarMayor(texto){
        if (texto.length > 6) {
           //alert('El texto es muy largo');
           document.getElementById('contadorA').disabled = true;
           document.getElementById('aplicador').focus();
           return false;
       }
       return true;
    }

    function validarMenor(texto){
        if (texto.length < 7) {
           alert('Coloca los ceros faltantes antes del número real. ');
           document.getElementById('contadorA').focus();
           return false;
        } else {
            return true;
        }
    }
    
    function activaContador(){
        document.getElementById('contadorA').value = "";
        document.getElementById('contadorA').disabled = false;
        document.getElementById('contadorA').focus();
        
        document.getElementById("chk1").checked = false;
        document.getElementById("chk2").checked = false;
        document.getElementById("chk3").checked = false;
        document.getElementById("chk4").checked = false;
        document.getElementById("chk5").checked = false;
    }

    function desactivaContador(){
        document.getElementById('contadorA').value = "";
        document.getElementById('contadorA').disabled = true;
        document.getElementById('aplicador').focus();
        
        document.getElementById("chk1").checked = true;
        document.getElementById("chk2").checked = true;
        document.getElementById("chk3").checked = true;
        document.getElementById("chk4").checked = true;
        document.getElementById("chk5").checked = true;
    }
    
    function limpiaControles(){
        document.getElementById('optradio1').checked = false;
        document.getElementById('optradio2').checked = false;
        document.getElementById('contadorA').value = "";
        document.getElementById('contadorA').disabled = false;
        document.getElementById('aplicador').selectedIndex = 0;
        document.getElementById('observaciones').value = "";
        document.getElementById('chk1').checked = false;
        document.getElementById('chk2').checked = false;
        document.getElementById('chk3').checked = false;
        document.getElementById('chk4').checked = false;
        document.getElementById('chk5').checked = false;
        document.getElementById('observaciones').value = "";
    }
    
    function limpiaControlesMantto(){
        document.getElementById('chkMantto1').checked = false;
        document.getElementById('chkMantto2').checked = false;
        document.getElementById('chkMantto3').checked = false;
        document.getElementById('chkMantto4').checked = false;
        document.getElementById('chkMantto5').checked = false;
        document.getElementById('chkMantto6').checked = false;
        document.getElementById('chkMantto7').checked = false;
        document.getElementById('chkMantto8').checked = false;
        document.getElementById('txtObservacionesMantto').value = "";
        document.getElementById('fileMicroseccion').value = "";
    }
</script>
    
</head>
<body>      
    <div class="container">
        <div class="row-fluid">
            
            <div class="col-sm-12">
                    <center>
                    <table>
                        <tr>
                            <td>
                                <img src='<?php echo base_url();?>images/aplicador2.jpg' style='width: 100px;height: 120px;'/>
                            </td>
                            <td>
                                <h4 class="text-center" style="color:#cc0000;font-size: 24px;font-weight: bold;">Aplicadores</h4>
                            </td>
                        </tr>
                    </table>
                    </center>
                    <div class="form-group">
                        <h4 style="color:#cc0000;font-size: 18px;font-weight: bold;">Información del movimiento a realizar:</h4>
                        <div class="radio">
                            <table>
                                <tr>
                                    <td>
                                        <label style="display:none"><input type="radio" id='optradio1' onclick="activaContador();" name="optradio" required disabled>Entrada</label>
                                        <label style="display:none"><input type="radio" id='optradio2' onclick="desactivaContador();" name="optradio" required disabled>Salida</label>
                                        <p id='txtCiclos' style="display:none; background-color:#ccc; font-weight: bold; font-size: 17px;"></p>
                                    </td>
                                    <td>
                                        <p id='txtYipoMovimiento' style="display:none; margin-left: 15px; background-color:#ccc; font-weight: bold; font-size: 17px;"></p>
                                    </td>
                                    <td>
                                        <p id='txtNuevoMovimiento' style="display:none; margin-left: 15px; background-color:#ccc; font-weight: bold; font-size: 17px;"></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div> 

                    <div class="form-group">
                        <div class="col-sm-12">
                            <h5 style="color:#cc0000;font-size: 14px;font-weight: bold;">Selecciona el aplicador:</h5>
                            <select class="form-control" name="aplicador" id="aplicador" onchange="consultaUltimoMov(this.value)" required>
                                <option value="">Aplicador...</option>
                                <?php 
                                    foreach($aplicadores as $fila) {
                                        echo "<option value=".$fila->{'idAplicador'}.">".$fila->{'aplicador'}."</option>";
                                    } 
                                ?>
                            </select>
                            <br>
                        </div> 
                    </div>
                
                    <div class="form-group">
                      <div class="col-sm-12">
                          <h5 style="color:#cc0000;font-size: 14px;font-weight: bold;">Ingresa el número de Golpes:</h5>
                          <input onkeydown="javascript:return validarMayor(this.value)" type="number" class="form-control" id="contadorA" name="contadorA" id="contadorA" placeholder="Contador" required autofocus>
                          <br>
                      </div>					  
                    </div> 
                    
                    <div class="form-group">
                        <h5 style="color:#cc0000;font-size: 14px;font-weight: bold;">&nbsp;&nbsp; Observaciones</h5>
                        <textarea class="form-control" rows="3" id="observaciones" name="observaciones" id="observaciones"></textarea>
                    </div>
                
                    <br>
                    <div class="form-group">
                        <p><input style="width:20px;" type="checkbox" name="chk1" id="chk1" > Eliminar residuos ajenos al aplicador, deberá de quedar limpio en su totalidad.</p>
                        <p><input style="width:20px;" type="checkbox" name="chk2" id="chk2" > Asegurarse que todas las partes móviles presenten buena lubricación.</p>
                        <p><input style="width:20px;" type="checkbox" name="chk3" id="chk3" > Verificar que no exista desgaste en las partes o juegos excesivos en el ram.</p>
                        <p><input style="width:20px;" type="checkbox" name="chk4" id="chk4" > Revisión del sistema de avance (leva, dedo, baleros, guías). Realizar una carrera manual para comprobar su buen funcionamiento.</p>
                        <p><input style="width:20px;" type="checkbox" name="chk5" id="chk5" > Verificar sistema de yunques, matriz de corte, navaja, resorte y tope.</p>
                    </div>
                    
                    <div class="form-group">
                        <br>
                        <center>
                            <table>
                                <tr>
                                    <td>
                                        <input onclick="verificarEstado();" type="button" class="btn btn-primary center-block" value="Verificar Estado Aplicador" />
                                    </td>
                                    <td>
                                        <input style='margin-left:20px;' type="submit" class="btn btn-primary center-block" value="Guardar" name='submit' id='myBtn' />
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </div> 
            </div>	
        </div>
    </div>
    
    <div id="myModal" class="modal">
      <div class="modal-content">
        <h3 id='tituloActBusq' style="text-align:center;color: #cc3300">Registro de mantenimiento de Aplicador.  <p class="close">&times;</p></h3>
        <br>
        <h4 id='tituloActBusq' style="font-weight:bold;color: #cc3300">Actividades a realizar:</h4>
        <div class="form-group">
            <p><input style="width:20px;" type="checkbox" name="chkMantto1" id="chkMantto1"> Limpieza general del aplicador, use desengrasante para retirar elementos contaminantes.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto2" id="chkMantto2"> Revisión de componentes, para detección de desgastes o partes dañadas.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto3" id="chkMantto3"> Revisión de ram de aplicador, buscando juegos excesivos o desgastes.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto4" id="chkMantto4"> Revisión del sistema de avance, (leva, dedo, baleros, guías).</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto5" id="chkMantto5"> Verificar sistema de yunques, matriz de corte, navaja, resorte y tope.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto6" id="chkMantto6"> Verificar desgastes y/o posibles daños en formadores.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto7" id="chkMantto7"> Lubricación del sistema en general.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto8" id="chkMantto8"> Entregar muestra a calidad para microsección.</p>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Observaciones:</label>
            <textarea class="form-control" id="txtObservacionesMantto" rows="3"></textarea>
        </div>        
        <br>
        <div class="form-group">
          <label for="exampleFormControlFile1">Selecciona el archivo de la microsección: </label>
          <input type="file" class="form-control-file" accept="application/pdf, application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" id="fileMicroseccion" >
        </div>        
        <div class="modal-footer">
            <input type="hidden" id='manttoTempHidden' />
          <button type="button" id="btnGuardarMantto" class="btn btn-primary" data-dismiss="Guardar">Guardar</button>
          <button type="button" id="btnCierraModal" class="btn btn-info" data-dismiss="Cerrar" onclick="cierraModal()">Cerrar</button>
        </div>
      </div>
    </div>    
    
    <script>
    var ciclosRegistrados = 0;
    var idUsuario = '<?php echo $usuario_herramental->{'idUsuario'}; ?>';
    var mantenimiento = '';
    var movimiento = '';
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("myBtn");
    var span = document.getElementsByClassName("close")[0];
    btn.onclick = function() {
        var idAplicador = document.getElementById("aplicador").value;
        var contadorActual = document.getElementById("contadorA").value;
        
        //verifica que exista la captura de los ciclos actual
        if ((contadorActual == "") && (document.getElementById('optradio1').checked == true)) {
            alert("Debes registrar el número de ciclos actual.");
            document.getElementById('contadorA').value = "";
            document.getElementById('contadorA').disabled = false;
            document.getElementById('contadorA').focus();
            return;
        }
        
        //verifica que exista un aplicador seleccionado
        if (idAplicador == "") {
            alert("Debes seleccionar un aplicador");
            return;
        }
        
        if (document.getElementById('optradio1').checked == false && document.getElementById('optradio2').checked == false) {
            alert("Selecciona una operación.");
            return;
        }
        
        consultaAjaxPromesa(idAplicador)
          .then((response) => {
                var respuestaSinComillas = response.replace("\"", "");
                respuestaSinComillas = respuestaSinComillas.replace("\"", "");
                if (parseInt(contadorActual,10) <= parseInt(respuestaSinComillas)) {
                    alert('El número de ciclos ingresado es menor o igual al último registro guardado. Ingresa nuevamente la información.');
                    document.getElementById('contadorA').value = "";
                    document.getElementById('contadorA').disabled = false;
                    document.getElementById('contadorA').focus();
                } else {
                      var contadorTemp = document.getElementById('contadorA').value;
                      if ((contadorTemp.length < 7) && (document.getElementById('optradio1').checked == true)) {
                         alert('Coloca los ceros faltantes antes del número real. ');
                         document.getElementById('contadorA').focus();
                         return;
                      }
                      contadorActual = contadorActual.replace(/^0+/, '');

                      //arma cadena del movimiento
                      movimiento = movimiento + idAplicador + "|";
                      movimiento = movimiento + idUsuario + "|";
                      movimiento = movimiento + contadorActual + "|";
                      if (document.getElementById('optradio1').checked == true) {
                          movimiento = movimiento + "Entrada" + "|";
                      }
                      if (document.getElementById('optradio2').checked == true) {
                          movimiento = movimiento + "Salida" + "|";
                      }
                      if (document.getElementById('chk1').checked == true) {
                          movimiento = movimiento + "1";
                      } else {
                          movimiento = movimiento + "0";
                      }
                      if (document.getElementById('chk2').checked == true) {
                          movimiento = movimiento + "1";
                      } else {
                          movimiento = movimiento + "0";
                      }
                      if (document.getElementById('chk3').checked == true) {
                          movimiento = movimiento + "1";
                      } else {
                          movimiento = movimiento + "0";
                      }
                      if (document.getElementById('chk4').checked == true) {
                          movimiento = movimiento + "1";
                      } else {
                          movimiento = movimiento + "0";
                      }
                      if (document.getElementById('chk5').checked == true) {
                          movimiento = movimiento + "1";
                      } else {
                          movimiento = movimiento + "0";
                      }
                      movimiento = movimiento + "|";
                      movimiento = movimiento + document.getElementById('observaciones').value + "|";
                      //fin arma cadena del movimiento

                      if (optradio1.checked == true) { //solo si es entrada se checa si ocupa mantenimieto
                          jQuery.ajax({
                              url: "<?php echo base_url(); ?>/consultas_ajax/consultaGolpesMantenimientosHerramentales.php?idAplicador=" + idAplicador + "&contadorActual=" + parseInt(contadorActual),
                              cache: false,
                              contentType: "text/html; charset=UTF-8",
                              success: function(response){
                                  //si ocupa mantenimento graba mantenimiento y movimiento
                                  if (response == 1) {
                                      document.getElementById('manttoTempHidden').value = movimiento;
                                      modal.style.display = "block";
                                  } else { ////si es entrada y no ocupa mantenimiento graba movimiento
                                      movimiento = movimiento + "1" + "|";
                                      jQuery.ajax({
                                          url: "<?php echo base_url(); ?>/consultas_ajax/grabaMovimientoAplicador.php?movimiento=" + movimiento,
                                          cache: false,
                                          contentType: "text/html; charset=UTF-8",
                                          success: function(response){
                                              alert("Registro de entrada exitoso.");
                                              document.getElementById('contadorA').value = '';
                                              document.getElementById('contadorA').disabled = false;
                                              document.getElementById('optradio1').checked = false;
                                              document.getElementById('optradio2').checked = false;
                                              document.getElementById('txtCiclos').style.display = 'none';
                                              document.getElementById('txtYipoMovimiento').style.display = 'none';
                                              document.getElementById('txtNuevoMovimiento').style.display = 'none';
                                              
                                          },
                                          error: function(response){
                                              alert("Error");
                                          }
                                      });	
                                      movimiento = "";
                                      limpiaControles();
                                      //limpiaControlesMantto();
                                  }
                              },
                              error: function(response){
                                  alert("Error");
                                  movimiento = "";
                              }
                          });	
                      } else { //si es salida graba movimiento
                          movimiento = movimiento + "2" + "|";
                          jQuery.ajax({
                              url: "<?php echo base_url(); ?>/consultas_ajax/grabaMovimientoAplicador.php?movimiento=" + movimiento,
                              cache: false,
                              contentType: "text/html; charset=UTF-8",
                              success: function(response){
                                  alert("Registro de salida exitoso.");
                                  document.getElementById('txtCiclos').style.display = 'none';
                                  document.getElementById('txtYipoMovimiento').style.display = 'none';
                                  document.getElementById('txtNuevoMovimiento').style.display = 'none';
                                  
                              },
                              error: function(response){
                                  alert("Error");
                              }
                          });	
                          movimiento = "";
                          limpiaControles();
                          limpiaControlesMantto();
                      }
                    
                } 
          })
          .catch((error) => {
          });
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    
    btnCierraModal.onclick = function() {
        modal.style.display = "none";
    }
    
    btnGuardarMantto.onclick = function() {
        if (!document.getElementById("fileMicroseccion").value) {
            alert('Debes seleccionar un archivo.');
            return;
        }
        
        //separo nombre del archivo para obtener la extension
        var archivoSelecc = document.getElementById("fileMicroseccion").value;
        var splitArchivo = archivoSelecc.split("\\");
        var nombreArchTemp = splitArchivo[splitArchivo.length - 1];
        var partesArchivo = nombreArchTemp.split(".");
        var extension = partesArchivo[partesArchivo.length - 1];
        
        //obtiene datos de movimiento
        var movimientoMantto = document.getElementById('manttoTempHidden').value;
        movimientoMantto = movimientoMantto + "0" + "|";
        movimientoMantto = movimientoMantto + extension + "|";
        
        //obtiene datos de mantenimiento
        var mantenimientoData = "";
        if (document.getElementById('chkMantto1').checked == true) {
            mantenimientoData = mantenimientoData + "1";
        } else {
            mantenimientoData = mantenimientoData + "0";
        }
        if (document.getElementById('chkMantto2').checked == true) {
            mantenimientoData = mantenimientoData + "1";
        } else {
            mantenimientoData = mantenimientoData + "0";
        }
        if (document.getElementById('chkMantto3').checked == true) {
            mantenimientoData = mantenimientoData + "1";
        } else {
            mantenimientoData = mantenimientoData + "0";
        }
        if (document.getElementById('chkMantto4').checked == true) {
            mantenimientoData = mantenimientoData + "1";
        } else {
            mantenimientoData = mantenimientoData + "0";
        }
        if (document.getElementById('chkMantto5').checked == true) {
            mantenimientoData = mantenimientoData + "1";
        } else {
            mantenimientoData = mantenimientoData + "0";
        }
        if (document.getElementById('chkMantto6').checked == true) {
            mantenimientoData = mantenimientoData + "1";
        } else {
            mantenimientoData = mantenimientoData + "0";
        }
        if (document.getElementById('chkMantto7').checked == true) {
            mantenimientoData = mantenimientoData + "1";
        } else {
            mantenimientoData = mantenimientoData + "0";
        }
        if (document.getElementById('chkMantto8').checked == true) {
            mantenimientoData = mantenimientoData + "1";
        } else {
            mantenimientoData = mantenimientoData + "0";
        }
        mantenimientoData = mantenimientoData + "|";
        mantenimientoData = mantenimientoData + document.getElementById('txtObservacionesMantto').value + "|";
        
        // envia archivo a servidor
        var file_data = $('#fileMicroseccion').prop('files')[0];   
        var form_data = new FormData();                  
        form_data.append('file', file_data);
        form_data.append('movimiento', movimientoMantto);
        form_data.append('mantenimientoData', mantenimientoData);
        $.ajax({
            url: "<?php echo base_url(); ?>/consultas_ajax/grabaMantenimientoAplicador.php", 
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success: function(response){
                alert("Mantenimiento guardado correctamente.");
                document.getElementById('txtCiclos').style.display = 'none';
                document.getElementById('txtYipoMovimiento').style.display = 'none';
                document.getElementById('txtNuevoMovimiento').style.display = 'none';
            },
            error: function(response){
                alert("Error");
            }
         });   
        mantenimientoData = "";
        document.getElementById('manttoTempHidden').value = "";
        movimientoMantto = ""; 
        movimiento = "";
        limpiaControles();
        limpiaControlesMantto();
        modal.style.display = "none";
        
    }
    
    function consultaUltimoMov(idAplicador){
        if ((idAplicador == "") || (idAplicador == null)) {
            document.getElementById('contadorA').value = '';
            document.getElementById('contadorA').disabled = false;
            document.getElementById('txtCiclos').style.display = 'none';
            document.getElementById('txtYipoMovimiento').style.display = 'none';
            document.getElementById('txtNuevoMovimiento').style.display = 'none';
            
            document.getElementById("chk1").checked = false;
            document.getElementById("chk2").checked = false;
            document.getElementById("chk3").checked = false;
            document.getElementById("chk4").checked = false;
            document.getElementById("chk5").checked = false;
            
        } else {
            document.getElementById('contadorA').disabled = true;
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/consultaUltimoGolpesAplicadorById_ConTipoMov.php?idAplicador=" + idAplicador,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    if (response != 0) {
                        var resp = response.replace("\"","");
                        resp = response.replace("\"","");
                        var datos = resp.split("|");
                        document.getElementById('txtCiclos').style.display = 'block';
                        document.getElementById('txtCiclos').innerHTML = "Ciclos registrados: " + datos[0];
                        ciclosRegistrados = datos[0];
                        document.getElementById('txtYipoMovimiento').style.display = 'block';
                        document.getElementById('txtYipoMovimiento').innerHTML = "Últ. Mov. registrado: " + datos[1];
                        if (datos[1] == "Entrada") {
                            document.getElementById('txtNuevoMovimiento').style.display = 'block';
                            document.getElementById('txtNuevoMovimiento').innerHTML = "Movimiento a realizar: Salida";
                            
                            document.getElementById('optradio1').checked = false;
                            document.getElementById('optradio2').checked = true;
                            
                            document.getElementById("chk1").checked = true;
                            document.getElementById("chk2").checked = true;
                            document.getElementById("chk3").checked = true;
                            document.getElementById("chk4").checked = true;
                            document.getElementById("chk5").checked = true;
                            
                        } else {
                            if (datos[1] == "Salida") {
                                document.getElementById('txtNuevoMovimiento').style.display = 'block';
                                document.getElementById('txtNuevoMovimiento').innerHTML = "Movimiento a realizar: Entrada";
                                
                                document.getElementById('optradio1').checked = true;
                                document.getElementById('optradio2').checked = false;
                                document.getElementById('contadorA').value = '';
                                document.getElementById('contadorA').disabled = false;
                                document.getElementById('contadorA').focus();
                                
                                document.getElementById("chk1").checked = false;
                                document.getElementById("chk2").checked = false;
                                document.getElementById("chk3").checked = false;
                                document.getElementById("chk4").checked = false;
                                document.getElementById("chk5").checked = false;
                                
                            } else {
                                document.getElementById('txtNuevoMovimiento').style.display = 'block';
                                document.getElementById('txtNuevoMovimiento').innerHTML = "Movimiento a realizar: Salida";
                                
                                document.getElementById('optradio1').checked = false;
                                document.getElementById('optradio2').checked = true;
                                
                                document.getElementById("chk1").checked = true;
                                document.getElementById("chk2").checked = true;
                                document.getElementById("chk3").checked = true;
                                document.getElementById("chk4").checked = true;
                                document.getElementById("chk5").checked = true;
                                
                            }                        
                        }
                    } else {
                        alert("No está registrado el Aplicador.");
                        document.getElementById('contadorA').value = '';
                        document.getElementById('contadorA').disabled = false;
                        document.getElementById('optradio1').checked = false;
                        document.getElementById('optradio2').checked = false;
                        document.getElementById('txtCiclos').style.display = 'none';
                        document.getElementById('txtYipoMovimiento').style.display = 'none';
                        
                        document.getElementById("chk1").checked = false;
                        document.getElementById("chk2").checked = false;
                        document.getElementById("chk3").checked = false;
                        document.getElementById("chk4").checked = false;
                        document.getElementById("chk5").checked = false;
                        
                    }
                },
                error: function(response){
                    alert("Error");
                    movimiento = "";
                }
            });	
        }
    }
    
    function verificarEstado(){
        var idAplicador = document.getElementById("aplicador").value;
        var contadorActual = document.getElementById("contadorA").value;
        if ((idAplicador == "") || (contadorActual == "")) {
            alert("Debe estar seleccionado un aplicador y establecido un número de golpes.");
            return;
        }
        
        var ciclosVerificar = parseInt(contadorActual,10);
        if (parseInt(ciclosVerificar) <= parseInt(ciclosRegistrados)) {
            alert('El número de ciclos ingresado es menor o igual al último registro guardado. Ingresa nuevamente la información.');
            document.getElementById("contadorA").value = "";
            document.getElementById("contadorA").disabled = false;
            document.getElementById("contadorA").focus();
            ciclosRegistrados = 0;
            return;
        }
        
        jQuery.ajax({
            url: "<?php echo base_url(); ?>/consultas_ajax/consultaEstadoAplicadorById.php?idAplicador=" + idAplicador + "&ciclosVerificar=" + ciclosVerificar + "&ciclosRegistrados=" + ciclosRegistrados, 
            cache: false,
            contentType: "text/html; charset=UTF-8",
            success: function(response){
                var respuesta = response.replace("\"","");
                respuesta = response.replace("\"","");
                var respuestaArray = respuesta.split("|");
                if (respuestaArray[0] == "Si") {
                    if (respuestaArray[1] == 0) {
                        alert("Se requiere mantenimiento, el aplicador está justo en el límite.");
                    } else {
                        alert("Se requiere mantenimiento, cantidad excedida: " + respuestaArray[1] + " ciclos.");
                    }
                }
                if (respuestaArray[0] == "No") {
                    alert("Se registraría entrada normal, faltan: " + respuestaArray[1] + " ciclos para el mantenimiento.");
                }
            },
            error: function(response){
                alert("Error");
            }
         });   
        ciclosRegistrados = 0;
    }
    </script>   

</body>
</html>