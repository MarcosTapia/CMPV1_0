<?php
/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "kikinalba";
$dbname = "treeview";
$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}



$sql = "SELECT id, name, text, link as href, parent_id FROM treeview";
$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
//iterate on results row and create new index array of data
while( $row = mysqli_fetch_assoc($res) ) { 
  $data[] = $row;
}
$itemsByReference = array();
// Build array of item references:
foreach($data as $key => &$item) {
   $itemsByReference[$item['id']] = &$item;
}
// Set items as children of the relevant parent item.
foreach($data as $key => &$item)  {    
   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
	  $itemsByReference [$item['parent_id']]['nodes'][] = &$item;
	}
}
// Remove items that were added to parents elsewhere:
foreach($data as $key => &$item) {
   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
	  unset($data[$key]);
}
// Encode:
echo json_encode($data);
?>

