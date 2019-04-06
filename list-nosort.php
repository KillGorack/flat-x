<?php include 'includes/header.php'; ?>
<p>
<?php
$apppath ="data/applications/".$_GET["app"].".dat";
$parsdeliminator = "|*#*|";

	// ===================================================================
	// Preperation for paging..
	// ===================================================================
    $pagesize = 10;
    $focus = 2;
    if ($_GET["page"] == "") {
      $currentpage = 1;
    }else{
      $currentpage = Trim($_GET["page"]);
    }
    $startrecord = ($currentpage * $pagesize) - $pagesize + 1;
    $endrecord = $startrecord  + $pagesize - 1;
	// ===================================================================

if (!file_exists($apppath)) {

  echo "<div class=text2>No such application!</div>";

} else {


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
	// ===================================================================



  ?><div class="text2"><?php echo $apparray[1]; ?></div><hr class="rule1"><?php


  echo "  <table class=cells>"."\r\n";
  echo "    <tr>"."\r\n";
  
	// ===================================================================
	// Column headers...
	// ===================================================================
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
  
  
  
	// ===================================================================
	// Data in all its glory!
	// ===================================================================
    $filepath = "data/records"."/".trim($_GET["app"]);
    $recordcount = 0;
    
    

    if ($handle = opendir($filepath)) {
    
      while (false !== ($entry = readdir($handle))) {
      
        if(substr($entry, -3) == "txt"){
          $recordcount = $recordcount + 1;

        // ===================================================================
        // Records to be shown..
        // ===================================================================
          If ($recordcount >= $startrecord and $recordcount <= $endrecord) { // page logic, opening files should not happen outside of this!!
            $filepathfull = $filepath."/".$entry;
            $readdata = fopen($filepathfull, 'r');
            $readline = fgets($readdata);
            fclose($readdata);
            $pieces = explode($parsdeliminator , $readline);
            ?>    <tr onClick="location.href='detail.php?app=<?php  echo trim($_GET["app"]);?>&amp;rec=<?php  echo $pieces[0]; ?>';" class="data"><?php
            echo "\r\n";
            $rowcounter = 0;
            foreach($pieces as $key) {
              If (Trim($fieldarray[$rowcounter-1][6]) == "True" And $rowcounter > 0) {
                echo "      <td><div class=text5>".Trim($key)."</div></td>"."\r\n";
              }
            $rowcounter = $rowcounter + 1;
            }
            ?>      <td style="width:30px;"><div class="text4"><a href="edit.php?app=<?php echo trim($_GET["app"]) ?>&amp;rec=<?php echo $pieces[0]; ?>"><img src="images/edit.gif" alt="edit"></a></div></td><td  style="width:30px;"><div class="text4"><a href="delete.php?app=<?php echo trim($_GET["app"]) ?>&amp;rec=<?php echo $pieces[0]; ?>"><img src="images/drop.gif" alt="delete"></a></div></td><?php
            echo "\r\n"."    </tr>"."\r\n";
          } // page logic
        // ===================================================================
        
        }
      }
    }
	// ===================================================================

  
  echo "  </table>"."\r\n";
  
$totalpages = ceil($recordcount / $pagesize);

if($recordcount > $pagesize){

	// ===================================================================
	// paging links
	// ===================================================================
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
	// ===================================================================
	
}

}
?>

<p>
<?php include 'includes/footer.php'; ?>