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
    <form id="form.main" action="<?php echo $url->current . $url->query; ?>" method="post">
      <?php Hook::fire('panel', [Lot::get(null, [])]); ?>
    </form>
  </body>
</html>