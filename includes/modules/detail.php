<p>
<?php
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



	// ===================================================================
	// Crack open the file, and chop it up...
	// ===================================================================
      $filepath = "data/records"."/".$_GET["app"]."/".$_GET["rec"].".txt";
      $readdata = fopen($filepath, 'r');
      $readline = fgets($readdata);
      fclose($readdata);
      $pieces = explode($parsdeliminator , $readline);
	// ===================================================================



	// ===================================================================
	// Record output..
	// ===================================================================
      ?>
  <div class="form_container">
  <table border="0" cellpadding="0" cellspacing="0" width="427">
    <?php
        $divider = "<tr height=\"10\"><td colspan=\"2\"></td></tr><tr height=\"1\"><td colspan=\"2\" bgcolor=\"#FFFFFF\"></td></tr><tr height=\"10\"><td colspan=\"2\"></td></tr>";
        $rowcounter = 0;
        $fieldcount = 0;
        foreach($fieldarray as $linearray) {
          If (trim($fieldarray[$rowcounter][0]) == "field") {
            $fieldcount = $fieldcount + 1;
            If (trim($fieldarray[$rowcounter][1]) == "textarea") {
              ?>    <tr><td colspan="2"><center><?php echo $fieldarray[$rowcounter][2]; ?></center><br><?php echo $pieces[$fieldcount]; ?></td></tr><?php
              echo "\r\n";
            } else {
              ?>    <tr height="27"><td><?php echo $fieldarray[$rowcounter][2]; ?>: </td><td align="right"><?php echo $pieces[$fieldcount]; ?></td></tr><?php
              echo "\r\n";
            }
          } elseif (trim($fieldarray[$rowcounter][0]) == "divider") {
            echo "    ".$divider."<!-- Seperator -->"."\r\n";
          } elseif (trim($fieldarray[$rowcounter][0]) == "heading") {
            ?><tr height="27"><td colspan="2"><div class="text2"><?php echo $fieldarray[$rowcounter][1]; ?></div></td></tr><?php
            echo "\r\n";
          }
          $rowcounter = $rowcounter + 1;
        }
      ?>
    <tr height="27">
      <td colspan=2>
        <a href="edit.php?app=<?php echo trim($_GET["app"]) ?>&amp;rec=<?php echo $_GET["rec"]; ?>"><img src="images/edit.gif" alt="edit"></a>
        <a href="delete.php?app=<?php echo trim($_GET["app"]) ?>&amp;rec=<?php echo $_GET["rec"]; ?>"><img src="images/drop.gif" alt="delete"></a>
      </td>
    </tr>
  </table>
  </div>


<?php if($apparray[7] == "True") { ?>
<?php include 'includes/exchange.php'; ?>
<?php } ?>
<p>
