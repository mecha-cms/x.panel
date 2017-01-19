<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="<?php echo 'path-' . str_replace('/', ' path-', $path); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <title>Control Panel</title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body spellcheck="false">
    <?php Shield::get(__DIR__ . DS . 'menu.php'); ?>