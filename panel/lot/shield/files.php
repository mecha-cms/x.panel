<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<?php echo panel\desk(panel\_config([], 'desk'), $panel->id); ?>
<?php foreach (Config::get('panel.$.menu[]', [], true) as $k => $v): ?>
<?php echo panel\menus($v, $k, [
    'data[]' => ['js-enter' => '#js:' . $k]
]); ?>
<?php endforeach; ?>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>