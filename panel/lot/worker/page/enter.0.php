<fieldset>
  <legend><?php echo $language->log_in; ?></legend>
  <p class="f f-user">
    <label for="f-user"><?php echo $language->user; ?></label>
    <span><?php echo Form::text('user', null, null, ['classes' => ['input', 'block'], 'id' => 'f-user', 'autofocus' => true]); ?></span>
  </p>
  <p class="f f-pass">
    <label for="f-pass"><?php echo $language->pass; ?></label>
    <span><?php echo Form::password('pass', null, Request::restore('post', 'pass_x') ? l($language->new__($language->password)) : null, ['classes' => ['input', 'block'], 'id' => 'f-pass']); ?></span>
  </p>
  <?php echo Form::hidden('kick', Request::get('kick', $__state->path . '/::g::/page')); ?>
</fieldset>
<p class="f f-enter expand">
  <label for="f-enter"><?php echo $language->enter; ?></label>
  <span><?php echo Form::submit('enter', 1, $language->enter, ['classes' => ['button', 'set'], 'id' => 'f-enter']); ?></span>
</p>