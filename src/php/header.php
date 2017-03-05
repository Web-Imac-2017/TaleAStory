<?php
?>
<head>
  <title>Tale A Story</title>
  <meta name="description" content="Soit le maitre de ta propre aventure dans Tale A Story" />
  <link rel="stylesheet" type="text/css" href="<?= router\Router::$webRoot ?>assets/css/main.min.css">
  <script type="text/javascript" src= "<?= router\Router::$webRoot ?>assets/js/main.min.js"></script>
  <script type="text/javascript">
    document.globalBack.setObject(<?= json_encode($param) ?>);
    document.globalBack.set('webRoot', '<?= router\Router::$webRoot ?>');
  </script>
</head>
