<aside class="secondary">
  <?php if ($__kins[0]): ?>
  <section class="secondary-kin">
    <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
    <ul>
    <?php foreach ($__kins[0] as $k => $v): ?>
      <?php $s = $__kins[1][$k]->key; ?>
      <li><?php echo HTML::a($s, $__state->path . '/::g::/' . $__chops[0] . '/' . $s); ?></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>
</aside>
<main class="main">
  <?php echo $__message; ?>
  <form id="form.main" action="" method="post">
  <p class="f expand">
    <label for="f-content"><?php echo $language->content; ?></label>
    <div>
<?php echo Form::textarea('content', To::yaml($__page[0]->content), null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'f-content'
]); ?>
    </div>
  </p>
  <?php echo Form::token(); ?>
  </form>
</main>