<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="<?php echo 'page-' . str_replace('/', ' page-', $url->path); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <title>Control Panel</title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
    <?php

    echo Asset::css(__DIR__ . DS . 'asset' . DS . 'css' . DS . 'mecha.min.css');
    echo Asset::css(__DIR__ . DS . 'asset' . DS . 'css' . DS . 'mecha.t-i-b.min.css');
    echo Asset::css(__DIR__ . DS . 'asset' . DS . 'css' . DS . 'mecha.code-mirror.min.css');

    ?>
  </head>
  <body>
    <?php include __DIR__ . DS . 'header.php'; ?>