<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<?php Shield::get(__DIR__ . DS . 'nav.php'); ?>
<div class="c">
  <?php if ($site->is_f): ?>
  <form id="form.m.<?php echo $site->is_f; ?>" action="" method="post" enctype="multipart/form-data">
  <?php endif; ?>
    <aside class="s s-1">
      <?php Shield::get([
          $__path_shield . DS . $site->is . DS . $__chops[0] . '.s.php',
          $__path_shield . DS . $site->is . DS . $__chops[0] . '.s.1.php',
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.s.php',
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.s.1.php',
          __DIR__ . DS . 'worker' . DS . '-s.php',
          __DIR__ . DS . 'worker' . DS . '-s.1.php'
      ]); ?>
    </aside>
    <main class="m">
	  <?php echo $__message; ?>
	  <?php Shield::get([
          $__path_shield . DS . $site->is . DS . $__chops[0] . '.m.php',
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.m.php',
          __DIR__ . DS . 'worker' . DS . '-m.php'
      ]); ?>
	</main>
    <aside class="s s-2">
      <?php Shield::get([
          $__path_shield . DS . $site->is . DS . $__chops[0] . '.s.2.php',
          __DIR__ . DS . $site->is . DS . $__chops[0] . '.s.2.php',
          __DIR__ . DS . 'worker' . DS . '-s.2.php'
      ]); ?>
    </aside>
  <?php if ($site->is_f): ?>
  <?php echo Form::hidden('token', $__token); ?>
  </form>
  <?php endif; ?>
</div>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>