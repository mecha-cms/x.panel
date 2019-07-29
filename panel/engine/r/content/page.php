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

echo _\lot\x\panel(['lot' => [
    0 => [
        'type' => 'nav',
        'lot' => [
            0 => [
                'type' => 'ul',
                'lot' => [
                    0 => [
                        'type' => 'li',
                        'lot' => [
                            0 => [
                                'type' => 'a',
                                'title' => 'Menu 1',
                                'url' => 'http://example.com'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    1 => [
        'type' => 'desk',
        'lot' => [
            0 => [
                'type' => 'desk.header',
                'content' => 'Header goes here.'
            ],
            1 => [
                'type' => 'desk.body',
                'content' => 'Body goes here.'
            ],
            2 => [
                'type' => 'desk.footer',
                'content' => 'Footer goes here.'
            ]
        ]
    ]
]], 0, '#');

?>

  </body>
</html>