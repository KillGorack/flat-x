
<p>
<?php

  if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ===================================================================
    // Putting the form post through some critical observation.
    // ===================================================================
        $stopforerror = "False";
        $errormessage = "";
        foreach($package['fields'] as $linearray) {
          if(count($linearray) > 3){
            if(trim($linearray[5]) == "True" And trim($_POST[trim($linearray[3])]) == ""){
              $stopforerror = "True";
              $errormessage = $errormessage."Sorry [".$linearray[2]."] is required<br>";
            }
            if($_POST[trim($linearray[3])] <> "") {
              if(trim($linearray[1]) == "number" And !is_numeric($_POST[trim($linearray[3])])) {
                $stopforerror = "True";
                $errormessage = $errormessage."Sorry [".$linearray[2]."] is required to be numeric<br>";
              }
            }
            if($_POST[trim($linearray[3])] <> "") {
              if(trim($linearray[1]) == "date" And strtotime($_POST[trim($linearray[3])]) == false) {
                $stopforerror = "True";
                $errormessage = $errormessage."[".$linearray[2]."] is required to be in date format<br>";
              }
            }
          }
        }
    // ===================================================================


    if($stopforerror == "True"){

      echo "<div class=alert><img src=images/alert.png><hr>".$errormessage."</div>";

    }else{

		// ===================================================================
		// Directory creation, getting a filecount, generating filename.
		// ===================================================================
        $datapath = "data/records"."/".$package['app'][0];
  			if (!file_exists($datapath)) {
  				mkdir($datapath, 0755, true);
  			}
        $filename = date('U');
        $filenameext = ".txt";
		// ===================================================================
		// Building data to write, filter, removing breaks, and the like..
		// ===================================================================
  			$recordcontents = $filename.$parsdeliminator;
  			foreach($package['fields'] as $linearray) {
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

		?><meta http-equiv="refresh" content="1;URL='index.php?app=<?php echo $package['app'][0]; ?>&rec=<?php echo $filename; ?>&content=detail'" /> <?php

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

    foreach($package['fields'] as $linearray) {
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

?>

<p>
