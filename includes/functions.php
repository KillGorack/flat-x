<?php

  function pre($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
  }

  class package{

    function __construct(){
      if(count($_GET) > 0){
        $this->qs = $_GET;
      }else{
        $this->qs = false;
      }
      if(isset($this->qs['app'])){
        $this->app = $this->qs['app'];
      }else{
        $this->app = false;
      }
      $this->delim = "|*#*|";
    }

    function get_package(){
      $package = array();
      if($this->app <> false){
        $package['fieds'] = $this->fields()[0];
        $package['app'] = $this->fields()[1];
      }
      return $package;
    }

    function fields(){
      $apppath ="data/applications/".$this->app.".dat";
      $apparray = array();
      $fieldarray = array();
      $counter = 0;
      foreach (file($apppath) as $readline){
      $pieces = explode($this->delim , $readline);
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
      return array($fieldarray, $apparray);
    }

  }

?>
