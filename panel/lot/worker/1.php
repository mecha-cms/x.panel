<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<?php Shield::get(__DIR__ . DS . 'nav.php'); ?>
<div class="c">
  <?php if ($__name = Config::get('panel.c:f', false)): ?>
  <form id="form.m.<?php echo $__name; ?>" action="" method="post" enctype="multipart/form-data">
  <?php endif; ?>
    <main class="m">
	  <?php echo $__message; ?>
	  <?php Shield::get([
          $__path_shield . DS . $site->is . DS . $__chops[0] . '.m.php',
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.m.php',
          __DIR__ . DS . 'worker' . DS . '-m.php'
      ]); ?>
	</main>
  <?php if ($__name): ?>
  <?php echo Form::hidden('token', $__token); ?>
  </form>
  <?php endif; ?>
</div>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>