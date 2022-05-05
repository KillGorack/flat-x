<p>
<?php
$deletetarget = "data/records" . "/" . $_GET["app"] . "/" . $_GET["rec"] . ".txt";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST["confirmdelete"] == "acknowledged") {
        unlink($deletetarget);
        echo "<div class=alert><img src=images/alert.png><hr>" . "Record Deleted!, Now redirecting.." . "</div>";
        ?><meta http-equiv="refresh" content="0;URL='index.php?app=<?php echo $_GET["app"]; ?>&content=list'" /><?php
}
} else {
    echo "<div class=alert>";
    echo "<img src=images/alert.png><hr>";
    echo "<center>Are you sure you want to delete the record<br><strong>[" . $deletetarget . "]</strong><br> along with all uploaded data?<br>Doing this cannot be undone!</center>";
    ?>
      <hr>
      <form METHOD="POST" ACTION="">
      <input type="hidden" value="acknowledged" name="confirmdelete" />
      <input type="image" src="images/submit.png" /></div>
    <?php
}
?>
</p>
