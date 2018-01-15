<?php

$__query = HTTP::query($__q = [
    'token' => false,
    'r' => false,
    $config->q => false
]);

if (Request::get('q')) {
    $__links = ['reset' => [$language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step . $__query]];
} else {
    if (count($__chops) > 1 && is_dir(LOT . DS . $__path)) {
        $__q['token'] = $__token;
        $__links['reset'] = [$language->delete, $__state->path . '/::r::/' . $__path . HTTP::query($__q)];
        $__q['m']['t:v'] = 'file';
    }
    $__q['token'] = false;
    $__links = [
        'set' => [$language->{count($__chops) === 1 ? Config::get('panel.n.' . $__chops[0] . '.text', $__chops[0]) : 'file'}, $__state->path . '/::s::/' . $__path . HTTP::query($__q)]
    ];
    if (count($__chops) > 1) {
        $__q['m']['t:v'] = 'folder';
        $__links['folder'] = [$language->folder, $__state->path . '/::s::/' . $__path . HTTP::query($__q)];
        $__q['m']['t:v'] = 'upload';
        $__links['upload'] = [$language->upload, $__state->path . '/::s::/' . $__path . HTTP::query($__q)];
    }
    unset($__q);
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
<section class="m-file">
  <?php require __DIR__ . DS . '-m.php'; ?>
</section>