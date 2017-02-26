<?php

class Response
{
  public static function generateIndex($param){
    require"../header.html";
    echo '<script type=\'text/javascript\'>',$param,'</script></head>';
    require"../body.html";
  }
}
 ?>
