<!DOCTYPE html>
<html dir="ltr" class>
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width" name="viewport">
    <meta content="noindex" name="robots">
    <title><?php echo w($t->reverse); ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body>

<?php

$content = require __DIR__ . DS . '-panel.php';

if (!empty($GLOBALS['SVG'])) {
    $icons = '<svg xmlns="http://www.w3.org/2000/svg" display="none">';
    foreach ($GLOBALS['SVG'] as $k => $v) {
        $icons .= '<symbol id="i:' . $k . '" viewBox="0 0 24 24">';
        $icons .= strpos($v, '<') === 0 ? $v : '<path d="' . $v . '"></path>';
        $icons .= '</symbol>';
    }
    $icons .= '</svg>';
    echo $icons;
}

echo $content;

?>

  </body>
</html>