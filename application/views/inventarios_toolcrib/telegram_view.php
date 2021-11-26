<?php
$content = file_get_contents("php://input");
if($content){
    $token = '1676714028:AAH0aWRKqqhVIauq-5M6B7Fqz0AundeBdT0';    
    $apiLink = "https://api.telegram.org/bot$token/";    
    echo '<pre>content = '; print_r($content); echo '</pre>';
    $update = json_decode($content, true);
    if(!@$update["message"]) {
		$val = $update['callback_query'];
	} else {
		$val = $update;
	}
    $chat_id = $val['message']['chat']['id'];
    $text = $val['message']['text'];
    $update_id = $val['update_id'];
    $sender = $val['message']['from'];
	
	//en text recibo la consulta y la proceso
	$conn = mysqli_connect("localhost", "root", "kikinalba", "cmpv1_0") or die("Database Error");
	//$getMesg = mysqli_real_escape_string($conn, $_POST['text']);
	$check_data = "SELECT replies FROM chatbot WHERE queries LIKE '%".$text."%'";
	$run_query = mysqli_query($conn, $check_data) or die("Error");
	if(mysqli_num_rows($run_query) > 0){
		$respuesta = "- ";
		while($row = mysqli_fetch_assoc($run_query)) {
			$respuesta = $respuesta.$row["replies"].PHP_EOL."- ";
		}	
	} else{
		$respuesta = "Lo siento no puedo responder tu pregunta!. Intenta de nuevo solo con la desripción o "
		."con el num sap o con el número de parte o los tres juntos sap, num_parte y descripción separados por espacios.";
	}
	//Fin en text recibo la consulta y la proceso
	
    file_get_contents($apiLink . "sendmessage?chat_id=$chat_id&text=Respuesta a tu consulta:".PHP_EOL.$respuesta);
} else {
	echo 'Only telegram can access this url.';
}
?>