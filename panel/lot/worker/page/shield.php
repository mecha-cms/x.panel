<form id="form.m.editor" action="" method="post">
  <aside class="s">
    <?php if ($__kins[0]): ?>
    <section class="s-kin">
      <h3><?php echo $language->{count($__kins[0]) === 1 ? 'file' : 'files'}; ?></h3>
      <ul>
      <?php foreach ($__kins[0] as $k => $v): ?>
        <?php $s = $__kins[1][$k]->key; ?>
        <li><?php echo HTML::a($s, $__state->path . '/::g::/' . $__chops[0] . '/' . $s); ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php endif; ?>
  </aside>
  <main class="m">
    <?php echo $__message; ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <div class="f f-content expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', $__page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'f-content',
    'data' => ['type' => $__page[0]->state]
]); ?>
        </div>
      </div>
    </fieldset>
    <p class="f f-state expand">
      <label for="f-state"><?php echo $language->state; ?></label>
      <span>
<?php echo Form::submit('x', $__page[0]->state, $language->update, [
    'classes' => ['button', 'x-page'],
    'id' => 'f-state:php'
]); ?>
      </span>
    </p>
    <?php echo Form::token(); ?>
  </main>
</form>