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
<div class="f f-description p">
  <label for="f-description"><?php echo $language->description; ?></label>
  <div>
<?php echo Form::textarea('description', $__page[0]->description, $language->f_description($language->language), [
    'classes' => ['textarea', 'block'],
    'id' => 'f-description'
]); ?>
  </div>
</div>
<p class="f f-version">
  <label for="f-version"><?php echo $language->version; ?></label>
  <span>
<?php echo Form::text('version', $__page[0]->version, $__page[1]->version, [
    'classes' => ['input'],
    'id' => 'f-version'
]); ?>
  </span>
</p>
<p class="f f-locale">
  <label for="f-locale"><?php echo $language->locale; ?></label>
  <span>
<?php echo Form::text('slug', $__page[0]->slug, $__page[1]->slug, [
    'classes' => ['input'],
    'id' => 'f-locale',
    'data' => ['slug-o' => 'title'],
    'pattern' => '^[a-z\\d]+(?:-[a-z\\d]+)*$',
    'required' => true
]); ?>
  </span>
</p>