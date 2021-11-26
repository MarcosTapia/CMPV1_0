<!DOCTYPE html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script>
        function verificaCamposEliminar(){
            if (document.getElementById('maquinaOrigen2').value == "") {
                alert("El campo no debe ser vacío");
                return false;
            } else {
                return true;
            }
        }
        
        function verificaCamposActualizar(){
            if ((document.getElementById('maquinaOrigen').value == "") && 
                    (document.getElementById('maquinaDestino').value == "")){
                alert("Los campos no deben estar vacíos");
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
    </script>
    
</head>
<body onload="mensaje()">      
    <div class="container"> <!--class="container-fluid" -->
        <div class="row-fluid">
            <?php 
                $correcto = $this->session->flashdata('correcto');
                if ($correcto) { ?>
            <span id="registroCorrecto" style="color:blue;"><?= $correcto ?></span>
            <?php } ?>
            <br>            
            <div class="col-sm-6">
                <form onsubmit="javascript:return verificaCamposActualizar();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/mantenimiento_controller/actualizarMantenimientoMasivo" method="post">
                    <br>
                    <fieldset>
                    <legend>Cambio de Responsable:</legend>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="maquinaOrigen">Mantenimiento a cambiar:</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="maquinaOrigen" id="maquinaOrigen" required>
                                    <option value="">Selecciona uno..</option>
                                    <?php 
                                        $mantoStrTemp = "";
                                        foreach($mantenimientosSimple as $fila) {
                                            $posicionContenidoMaquina = array_search($fila->{'idMaquina'},$maquinasArray);
                                            $elemArrayMaq = explode("|", $posicionContenidoMaquina);

                                            $posicionContenidoUsuario = array_search($fila->{'idResponsable'},$usuariosArray);
                                            $elemArrayUsu = explode("|", $posicionContenidoUsuario);

                                            $mantoStr = $elemArrayMaq[0]." - ".$elemArrayMaq[1]." - ".$elemArrayUsu[0]." ".$elemArrayUsu[1]." ".$elemArrayUsu[2];
                                            if ($mantoStr != $mantoStrTemp) {
                                                echo "<option value=".$fila->{'idMaquina'}."|".$fila->{'idResponsable'}.">".$mantoStr."</option>";
                                                $mantoStrTemp = $mantoStr;
                                            }
                                        } 
                                    ?>
                                </select>
                            </div>                         
                        </div>                         

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="maquinaDestino">Nuevo Responsable:</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="maquinaDestino" id="maquinaDestino" required>
                                    <option value="">Selecciona uno..</option>
                                    <?php 
//                                        foreach($maquinasSimple as $fila) {
//                                            $posicionContenidoUsuario = array_search($fila->{'responsable_maquina'},$usuariosArray);
//                                            $elemArrayUsu = explode("|", $posicionContenidoUsuario);
//                                            if ($fila->{'status'} == 1) {
//                                                echo "<option value=".$fila->{'responsable_maquina'}.">".$elemArrayUsu[0]." ".$elemArrayUsu[1]." ".$elemArrayUsu[2]."</option>";
//                                            }
//                                        } 
                                    
                                        foreach($usuariosGlobal as $fila) {
                                            if (($fila->{'idUsuario'} != 1) && ($fila->{'permisos'} != "Administrador")){
                                                echo "<option value=".$fila->{'idUsuario'}.">".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'nombre'}."</option>";
                                            }
                                        } 
                                    
                                    ?>
                                </select>
                            </div>                         
                        </div>                         

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="boton"></label>
                            <div class="col-sm-8">
                                <?php $submitBtn = array('class' => 'btn btn-primary
                                ',  'value' => 'REALIZAR CAMBIO', 'name'=>'submit'); 
                                echo form_submit($submitBtn); ?>
                                
                                &nbsp;&nbsp;
                                    <?php 
//echo "<img src='".base_url()."/images/sistemaicons/soporte.ico' "
                            //."alt='Soporte. Las fechas,actividades y todos los demás campos permanecen solo cambia la maquina y el responsable.' "
                            //."title='Soporte. Las fechas,actividades y todos los demás campos permanecen solo cambia la maquina y el responsable.' />";
                            ?>
                            </div>                         
                        </div>                         

                    </fieldset>                    
                </form>
            </div>
        </div>

        
        <div class="row-fluid">
            
            <div class="col-sm-6">
                <form onsubmit="javascript:return verificaCamposEliminar();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/mantenimiento_controller/eliminarMantenimientoMasivo" method="post">
                    <br>
                    
                        <fieldset>
                        <legend>Eliminación de Mantenimiento:</legend>

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="maquinaOrigen2">Mantenimiento a Eliminar:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="maquinaOrigen2" id="maquinaOrigen2" required>
                                        <option value="">Selecciona uno..</option>
                                        <?php 
                                            $mantoStrTemp = "";
                                            foreach($mantenimientosSimple as $fila) {
                                                $posicionContenidoMaquina = array_search($fila->{'idMaquina'},$maquinasArray);
                                                $elemArrayMaq = explode("|", $posicionContenidoMaquina);
                                                
                                                $posicionContenidoUsuario = array_search($fila->{'idResponsable'},$usuariosArray);
                                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                                                
                                                $mantoStr = $elemArrayMaq[0]." - ".$elemArrayMaq[1]." - ".$elemArrayUsu[0]." ".$elemArrayUsu[1]." ".$elemArrayUsu[2];
                                                if ($mantoStr != $mantoStrTemp) {
                                                    echo "<option value='".$fila->{'idMaquina'}."|".$fila->{'idResponsable'}."'>".$mantoStr."</option>";
                                                    $mantoStrTemp = $mantoStr;
                                                }
                                            } 
                                        ?>
                                    </select>
                                </div>                         
                            </div>                         
                        
                            <br><br>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="boton"></label>
                                <div class="col-sm-8">
                                    <?php $submitBtn = array('class' => 'btn btn-primary
                                    ',  'value' => 'ELIMINAR MANTTO.', 'name'=>'submit'); 
                                    echo form_submit($submitBtn); ?>
                                </div>                         
                            </div>                         
                        
                        </fieldset>                    
                    
                </form>
            </div>	
        </div>

        
    </div>
<script>
    if (document.getElementById('registroCorrecto').innerHTML != "") {
        setTimeout(function(){ location.reload(); }, 1000);
    }
</script>
</body>

</html>