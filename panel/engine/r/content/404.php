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

$GLOBALS['_']['lot'] = [
    'desk' => [
        'lot' => [
            'form' => false,
            'message' => [
                'type' => 'Section',
                'title' => $language->isError . ' :(',
                'description' => null,
                'content' => "",
                'stack' => 10
            ]
        ]
    ]
];

$content = require __DIR__ . DS . '-panel.php';
$icon = require __DIR__ . DS . '-icon.php';

echo $icon . $content;

?>

  </body>
</html>