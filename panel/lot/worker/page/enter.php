<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="<?php echo 'path-' . str_replace('/', ' path-', $__path); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <!-- Prevent search engines from indexing the login page -->
    <meta name="robots" content="noindex">
    <title><?php echo $language->{$__chops[0]} . ' &#x00B7; ' . $language->__title; ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body spellcheck="false">
    <form id="form.main" action="<?php echo $url->current; ?>" method="post">
      <?php echo $__message; ?>
      <p class="f">
        <label for="f-user"><?php echo $language->user; ?></label>
        <span><?php echo Form::text('user', null, null, ['classes' => ['input', 'block'], 'id' => 'f-user', 'autofocus' => true]); ?></span>
      </p>
      <p class="f">
        <label for="f-pass"><?php echo $language->pass; ?></label>
        <span><?php echo Form::password('pass', null, null, ['classes' => ['input', 'block'], 'id' => 'f-pass']); ?></span>
      </p>
      <p class="f">
        <label for="f-enter"><?php echo $language->enter; ?></label>
        <span><?php echo Form::submit('enter', 1, $language->enter, ['classes' => ['button', 'set'], 'id' => 'f-enter']); ?></span>
      </p>
      <?php echo Form::token(); ?>
    </form>
  </body>
</html>