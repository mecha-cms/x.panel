<?php

$__query = HTTP::query([
    'token' => false,
    'r' => false,
    $config->q => false
]);

if (Request::get('q')) {
    $__links = ['reset' => [$language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step . $__query]];
} else {
    $__links = ['set' => [Config::get('panel.n.' . $__chops[0] . '.text', $language->{$__chops[0]}), $__state->path . '/::s::/' . $__path . $__query]];
}

$__links = Hook::fire('panel.a.' . $__chops[0] . 's', [$__links]);

foreach ($__links as $__k => $__v) {
    if (!isset($__v)) continue;
    if (is_array($__v)) {
        $__links[$__k] = call_user_func_array('HTML::a', array_replace_recursive([null, null, false, ['class[]' => ['button', 'button:' . $__k]]], $__v));
    }
}

?>
<?php if ($__links): ?>
<section class="m-button">
  <p><?php echo implode(' ', $__links); ?></p>
</section>
<?php endif; ?>
<section class="m-page">
  <?php require __DIR__ . DS . '-m.php'; ?>
</section>