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

$svg = "";
$panel = _\lot\x\panel\lot(['lot' => $_['lot']], 0); // Load layout first, to queue the icon data

// Build icon(s)
if (!empty($GLOBALS['SVG'])) {
    $svg .= '<svg xmlns="http://www.w3.org/2000/svg" display="none">';
    foreach ($GLOBALS['SVG'] as $k => $v) {
        $svg .= '<symbol id="i:' . $k . '" viewBox="0 0 24 24">';
        $svg .= 0 === strpos($v, '<') ? $v : '<path d="' . $v . '"></path>';
        $svg .= '</symbol>';
    }
    $svg .= '</svg>';
}

echo $svg . $panel; // Put icon(s) before layout. Why? Because HTML5!

  ?>
  </body>
</html>
