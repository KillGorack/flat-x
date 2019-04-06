<?php $it = microtime(true); ?>
<!DOCTYPE html>
<HTML>
<HEAD>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="style.css" />
  <TITLE>nothing really..</TITLE>
</HEAD>

<BODY>

<div class="FullWrapper">
<div class="banner"></div>

<!-- Menu Start -->
<div class="NavSpace">
<a href="index.php" class="FirstNav">Home</a>
<?php


	// ===================================================================
		$parsdeliminator = "|*#*|";
		$filepath = "Data/Applications";
		date_default_timezone_set('America/New_York');
	// ===================================================================

	if ($handle = opendir('Data\Applications')) {

		while (false !== ($entry = readdir($handle))) {
			if(substr($entry, -3) == "dat"){
				$showlink = "True";
				$filepathfull = $filepath."/".$entry;
				$counter = 0;
				foreach (file($filepathfull) as $readline){
					$pieces = explode($parsdeliminator , $readline);

					if ($counter == 3) {
						$varname = $pieces[2];
					} elseif($counter == 4) {
						$linktext = $pieces[2];
					}
					$counter = $counter + 1;
				}
			}
			$filepathfull = "";

If (isset($showlink) and $showlink == "True") {
?>
<a href="index.php?app=<?php echo $varname; ?>&content=list" class="FirstNav"><?php echo $linktext; ?></a>
<?php If (isset($_GET["app"]) and trim($_GET["app"]) == trim($varname)) { ?>
  <div class="SubMenu">
    <div class="text3"><img src="images/sb.gif" alt="bullet" height=10 width=10> <a href="index.php?app=<?php echo $varname; ?>&content=list">List view</a></div>
    <div class="text3"><img src="images/sb.gif" alt="bullet" height=10 width=10> <a href="add.php?app=<?php echo $varname; ?>">Add A Record</a></div>
    <div class="text3"><img src="images/sb.gif" alt="bullet" height=10 width=10> <a href="search.php?app=<?php echo $varname; ?>">Search</a></div>
  </div>
<?php } ?>
<?php
}

		}

		closedir($handle);
	}
?>
</div>
<!-- Menu End -->


<div class="ContentContainer">
<div class="InnerContentContainer">
<!-- Content Start -->
