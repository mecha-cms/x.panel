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
        'js' => 'JavaScript',
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
<?php $__n = $__page[0]->name; echo Form::text('name', $__n, null, [
    'classes' => ['input', 'block'],
    'id' => 'f-name',
    'required' => true,
    'readonly' => strpos($__n, 'about.') === 0 && Path::X($__n) === 'page' ? true : null
]); ?>
  </span>
</p>