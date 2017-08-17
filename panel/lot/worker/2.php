<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<?php Shield::get(__DIR__ . DS . 'nav.php'); ?>
<div class="c">
  <?php if ($__cf = Config::get('panel.c:f', false)): ?>
  <form id="form.c.<?php echo $__cf === true ? $__chops[0] : $__cf; ?>" action="" method="post" enctype="multipart/form-data">
  <?php endif; ?>
    <aside class="s s-1">
      <?php Shield::get([
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.s.1.php',
          __DIR__ . DS . 'worker' . DS . '-s.1.php'
      ]); ?>
    </aside>
    <main class="m">
      <?php if ($__mf = Config::get('panel.m:f', false)): ?>
      <form id="form.m.<?php echo $__mf === true ? $__chops[0] : $__mf; ?>" action="" method="post" enctype="multipart/form-data">
      <?php endif; ?>
	  <?php echo $__message; ?>
	  <?php Shield::get([
          // Custom file manager layout
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.m.php',
          // --ditto
          __DIR__ . DS . 'worker' . DS . Request::get('view', Config::get('panel.view', 'file')) . ($site->is === 'page' ? "" : 's') . '.m.php',
          // Default to file manager
          __DIR__ . DS . 'worker' . DS . 'file' . ($site->is === 'page' ? "" : 's') . '.m.php'
          
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