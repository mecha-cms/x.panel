<form id="form.m.view" action="<?php echo $url . '/' . $__state->path . '/::r::/' . $__path . $url->query; ?>" method="post">
  <aside class="s">
  </aside>
  <main class="m">
    <?php echo $__message; ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <div class="f f-content expand p">
        <label for="f-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', $__page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'f-content'
]); ?>
        </div>
      </div>
    </fieldset>
    <p class="f f-state expand">
      <label for="f-state"><?php echo $language->state; ?></label>
      <span>
<?php echo Form::submit('x', 'trash', $language->delete, ['classes' => ['button', 'set', 'x-trash'], 'id' => 'f-state:trash']); ?>
      </span>
    </p>
    <?php echo Form::hidden('token', $__token); ?>
  </main>
</form>