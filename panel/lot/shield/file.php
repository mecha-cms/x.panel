<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<?php echo panel\desk(panel\_config([], 'desk', 'desk:' . $panel->id), $panel->id); ?>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>