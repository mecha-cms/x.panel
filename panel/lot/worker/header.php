<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="<?php echo 'path-' . str_replace('/', ' path-', $__path); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php echo $language->{$__chops[0]} . ' &#x00B7; ' . $language->__title; ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body spellcheck="false">
<?php Shield::get([
    $__path_shield . DS . 'nav.php',
    __DIR__ . DS . 'nav.php'
]); ?>