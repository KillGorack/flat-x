<?php

echo "<p>";
echo "<div class=\"text2\">" . $package['app'][1] . "</div><hr class=\"rule1\">";
// ===================================================================
// Get a filecount.
// ===================================================================
$filepath    = "data/records" . "/" . trim($_GET["app"]);
$recordcount = 0;
if ($handle = opendir($filepath)) {
    while (false !== ($entry = readdir($handle))) {
        if (substr($entry, -3) == "txt") {
            $recordcount = $recordcount + 1;
        }
    }
}
// ===================================================================
// Preperation for paging..
// ===================================================================
$pagesize = $package['app'][8];
$focus    = 2;
if (!isset($_GET["page"])) {
    $currentpage = 1;
} else {
    $currentpage = Trim($_GET["page"]);
}
$startrecord = $recordcount - (($currentpage - 1) * $pagesize);
$endrecord   = $startrecord - $pagesize;
// ===================================================================
// Create array with files we need.. 0.04 seconds. (UGH!)
// ===================================================================
$recordcounter = 0;
$tblarray      = array();
if ($handle = opendir($filepath)) {
    while (false !== ($entry = readdir($handle))) {
        if (substr($entry, -3) == "txt") {
            // =========================================================
            // Open files inside of here ONLY!
            // =========================================================
            if ($recordcounter < $startrecord and $recordcounter >= $endrecord) {
                $filepathfull = $filepath . "/" . $entry;
                $readdata     = fopen($filepathfull, 'r');
                $readline     = fgets($readdata);
                fclose($readdata);
                $tblarray[] = $readline;
            }
            // =========================================================
            $recordcounter = $recordcounter + 1;
        }
    }
}
// ===================================================================
// Column headers...
// ===================================================================
echo "  <table class=cells>" . "\r\n";
echo "    <tr>" . "\r\n";
$shw = array();
foreach ($package['fields'] as $linearray) {
    if (isset($linearray[6]) and $linearray[6] == "True") {
        echo "<td class=\"topper\"><div class=\"text4\">" . $linearray[2] . "</div></td>";
    }
    if (isset($linearray[6])) {
        $shw[] = $linearray[6];
    }
}
echo "<td class=\"topper\" colspan=\"2\"><div class=\"text4\">Actions</div></td>";
echo "</tr>";
// ===================================================================
// Spit out the data..
// ===================================================================
$reversed = array_reverse($tblarray);
foreach ($reversed as $filecontents) {
    $datapieces  = explode($parsdeliminator, $filecontents);
    $cellcounter = 0;
    echo "<tr onClick=\"location.href='index.php?app=" . trim($_GET["app"]) . "&amp;rec=" . $datapieces[0] . "&content=detail';\" class=\"data\">";
    foreach (array_slice($datapieces, 1, count($datapieces) - 1) as $cellvalue) {
        if (isset($shw[$cellcounter]) AND $shw[$cellcounter] == "True") {
            echo "<td><div class=text5>" . Trim($cellvalue) . "</div></td>";
        }
        $cellcounter = $cellcounter + 1;
    }
    echo "<td style=\"width:30px;\">";
    echo "<div class=\"text4\"><a href=\"index.php?app=" . trim($_GET["app"]) . "&amp;rec=" . $datapieces[0] . "&function=edit\"><img src=\"images/edit.gif\" alt=\"edit\"></a></div>";
    echo "</td>";
    echo "<td  style=\"width:30px;\">";
    echo "<div class=\"text4\"><a href=\"index.php?app=" . trim($_GET["app"]) . "&amp;rec=" . $datapieces[0] . "&function=delete\"><img src=\"images/drop.gif\" alt=\"delete\"></a></div>";
    echo "</td>";
    echo "</tr>";
}
// ===================================================================
// Finish the table
// ===================================================================
echo "</table>";
// ===================================================================
// paging links
// ===================================================================
$totalpages = ceil($recordcount / $pagesize);
if ($recordcount > $pagesize) {
    echo "<table class=\"bottom_info\"><tr><td><table class=\"paging\">";
    echo "<tr>";
    if ($currentpage > 1) {
        echo "<td style=\"background-color: #C4C4C4;\";><a href=\"index.php?app=" . $_GET["app"] . "&page=1&content=list\"><img src=\"images/first.gif\"></a></td>";
        echo "<td style=\"background-color: #C4C4C4;\";><a href=\"index.php?app=" . $_GET["app"] . "&page=" . ($currentpage - 1) . "&content=list\"><img src=\"images/left.gif\"></a></td>";
    }
    for ($x = 1; $x <= $totalpages; $x++) {
        if ($x > $currentpage - ($focus + 1) and $x < $currentpage + ($focus + 1)) {
            if ($x == $currentpage) {
                $cellcolor = "background-color: #AEAEAE;";
            } else {
                $cellcolor = "background-color: #C4C4C4;";
            }
            echo "<td style=\"" . $cellcolor . "\";><a href=\"index.php?app=" . $_GET["app"] . "&page=" . $x . "&content=list\">" . $x . "</a></td>";
        }
    }
    if ($currentpage < $totalpages) {
        echo "<td style=\"background-color: #C4C4C4;\";><a href=\"index.php?app=" . $_GET["app"] . "&page=" . ($currentpage + 1) . "&content=list\"><img src=\"images/right.gif\"></a></td>";
        echo "<td style=\"background-color: #C4C4C4;\";><a href=\"index.php?app=" . $_GET["app"] . "&page=" . $totalpages . "&content=list\"><img src=\"images/last.gif\"></a></td>";
    }
    echo "</tr>";
    echo "</table></td><td align=right>Page " . number_format($currentpage) . " of " . number_format($totalpages) . " (" . number_format($recordcount) . " records)</td></table>";
}
// ===================================================================
echo "</p>";

?>
