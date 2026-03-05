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
    <link href="<?= eat($link->base('/favicon.ico')); ?>" rel="icon">
    <link href="<?= eat($link->current(false, false)); ?>" rel="canonical">
  </head>
  <body spellcheck="false">
    <?= $content; ?>
  </body>
</html>