<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="js:false <?php echo 'command:' . $__command . ' layout:' . Config::get('panel.layout', 0) . ' view:' . Config::get('panel.view', 'file') . ' path:' . $__path . ' chop:' . str_replace('/', ' chop:', $__path); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php echo Config::get('panel.n.' . $__chops[0] . '.text', $language->{$__chops[0]}) . ' &#x00B7; ' . $language->__title; ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
    <script>!function(n,e){n[e]=n[e].replace(/\bjs:false\b/g,"js:true")}(document.documentElement,"className");</script>
  </head>
  <body spellcheck="false">