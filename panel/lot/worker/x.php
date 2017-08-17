<?php $i = $config->status('404'); HTTP::status($i); ?>
<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="p-x x-<?php echo $i; ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <meta name="robots" content="noindex">
    <title><?php echo (isset(HTTP::$message[$i]) ? Config::get('_language.message_' . $i, HTTP::$message[$i]) : $i) . ' &#x00B7; &#x0CA0;&#x005F;&#x0CA0; &#x00B7; ' . $language->__title; ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body spellcheck="false">
    <p>&#x0CA0;&#x005F;&#x0CA0;</p>
  </body>
</html>