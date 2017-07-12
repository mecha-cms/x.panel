<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<div class="c">
  <?php if ($site->is_f): ?>
  <form id="form.m.<?php echo $site->is_f; ?>" action="" method="post" enctype="multipart/form-data">
  <?php endif; ?>
    <main class="m">
	  <?php echo $__message; ?>
	  <?php Shield::get([
          $__path_shield . DS . $site->is . DS . $__chops[0] . '.m.php',
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.m.php',
          __DIR__ . DS . 'worker' . DS . '-m.php'
      ]); ?>
	</main>
  <?php if ($site->is_f): ?>
  <?php echo Form::hidden('token', $__token); ?>
  </form>
  <?php endif; ?>
</div>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>