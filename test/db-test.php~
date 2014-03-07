<?php

$hostname = $_SERVER["DB1_HOST"].':'.$_SERVER["DB1_PORT"];
$conn = mysqli_connect($hostname, $_SERVER["DB1_USER"], $_SERVER["DB1_PASS"], "Shayne");
 
// check connection
if (mysqli_connect_errno()) {
  print('Database connection failed: '  . mysqli_connect_error() . "<br>");
}

$sql = 'SELECT * FROM tags';
 
$rs = $conn->query($sql);
 
if ($rs === false) {
  print ('Wrong SQL: ' . $sql . ' Error: ' . $conn->error);
} else {
  $rows_returned = $rs->num_rows;
}

$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
  echo "UID: " . $row['UID'] . '<br>';
  echo "Tag: " . $row['Tag'] . '<br>';
}

?>
