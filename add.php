<?php include 'includes/header.php'; ?>
<p>
<?php
$apppath ="data/applications/".$_GET["app"].".dat";
$parsdeliminator = "|*#*|";

if (!file_exists($apppath)) {

  ?><div class="text2">No such application!</div><?php

} else {

	// ===================================================================
	// Create array from file (For app, and its fields)
	// ===================================================================
    $apparray = array();
    $fieldarray = array();
    $counter = 0;
    foreach (file($apppath) as $readline){
    $pieces = explode($parsdeliminator , $readline);
      If ($counter > 2 and $counter < 13) {
          $apparray[] = Trim($pieces[2]);
      }
      If (trim($pieces[0]) == "field") {
          $fieldarray[] = array($pieces[0], $pieces[1], $pieces[2], $pieces[3], $pieces[4], $pieces[5], $pieces[6], $pieces[7], $pieces[8], $pieces[9]);
      }
      If (trim($pieces[0]) == "divider") {
          $fieldarray[] = array("divider");
      }
      If (trim($pieces[0]) == "heading") {
          $fieldarray[] = array($pieces[0], $pieces[1]);
      }
      $counter = $counter + 1;
    }
	// ===================================================================


  if($_SERVER['REQUEST_METHOD'] == 'POST') {


    // ===================================================================
    // Putting the form post through some critical observation.
    // ===================================================================
      $stopforerror = "False";
      $errormessage = "";
      foreach($fieldarray as $linearray) {
        If (trim($linearray[5]) == "True" And trim($_POST[trim($linearray[3])]) == ""){
          $stopforerror = "True";
          $errormessage = $errormessage."Sorry [".$linearray[2]."] is required<br>";
        }
        If ($_POST[trim($linearray[3])] <> "") {
          If (trim($linearray[1]) == "number" And !is_numeric($_POST[trim($linearray[3])])) {
            $stopforerror = "True";
            $errormessage = $errormessage."Sorry [".$linearray[2]."] is required to be numeric<br>";
          }
        }
        // date
        If ($_POST[trim($linearray[3])] <> "") {
          If (trim($linearray[1]) == "date" And strtotime($_POST[trim($linearray[3])]) == false) {
            $stopforerror = "True";
            $errormessage = $errormessage."[".$linearray[2]."] is required to be in date format<br>";
          }
        }
      }
    // ===================================================================


    if($stopforerror == "True") {

      echo "<div class=alert><img src=images/alert.png><hr>".$errormessage."</div>";

    } else {

		// ===================================================================
		// Directory creation, getting a filecount, generating filename.
		// ===================================================================
      $datapath = "data/records"."/".$apparray[0];
			if (!file_exists($datapath)) {
				mkdir($datapath, 0755, true);
			}
      $filename = date('U');
      $filenameext = ".txt";
		// ===================================================================
		// Building data to write, filter, removing breaks, and the like..
		// ===================================================================
			$recordcontents = $filename.$parsdeliminator;
			foreach($fieldarray as $linearray) {
        If ($linearray[0] == "field") {
        $str = str_replace("\n", '<br>', $_POST[trim($linearray[3])]);
          $recordcontents = $recordcontents.$str.$parsdeliminator;
        }
			}
		// ===================================================================
		// Write the file
		// ===================================================================
			$ourFileName = $datapath."/".$filename.$filenameext;
			$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
			fwrite($ourFileHandle, $recordcontents."active");
			fclose($ourFileHandle);
		// ===================================================================

		echo "<div class=alert><img src=images/alert.png><hr>"."Work done, redirecting"."</div>";

		?><meta http-equiv="refresh" content="1;URL='http://localhost/testing/detail.php?app=<?php echo $apparray[0]; ?>&rec=<?php echo $filename; ?>'" /> <?php

    }

	} else {

    $divider = "<tr style=\"height:10px;\"><td colspan=\"2\"></td></tr><tr style=\"height:1px;\"><td colspan=\"2\" bgcolor=\"#FFFFFF\"></td></tr><tr style=\"height:10px;\"><td colspan=\"2\"></td></tr>";

?>
<form METHOD="POST" ACTION="">
  <div class="form_container">
  <table cellpadding="0" width="427" cellspacing="0" border="0">
<?php

	// ===================================================================
	// create form from array..
	// ===================================================================

    foreach($fieldarray as $linearray) {
      if(count($linearray)> 2){
        $fieldtype = trim($linearray[1]);
        $humanname = trim($linearray[2]).": ";
        $variablename = trim($linearray[3]);
        $maxlength = trim($linearray[4]);
        $required = trim($linearray[5]);
        $onindex = trim($linearray[6]);
        $ondetail = trim($linearray[7]);
        $selection = trim($linearray[8]);
        $unique = trim($linearray[9]);
        If (trim($linearray[5]) == "True"){$reqmark = "*";}else{$reqmark = " ";}
      }
	// ===================================================================
	// Seperator!
	// ===================================================================
      If ($linearray[0] == "divider") {
        echo "    ".$divider."<!-- seperator -->";
        echo "\r\n";
	// ===================================================================
	// heading..
	// ===================================================================
      } elseif($linearray[0] == "heading") {
        ?>    <tr style="height:27px;"><td colspan="2"><div class="text2"><?php echo $linearray[1]; ?></div></td></tr><?php
        echo "\r\n";
	// ===================================================================

      } else {

    // ===================================================================
    // Generic form field....
    // ===================================================================
        if ($fieldtype == "text" or $fieldtype == "number" or $fieldtype == "date") {
          ?>    <tr style="height:27px;"><td><?php echo $humanname.$reqmark; ?></td><td align="right"><input type="text" name="<?php echo $variablename; ?>" maxlength="<?php echo $maxlength; ?>" size="42"></td></tr><?php
          echo "\r\n";
    // ===================================================================
    // textarea form field..
    // ===================================================================
        }elseif($fieldtype == "textarea") {
          ?>    <tr><td colspan="2"><center><?php echo $humanname.$reqmark; ?></center><textarea rows="4" cols="50" name="<?php echo $variablename; ?>"></textarea></td></tr><?php
    // ===================================================================
    // yes-no form field
    // ===================================================================
        }elseif($fieldtype == "yes-no") {
          ?>
          <tr style="height:27px;">
            <td><?php echo $humanname.$reqmark; ?></td>
            <td align="right">
              <img src="images/on.png"><input type="radio" checked name="<?php echo $variablename; ?>" value="<img src=images/on.png>">
              <img src="images/off.png"><input type="radio" name="<?php echo $variablename; ?>" value="<img src=images/off.png>">
            </td>
          </tr><?php
    // ===================================================================
    // unknown form field (ERROR!)
    // ===================================================================
        } else {
          echo "<tr height=\"27\"><td colspan=\"2\"><center>There is an error with this field type[".$fieldtype."], please check configuration..</center></td></tr>"."\r\n";
    // ===================================================================
        }
      }
    }
	// ===================================================================

		?>
    <tr height="27"><td colspan=2><input type="image" src="images/submit.png" alt="Submit Form" /></td></tr>
  </table>
  </div>
</form><?php

}

	// ===================================================================
	// Destroy arrays
	// ===================================================================
    unset($apparray);
    unset($fieldarray );
	// ===================================================================

}
?>

<p>
<?php include 'includes/footer.php'; ?>
