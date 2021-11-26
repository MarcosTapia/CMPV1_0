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
    
    <link rel="stylesheet" href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
    <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="jqueryui/style.css">
    
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable( {
                    "sPaginationType": "full_numbers"
            } );
        } );

    </script>
                
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">
    <div class="table-responsive">   
        <?php 
            $posicionContenidoUsuario = array_search($idResponsable,$usuariosArray);
            $elemArrayUsu = explode("|", $posicionContenidoUsuario);
            $posicionContenidoMaquina = array_search($idMaquina,$maquinasArray);
            $elemArrayMaq = explode("|", $posicionContenidoMaquina);
            $cadena = "";
        ?>

        <?php
        if (trim($week) != "||") { ?>
        <h4 style="color:#330066;text-align: center;">Mantenimientos de la Semana: <?php $weeks = explode("|",$week); if ($weeks[1]!="") { echo $weeks[0]." a ".$weeks[1]; } else { echo $weeks[0]; }?></h4>
        <?php }
            if ($idResponsable != 0) { 
                //Si solo esta el resposable
                $cadena = "Responsable: ".$elemArrayUsu[0].".";
            } else {
                if ($idMaquina != 0) { 
                    //Si solo esta la maquina
                    $posicionContenidoUsuario = array_search($idResponsableMaq,$usuariosArray);
                    $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                    $cadena = "Máquina: ".$elemArrayMaq[0]." ".$elemArrayMaq[1].". Responsable: ".$elemArrayUsu[0].".";
                }
            }
        ?>
        <h4 style="color:#330066;text-align: center;"><?php echo $cadena; ?></h4>
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>M&aacute;quina</th>
                    <th>Num M&aacute;quina</th>
                    <th>Actividad</th>
                    <th>Responsable</th>
                    <th>Status</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($mantenimientos)) {
                    $i=1;
                    foreach($mantenimientos as $fila) {?>
                        <tr id="fila-<?php echo $fila->{'idFechaMantenimiento'} ?>">
                            <td><?php echo $fila->{'fechaMantenimiento'} ?></td>
                            <?php 
                                $posicionContenidoUsuario = array_search($fila->{'idResponsable'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                            
                                $posicionContenidoMaquina = array_search($fila->{'idMaquina'},$maquinasArray);
                                $elemArrayMaq = explode("|", $posicionContenidoMaquina);
                            ?>
                            
                            <td><?php echo $elemArrayMaq[0]; ?></td>
                            <td><?php echo $elemArrayMaq[1]; ?></td>
                            
                            <?php 
                                $posicionContenidoActividad = array_search($fila->{'idActividad'},$actividadesArray);
                                $elemArrayAct = explode("|", $posicionContenidoActividad);
                            ?>
                            
                            <td><?php echo $elemArrayAct[0]; ?></td>
                            <td><?php echo $elemArrayUsu[0]; ?></td>
                            <td><?php
                                if ($fila->{'condicion_maquina'} == "NO ATENDIDA"){
                                    echo "<p style='opacity: 0; color: transparent;font-size:1px;'>##</p> <img src='".base_url()."/images/sistemaicons/nook.ico' alt='Pendiente' title='Pendiente' />";
                                } else {
                                    echo "<p style='opacity: 0; color: transparent;font-size:1px;'>@@</p> <img src='".base_url()."/images/sistemaicons/ok.ico' alt='Ok' title='Ok' />";
                                } 
                                ?>
                            </td>
                            <td>
                                <?php echo $fila->{'observaciones_maquina'}; ?>
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
                    <th>Fecha</th>
                    <th>M&aacute;quina</th>
                    <th>Num M&aacute;quina</th>
                    <th>Actividad</th>
                    <th>Responsable</th>
                    <th>Status</th>
                    <th>Observaciones</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
