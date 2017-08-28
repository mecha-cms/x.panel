<?php

$__links = Hook::fire('panel.a.' . $__chops[0] . 's', [[]]);

foreach ($__links as $__k => $__v) {
    if (!$__v) continue;
    if (is_array($__v)) {
        $__links[$__k] = call_user_func_array('HTML::a', array_replace_recursive([null, null, false, ['classes' => ['button', 'button:' . $__k]]], $__v));
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