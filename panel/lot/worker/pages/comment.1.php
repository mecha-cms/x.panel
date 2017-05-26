<?php include __DIR__ . DS . '-search.php'; ?>
<?php if ($__pager[0]): ?>
<section class="s-nav">
  <h3><?php echo $language->navigation; ?></h3>
  <p><?php echo $__pager[0]; ?></p>
</section>
<?php endif; ?>