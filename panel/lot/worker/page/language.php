<form id="form.main" action="<?php echo $url . '/' . $__state->path . '/::s::/' . implode('/', $__chops); ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
    <section class="secondary-language">
      <h3><?php echo $language->{count($__kins[0]) === 1 ? 'language' : 'languages'}; ?></h3>
      <ul>
        <?php foreach ($__kins[0] as $k => $v): ?>
        <li class="language-<?php echo $v->slug; ?>"><a href="<?php echo $url . '/' . $__state->path . '/::g::/' . $__chops[0] . '/' . $v->slug; ?>"><?php echo $__kins[1][$k]->title($v->slug); ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?php echo $url . '/' . $__state->path . '/::s::/' . implode('/', $__chops); ?>" title="<?php echo $language->add; ?>">&#x2795;</a></li>
      </ul>
    </section>
    <?php Hook::NS('panel.secondary.1.after'); ?>
  </aside>
  <main class="main">
    <?php echo $__message; ?>
    <?php Hook::NS('panel.main.before'); ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <p class="f expand">
        <label for="f-title"><?php echo $language->title; ?></label> <span>
<?php echo Form::text('title', $__page[0]->title, $__page[1]->title, [
    'classes' => ['input', 'block'],
    'id' => 'f-title'
]); ?>
        </span>
      </p>
      <div class="f expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', $__page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'f-content',
    'data' => ['type' => $__page[0]->type]
]); ?>
        </div>
      </div>
      <div class="f p">
        <label for="f-description"><?php echo $language->description; ?></label>
        <div>
<?php echo Form::textarea('description', $__page[0]->description, $__page[1]->description, [
    'classes' => ['textarea', 'block'],
    'id' => 'f-description'
]); ?>
        </div>
      </div>
      <p class="f">
        <label for="f-version"><?php echo $language->version; ?></label> <span>
<?php echo Form::text('version', $__page[0]->version, $__page[1]->version, [
    'classes' => ['input'],
    'id' => 'f-version'
]); ?>
        </span>
      </p>
      <p class="f">
        <label for="f-slug"><?php echo $language->locale; ?></label> <span>
<?php echo Form::text('slug', $__page[0]->slug, $__page[1]->slug, [
    'classes' => ['input'],
    'id' => 'f-slug'
]); ?>
        </span>
      </p>
    </fieldset>
    <?php echo Form::hidden('type', $__page[0]->type); ?>
    <?php echo Form::token(); ?>
    <?php Hook::NS('panel.main.after'); ?>
    <p class="f expand">
      <label for="f-x"><?php echo $language->state; ?></label> <span>
<?php echo Form::submit('x', 'page', $language->{$__sgr === 's' ? 'create' : 'update'}, [
    'classes' => ['button', 'x-page'],
    'id' => 'f-x'
]); ?>
      </span>
    </p>
<?php Shield::get([
    $__path_shield . DS . $site->type . DS . '_footer.php',
    __DIR__ . DS . '_footer.php'
]); ?>
  </main>
</form>