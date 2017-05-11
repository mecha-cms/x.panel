    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
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
<?php echo Form::textarea('description', $__page[0]->description, $language->f_description($language->tag), [
    'classes' => ['textarea', 'block'],
    'id' => 'f-description'
]); ?>
        </div>
      </div>
    </fieldset>
    <p class="f f-state expand">
      <label for="f-state"><?php echo $language->state; ?></label>
      <span>
<?php

$__x = $__page[0]->state;

if ($__sgr !== 's') {
    echo Form::submit('x', $__x, $language->update, [
        'classes' => ['button', 'set', 'x-' . $__x],
        'id' => 'f-state:' . $__x
    ]);
}

foreach ([
    'page' => $language->publish,
    'draft' => $language->save,
    'trash' => $__sgr === 's' ? false : $language->delete
] as $__k => $__v) {
    if (!$__v || $__x === $__k) continue;
    echo ' ' . Form::submit('x', $__k, $__v, [
        'classes' => ['button', 'set', 'x-' . $__k],
        'id' => 'f-state:' . $__k
    ]);
}

?>
      </span>
    </p>