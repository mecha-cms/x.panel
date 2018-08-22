<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<header><?php echo panel\tools(Config::get('panel.tool.header'), $panel->id); ?></header>
<main><?php echo panel\tabs([
    $panel->id . 's' => [
        'files' => [LOT . DS . $panel->id . DS . $panel->path, $panel->id],
        'stack' => 10
    ]
]); ?></main>
<footer><?php echo panel\pager(LOT . DS . $panel->path, $panel->id); ?></footer>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>