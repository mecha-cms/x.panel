<?php include __DIR__ . DS . '-search.php'; ?>
<?php if ($__parent[0]): ?>
<section class="s-parent">
  <h3><?php echo $language->parent; ?></h3>
  <ul>
    <li><?php echo HTML::a($__parent[1]->title, $__parent[0]->url . '/1'); ?></li>
  </ul>
</section>
<?php endif; ?>
<?php if ($__kins[0]): ?>
<section class="s-kin">
  <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
  <ul>
    <?php foreach ($__kins[0] as $__k => $__v): ?>
    <li><?php echo HTML::a($__kins[1][$__k]->title, $__v->url . '/1'); ?></li>
    <?php endforeach; ?>
    <?php if ($__is_has_step_kin): ?>
    <li><?php echo HTML::a('&#x2026;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]); ?></li>
    <?php endif; ?>
  </ul>
</section>
<?php endif; ?>
<?php if ($__pager[0]): ?>
<section class="s-nav">
  <h3><?php echo $language->navigation; ?></h3>
  <p><?php echo $__pager[0]; ?></p>
</section>
<?php endif; ?>