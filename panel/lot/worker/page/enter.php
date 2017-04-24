<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="<?php echo 'p-' . str_replace('/', ' p-', $__path); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <!-- Prevent search engines from indexing the login page -->
    <meta name="robots" content="noindex">
    <title><?php echo $language->{$__chops[0]} . ' &#x00B7; ' . $language->__title; ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body spellcheck="false">
    <div class="c">
      <main class="m">
        <form id="form.m.enter" action="" method="post">
          <?php echo $__message; ?>
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
          <?php echo Form::hidden('token', $__token); ?>
        </form>
      </main>
    </div>
  </body>
</html>