<aside class="secondary">
  <?php Hook::NS('panel.secondary.1.before'); ?>
  <section class="secondary-language">
    <h3><?php echo $language->languages; ?></h3>
    <ul>
      <?php foreach ($pages[0] as $k => $v): ?>
      <li class="language-<?php echo $v->slug; ?>"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . $chops[0] . '/' . $v->slug; ?>"><?php echo $pages[1][$k]->title($v->slug); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php Hook::NS('panel.secondary.1.after'); ?>
</aside>
<main class="main">
  <?php Hook::NS('panel.main.before'); ?>
  <section>
    <form id="form.main" action="" method="post">
      <fieldset>
        <legend><?php echo $language->editor; ?></legend>
        <p class="control expand">
          <label for="control-language-title"><?php echo $language->title; ?></label>
          <span>
<?php echo Form::text('title', $page[0]->title, $page[1]->title, [
    'classes' => ['input', 'block'],
    'id' => 'control-language-title'
]); ?>
          </span>
        </p>
        <p class="control expand">
          <label for="control-language-content"><?php echo $language->content; ?></label>
          <span>
<?php echo Form::textarea('content', $page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'control-language-content'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-language-description"><?php echo $language->description; ?></label>
          <span>
<?php echo Form::textarea('description', $page[0]->description, $page[1]->description, [
    'classes' => ['textarea', 'block'],
    'id' => 'control-language-description'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-language-version"><?php echo $language->version; ?></label>
          <span>
<?php echo Form::text('version', $page[0]->version, $page[1]->version, [
    'classes' => ['input'],
    'id' => 'control-language-version'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-language-slug"><?php echo $language->locale; ?></label>
          <span>
<?php echo Form::text('slug', $page[0]->slug, $page[1]->slug, [
    'classes' => ['input'],
    'id' => 'control-language-slug'
]); ?>
          </span>
        </p>
      </fieldset>
      <p class="control expand">
        <label for="control-language-x"><?php echo $language->state; ?></label>
        <span>
<?php echo Form::button('x', 'page', $language->update, [
    'classes' => ['button', 'x-page'],
    'id' => 'control-language-x'
]); ?>
        </span>
      </p>
      <?php echo Form::hidden('type', $page[0]->type); ?>
      <?php echo Form::hidden('token', $token); ?>
    </form>
  </section>
  <?php Hook::NS('panel.main.after'); ?>
  <?php Shield::get($shield_path . DS . 'footer.php'); ?>
</main>