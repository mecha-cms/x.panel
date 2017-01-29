<?php array_pop($__chops); $__path = implode('/', $__chops); ?>
<form id="form.main" action="<?php echo $url . '/' . $__state->path . '/::s::/' . $__path . '/d+' . $url->query; ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
    <?php if ($__page[0]): ?>
    <section class="secondary-page">
      <h3><?php echo $language->source; ?></h3>
      <ul>
        <li class="x-<?php echo $__page[0]->state; ?>"><?php echo HTML::a($__page[1]->title, $__page[0]->url); ?></li>
      </ul>
    </section>
    <?php endif; ?>
    <?php if ($__kins[0]): ?>
    <section class="secondary-kin">
      <h3><?php echo $language->kins; ?></h3>
      <ul>
        <?php foreach ($__kins[0] as $k => $v): ?>
        <li class="data-<?php echo $v->key; ?>"><?php echo HTML::a($__kins[1][$k]->key, $__state->path . '/::g::/' . $__path . '/d:' . $v->key); ?></li>
        <?php endforeach; ?>
        <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . $__path . '/d+', false, ['title' => $language->add]); ?></li>
      </ul>
    </section>
    <?php endif; ?>
    <?php Hook::NS('panel.secondary.1.after'); ?>
  </aside>
  <main class="main">
    <?php echo $__message; ?>
    <?php Hook::NS('panel.main.before'); ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <div class="f expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', $__data[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'f-content'
]); ?>
        </div>
      </div>
      <p class="f">
        <label for="f-key"><?php echo $language->key; ?></label> <span>
<?php echo Form::text('key', $__data[0]->key, $__data[0]->key, [
    'classes' => ['input'],
    'id' => 'f-key'
]); ?>
        </span>
      </p>
    </fieldset>
    <?php echo Form::token(); ?>
    <?php Hook::NS('panel.main.after'); ?>
    <p class="f expand">
      <label for="f-x"><?php echo $language->state; ?></label> <span>
<?php

echo Form::submit('x', 'data', $language->{$__sgr === 's' ? 'create' : 'update'}, ['classes' => ['button', 'x-data'], 'id' => 'f-x:data']);
echo ' ' . Form::submit('x', 'trash', $language->delete, ['classes' => ['button', 'x-trash'], 'id' => 'f-x:trash']);

?>
      </span>
    </p>
<?php Shield::get([
    $__path_shield . DS . $site->type . DS . '_footer.php',
    __DIR__ . DS . '_footer.php'
]); ?>
  </main>
</form>