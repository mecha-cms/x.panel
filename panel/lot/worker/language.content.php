<form id="form.main" action="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops); ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
    <section class="secondary-language">
      <h3><?php echo $language->languages; ?></h3>
      <ul>
        <?php foreach ($pages[0] as $k => $v): ?>
        <li class="language-<?php echo $v->slug; ?>"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . $chops[0] . '/' . $v->slug; ?>"><?php echo $pages[1][$k]->title($v->slug); ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops); ?>" title="<?php echo $language->add; ?>">&#x2795;</a></li>
      </ul>
    </section>
    <?php Hook::NS('panel.secondary.1.after'); ?>
  </aside>
  <main class="main">
    <?php echo $message; ?>
    <?php Hook::NS('panel.main.before'); ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <p class="field expand">
        <label for="field-title"><?php echo $language->title; ?></label> <span>
<?php echo Form::text('title', $page[0]->title, $page[1]->title, [
    'classes' => ['input', 'block'],
    'id' => 'field-title'
]); ?>
        </span>
      </p>
      <div class="field expand p">
        <label for="field-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', $page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'field-content',
    'data' => ['type' => $page[0]->type]
]); ?>
        </div>
      </div>
      <div class="field p">
        <label for="field-description"><?php echo $language->description; ?></label>
        <div>
<?php echo Form::textarea('description', $page[0]->description, $page[1]->description, [
    'classes' => ['textarea', 'block'],
    'id' => 'field-description'
]); ?>
        </div>
      </div>
      <p class="field">
        <label for="field-version"><?php echo $language->version; ?></label> <span>
<?php echo Form::text('version', $page[0]->version, $page[1]->version, [
    'classes' => ['input'],
    'id' => 'field-version'
]); ?>
        </span>
      </p>
      <p class="field">
        <label for="field-slug"><?php echo $language->locale; ?></label> <span>
<?php echo Form::text('slug', $page[0]->slug, $page[1]->slug, [
    'classes' => ['input'],
    'id' => 'field-slug'
]); ?>
        </span>
      </p>
    </fieldset>
    <?php echo Form::hidden('type', $page[0]->type); ?>
    <?php echo Form::hidden('token', $token); ?>
    <?php Hook::NS('panel.main.after'); ?>
    <p class="field expand">
      <label for="field-x"><?php echo $language->state; ?></label> <span>
<?php echo Form::submit('x', 'page', $language->{$sgr === 's' ? 'create' : 'update'}, [
    'classes' => ['button', 'x-page'],
    'id' => 'field-x'
]); ?>
      </span>
    </p>
    <?php Shield::get(__DIR__ . DS . 'footer.content.php'); ?>
  </main>
</form>