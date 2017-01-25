<?php array_pop($__chops); ?>
<form id="form.main" action="<?php echo $url . '/' . $__state->path . '/::s::/' . implode('/', $__chops) . '/d+'; ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
    <?php if ($__page[0]): ?>
    <section class="secondary-page">
      <h3><?php echo $language->source; ?></h3>
      <ul>
        <li class="state-<?php echo $__page[0]->state; ?>"><a href="<?php echo $__page[0]->url; ?>"><?php echo $__page[1]->title; ?></a></li>
      </ul>
    </section>
    <?php endif; ?>
    <?php if ($__kins[0]): ?>
    <section class="secondary-kin">
      <h3><?php echo $language->kins; ?></h3>
      <ul>
        <?php foreach ($__kins[0] as $k => $v): ?>
        <li class="data-<?php echo $v->key; ?>"><a href="<?php echo $url . '/' . $__state->path . '/::g::/' . implode('/', $__chops) . '/d:' . $v->key; ?>"><?php echo $__kins[1][$k]->key; ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?php echo $url . '/' . $__state->path . '/::s::/' . implode('/', $__chops) . '/d+'; ?>" title="<?php echo $language->add; ?>">&#x2795;</a></li>
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

echo Form::submit('x', 'data', $language->{$__sgr === 's' ? 'create' : 'update'}, ['classes' => ['button', 'x-data'], 'id' => 'f-x:data']) . ' ';
echo Form::submit('x', 'trash', $language->delete, ['classes' => ['button', 'x-trash'], 'id' => 'f-x:trash']);

?>
      </span>
    </p>
<?php Shield::get([
    $__path_shield . DS . $site->type . DS . '_footer.php',
    __DIR__ . DS . '_footer.php'
]); ?>
  </main>
</form>