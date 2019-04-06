<hr>

<div class="exchange_container">
  <form action="" method="post" enctype="multipart/form-data">
    <label for="file">Filename:</label>
    <input type="file" name="file" id="file">
    <input type="submit" name="submit" value="Submit">
  </form>
</div>
<?php

		// ===================================================================
		// generating paths, and files name.
		// ===================================================================
      $datapath = "uploads"."/".$_GET["app"]."/".$_GET["rec"]."/"."data";
      $filepath = "uploads"."/".$_GET["app"]."/".$_GET["rec"]."/"."files";
      $filename = date('U');
		// ===================================================================
		// Get filecount (is there an easier way?)
		// ===================================================================
      $datacounter = 0;
			if (file_exists($datapath)) {
        if ($handle = opendir($datapath)) {
          while (false !== ($entry = readdir($handle))) {
            if(substr($entry, -3) == "dat"){$datacounter = $datacounter + 1;}
          }
        }
			}
		// ===================================================================

		
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $temp = explode(".", $_FILES["file"]["name"]);
  if ($_FILES["file"]["error"] > 0){
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  }else{
    $upldname = $_FILES["file"]["name"];
    $filetype = $_FILES["file"]["type"];
    $filesize = (round($_FILES["file"]["size"] / 1024 / 1024, 3))." MB";
    if (file_exists($filepath."/".$_FILES["file"]["name"])){
    
?>
<script type="text/vbscript">
MsgBox "<?php echo $_FILES["file"]["name"] . " already exists, probably should delete the existing file and try again ;0)"; ?>"
</script>
<?php

      
    }else{
      
		// ===================================================================
		// Creating string for datafile..
		// ===================================================================
			$recordcontents = $filename.$parsdeliminator;
			$recordcontents = $recordcontents.$filepath."/".$parsdeliminator;
			$recordcontents = $recordcontents.$_FILES["file"]["name"].$parsdeliminator;
			$recordcontents = $recordcontents.$filetype.$parsdeliminator;
			$recordcontents = $recordcontents.$filesize.$parsdeliminator;
		// ===================================================================
		// Create directories, and write datafile.
		// ===================================================================
			if (!file_exists($datapath)) {
				mkdir($datapath, 0755, true);
			}
			if (!file_exists($filepath)) {
				mkdir($filepath, 0755, true);
			}
			$ourFileName = $datapath."/".$filename.".dat";
			$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
			fwrite($ourFileHandle, $recordcontents);
			fclose($ourFileHandle);
		// ===================================================================
		// Drop the fine into the directory, increment filecount by one..
		// ===================================================================
      move_uploaded_file($_FILES["file"]["tmp_name"],
      $filepath."/".$_FILES["file"]["name"]);
      $datacounter = $datacounter + 1;
		// ===================================================================
		
    }
  }
}

if ($datacounter > 0) {

?><hr>
<!-- Attached Files -->
  <table class=cells>
    <tr>
      <td class="topper"><div class="text4">File Name</div></td>
      <td class="topper"><div class="text4">File Type</div></td>
      <td class="topper"><div class="text4">File Size</div></td>
      <td class="topper" colspan=2><div class="text4">Actions</div></td>
    </tr>
<?php

		// ===================================================================
		// Uploaded files (list view)
		// ===================================================================
      if ($handle = opendir($datapath)) {
        while (false !== ($entry = readdir($handle))) {
          if(substr($entry, -3) == "dat"){
              $filepathfull = $datapath."/".$entry;
              $readdata = fopen($filepathfull, 'r');
              $readline = fgets($readdata);
              fclose($readdata);
              $pieces = explode($parsdeliminator , $readline);
?>
    <tr class="data">
      <td><div class=text5><?php echo $pieces[2]; ?></div></td>
      <td><div class=text5><?php echo $pieces[3]; ?></div></td>
      <td><div class=text5><?php echo $pieces[4]; ?></div></td>
      <td style="width:30px;"><div class=text5><a href="<?php echo $pieces[1]; ?><?php echo $pieces[2]; ?>"><img src="images/download.png"></a></div></td>
      <td style="width:30px;"><div class=text5><a href="filedelete.php?app=<?php echo $_GET["app"]; ?>&rec=<?php echo $_GET["rec"]; ?>&file=<?php echo $pieces[0]; ?>"><img src="images/drop.gif"></a></div></td>
    </tr>
<?php     
              
              
        }
      }
    }
  }

		// ===================================================================

?>
  </table>
<!-- Attached Files -->