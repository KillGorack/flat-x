<p>
<?php
$apppath         = "data/applications/" . $_GET["app"] . ".dat";
$parsdeliminator = "|*#*|";

if (!file_exists($apppath)) {

    echo "<div class=text2>No such application!</div>";

} else {

    // ===================================================================
    // Create array from file (For app, and its fields)
    // ===================================================================
    $apparray   = array();
    $fieldarray = array();
    $counter    = 0;
    foreach (file($apppath) as $readline) {
        $pieces = explode($parsdeliminator, $readline);
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
    $filepath = "data/records" . "/" . $_GET["app"] . "/" . $_GET["rec"] . ".txt";
    $readdata = fopen($filepath, 'r');
    $readline = fgets($readdata);
    fclose($readdata);
    $piecesdata = explode($parsdeliminator, $readline);
    // ===================================================================

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // ===================================================================
        // Putting the form post through some critical observation.
        // ===================================================================
        $stopforerror = "False";
        $errormessage = "";
        foreach ($fieldarray as $linearray) {
            // Required
            if (count($linearray) > 2) {
                if (trim($linearray[5]) == "True" And trim($_POST[trim($linearray[3])]) == "") {
                    $stopforerror = "True";
                    $errormessage = $errormessage . "[" . $linearray[2] . "] is required<br>";
                }
                // Numeric
                If ($_POST[trim($linearray[3])] != "") {
                    If (trim($linearray[1]) == "number" And !is_numeric($_POST[trim($linearray[3])])) {
                        $stopforerror = "True";
                        $errormessage = $errormessage . "[" . $linearray[2] . "] is required to be numeric<br>";
                    }
                }
                // date
                If ($_POST[trim($linearray[3])] != "") {
                    If (trim($linearray[1]) == "date" And strtotime($_POST[trim($linearray[3])]) == false) {
                        $stopforerror = "True";
                        $errormessage = $errormessage . "[" . $linearray[2] . "] is required to be in date format<br>";
                    }
                }
            }
        }
        // ===================================================================

        if ($stopforerror == "True") {

            echo "<div class=alert><img src=images/alert.png><hr>" . $errormessage . "</div>";

        } else {

            // ===================================================================
            // Building data to write..
            // ===================================================================
            $fieldcounter   = 1;
            $recordcontents = $_POST["ID"] . $parsdeliminator;
            foreach ($fieldarray as $linearray) {
                If ($linearray[0] == "field") {
                    if ($fieldcounter > 0) {
                        $str            = str_replace("\n", '<br>', $_POST[trim($linearray[3])]);
                        $recordcontents = $recordcontents . $str . $parsdeliminator;
                    }
                    $fieldcounter = $fieldcounter + 1;
                }
            }

            // ===================================================================
            // Write the file
            // ===================================================================
            $ourFileName   = $filepath;
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
            fwrite($ourFileHandle, $recordcontents . "active");
            fclose($ourFileHandle);
            // ===================================================================

            echo "<div class=alert><img src=images/alert.png><hr>" . "Work done, redirecting" . "</div>";
            ?><meta http-equiv="refresh" content="1;URL='index.php?app=<?php echo $apparray[0]; ?>&rec=<?php echo $_POST["ID"]; ?>&content=detail'" /><?php

        }

    } else {

        $divider = "<tr height=\"10\"><td colspan=\"2\"></td></tr><tr height=\"1\"><td colspan=\"2\" bgcolor=\"#FFFFFF\"></td></tr><tr height=\"10\"><td colspan=\"2\"></td></tr>";

        ?>
<form METHOD="POST" ACTION="">
<input type="hidden" name="ID" value="<?php echo "$piecesdata[0]"; ?>">
  <div class="form_container">
  <table border="0" cellpadding="0" cellspacing="0" width="427">
	<?php

        // ===================================================================
        // create form from array..
        // ===================================================================
        $fieldcounter = 0;
        foreach ($fieldarray as $linearray) {

            if (count($linearray) > 2) {
                $fieldtype    = trim($linearray[1]);
                $humanname    = trim($linearray[2]) . ": ";
                $variablename = trim($linearray[3]);
                $maxlength    = trim($linearray[4]);
                $required     = trim($linearray[5]);
                $onindex      = trim($linearray[6]);
                $ondetail     = trim($linearray[7]);
                $selection    = trim($linearray[8]);
                $unique       = trim($linearray[9]);

                If (trim($linearray[5]) == "True") {$reqmark = "*";} else { $reqmark = " ";}
            }

            // ===================================================================
            // Seperator!
            // ===================================================================
            If ($linearray[0] == "divider") {
                echo "    " . $divider . "<!-- seperator -->";
                echo "\r\n";
                // ===================================================================
                // Heading..
                // ===================================================================
            } elseif ($linearray[0] == "heading") {
                ?><tr height="27"><td colspan="2"><div class="text2"><?php echo $linearray[1]; ?></div></td></tr><?php
echo "\r\n";
                // ===================================================================

            } elseif ($linearray[0] == "field") {

                $fieldcounter = $fieldcounter + 1;

                // ===================================================================
                // generic form element
                // ===================================================================
                if ($fieldtype == "text" or $fieldtype == "number" or $fieldtype == "date") {
                    ?>    <tr height="27"><td><?php echo $humanname . $reqmark; ?></td><td align="right"><input value="<?php echo $piecesdata[$fieldcounter]; ?>" type="text" name="<?php echo $variablename; ?>" maxlength="<?php echo $maxlength; ?>" ></td></tr><?php
echo "\r\n";
                    // ===================================================================
                    // textarea form element
                    // ===================================================================
                } elseif ($fieldtype == "textarea") {
                    $str = str_replace("<br>", "\n", $piecesdata[$fieldcounter]);
                    ?>    <tr height="27"><td colspan="2"><center><?php echo trim($humanname) . $reqmark; ?></center><textarea rows="4" cols="50" name="<?php echo $variablename; ?>"><?php echo $str; ?></textarea></td></tr><?php
echo "\r\n";
                    // ===================================================================
                    // yes-no form element
                    // ===================================================================
                } elseif ($fieldtype == "yes-no") {
                    If ($piecesdata[$fieldcounter] == "<img src=images/on.png>") {
                        $onmark  = "checked";
                        $offmark = "";
                    } else {
                        $onmark  = "";
                        $offmark = "checked";
                    }
                    ?>
                <tr height="27">
                  <td><?php echo $humanname . $reqmark; ?></td>
                  <td align="right">
                    <img src="images/on.png"><input type="radio" <?php echo $onmark; ?> name="<?php echo $variablename; ?>" value="<img src=images/on.png>">
                    <img src="images/off.png"><input type="radio" <?php echo $offmark; ?> name="<?php echo $variablename; ?>" value="<img src=images/off.png>">
                  </td>
                </tr><?php
echo "\r\n";
                    // ===================================================================
                    // Unknown form element! (ERROR!)
                    // ===================================================================
                } else {
                    ?><tr height="27"><td colspan="2"><center>There is an error with this field type [<?php echo $fieldtype; ?>], please check configuration..</center></td></tr><?php
echo "\r\n";
                }
                // ===================================================================
            }
        }
        // ===================================================================

        ?>
    <tr height="27"><td colspan="2"><input type="image" src="images/submit.png" alt="Submit Form" /></td></tr>
  </table>
  </div>
</form>
<?php

    }

    // ===================================================================
    // Destroy arrays
    // ===================================================================
    unset($apparray);
    unset($fieldarray);
    // ===================================================================

}

?>
<p>
