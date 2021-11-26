<!doctype html>
<html lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:addthis="https://www.addthis.com/help/api-spec"  prefix="og: http://ogp.me/ns#" class="no-js">
<head>
    <script>
        var msgComprobacion = '<?php echo $mensaje;?>';
        if (msgComprobacion == '1') {
            alert("Registro actualizado exitosamente.");
        } else {
            alert("Error. No se actualizó el registro.");
        }
        var opcion = confirm("¿Deseas hacer otra lectura?");
        if (opcion == true) {
            window.location.replace('https://192.168.98.200/cmpv1_0/index.php/usuarios_controller/scaneo');
        } else {
            window.location.replace('https://192.168.98.200/cmpv1_0/index.php/usuarios_controller/cerrarSesionQR');
        }
    </script>
</head>
<body>
</body>
</html>
