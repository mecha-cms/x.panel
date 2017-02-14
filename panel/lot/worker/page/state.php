<form id="form.main" action="" method="post">
  <aside class="secondary">
    <?php if ($__kins[0]): ?>
    <section class="secondary-kin">
      <h3><?php echo $language->{count($__kins[0]) === 1 ? 'config' : 'configs'}; ?></h3>
      <ul>
      <?php foreach ($__kins[0] as $k => $v): ?>
        <?php $s = $__kins[1][$k]->key; ?>
        <li><?php echo HTML::a($s, $__state->path . '/::g::/' . $__chops[0] . '/' . $s); ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php endif; ?>
  </aside>
  <main class="main">
    <?php echo $__message; ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <?php if (Path::N($__page[0]->path) === 'config'): ?>
      <p class="f f-zone">
        <label for="f-zone"><?php echo $language->time_zone; ?></label>
        <span><?php echo Form::select('config[zone]', Get::zone(), $__page[0]->content['zone'], ['classes' => ['select', 'block'], 'id' => 'f-zone']); ?></span>
      </p>
      <p class="f f-language">
        <label for="f-language"><?php echo $language->language; ?></label>
        <?php $__languages = []; ?>
        <?php foreach (glob(LANGUAGE . DS . '*.page') as $v): ?>
        <?php $__languages[Path::N($v)] = (new Page($v, [], 'language'))->title; ?>
        <?php endforeach; ?>
        <span><?php echo Form::select('config[language]', $__languages, $__page[0]->content['language'], ['classes' => ['select', 'block'], 'id' => 'f-language']); ?></span>
      </p>
      <p class="f f-title">
        <label for="f-title"><?php echo $language->title; ?></label>
        <span><?php echo Form::text('config[title]', $__page[0]->content['title'], null, ['classes' => ['input', 'block'], 'id' => 'f-title']); ?></span>
      </p>
      <div class="f f-description p">
        <label for="f-title"><?php echo $language->description; ?></label>
        <div>
          <?php echo Form::textarea('config[description]', $__page[0]->content['description'], $language->f_description($language->site), ['classes' => ['textarea', 'block'], 'id' => 'f-description']); ?>
        </div>
      </div>
      <p class="f f-shield">
        <label for="f-shield"><?php echo $language->shield; ?></label>
        <?php $__shields = []; ?>
        <?php foreach (glob(SHIELD . DS . '*', GLOB_ONLYDIR) as $v): $v = Path::B($v); ?>
        <?php $__shields[$v] = Shield::info($v)->title; ?>
        <?php endforeach; ?>
        <span><?php echo Form::select('config[shield]', $__shields, $__page[0]->content['shield'], ['classes' => ['select', 'block'], 'id' => 'f-shield']); ?></span>
      </p>
      <?php else: ?>
      <div class="f f-content expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
          <?php echo Form::textarea('content', To::yaml($__page[0]->content), null, ['classes' => ['textarea', 'block', 'expand', 'code'], 'id' => 'f-content', 'data' => ['type' => 'YAML']]); ?>
        </div>
      </div>
      <?php endif; ?>
    </fieldset>
    <p class="f f-state expand">
      <label for="f-state"><?php echo $language->state; ?></label>
      <span>
        <?php echo Form::submit('x', 'php', $language->update, ['classes' => ['button', 'x-page'], 'id' => 'f-state:php']); ?>
      </span>
    </p>
    <?php echo Form::token(); ?>
  </main>
</form>