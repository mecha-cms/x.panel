<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<form action="<?php echo $url->clean . HTTP::query(['token' => $token]); ?>" method="post" enctype="multipart/form-data">
<?php echo panel\desk(panel\_config([], 'desk'), $panel->id); ?>
</form>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>