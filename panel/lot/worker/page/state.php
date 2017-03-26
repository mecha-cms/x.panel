<form id="form.m.editor" action="" method="post">
  <aside class="s">
    <?php if ($__kins[0]): ?>
    <section class="s-kin">
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
  <main class="m">
    <?php echo $__message; ?>
    <fieldset>
      <legend><?php echo $language->site; ?></legend>
      <?php if (Path::N($__page[0]->path) === 'config'): ?>
      <p class="f f-zone">
        <label for="f-zone"><?php echo $language->time_zone; ?></label>
        <span>
<?php echo Form::select('config[zone]', Get::zone(), $__page[0]->content['zone'], [
    'classes' => ['select', 'block'],
    'id' => 'f-zone'
]); ?>
        </span>
      </p>
      <p class="f f-charset">
        <label for="f-charset"><?php echo $language->encoding; ?></label>
        <span>
<?php echo Form::text('config[charset]', $__page[0]->content['charset'], 'utf-8', [
    'classes' => ['input', 'block'],
    'id' => 'f-charset'
]); ?>
        </span>
      </p>
      <p class="f f-language">
        <label for="f-language"><?php echo $language->language; ?></label>
        <?php $__languages = []; ?>
        <?php foreach (glob(LANGUAGE . DS . '*.page') as $v): ?>
        <?php $__languages[Path::N($v)] = new Page($v, [], 'language')->title; ?>
        <?php endforeach; ?>
        <span>
<?php echo Form::select('config[language]', $__languages, $__page[0]->content['language'], [
    'classes' => ['select'],
    'id' => 'f-language'
]); ?>
        </span>
      </p>
      <p class="f f-direction">
        <label for="f-direction"><?php echo $language->direction; ?></label>
        <span>
<?php echo Form::select('config[direction]', [
    'ltr' => 'Left to Right (LTR)',
    'rtl' => 'Right to Left (RTL)'
], $__page[0]->content['direction'], [
    'classes' => ['select'],
    'id' => 'f-direction'
]); ?>
        </span>
      </p>
      <p class="f f-title">
        <label for="f-title"><?php echo $language->title; ?></label>
        <span>
<?php echo Form::text('config[title]', $__page[0]->content['title'], null, [
    'classes' => ['input', 'block'],
    'id' => 'f-title'
]); ?>
        </span>
      </p>
      <div class="f f-description p">
        <label for="f-title"><?php echo $language->description; ?></label>
        <div>
<?php echo Form::textarea('config[description]', $__page[0]->content['description'], $language->f_description($language->site), [
    'classes' => ['textarea', 'block'],
    'id' => 'f-description'
]); ?>
        </div>
      </div>
      <p class="f f-shield">
        <label for="f-shield"><?php echo $language->shield; ?></label>
        <?php $__shields = []; ?>
        <?php foreach (glob(SHIELD . DS . '*', GLOB_ONLYDIR) as $v): $v = Path::B($v); ?>
        <?php $__shields[$v] = Shield::info($v)->title; ?>
        <?php endforeach; ?>
        <span>
<?php echo Form::select('config[shield]', $__shields, $__page[0]->content['shield'], [
    'classes' => ['select'],
    'id' => 'f-shield'
]); ?>
        </span>
      </p>
      <fieldset>
        <legend><?php echo $language->page; ?></legend>
        <p class="h"><?php echo $language->h_page; ?></p>
<?php

$__s = isset($__page[0]->content['page']) ? (array) $__page[0]->content['page'] : [];
$__ss = [
    'title' => null,
    'author' => null,
    'type' => 'HTML',
    'content' => null
];

$__s = Anemon::extend($__ss, $__s);

?>
        <p class="f f-page f-page-title">
          <label for="f-page-title"><?php echo $language->title; ?></label>
          <span>
<?php echo Form::text('config[page][title]', $__s['title'], $language->f_title, [
    'classes' => ['input', 'block'],
    'id' => 'f-page-title'
]); ?>
          </span>
        </p>
        <p class="f f-page f-page-author">
          <label for="f-page-author"><?php echo $language->author; ?></label>
          <span>
<?php echo Form::text('config[page][author]', $__s['author'], '@' . l($language->user), [
    'classes' => ['input', 'block'],
    'id' => 'f-page-author'
]); ?>
          </span>
        </p>
        <p class="f f-page f-page-type">
          <label for="f-page-type"><?php echo $language->type; ?></label>
          <span>
<?php echo Form::select('config[page][type]', a(Config::get('panel.f.page.types')), $__s['type'], [
    'classes' => ['select'],
    'id' => 'f-page-type'
]); ?>
          </span>
        </p>
        <div class="f f-page f-page-content p">
          <label for="f-page-content"><?php echo $language->content; ?></label>
          <div>
<?php echo Form::textarea('config[page][content]', $__s['content'], $language->f_content, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'f-page-content',
    'data' => ['type' => $__s['type']]
]); ?>
          </div>
        </div>
      </fieldset>
      <?php else: ?>
      <div class="f f-content expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', To::yaml($__page[0]->content), null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'f-content',
    'data' => ['type' => 'YAML']
]); ?>
        </div>
      </div>
      <?php endif; ?>
    </fieldset>
    <p class="f f-state expand">
      <label for="f-state"><?php echo $language->state; ?></label>
      <span>
<?php echo Form::submit('x', 'php', $language->update, [
    'classes' => ['button', 'x-page'],
    'id' => 'f-state:php'
]); ?>
      </span>
    </p>
    <?php echo Form::token(); ?>
  </main>
</form>