<?php

class Response
{
  public static function generateIndex(){
    global $param;
    $addScript = "<script type="'text/javascript'" src="'assets/js/main.min.js'">".$param."</script>";
    require"../header.html";
    echo $addScript;
    require"../body.html";
  }
}


 ?>
