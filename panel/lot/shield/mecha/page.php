<?php include __DIR__ . DS . 'top.php'; ?>
<div class="container">
  <aside class="secondary">
    <h3>Children</h3>
    <ul>
      <li><a href="">foo/lorem-ipsum</a></li>
      <li><a href="">foo/dolor-sit</a></li>
      <li><a href="">foo/amet</a></li>
      <li><a href="">&#x271A;</a></li>
    </ul>
  </aside>
  <article class="main">
    <form id="form.main" action="/" method="post">
      <fieldset>
        <legend>New Page</legend>
        <p class="control expand">
          <label for="control-<?php echo $chops[0]; ?>-data-title"><?php echo $language->title; ?></label> <span>
<?php echo Form::text('title', $page_->title, 'Title Here', [
    'classes' => ['input', 'block'],
    'id' => 'control-' . $chops[0] . '-data-title'
]); ?>
          </span>
        </p>
        <p class="control expand">
          <label for="control-<?php echo $chops[0]; ?>-data-slug"><?php echo $language->slug; ?></label> <span>
<?php echo Form::text('slug', $page_->slug, 'title-here', [
    'classes' => ['input', 'block'],
    'id' => 'control-' . $chops[0] . '-data-slug'
]); ?>
          </span>
        </p>
        <p class="control expand">
          <label for="control-<?php echo $chops[0]; ?>-data-content"><?php echo $language->content; ?></label> <span>
<?php echo Form::textarea('content', $page_->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'control-' . $chops[0] . '-data-content'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-data-type"><?php echo $language->type; ?></label> <span>
<?php echo Form::select('type', a(Panel::get('page.type', [])), $page_->type, [
    'classes' => ['select'],
    'id' => 'control-' . $chops[0] . '-data-type'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-data-description"><?php echo $language->description; ?></label> <span>
<?php echo Form::textarea('description', $page_->description, null, [
    'classes' => ['textarea', 'block'],
    'id' => 'control-' . $chops[0] . '-data-description'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-data-kind"><?php echo $language->kind; ?></label> <span>
<?php echo Form::text('kind', $page_->kind === [0] ? "" : implode(', ', $page_->kind), null, [
    'classes' => ['input', 'block', 'query'],
    'id' => 'control-' . $chops[0] . '-data-kind'
]); ?>
          </span>
        </p>
      </fieldset>
      <fieldset>
        <legend>Embedded</legend>
        <p class="control"><label>TOC</label> <span><textarea class="textarea"></textarea></span></p>
        <p class="control"><label>Ads</label> <span><input class="input" type="checkbox"> Enable</span></p>
      </fieldset>
      <p><button class="button">Publish</button> <button class="button">Save</button> <button class="button">Delete</button></p>
    </form>
    <footer class="main-footer">
      <p>&copy; 2017 &middot; Mecha CMS</p>
    </footer>
  </article>
  <aside class="secondary">
    <h3>Search</h3>
    <form class="search">
      <p><input class="input" name="q" type="text"> <button class="button">Search</button>
    </form>
    <h3>Data</h3>
    <ul>
      <li><a href="">css</a></li>
      <li><a href="">js</a></li>
      <li><a href="">toc</a></li>
      <li><a href="">&#x271A;</a></li>
    </ul>
  </aside>
</div>
<?php include __DIR__ . DS . 'bottom.php'; ?>