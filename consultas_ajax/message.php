<?php
    $conn = mysqli_connect("localhost", "root", "kikinalba", "cmpv1_0") or die("Database Error");
    $getMesg = mysqli_real_escape_string($conn, $_POST['text']);
    //$check_data = "SELECT replies FROM chatbot WHERE queries LIKE '%".$getMesg."%'";
    //cambio para eliminar una tabla y que se actualice segun inventario
    $check_data = "SELECT concat('Descripción: ',`descripcion`,' , Stock: ',`stock`,' , Ubicación: ',`ubicacion`) as replies FROM `inventarios_toolcrib` WHERE concat(`sap`,' ',`numero_parte`,' ', `descripcion`) like '%".$getMesg."%'";
    $run_query = mysqli_query($conn, $check_data) or die("Error");
    if(mysqli_num_rows($run_query) > 0){
        $respuesta = "- ";
        while($row = mysqli_fetch_assoc($run_query)) {
            $respuesta = $respuesta.$row["replies"]."<br>"."- ";
        }	
        echo $respuesta;
    } else {
        echo "Lo siento no puedo responder tu pregunta!. Intenta de nuevo solo con la desripción o con el num sap o con el número de parte o los tres juntos sap, num_parte y descripción separados por espacios.";
    }
?>
