<?php include __DIR__ . DS . '-t.php'; ?>
<p class="f f-state expand">
  <label for="f-state"><?php echo $language->state; ?></label>
  <span>
<?php

$__x = $__page[0]->state;

if ($__sgr !== 's') {
    echo Form::submit('x', $__x, $language->update, [
        'classes' => ['button', 'set', 'x-' . $__x],
        'id' => 'f-state:' . $__x
    ]);
}

foreach ([
    'page' => $language->publish,
    'draft' => $language->save,
    'trash' => $__sgr === 's' ? false : $language->delete
] as $__k => $__v) {
    if (!$__v || $__x === $__k) continue;
    echo ' ' . Form::submit('x', $__k, $__v, [
        'classes' => ['button', 'set', 'x-' . $__k],
        'id' => 'f-state:' . $__k
    ]);
}

?>
  </span>
</p>