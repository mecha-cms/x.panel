<aside class="secondary">
  <section>
    <h3><?php echo $language->languages; ?></h3>
    <ul>
      <?php foreach ($pages[0] as $k => $v): ?>
      <li class="language-<?php echo $v->slug; ?>"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . $chops[0] . '/' . $v->slug; ?>"><?php echo $pages[1][$k]->title($v->slug); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </section>
</aside>
<main class="main">
  <section>
    <form id="form.main" action="" method="post">
      <fieldset>
        <legend><?php echo $language->editor; ?></legend>
        <p class="control expand">
          <label for="control-<?php echo $chops[0]; ?>-title"><?php echo $language->title; ?></label>
          <span><?php echo Form::text('title', $page[0]->title, $page[0]->title, ['classes' => ['input', 'block']]); ?></span>
        </p>
        <p class="control expand">
          <label for="control-<?php echo $chops[0]; ?>-content"><?php echo $language->content; ?></label>
          <span><?php echo Form::textarea('content', $page[0]->content, null, ['classes' => ['textarea', 'block', 'expand', 'code']]); ?></span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-description"><?php echo $language->description; ?></label>
          <span><?php echo Form::textarea('description', $page[0]->description, $page[0]->description, ['classes' => ['textarea', 'block']]); ?></span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-version"><?php echo $language->version; ?></label>
          <span><?php echo Form::text('version', $page[0]->version, $page[0]->version, ['classes' => ['input']]); ?></span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-slug"><?php echo $language->language; ?></label>
          <span><?php echo Form::text('slug', $page[0]->slug, $page[0]->slug, ['classes' => ['input']]); ?></span>
        </p>
      </fieldset>
      <p><?php echo Form::button('state', 'page', $language->update, ['classes' => ['button', 'set', 'page']]); ?></p>
      <?php echo Form::hidden('type', $page[0]->type); ?>
    </form>
  </section>
  <?php Shield::get($shield_path . DS . 'footer.php'); ?>
</main>