<?php include __DIR__ . DS . '-t.php'; ?>
<p class="f f-state expand">
  <label for="f-state"><?php echo $language->state; ?></label>
  <span>
<?php

echo Form::submit('x', 'page', $language->{$__sgr === 's' ? 'create' : 'update'}, [
    'classes' => ['button', 'set', 'x-page'],
    'id' => 'f-state:page'
]);

if ($__sgr !== 's') {
    echo ' ' . Form::submit('x', 'trash', $language->delete, [
        'classes' => ['button', 'set', 'x-trash'],
        'id' => 'f-state:trash'
    ]);
}

?>
  </span>
</p>