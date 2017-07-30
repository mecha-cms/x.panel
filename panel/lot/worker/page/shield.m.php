<?php if (isset($__page[0]->name)): ?>
<?php include __DIR__ . DS . '..' . DS . 'worker' . DS . 'file.m.php'; ?>
<?php else: ?>
<section class="m-button">
  <p>
    <?php if (Request::get('q')): ?>
    <?php $__links = [HTML::a('&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step, false, ['classes' => ['button', 'reset']])]; ?>
    <?php else: ?>
    <?php $__links = [HTML::a('&#x2795; ' . $language->{$__chops[0]}, $__state->path . '/::s::/' . $__path, false, ['classes' => ['button', 'set']])]; ?>
    <?php endif; ?>
    <?php echo implode(' ', Hook::fire('panel.a.' . $__chops[0] . 's', [$__links])); ?>
  </p>
</section>
<section class="m-file">
  <?php if ($__action === 's' && count($__chops) === 1): ?>
  <fieldset>
    <legend><?php echo $language->file; ?></legend>
    <?php echo __panel_f__('file', [
    'type' => 'file',
    'description' => $language->h_shield_upload,
    'expand' => true
]); ?>
  </fieldset>
  <p class="f f-state expand">
    <label for="f-state"><?php echo $language->state; ?></label>
    <span><?php echo Form::submit('x', 'zip', $language->upload, ['classes' => ['button'], 'id' => 'f-state']); ?></span>
  </p>
  <?php else: ?>
  <ul class="m-file">
    <?php foreach ($__datas[0] as $__k => $__v): ?>
    <?php if ($__v->extension === 'trash') continue; ?>
    <li class="file"><?php echo HTML::a('<i class="i i-' . (is_dir($__v->path) ? 'd' : 'f') . '"></i> ' . $__datas[1][$__k]->title, $__v->url); ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</section>
<?php endif; ?>