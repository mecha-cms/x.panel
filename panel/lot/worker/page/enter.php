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
            <p class="f f-_key">
              <label for="f-_key"><?php echo $language->user; ?></label>
              <span><?php echo Form::text('_key', null, null, ['classes' => ['input', 'block'], 'id' => 'f-_key', 'autofocus' => true]); ?></span>
            </p>
            <p class="f f-_pass">
              <label for="f-_pass"><?php echo $language->pass; ?></label>
              <span><?php echo Form::password('_pass', null, null, ['classes' => ['input', 'block'], 'id' => 'f-_pass']); ?></span>
            </p>
            <?php echo Form::hidden('kick', Request::get('kick', $__state->path . '/::g::/page')); ?>
          </fieldset>
          <p class="f f-_enter expand">
            <label for="f-_enter"><?php echo $language->enter; ?></label>
            <span><?php echo Form::submit('_enter', 1, $language->enter, ['classes' => ['button', 'enter'], 'id' => 'f-_enter']); ?></span>
          </p>
          <?php echo Form::token(); ?>
        </form>
      </main>
    </div>
  </body>
</html>