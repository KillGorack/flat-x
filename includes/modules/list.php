<p>
<?php

	// ===================================================================
	// Create array from file
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
      $counter = $counter + 1;
    }
    ?><div class="text2"><?php echo $apparray[1]; ?></div><hr class="rule1"><?php
	// ===================================================================
	// Get a filecount. 0.04 seconds. (UGH!)
	// Write a counter file to avoid this!
	// ===================================================================
    $filepath = "data/records"."/".trim($_GET["app"]);
    $recordcount = 0;
    if ($handle = opendir($filepath)) {
      while (false !== ($entry = readdir($handle))) {
        if(substr($entry, -3) == "txt"){
          $recordcount = $recordcount + 1;
        }
      }
    }
	// ===================================================================
	// Preperation for paging..
	// ===================================================================
    $pagesize = $apparray[8];
    $focus = 2;
    if (!isset($_GET["page"])) {
      $currentpage = 1;
    }else{
      $currentpage = Trim($_GET["page"]);
    }
    $startrecord = $recordcount - (($currentpage - 1) * $pagesize);
    $endrecord = $startrecord - $pagesize;
	// ===================================================================
	// Create array with files we need.. 0.04 seconds. (UGH!)
	// ===================================================================
    $recordcounter = 0;
    $tblarray = array();
    if ($handle = opendir($filepath)) {
      while (false !== ($entry = readdir($handle))) {
        if(substr($entry, -3) == "txt"){
          // ===================================================================
          // Open files inside of here ONLY!
          // ===================================================================
            if ($recordcounter < $startrecord and $recordcounter >= $endrecord) {
              $filepathfull = $filepath."/".$entry;
              $readdata = fopen($filepathfull, 'r');
              $readline = fgets($readdata);
              fclose($readdata);
              $tblarray[] = $readline;
            }
          // ===================================================================
          $recordcounter = $recordcounter  + 1;
        }
      }
    }
	// ===================================================================
	// Column headers...
	// ===================================================================
	  echo "  <table class=cells>"."\r\n";
    echo "    <tr>"."\r\n";
    foreach($fieldarray as $linearray) {
      If ($linearray[6] == "True") {
        ?>
          <td class="topper"><div class="text4"><?php echo $linearray[2]; ?></div></td>
        <?php
      }
    }
    ?>
    <td class="topper" colspan="2"><div class="text4">Actions</div></td>
    </tr>
    <?php
	// ===================================================================
	// Spit out the data in all its glory!
	// ===================================================================
    $reversed = array_reverse($tblarray);
    foreach($reversed  as $filecontents) {
      $datapieces = explode($parsdeliminator , $filecontents);
      $cellcounter = 0;
      ?>    <tr onClick="location.href='index.php?app=<?php  echo trim($_GET["app"]);?>&amp;rec=<?php  echo $datapieces[0]; ?>&content=detail';" class="data"><?php
        foreach($datapieces  as $cellvalue) {
          If (isset($fieldarray[$cellcounter-1][6]) and trim($fieldarray[$cellcounter-1][6]) == "True" And $cellcounter > 0) {
            echo "      <td><div class=text5>".Trim($cellvalue)."</div></td>"."\r\n";
          }
        $cellcounter = $cellcounter + 1;
        }
      ?>      <td style="width:30px;"><div class="text4"><a href="edit.php?app=<?php echo trim($_GET["app"]) ?>&amp;rec=<?php echo $datapieces[0]; ?>"><img src="images/edit.gif" alt="edit"></a></div></td><td  style="width:30px;"><div class="text4"><a href="delete.php?app=<?php echo trim($_GET["app"]) ?>&amp;rec=<?php echo $datapieces[0]; ?>"><img src="images/drop.gif" alt="delete"></a></div></td><?php
      echo "\r\n"."    </tr>"."\r\n";
    }
	// ===================================================================
	// Finish the table
	// ===================================================================
    echo "  </table>"."\r\n";
	// ===================================================================
	// paging links
	// ===================================================================
    $totalpages = ceil($recordcount / $pagesize);
    if($recordcount > $pagesize){
      ?>
       <table class="bottom_info"><tr><td><table class="paging">
      <tr>
      <?php if($currentpage > 1){ ?>
      <td style="background-color: #C4C4C4;";><a href="list.php?app=<?php echo $_GET["app"]; ?>&page=1"><img src="images/first.gif"></a></td>
      <td style="background-color: #C4C4C4;";><a href="list.php?app=<?php echo $_GET["app"]; ?>&page=<?php echo ($currentpage - 1); ?>"><img src="images/left.gif"></a></td>
      <?php } ?>

      <?php
      for ($x=1; $x<=$totalpages; $x++){
        If ($x > $currentpage - ($focus + 1) and $x < $currentpage + ($focus + 1)) {
          if($x == $currentpage){
            $cellcolor = "background-color: #AEAEAE;";
          }else{
            $cellcolor = "background-color: #C4C4C4;";
          }
          ?><td style="<?php echo $cellcolor; ?>";><a href="list.php?app=<?php echo $_GET["app"]; ?>&page=<?php echo $x; ?>"><?php echo $x; ?></a></td><?php
        }
      }
      ?>

      <?php if($currentpage < $totalpages){ ?>
      <td style="background-color: #C4C4C4;";><a href="list.php?app=<?php echo $_GET["app"]; ?>&page=<?php echo ($currentpage + 1); ?>"><img src="images/right.gif"></a></td>
      <td style="background-color: #C4C4C4;";><a href="list.php?app=<?php echo $_GET["app"]; ?>&page=<?php echo $totalpages; ?>"><img src="images/last.gif"></a></td>
      <?php } ?>

      </tr>
      </table></td><td align=right>Page <?php echo number_format($currentpage); ?> of <?php echo number_format($totalpages); ?> (<?php echo number_format($recordcount); ?> records)</td></table>

      <?php
    }
	// ===================================================================

?>

<p>
