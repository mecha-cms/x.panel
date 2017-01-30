<!-- remove the right sidebar -->
<?php Hook::reset('panel', 'panel_secondary_2'); ?>
<!-- load the cargo -->
<?php Hook::fire('panel'); ?>