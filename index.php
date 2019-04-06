<?php

  include 'includes/header.php';
  include 'includes/functions.php';

  $package_obj = new package;
  $package = $package_obj->get_package();

  include 'includes.switcher.php';
  include 'includes/footer.php';

?>
