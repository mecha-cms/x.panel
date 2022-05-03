<?php if (isset($_[0]) || isset($_[1])): ?>
  <?= new HTML([$_[0] ?? false, $_[1] ?? "", $_[2] ?? []]); ?>
<?php else: ?>
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
      <link href="<?= $url->current(false, false); ?>" rel="canonical">
      <link href="<?= $url; ?>/favicon.ico" rel="icon">
    </head>
    <body spellcheck="false">
      <?= $content; ?>
    </body>
  </html>
<?php endif; ?>