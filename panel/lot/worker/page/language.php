<form id="form.main" action="" method="post">
  <aside class="secondary">
    <section class="secondary-language">
      <h3><?php echo $language->language; ?></h3>
      <ul>
        <?php foreach ($__kins[0] as $k => $v): ?>
        <li><?php echo HTML::a($__kins[1][$k]->title, $__state->path . '/::g::/' . $__chops[0] . '/' . $v->slug); ?></li>
        <?php endforeach; ?>
        <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . $__chops[0], false, ['title' => $language->add]); ?></li>
      </ul>
    </section>
  </aside>
  <main class="main">
    <?php echo $__message; ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <p class="f f-title expand">
        <label for="f-title"><?php echo $language->title; ?></label>
        <span>
          <?php echo Form::text('title', $__page[0]->title, $language->f_title, ['classes' => ['input', 'block'], 'id' => 'f-title', 'data' => ['slug-i' => 'title']]); ?>
        </span>
      </p>
      <div class="f f-content expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
          <?php echo Form::textarea('content', $__page[0]->content, $language->f_content, ['classes' => ['textarea', 'block', 'expand', 'code'], 'id' => 'f-content', 'data' => ['type' => $__page[0]->type]]); ?>
        </div>
      </div>
      <div class="f f-description p">
        <label for="f-description"><?php echo $language->description; ?></label>
        <div>
          <?php echo Form::textarea('description', $__page[0]->description, $language->f_description($language->language), ['classes' => ['textarea', 'block'], 'id' => 'f-description']); ?>
        </div>
      </div>
      <p class="f f-version">
        <label for="f-version"><?php echo $language->version; ?></label>
        <span>
          <?php echo Form::text('version', $__page[0]->version, $__page[1]->version, ['classes' => ['input'], 'id' => 'f-version']); ?>
        </span>
      </p>
      <p class="f f-locale">
        <label for="f-locale"><?php echo $language->locale; ?></label>
        <span>
          <?php echo Form::text('slug', $__page[0]->slug, $__page[1]->slug, ['classes' => ['input'], 'id' => 'f-locale', 'data' => ['slug-o' => 'title']]); ?>
        </span>
      </p>
      <?php echo Form::hidden('type', $__page[0]->type); ?>
    </fieldset>
    <p class="f f-state expand">
      <label for="f-state"><?php echo $language->state; ?></label>
      <span>
        <?php

        echo Form::submit('x', 'page', $language->{$__sgr === 's' ? 'create' : 'update'}, ['classes' => ['button', 'x-page'], 'id' => 'f-state:page']);

        if ($__sgr !== 's') {
            echo ' ' . Form::submit('x', 'trash', $language->delete, ['classes' => ['button', 'x-trash'], 'id' => 'f-state:trash']);
        }

        ?>
      </span>
    </p>
    <?php echo Form::token(); ?>
  </main>
</form>