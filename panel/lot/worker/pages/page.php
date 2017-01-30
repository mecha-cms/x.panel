<!-- eject the right sidebar -->
<?php Hook::reset('panel', 'panel_s_right'); ?>
<!-- load the cargo -->
<?php Hook::fire('panel'); ?>