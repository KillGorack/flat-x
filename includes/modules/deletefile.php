<p>
<?php

// ===================================================================
// Get the paths of the files that need to be deleted...
// ===================================================================
$datfilepath = "uploads" . "/" . $_GET["app"] . "/" . $_GET["rec"] . "/data/" . $_GET["file"] . ".dat";
$readdata    = fopen($datfilepath, 'r');
$readline    = fgets($readdata);
fclose($readdata);
$pieces      = explode($parsdeliminator, $readline);
$uplfilepath = $pieces[1] . $pieces[2];
// ===================================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST["confirmdelete"] == "acknowledged") {

        unlink($uplfilepath);
        unlink($datfilepath);

        ?>
 <div class="alert"><img src="images/alert.png"><hr>File Deleted!, Now redirecting..</div>
 <meta http-equiv="refresh" content="2;URL='index.php?app=<?php echo $_GET["app"]; ?>&rec=<?php echo $_GET["rec"]; ?>&content=detail'" />
 <?php

    }
} else {

    echo "<div class=alert>";
    echo "<img src=images/alert.png><hr>";
    echo "<center>Are you sure you want to delete the file<br><strong>[" . $pieces[2] . "]</strong>?<br>Doing this cannot be undone!</center>";
    ?>
      <hr>
      <form METHOD="POST" ACTION="">
      <input type="hidden" value="acknowledged" name="confirmdelete" />
      <input type="image" src="images/submit.png" /></div>
    <?php
}

?>
<p>
