<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="no-js <?php echo 'p-' . str_replace('/', ' p-', $__path) . ' layout-' . Config::get('panel.layout', 0); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php echo Config::get('panel.n.' . $__chops[0] . '.text', $language->{$__chops[0]}) . ' &#x00B7; ' . $language->__title; ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
    <script>!function(n,e){n[e]=n[e].replace(/\bno-js\b/g,"js")}(document.documentElement,"className");</script>
  </head>
  <body spellcheck="false">