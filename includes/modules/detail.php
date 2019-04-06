<?php

      echo "<p>";
	// ===================================================================
	// Crack open the file, and chop it up...
	// ===================================================================
      $filepath = "data/records"."/".$package['qs']["app"]."/".$package['qs']["rec"].".txt";
      $readdata = fopen($filepath, 'r');
      $readline = fgets($readdata);
      fclose($readdata);
      $pieces = explode($parsdeliminator , $readline);
	// ===================================================================
	// Record output..
	// ===================================================================
      echo "<div class=\"form_container\">";
      echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"427\">";
      $divider = "<tr height=\"10\"><td colspan=\"2\"></td></tr><tr height=\"1\"><td colspan=\"2\" bgcolor=\"#FFFFFF\"></td></tr><tr height=\"10\"><td colspan=\"2\"></td></tr>";
      $rowcounter = 0;
      $fieldcount = 0;
      foreach($package['fields'] as $linearray) {
        if(trim($package['fields'][$rowcounter][0]) == "field"){
          $fieldcount = $fieldcount + 1;
          if(trim($package['fields'][$rowcounter][1]) == "textarea"){
            echo "<tr><td colspan=\"2\"><center>".$package['fields'][$rowcounter][2]."</center><br>".$pieces[$fieldcount]."</td></tr>";
          }else{
            echo "<tr height=\"27\"><td>".$package['fields'][$rowcounter][2].": </td><td align=\"right\">".$pieces[$fieldcount]."</td></tr>";
          }
        }elseif(trim($package['fields'][$rowcounter][0]) == "divider"){
          echo $divider;
        }elseif(trim($package['fields'][$rowcounter][0]) == "heading"){
          echo "<tr height=\"27\"><td colspan=\"2\"><div class=\"text2\">".$package['fields'][$rowcounter][1]."</div></td></tr>";
        }
        $rowcounter = $rowcounter + 1;
      }
      echo "<tr height=\"27\">";
      echo "<td colspan=\"2\">";
      echo "<a href=\"index.php\"><img src=\"images/edit.gif\" alt=\"edit\" style=\"margin-right:10px;\"></a>";
      echo "<a href=\"index.php\"><img src=\"images/drop.gif\" alt=\"delete\"></a>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</div>";
	// ===================================================================
  // files...
  // ===================================================================
      if($package['app'][7] == "True") {
        include 'includes/exchange.php';
      }
  // ===================================================================
    echo "<p>";
    
?>
