<!DOCTYPE html>
<html class>
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width" name="viewport">
    <meta content="<?= w($description); ?>" name="description">
    <meta content="noindex" name="robots">
    <title>
      <?= w($title); ?>
    </title>
    <link href="<?= eat($url->current(false, false)); ?>" rel="canonical">
    <link href="<?= eat($url . '/favicon.ico'); ?>" rel="icon">
  </head>
  <body spellcheck="false">
    <?= $content; ?>
  </body>
</html>