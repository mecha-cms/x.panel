<div class="f f-content expand p">
  <label for="f-content"><?php echo $language->content; ?></label>
  <div>
<?php $__content = $__data[0]->content; ?>
<?php echo Form::textarea('content', is_array($__content) ? To::json($__content) : $__content, $language->f_content, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'f-content'
]); ?>
  </div>
</div>
<p class="f f-key">
  <label for="f-key"><?php echo $language->key; ?></label>
  <span>
<?php echo Form::text('key', $__data[0]->key, null, [
    'classes' => ['input'],
    'id' => 'f-key',
    'pattern' => '^[a-z\\d]+(?:_[a-z\\d]+)*$',
    'required' => true
]); ?>
  </span>
</p>