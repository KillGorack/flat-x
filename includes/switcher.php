<?php

  $fx_obj = new package;
  $package = $fx_obj->get_package();

  if(isset($package['qs']['app'])){
    $apppath ="data/applications/".$package['qs']['app'].".dat";
    if(!file_exists($apppath)){
      unset($ap);
    }else{
      $ap = $package['qs']['app'];
    }
  }

  if(isset($ap) and isset($package['qs']['content'])){
    if($package['qs']['content'] == "list"){
      include('./includes/modules/list.php');
    }
    if($package['qs']['content'] == "detail" and isset($package['qs']['rec'])){
      include('./includes/modules/detail.php');
    }
  }

  if(isset($ap) and isset($package['qs']['function'])){
    if($package['qs']['function'] == "delete" and isset($package['qs']['rec'])){
      include('./includes/modules/delete.php');
    }
    if($package['qs']['function'] == "add"){
      include('./includes/modules/add.php');
    }
  }

?>
