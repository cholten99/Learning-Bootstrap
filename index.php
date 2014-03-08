<?php

function getTags() {
  $conn = new mysqli( $_SERVER["DB1_HOST"],  $_SERVER["DB1_USER"], $_SERVER["DB1_PASS"], $_SERVER["DB1_NAME"],$_SERVER["DB1_PORT"]);

  // check connection
  if (mysqli_connect_errno()) {
    print('Database connection failed: '  . mysqli_connect_error() . "<br>");
  }

  $sql = 'SELECT DISTINCT Tag FROM tags ORDER BY Tag';
 
  $rs = $conn->query($sql);
 
  if ($rs === false) {
    print ('Wrong SQL: ' . $sql . ' Error: ' . $conn->error);
  } else {
    $rows_returned = $rs->num_rows;
  }

  print "<select id='tagSelect'>\n";

  $rs->data_seek(0);
  while($row = $rs->fetch_assoc()) {
    echo "<option value='" . $row['Tag'] . "'>" . $row['Tag'] . "</option>\n";
  }

  print "</select>\n";

}

?>

<html>
  <head>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    <!-- My javascript -->
    <script src="myJavascript.js"></script>

    <!-- My CSS -->
    <link rel="stylesheet" href="myTypeaheadCSS.css">

  </head>
  <body>

    <div class="container">

      <div class="page-header">
        <h1>Learning Bootstrap</h1>
        <p class="lead">Some good examples to review and learn</p>
      </div>

      <div class="tags">
        <h2>Tags:
        <?php getTags(); ?>
        </h2>
      </div>

      <div id="results">

      </div> <!-- results -->

    </div> <!-- Container -->

  </body>
</html>
