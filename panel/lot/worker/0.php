<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<div class="c">
  <?php if ($__cf = Config::get('panel.c:f', false)): ?>
  <form id="form.c.<?php echo $__cf === true ? 'container' : $__cf; ?>" action="" method="post" enctype="multipart/form-data">
  <?php endif; ?>
    <main class="m">
      <?php if ($__mf = Config::get('panel.m:f', false)): ?>
      <form id="form.m.<?php echo $__mf === true ? 'main' : $__mf; ?>" action="" method="post" enctype="multipart/form-data">
      <?php endif; ?>
	  <?php echo $__message; ?>
	  <?php Shield::get([
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.m.php',
          __DIR__ . DS . 'worker' . DS . $site->is . '.m.php'
      ]); ?>
      <?php if ($__mf): ?>
      <?php echo Form::hidden('token', $__token); ?>
      </form>
      <?php endif; ?>
	</main>
  <?php if ($__cf): ?>
  <?php echo Form::hidden('token', $__token); ?>
  </form>
  <?php endif; ?>
</div>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>