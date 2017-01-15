<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="<?php echo 'page-' . str_replace('/', ' page-', $url->path); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <title>Control Panel</title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body>
    <?php Shield::get(__DIR__ . DS . 'menu.php'); ?>