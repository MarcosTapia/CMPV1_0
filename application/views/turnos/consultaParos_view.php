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
    
    <link rel="stylesheet" href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
    <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="jqueryui/style.css">
    
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable( {
                    "aLengthMenu": [ [10, 25, 50, 100, 500, 1000, -1], [10, 25, 50,  100, 500, 1000, "All"] ],
                    "sPaginationType": "full_numbers"
            } );
        } );

        function verificaFechas(){
            //verifica que vengan las dos fechas
            if ((document.getElementById('fecha1').value == "") && (document.getElementById('fecha2').value == "")) {
                alert("Debes ingresar al menos la fecha 1.");
                return false;
            }
            
            //verifica que no venga sola la fecha 2
            if (document.getElementById('fecha2').value != "") {
                if (document.getElementById('fecha1').value == "") {
                    alert("Si es solo una fecha selecciona la fecha 1");
                    return false;
                }                
            }
            //verifica que la fecha 2 sea mayor que la 1
            if ((document.getElementById('fecha1').value != "") && (document.getElementById('fecha2').value != "")) {
                var fecha1 = document.getElementById('fecha1').value; 
                var fecha2 = document.getElementById('fecha2').value;
                if (fecha1 > fecha2) {
                    alert("La fecha 1 debe ser menor o igual a la fecha 2");
                    return false;
                }
            }
            return true;
        }
        
        function validaConsultaParaExcel(){
            if (document.getElementById('parosHdn').value == "0") {
                alert("Debe existir información previamente cargada.");
                return false;
            } 
            return true;
        }

        function validaConsultaParaPdf(){
            if (document.getElementById('parosHdn').value == "0") {
                alert("Debe existir información previamente cargada.");
                return false;
            } 
            return true;
        }
    </script>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">
    <?php 
        $correcto = $this->session->flashdata('correcto');
        if ($correcto) { ?>
    <span id="registroCorrecto" style="color:blue;"><?= $correcto ?></span>
    <?php } ?>
    <script>mensaje();</script>;
    
    <div>
        <center>
            <table>
                <tr>
                    <td>
                        <p style="font-size: 20px; margin-top: -10px; text-align: center; color: #0000CC; ">Consulta por Fechas: </p>
                        <form onsubmit="javascript: return verificaFechas()" class="form-inline" action="<?php echo base_url();?>index.php/turnos_controller/consultaParos" method="post">
                            <center>
                            <div>
                                <!--
                              <div class="form-group">
                                <p>Proyecto:
                                <select style="height: 35px;" class="form-control" name="proyecto" id="proyecto">
                                    <option value="">General...</option>
                                    <?php 
                                        foreach($proyectos as $filaN) {
                                            echo "<option value=".$filaN->{'idProyecto'}.">".$filaN->{'descripcion_proyecto'}."</option>";
                                        } 
                                    ?>
                                </select>  
                                &nbsp;&nbsp;
                                </p>
                              </div>
                              -->  

                              <div class="form-group">
                                  <p>De:
                                      <input style="height: 35px;" type="date" name="fecha1" id="fecha1" value="<?php echo $fecha; ?>">
                                  </p>
                              </div>

                              <div class="form-group">
                                  <p style="margin-left:10px;">A:
                                  <input style="height: 35px;" type="date" name="fecha2" id="fecha2" value="<?php echo $fecha; ?>">

                                  <input type="submit" class="btn btn-primary" name="submit" value="Buscar" style="margin-left:20px;margin-top: -3px; height: 38px;"/>
                                </p>
                              </div>
                            </div>
                            </center>
                        </form>
                    </td>

                    <td>
                        <div style="margin-top:20px;margin-left: 20px;">
                            <form action="<?php echo base_url();?>index.php/turnos_controller/exportarExcelParosAdmin" method="post" style="display:inline;">  
                                  <?php if (isset($paros)) { ?>
                                  <input type="hidden" name="parosHiddenExcel" id="parosHiddenExcel" value="<?php echo htmlspecialchars(serialize($paros)) ?>" />
                                  <input type="hidden" name="parosHdn" id="parosHdn" value="1" />
                                  <?php } else { ?>
                                  <input type="hidden" name="parosHdn" id="parosHdn" value="0" />
                                  <?php } ?>
                                  <input onclick='javascript: return validaConsultaParaExcel()' style="padding-top: 2px; width: 33px; height: 33px;" type="image" src="<?php echo base_url(); ?>/images/sistemaicons/excelicon2.png" alt="Exportar a Excel la última consulta" title="Exportar a Excel la última consulta" />
                            </form>
                        </div>
                    </td>
                </tr>
            </table>
        </center>
        <br>
    </div>
    
    
    <div class="table-responsive">   
        <h4 style="color:#330066;text-align: center;">Notfificaciones</h4>
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Msg Operador</th>
                    <th>Msg Técnico</th>
                    <th>Estado</th>
                    <th>Not. Tecn</th>
                    <th>Not. Lider</th>
                    <th>Inicio Atención</th>
                    <th>Fin Atención</th>
                    <th>Duración Paro</th>
                    <th>Técnico</th>
                    <th>Lider</th>
                    <th>Eval</th>
                    <th>Falla</th>
                    <th>Observ. Técnico</th>
                    <th>Proyecto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($paros)) {
                    $i=1;
                    foreach($paros as $fila) {?>
                        <tr id="fila-<?php echo $fila->{'id'} ?>">
                            <td><?php echo $fila->{'id'} ?></td>
                            <td><?php echo $fila->{'mensajeOperador'} ?></td>
                            <td><?php echo $fila->{'mensajeTecnico'} ?></td>
                            <td><?php echo $fila->{'estado'} ?></td>
                            <td><?php echo $fila->{'fechaEnvioNotificacionAlTecnico'} ?></td>
                            <td><?php echo $fila->{'fechaEnvioNotificacionAlOperador'} ?></td>
                            <td><?php echo $fila->{'fechaInicioAtencion'} ?></td>
                            <td><?php echo $fila->{'fechaFinAtencion'} ?></td> 
                            <td><?php echo $fila->{'tiempoAtencion'} ?></td>
                            <?php
                            $tecnico = array_search($fila->{'idUsuarioTecnico'},$usuariosArray);
                            $elemArrayTec = explode("|", $tecnico);
                            
                            $lider = array_search($fila->{'idUsuarioOperador'},$usuariosArrayOperadores);
                            $elemArrayLider = explode("|", $lider);
                            
                            ?>
                            <td><?php echo $elemArrayTec[0]; ?></td>
                            <td><?php echo $elemArrayLider[0]; ?></td>
                            
                            
                            
                            <td><?php echo $fila->{'calificacionAtencion'}; ?></td>
                            <td><?php echo $fila->{'desc_falla'}; ?></td>
                            <td><?php echo $fila->{'observaciones_tecnico'}; ?></td>
                            
                            <?php 
                                foreach ($proyectos as $proyecto){
                                    if ($fila->{'idProyecto'} == $proyecto->{'idProyecto'}) {
                                        echo "<td>".$proyecto->{'descripcion_proyecto'}."</td>";
                                    }
                                }              
                             ?>
                        </tr>
                      <?php                   
                      $i++;  
                    }   
                } 
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>id</th>
                    <th>Msg Operador</th>
                    <th>Msg Técnico</th>
                    <th>Estado</th>
                    <th>Not. Tecn</th>
                    <th>Not. Lider</th>
                    <th>Inicio Atención</th>
                    <th>Fin Atención</th>
                    <th>Duración Paro</th>
                    <th>Técnico</th>
                    <th>Lider</th>
                    <th>Eval</th>
                    <th>Falla</th>
                    <th>Observ. Técnico</th>
                    <th>Proyecto</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
