<?php

// Logging
include "Logging.php";
ClearLog();

TestLog("Testy test");

$function = "";
if (empty($_GET['Function'])) {
  $function = $_POST['Function'];
  $dbArray = $_POST;
} else {
  $function = $_GET['Function'];
  $dbArray = $_GET;
}

unset($dbArray['Function']);
$returnedArray = call_user_func($function, $dbArray);
print(json_encode($returnedArray));
// MAIN Ends

// Connect to the database
function ConnectToDB() {

  $user = getenv("DB1_USER");
  $pass = getenv("DB1_PASS");

  $mysqli = new mysqli("localhost", $user, $pass);
  $mysqli->select_db("bowsy_learning-bootstrap");

  if (mysqli_connect_errno()) {
    TestLog("Connect failed: %s\n", mysqli_connect_errno());
    exit();
  } else {
    return($mysqli);
  }

}

// Get the number of entries in the DB
function GetDBSize($dbArray) {
  $mysqli = ConnectToDB();
  $queryString = "SELECT COUNT(*) FROM learningbootstrap";
  $result = $mysqli->query($queryString);
  $rows = $result->fetch_array(MYSQLI_ASSOC);
  $mysqli->close();
  return $rows;
}

// Get all the fields in a row given the UID
function GetWholeRow($dbArray) {
  $mysqli = ConnectToDB();
  $queryString = "SELECT * FROM learningbootstrap WHERE UID='" . $dbArray['UID'] . "'";
  $result = $mysqli->query($queryString);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $mysqli->close();
  return $row;
}

// Add a row to the database - return the new row number
function SaveRow($dbArray) {

  // Retrieve the actual data
  $dbArray = $dbArray['DataArray'];

  // See if it's already in there and delete if it is (yes, I should update and this is a hack - sue me)
  $mysqli = ConnectToDB();
  $queryString = "SELECT * FROM learningbootstrap WHERE UID='" . $dbArray['UID'] . "'";
  $findResult = $mysqli->query($queryString);
  $rowCount = $findResult->num_rows;
  if ($rowCount == 1) {
    $deleteString = "DELETE FROM learningbootstrap WHERE UID='" . $dbArray['UID'] . "';";
    $deleteResult = $mysqli->query($deleteString);
  }

  // Add new row
  $fields = "";
  $values = "";
  foreach ($dbArray as $key => $value) {
    $fields .= "" . $key . ",";
    $values .= "\"" . $mysqli->real_escape_string(stripslashes($value)) . "\",";
  }
  $fields = rtrim($fields, ",");
  $values = rtrim($values, ",");

  $insertString = "INSERT INTO learningbootstrap (" . $fields . ") VALUES (" . $values . ")";
  $mysqli->query($insertString);
  $mysqli->close();

  updateTags($dbArray);
}

// Update tags table after a new entry
function updateTags($dbArray) {
  $UID = $dbArray['UID'];
  $tags = $dbArray['Tags'];
  $tagArray = explode(",", $tags);

  $mysqli = ConnectToDB();

  // Remove any previous entries for this UID
  $deleteString = "DELETE FROM tags WHERE UID='" . $UID . "';";
  $deleteResult = $mysqli->query($deleteString);

  // Insert new ones
  foreach ($tagArray as $value) {
    $insertString = "INSERT INTO tags (UID, Tag) VALUES (" . $UID . ",'" . $value . "')";
    $mysqli->query($insertString);
  }

  $mysqli->close();

}

// List the tags
function GetTags($dbArray) {
  $mysqli = ConnectToDB();
  $queryString = "SELECT DISTINCT Tag FROM tags";
  $result = $mysqli->query($queryString);
  $returnArray = array();

  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $returnArray[] = $row['Tag'];
  }

  $mysqli->close();
  return $returnArray;
}

// Get examples
function GetExamples($dbArray) {
  $mysqli = ConnectToDB();
  $queryString = "SELECT * FROM learningbootstrap WHERE UID IN (SELECT DISTINCT UID FROM tags WHERE Tag = '" . $dbArray['Tag'] . "')";
  $result = $mysqli->query($queryString);
  $returnArray = array();
  $innerArray = array();

  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $returnArray[] = $row;
  }

  $mysqli->close();
  return $returnArray;
}

?>
