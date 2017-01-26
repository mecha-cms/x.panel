<form id="form.main" action="<?php echo $url . '/' . $__state->path . '/::r::/' . $__path; ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
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
<?php echo Form::textarea('content', $__page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code'],
    'id' => 'f-content'
]); ?>
        </div>
      </div>
    </fieldset>
    <?php echo Form::token(); ?>
    <?php Hook::NS('panel.main.after'); ?>
    <p class="f expand">
      <label for="f-x"><?php echo $language->state; ?></label> <span>
<?php

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