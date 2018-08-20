<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class>
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php echo To::text($site->trace); ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
    <?php echo str_replace('"stylesheet"', '"stylesheet/less"', Asset::css(EXTEND . '/panel/lot/asset/less/panel.less')); ?>
    <?php echo Asset::js(EXTEND . '/panel/lot/asset/index.js'); ?>
  </head>
  <body>
    <?php Shield::get(__DIR__ . DS . 'nav.php'); ?>
    <div class="desk">