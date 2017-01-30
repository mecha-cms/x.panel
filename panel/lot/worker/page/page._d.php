<!-- eject the right sidebar -->
<?php Hook::reset('panel', 'panel_s_right'); ?>
<form id="form.main" action="<?php echo $url->current . $url->query; ?>" method="post">
  <?php Hook::fire('panel'); ?>
</form>