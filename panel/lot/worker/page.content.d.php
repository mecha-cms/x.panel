<?php array_pop($chops); ?>
<form id="form.main" action="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops) . '/d+'; ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
    <?php if ($page[0]): ?>
    <section class="secondary-page">
      <h3><?php echo $language->source; ?></h3>
      <ul>
        <li class="state-<?php echo $page[0]->state; ?>"><a href="<?php echo $page[0]->url; ?>"><?php echo $page[1]->title; ?></a></li>
      </ul>
    </section>
    <?php endif; ?>
    <?php if ($kins[0]): ?>
    <section class="secondary-kin">
      <h3><?php echo $language->kins; ?></h3>
      <ul>
        <?php foreach ($kins[0] as $k => $v): ?>
        <li class="data-<?php echo $v->key; ?>"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . implode('/', $chops) . '/d:' . $v->key; ?>"><?php echo $kins[1][$k]->key; ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops) . '/d+'; ?>" title="<?php echo $language->add; ?>">&#x2795;</a></li>
      </ul>
    </section>
    <?php endif; ?>
    <?php Hook::NS('panel.secondary.1.after'); ?>
  </aside>
  <main class="main">
    <?php echo $message; ?>
    <?php Hook::NS('panel.main.before'); ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <div class="field expand p">
        <label for="field-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', $data[0]->content, $data[0]->content, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'field-content'
]); ?>
        </div>
      </div>
      <p class="field">
        <label for="field-key"><?php echo $language->key; ?></label> <span>
<?php echo Form::text('key', $data[0]->key, $data[0]->key, [
    'classes' => ['input'],
    'id' => 'field-key'
]); ?>
        </span>
      </p>
    </fieldset>
    <?php echo Form::hidden('token', $token); ?>
    <?php Hook::NS('panel.main.after'); ?>
    <p class="field expand">
      <label for="field-x"><?php echo $language->state; ?></label> <span>
<?php

echo Form::submit('x', 'data', $language->{$sgr === 's' ? 'create' : 'update'}, ['classes' => ['button', 'x-data'], 'id' => 'field-x:data']) . ' ';
echo Form::submit('x', 'trash', $language->delete, ['classes' => ['button', 'x-trash'], 'id' => 'field-x:trash']);

?>
      </span>
    </p>
    <?php Shield::get(__DIR__ . DS . 'footer.content.php'); ?>
  </main>
</form>