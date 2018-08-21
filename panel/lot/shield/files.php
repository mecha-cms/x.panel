<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<header><?php echo panel\tools(Config::get('panel.tools.header')); ?></header>
<main><?php echo panel\files(LOT . DS . $panel->path); ?></main>
<footer><?php echo panel\pager(LOT . DS . $panel->path); ?></footer>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>