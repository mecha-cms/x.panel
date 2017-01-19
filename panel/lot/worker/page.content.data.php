<form id="form.main" action="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops); ?>" method="post">
  <main class="main block">
    <?php Hook::NS('panel.main.before'); ?>
    <section>
      <?php echo $message; ?>
      <fieldset>
        <legend><?php echo $language->editor; ?></legend>
        <div class="control expand p">
          <label for="control-page-content"><?php echo $language->content; ?></label>
          <div>
<?php echo Form::textarea('content', null, null, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'control-page-content',
    'data' => ['type' => null]
]); ?>
          </div>
        </div>
      </fieldset>
      <p class="control expand">
        <label for="control-page-x"><?php echo $language->state; ?></label>
        <span>
<?php

echo Form::submit('x', 'data', $language->publish, ['classes' => ['button', 'x-data'], 'id' => 'control-page-x:data']) . ' ';
echo Form::submit('x', 'trash', $language->delete, ['classes' => ['button', 'x-trash'], 'id' => 'control-page-x:trash']);

?>
        </span>
      </p>
      <?php echo Form::hidden('token', $token); ?>
    </section>
    <?php Hook::NS('panel.main.after'); ?>
    <?php Shield::get($shield_path . DS . 'footer.php'); ?>
  </main>
</form>