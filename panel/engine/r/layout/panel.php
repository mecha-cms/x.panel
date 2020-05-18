<!DOCTYPE html>
<html class dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width" name="viewport">
    <meta content="noindex" name="robots">
    <title<?php echo !empty($state->x->panel->fetch) ? ' data-is-pull="' . i('Loading...') . '" data-is-push="' . i('Uploading...') . '" data-is-search="' . i('Searching...') . '"' : ""; ?>><?= w($t->reverse); ?></title>
    <link href="<?= $url; ?>/favicon.ico" rel="icon">
  </head>
  <body spellcheck="false">
  <?php

$panel = require __DIR__ . DS . '-panel.php';
$icon = require __DIR__ . DS . '-icon.php'; // Require icon(s) later

echo $icon . $panel; // But load icon(s) first

  ?>
  </body>
</html>
