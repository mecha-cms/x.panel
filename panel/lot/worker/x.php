<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="p-x x-<?php echo $lot[0]; ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <meta name="robots" content="noindex">
    <title><?php echo (isset(HTTP::$message[$lot[0]]) ? HTTP::$message[$lot[0]] : $lot[0]) . ' &#x00B7; &#x0CA0;&#x005F;&#x0CA0; &#x00B7; ' . $language->__title; ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body spellcheck="false">
    <p>&#x0CA0;&#x005F;&#x0CA0;</p>
  </body>
</html>