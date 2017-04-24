<aside class="s">
  <?php if (count($__chops) > 2): ?>
  <section class="s-parent">
    <h3><?php echo $language->parent; ?></h3>
    <ul>
      <li><?php $__p = Path::D($url->current); echo HTML::a('<i class="i i-0"></i> ' . (count($__chops) === 3 ? '.' : Path::B($__p)), $__p); ?></li>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($__kins[0] && count($__chops) === 2): ?>
  <section class="s-kin">
    <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
    <ul>
      <?php foreach ($__kins[0] as $__k => $__v): ?>
      <li><?php echo HTML::a($__kins[1][$__k]->title, $__v->url); ?></li>
      <?php endforeach; ?>
      <?php if ($__is_has_step_kin): ?>
      <li><?php echo HTML::a('&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]); ?></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if (count($__chops) > 2 && $__f = glob(SHIELD . DS . $__chops[1] . DS . Path::D($__page[0]->name) . DS . '*')): ?>
  <?php $__kins = [[]]; ?>
  <?php $__p = Path::D($url->current); $__n = Path::B($__page[0]->name); foreach ($__f as $__v): ?>
  <?php if (Path::B($__v) === $__n) continue; ?>
  <?php $__kins[0][] = $__v; ?>
  <?php endforeach; ?>
  <?php if ($__kins[0]): ?>
  <section class="s-kin">
    <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
    <ul>
	  <?php foreach ($__kins[0] as $__v): ?>
      <li><?php echo HTML::a('<i class="i i-' . (is_dir($__v) ? '0' : '1') . '"></i> ' . Path::B($__v), $__p . '/' . Path::B($__v)); ?></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($__page[0]->is->folder): ?>
  <section class="s-child">
    <h3><?php echo $language->{count($__datas[0]) === 1 ? 'child' : 'childs'}; ?></h3>
    <ul>
      <?php foreach ($__datas[0] as $__k => $__v): ?>
      <li><?php echo HTML::a('<i class="i i-' . (is_dir($__v->path) ? '0' : '1') . '"></i> ' . $__datas[1][$__k]->title, $__v->url); ?></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php endif; ?>
</aside>
<main class="m">
  <?php echo $__message; ?>
  <?php if (isset($__page[0]->name)): ?>
  <form id="form.m.editor" action="" method="post">
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <?php if (Is::these(explode(',', SCRIPT_X))->has($__page[0]->extension)): ?>
      <div class="f f-content expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', file_get_contents($__page[0]->path), $language->f_content, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'f-content',
    'data' => ['type' => Anemon::alter($__page[0]->extension, [
        'css' => 'CSS',
        'html' => 'HTML',
        'js' => 'JS',
        'page' => 'YAML',
        'php' => 'PHP'
    ])]
]); ?>
        </div>
      </div>
      <?php elseif (Is::these(explode(',', IMAGE_X))->has($__page[0]->extension)): ?>
      <div class="f f-content p">
        <label for="f-content"><?php echo u($__page[0]->extension); ?></label>
        <div id="f-content"><?php echo HTML::img($__page[0]->url); ?></div>
      </div>
      <?php endif; ?>
      <p class="f f-name">
        <label for="f-name"><?php echo $language->name; ?></label>
        <span>
<?php echo Form::text('name', $__page[0]->name, null, [
    'classes' => ['input', 'block'],
    'id' => 'f-name',
    'required' => true
]); ?>
        </span>
      </p>
    </fieldset>
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
    <?php echo Form::hidden('token', $__token); ?>
  </form>
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
    <ul class="files">
      <?php foreach ($__datas[0] as $__k => $__v): ?>
      <li class="file"><?php echo HTML::a('<i class="i i-' . (is_dir($__v->path) ? '0' : '1') . '"></i> ' . $__datas[1][$__k]->title, $__v->url); ?></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>
</main>