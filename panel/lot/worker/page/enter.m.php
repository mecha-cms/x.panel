<fieldset>
  <legend><?php echo $language->log_in; ?></legend>
  <?php $__pass_x = Request::restore('post', 'pass_x'); ?>
  <p class="f f-user">
    <label for="f-user"><?php echo $language->user; ?></label>
    <span><?php echo Form::text('user', null, $language->f_user, ['classes' => ['input', 'block'], 'id' => 'f-user', 'autofocus' => $__pass_x ? null : true]); ?></span>
  </p>
  <p class="f f-pass">
    <label for="f-pass"><?php echo $language->pass; ?></label>
    <span><?php echo Form::password('pass', null, $__pass_x ? l($language->new__($language->password)) : null, ['classes' => ['input', 'block'], 'id' => 'f-pass', 'autofocus' => $__pass_x ? true : null]); ?></span>
  </p>
  <?php echo Form::hidden('kick', Request::get('kick', $__state->path . '/::g::/page')); ?>
</fieldset>
<p class="f f-enter expand">
  <label for="f-enter"><?php echo $language->enter; ?></label>
  <span><?php echo Form::submit('enter', 1, $language->enter, ['classes' => ['button', 'set'], 'id' => 'f-enter']); ?></span>
</p>