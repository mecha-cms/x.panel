<!DOCTYPE html>
<html dir="ltr" class>
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width" name="viewport">
    <meta content="noindex" name="robots">
    <title><?= w($t->reverse); ?></title>
    <link href="<?= $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body spellcheck="false">
  <?php

$panel = require __DIR__ . DS . '-panel.php';
$icon = require __DIR__ . DS . '-icon.php'; // Require icon(s) later

echo $icon . $panel; // But load icon(s) first

  ?>
  </body>
</html>