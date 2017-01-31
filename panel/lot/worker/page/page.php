<?php if (substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false): ?>
<!-- eject the right sidebar -->
<?php Hook::reset('panel', 'panel_s_right'); ?>
<?php endif; ?>
<form id="form.main" action="<?php echo $url->current . $url->query; ?>" method="post">
  <?php Hook::fire('panel', [Lot::get(null, [])]); ?>
</form>