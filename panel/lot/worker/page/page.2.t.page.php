<p class="f f-title expand">
  <label for="f-title"><?php echo $language->title; ?></label>
  <span>
<?php echo Form::text('title', $__page[0]->title, $language->f_title, [
    'classes' => ['input', 'block'],
    'id' => 'f-title',
    'data' => ['slug-i' => 'title']
]); ?>
  </span>
</p>
<p class="f f-slug expand">
  <label for="f-slug"><?php echo $language->slug; ?></label>
  <span>
<?php echo Form::text('slug', $__page[0]->slug, To::slug($language->f_title), [
    'classes' => ['input', 'block'],
    'id' => 'f-slug',
    'data' => ['slug-o' => 'title'],
    'pattern' => '^[a-z\\d]+(?:-[a-z\\d]+)*$',
    'required' => true
]); ?>
  </span>
</p>
<div class="f f-content expand p">
  <label for="f-content"><?php echo $language->content; ?></label>
  <div>
<?php echo Form::textarea('content', $__page[0]->content, $language->f_content, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'f-content',
    'data' => ['type' => $__page[0]->type]
]); ?>
  </div>
</div>
<p class="f f-type">
  <label for="f-type"><?php echo $language->type; ?></label>
  <span>
<?php $__types = a(Config::get('panel.f.page.types')); ?>
<?php asort($__types); ?>
<?php echo Form::select('type', $__types, $__page[0]->type, [
    'classes' => ['select'],
    'id' => 'f-type'
]); ?>
  </span>
</p>
<div class="f f-description p">
  <label for="f-description"><?php echo $language->description; ?></label>
  <div>
<?php echo Form::textarea('description', $__page[0]->description, $language->f_description($language->page), [
    'classes' => ['textarea', 'block'],
    'id' => 'f-description'
]); ?>
  </div>
</div>
<p class="f f-link">
  <label for="f-link"><?php echo $language->link; ?></label>
  <span>
<?php echo Form::url('link', $__page[0]->link, $url->protocol, [
    'classes' => ['input', 'block'],
    'id' => 'f-link'
]); ?>
  </span>
</p>
<?php if (Extend::exist('tag')): ?>
<p class="f f-tags">
  <label for="f-tags"><?php echo $language->tags; ?></label>
  <span>
<?php

$__tags = [];
if ($__page[0]->kind) {
    foreach ($__page[0]->kind as $__v) {
        $__tags[] = To::tag($__v);
    }
}

sort($__tags);

echo Form::text('tags', implode(', ', (array) $__tags), $language->f_query, [
    'classes' => ['input', 'block', 'query'],
    'id' => 'f-tags'
]);

?>
  </span>
</p>
<?php endif; ?>
<?php if ($__sgr !== 's'): ?>
<p class="f f-time">
  <label for="f-time"><?php echo $language->time; ?></label>
  <span>
<?php echo Form::text('time', (new Date($__page[0]->time))->format('Y/m/d H:i:s'), date('Y/m/d H:i:s'), [
    'classes' => ['input', 'date'],
    'id' => 'f-time'
]); ?>
  </span>
</p>
<?php endif; ?>