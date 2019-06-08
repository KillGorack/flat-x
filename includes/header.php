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
		$filepath = "data/applications";
		date_default_timezone_set('America/New_York');
	// ===================================================================

	if ($handle = opendir('./data/applications')) {
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
        $navi[$linktext] = array(
          'list' => 'index.php?app='.$varname.'&content=list',
          'add' => 'index.php?app='.$varname.'&function=add',
          'search' => 'index.php?app='.$varname.'&function=search',
          'show' => $showlink,
          'var' => $varname
        );
      }
		}
		closedir($handle);
	}

  ksort($navi);

  foreach($navi as $key => $nav){
    echo "<a href=\"".$nav['list']."\" class=\"FirstNav\">".$key."</a>";
    If (isset($_GET["app"]) and trim($_GET["app"]) == trim($nav['var'])) {
      echo "<div class=\"SubMenu\">";
      echo "<div class=\"text3\"><img src=\"images/sb.gif\" alt=\"bullet\" height=10 width=10> <a href=\"".$nav['list']."\">List</a></div>";
      echo "<div class=\"text3\"><img src=\"images/sb.gif\" alt=\"bullet\" height=10 width=10> <a href=\"".$nav['add']."\">Add</a></div>";
      echo "<div class=\"text3\"><img src=\"images/sb.gif\" alt=\"bullet\" height=10 width=10> <a href=\"".$nav['search']."\">Search</a></div>";
      echo "</div>";
    }
  }

?>
</div>
<!-- Menu End -->


<div class="ContentContainer">
<div class="InnerContentContainer">
<!-- Content Start -->
