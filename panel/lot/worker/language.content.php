<form id="form.main" action="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops); ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
    <section class="secondary-language">
      <h3><?php echo $language->{count($pages[0]) === 1 ? 'language' : 'languages'}; ?></h3>
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
      <p class="f expand">
        <label for="f-title"><?php echo $language->title; ?></label> <span>
<?php echo Form::text('title', $page[0]->title, $page[1]->title, [
    'classes' => ['input', 'block'],
    'id' => 'f-title'
]); ?>
        </span>
      </p>
      <div class="f expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', $page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'f-content',
    'data' => ['type' => $page[0]->type]
]); ?>
        </div>
      </div>
      <div class="f p">
        <label for="f-description"><?php echo $language->description; ?></label>
        <div>
<?php echo Form::textarea('description', $page[0]->description, $page[1]->description, [
    'classes' => ['textarea', 'block'],
    'id' => 'f-description'
]); ?>
        </div>
      </div>
      <p class="f">
        <label for="f-version"><?php echo $language->version; ?></label> <span>
<?php echo Form::text('version', $page[0]->version, $page[1]->version, [
    'classes' => ['input'],
    'id' => 'f-version'
]); ?>
        </span>
      </p>
      <p class="f">
        <label for="f-slug"><?php echo $language->locale; ?></label> <span>
<?php echo Form::text('slug', $page[0]->slug, $page[1]->slug, [
    'classes' => ['input'],
    'id' => 'f-slug'
]); ?>
        </span>
      </p>
    </fieldset>
    <?php echo Form::hidden('type', $page[0]->type); ?>
    <?php echo Form::token(); ?>
    <?php Hook::NS('panel.main.after'); ?>
    <p class="f expand">
      <label for="f-x"><?php echo $language->state; ?></label> <span>
<?php echo Form::submit('x', 'page', $language->{$sgr === 's' ? 'create' : 'update'}, [
    'classes' => ['button', 'x-page'],
    'id' => 'f-x'
]); ?>
      </span>
    </p>
    <?php Shield::get(__DIR__ . DS . 'footer.content.php'); ?>
  </main>
</form>