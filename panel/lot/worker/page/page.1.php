<?php if (substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false): ?>
<?php include __DIR__ . DS . '-t.php'; ?>
<p class="f f-state expand">
  <label for="f-state"><?php echo $language->state; ?></label>
  <span>
<?php

$__s = substr($__path, -2) === '/+';
foreach ([
    'data' => $language->{$__s ? 'save' : 'update'},
    'trash' => $__s ? false : $language->delete
] as $__k => $__v) {
    if (!$__v) continue;
    echo ' ' . Form::submit('x', $__k, $__v, [
        'classes' => ['button', 'set', 'x-' . $__k],
        'id' => 'f-state:' . $__k
    ]);
}

?>
  </span>
</p>
<?php else: ?>
<?php include __DIR__ . DS . '-t.php'; ?>
<p class="f f-state expand">
  <label for="f-state"><?php echo $language->state; ?></label>
  <span>
<?php

$__x = $__page[0]->state;

if ($__sgr !== 's') {
    echo Form::submit('x', $__x, $language->update, [
        'classes' => ['button', 'set', 'x-' . $__x],
        'id' => 'f-state:' . $__x,
        'title' => $__x
    ]);
}

foreach ([
    'page' => $language->publish,
    'draft' => $language->save,
    'archive' => $language->archive,
    'trash' => $__sgr !== 's' ? $language->delete : false
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
<?php endif; ?>