<?php if (isset($__page[0]->name)): ?>
<?php include __DIR__ . DS . '-t.php'; ?>
<p class="f f-state expand">
  <label for="f-state"><?php echo $language->set; ?></label>
  <span>
<?php

$__t = Is::these(explode(',', SCRIPT_X))->has($__page[0]->extension) ? '1' : '0';

foreach ([
    (is_dir($__page[0]->path) ? '0' : '1') => $language->{$__t ? 'update' : 'rename'},
    '-1' => $language->delete
] as $__k => $__v) {
    echo ' ' . Form::submit('xx', $__k, $__v, [
        'classes' => ['button', 'set', 'x-' . $__k],
        'id' => 'f-state:' . $__k
    ]);
}

echo ' ' . HTML::a($language->cancel, Path::D($url->current), false, ['classes' => ['button', 'reset']])

?>
  </span>
</p>
<?php else: ?>
<section class="buttons">
  <p>
    <?php if (Request::get('q')): ?>
    <?php $__links = [HTML::a('&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step, false, ['classes' => ['button', 'reset']])]; ?>
    <?php else: ?>
    <?php $__links = [HTML::a('&#x2795; ' . $language->{$__chops[0]}, $__state->path . '/::s::/' . $__path, false, ['classes' => ['button', 'set']])]; ?>
    <?php endif; ?>
    <?php echo implode(' ', Hook::fire('panel.a.' . $__chops[0] . 's', [$__links])); ?>
  </p>
</section>
<section class="files">
  <?php if ($__sgr === 's' && count($__chops) === 1): ?>
  <fieldset>
    <legend><?php echo $language->file; ?></legend>
    <p class="f f-file expand">
      <label for="f-file"><?php echo $language->file; ?></label>
      <span><?php echo Form::file('file', ['id' => 'f-file']); ?></span>
    </p>
  </fieldset>
  <p class="f f-state expand">
    <label for="f-state"><?php echo $language->state; ?></label>
    <span><?php echo Form::submit('x', 'zip', $language->upload, ['classes' => ['button'], 'id' => 'f-state']); ?></span>
  </p>
  <?php else: ?>
  <ul class="files">
    <?php foreach ($__datas[0] as $__k => $__v): ?>
    <?php if ($__v->extension === 'trash') continue; ?>
    <li class="file"><?php echo HTML::a('<i class="i i-' . (is_dir($__v->path) ? '0' : '1') . '"></i> ' . $__datas[1][$__k]->title, $__v->url); ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</section>
<?php endif; ?>