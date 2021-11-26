<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
  
    <style type="text/css" title="currentStyle">
            @import "<?php echo base_url();?>media/css/demo_page.css";
            @import "<?php echo base_url();?>media/css/demo_table.css";
    </style>
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.dataTables.js"></script>
    
    <link href="css/smoothness/jquery-ui-1.9.0.custom.css" rel="stylesheet">
    <script language="javascript" type="text/javascript" src="jquery-1.8.2.js"></script>
    <script src="js/jquery-ui-1.9.0.custom.js"></script>    

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

    
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable( {
                    "sPaginationType": "full_numbers",
                    'aaSorting': [[ 0, 'desc' ]]
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
                setTimeout(function(){ location.reload(); }, 1000);
            }
        }
        
        function validaConsultaParaExcel(){
            if (document.getElementById('movsAplicadoresHiddenCheckDatasetExist').value == "0") {
                alert("Debe existir información previamente cargada.");
                return false;
            } 
            return true;
        }

        function validaConsultaParaPdf(){
            if (document.getElementById('movsAplicadoresHiddenCheckDatasetExist').value == "0") {
                alert("Debe existir información previamente cargada.");
                return false;
            } 
            return true;
        }

        
    </script>
                
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
    <h3 style="background-color:#C13030; color:white;text-align: center">Movimientos del Aplicador</h3>
    
    <div class="table table-responsive">
        <table>
            <tr>
                <td>
                    <a href="<?php echo base_url();?>index.php/herramentales_controller/funcioninicialAdministradorAplicadores">Regresar a aplicadores</a>
                </td>
                <td>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <form action="<?php echo base_url();?>index.php/herramentales_controller/exportarExcel" method="post" style="display:inline;">  
                          <?php if (isset($movimientos_aplicadores)) { ?>
                          <input type="hidden" name="movsAplicadoresHidden" id="movsAplicadoresHidden" value="<?php echo htmlspecialchars(serialize($movimientos_aplicadores)) ?>" />
                          <input type="hidden" name="movsAplicadoresHiddenCheckDatasetExist" id="movsAplicadoresHiddenCheckDatasetExist" value="1" />
                          <?php } else { ?>
                          <input type="hidden" name="movsAplicadoresHiddenCheckDatasetExist" id="movsAplicadoresHiddenCheckDatasetExist" value="0" />
                          <?php } ?>
                          <input onclick='javascript: return validaConsultaParaExcel()' style="margin-left:50px;; margin-top: 10px; width: 33px; height: 33px;" type="image" src="<?php echo base_url(); ?>/images/sistemaicons/excelicon2.png" alt="Exportar a Excel." title="Exportar a Excel." />
                    </form>    
                </td>
                <td>
                    <form action="<?php echo base_url();?>index.php/herramentales_controller/verManttoPdf" method="post" style="display:inline;">    
                          <?php if (isset($movimientos_aplicadores)) { ?>
                          <input type="hidden" name="movimientosHidden" id="movimientosHidden" value="<?php echo htmlspecialchars(serialize($movimientos_aplicadores)) ?>" />
                          <input type="hidden" name="movsAplicadoresHiddenCheckDatasetExist" id="movsAplicadoresHiddenCheckDatasetExist" value="1" />
                          <?php } else { ?>
                          <input type="hidden" name="movsAplicadoresHiddenCheckDatasetExist" id="movsAplicadoresHiddenCheckDatasetExist" value="0" />
                          <?php } ?>
                          <input onclick='javascript: return validaConsultaParaPdf()' style="margin-left:10px;; margin-top: 10px; width: 33px; height: 33px;" type="image" src="<?php echo base_url(); ?>/images/sistemaicons/pdficon2.png" alt="Ver en PDF." title="Ver en PDF." />
                   </form>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="table-responsive">     
        <br>
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>idMov</th>
                    <th>Responsable</th>
                    <th>Fecha</th>
                    <th>Ciclos</th>
                    <th>Tipo Movimiento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($movimientos_aplicadores) {
                    $i=1;
                    foreach($movimientos_aplicadores as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'idMov'} ?>">
                            <td><?php echo $fila->{'idMov'} ?></td>
                            <?php 
                                $posicionContenidoUsuario = array_search($fila->{'idUsuario'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                            ?>
                            <td><?php echo $elemArrayUsu[0]; ?></td>
                            <td><?php echo $fila->{'fecha'} ?></td>
                            <td><?php echo $fila->{'ciclos'} ?></td>
                            <td><?php echo $fila->{'tipoMovimiento'} ?></td>
                            <td>
                                <a id='verDetalle' onclick="verDetalle('<?php echo $fila->{'idMov'}; ?>');" href="#"><img src="<?php echo base_url(); ?>/images/sistemaicons/database.ico" alt="Ver Detalle" title="Ver Detalle" /></a>
                            &nbsp;&nbsp;
                            <?php if ($fila->{'nombreMicroseccion'} != "") { ?>
                            <a href="<?php echo base_url(); ?>microsecciones/<?php echo $fila->{'nombreMicroseccion'}; ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/folder.ico" alt="Ver Microsección" title="Ver Microsección" /></a>
                            <?php } ?>
                            </td>
                            
                        </tr>
                      <?php 
                  
                      $i++;  
                    }   
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>idMov</th>
                    <th>Responsable</th>
                    <th>Máquina</th>
                    <th>Ciclos</th>
                    <th>Tipo Movimiento</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        
                            <div id="dialog" style="display:none">
                                   <div>
                                   <iframe id="ventanaPdf" src=""></iframe>
                                   </div>
                            </div>                             
        
        <?php echo "<script>mensaje();</script>"; ?>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->


    <div id="myModal" class="modal">
      <div class="modal-content">
        <h3 id='tituloActBusq' style="text-align:center;color: #cc3300">Detalle del Movimiento.  <p class="close">&times;</p></h3>
        <div class="form-group">
            <label for="txtAplicador">Aplicador:</label>
            <input type="text" class="form-control" id="txtAplicador" placeholder="Aplicador" readonly>
        </div>        
        <div class="form-group">
            <label for="txtResponsable">Responsable:</label>
            <input type="text" class="form-control" id="txtResponsable" placeholder="Responsable" readonly>
        </div>        
        <div class="form-group">
            <label for="txtFecha">Fecha del Movimiento:</label>
            <input type="text" class="form-control" id="txtFecha" placeholder="Fecha del Movimiento" readonly>
        </div>        
        <div class="form-group">
            <label for="txtObsMantto">Observaciones del Mantenimiento:</label>
            <textarea class="form-control" id="txtObsMantto" rows="3" readonly></textarea>
        </div>        
        <div class="form-group">
            <h5 style="font-weight:bold;">Actividades del Mantenimiento: </h5>
            <p><input style="width:20px;" type="checkbox" name="chkMantto1" id="chkMantto1" > Limpieza general del aplicador, use desengrasante para retirar elementos contaminantes.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto2" id="chkMantto2" > Revisión de componentes, para detección de desgastes o partes dañadas.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto3" id="chkMantto3" > Revisión de ram de aplicador, buscando juegos excesivos o desgastes.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto4" id="chkMantto4" > Revisión del sistema de avance, (leva, dedo, baleros, guías).</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto5" id="chkMantto5" > Verificar sistema de yunques, matriz de corte, navaja, resorte y tope.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto6" id="chkMantto6" > Verificar desgastes y/o posibles daños en formadores.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto7" id="chkMantto7" > Lubricación del sistema en general.</p>
            <p><input style="width:20px;" type="checkbox" name="chkMantto8" id="chkMantto8" > Entregar muestra a calidad para microsección.</p>
        </div>
        <div class="form-group">
            <label for="txtCiclos">Número de Ciclos:</label>
            <input type="text" class="form-control" id="txtCiclos" placeholder="Número de Ciclos" readonly>
        </div>        
        <div class="form-group">
            <label for="txtStatus">Estatus:</label>
            <input type="text" class="form-control" id="txtStatus" placeholder="Estatus" readonly>
        </div>        
        <div class="form-group">
            <label for="txtTipoMovimiento">Tipo de Movimiento:</label>
            <input type="text" class="form-control" id="txtTipoMovimiento" placeholder="Tipo de Movimiento" readonly>
        </div>        
        <div class="form-group">
            <h5 style="font-weight:bold;">Actividades del Movimiento: </h5>
            <p><input style="width:20px;" type="checkbox" name="chk1" id="chk1" > Eliminar residuos ajenos al aplicador, deberá de quedar limpio en su totalidad.</p>
            <p><input style="width:20px;" type="checkbox" name="chk2" id="chk2" > Asegurarse que todas las partes móviles presenten buena lubricación.</p>
            <p><input style="width:20px;" type="checkbox" name="chk3" id="chk3" > Verificar que no exista desgaste en las partes o juegos excesivos en el ram.</p>
            <p><input style="width:20px;" type="checkbox" name="chk4" id="chk4" > Revisión del sistema de avance (leva, dedo, baleros, guías). Realizar una carrera manual para comprobar su buen funcionamiento.</p>
            <p><input style="width:20px;" type="checkbox" name="chk5" id="chk5" > Verificar sistema de yunques, matriz de corte, navaja, resorte y tope.</p>
        </div>
        <div class="form-group">
            <label for="txtObsMov">Observaciones del Movimiento:</label>
            <textarea class="form-control" id="txtObsMov" rows="3" readonly></textarea>
        </div>        
        
        <div class="modal-footer">
          <button type="button" id="btnCierraModal" class="btn btn-info" data-dismiss="Cerrar" onclick="cierraModal()">Cerrar</button>
        </div>
      </div>
    </div>    

    <script>
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("verDetalle");
        var span = document.getElementsByClassName("close")[0];
        
        function verDetalle(idMov){
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/consultaMovAplicadorByIdMov.php?idMov=" + idMov,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    var respuesta = response.replace("\"","");
                    respuesta = response.replace("\"","");
                    var regArray = respuesta.split("|");
                    document.getElementById('txtAplicador').value = regArray[0];
                    document.getElementById('txtResponsable').value = regArray[1];
                    document.getElementById('txtFecha').value = regArray[2];
                    document.getElementById('txtObsMantto').value = regArray[3];
                    //verifica checks de mantenimiento
                    if (regArray[4][0]=="1"){
                        document.getElementById('chkMantto1').checked = true;
                    }
                    if (regArray[4][1]=="1"){
                        document.getElementById('chkMantto2').checked = true;
                    }
                    if (regArray[4][2]=="1"){
                        document.getElementById('chkMantto3').checked = true;
                    }
                    if (regArray[4][3]=="1"){
                        document.getElementById('chkMantto4').checked = true;
                    }
                    if (regArray[4][4]=="1"){
                        document.getElementById('chkMantto5').checked = true;
                    }
                    if (regArray[4][5]=="1"){
                        document.getElementById('chkMantto6').checked = true;
                    }
                    if (regArray[4][6]=="1"){
                        document.getElementById('chkMantto7').checked = true;
                    }
                    if (regArray[4][7]=="1"){
                        document.getElementById('chkMantto8').checked = true;
                    }
                    
                    document.getElementById('txtCiclos').value = regArray[5];
                    document.getElementById('txtStatus').value = regArray[6];
                    document.getElementById('txtTipoMovimiento').value = regArray[7];
                    
                    //verifica checks del movimiento
                    if (regArray[8][0]=="1"){
                        document.getElementById('chk1').checked = true;
                    }
                    if (regArray[8][1]=="1"){
                        document.getElementById('chk2').checked = true;
                    }
                    if (regArray[8][2]=="1"){
                        document.getElementById('chk3').checked = true;
                    }
                    if (regArray[8][3]=="1"){
                        document.getElementById('chk4').checked = true;
                    }
                    if (regArray[8][4]=="1"){
                        document.getElementById('chk5').checked = true;
                    }
                    
                    document.getElementById('txtObsMov').value = regArray[9];
                    modal.style.display = "block";
                },
                error: function(response){
                    alert("Error");
                    //movimiento = "";
                }
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

        }
    </script>  
</body>	
</html>
