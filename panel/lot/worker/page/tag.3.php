<?php

$__i = 0;
foreach (glob(TAG . DS . '*' . DS . 'id.data', GLOB_NOSORT) as $__v) {
    $__id = (int) file_get_contents($__v);
    if ($__id > $__i) $__i = $__id;
}
++$__i;

echo __panel_s__('id', [
    'content' => '<p>' . Form::text('!id', $__action === 's' ? $__i : $__page[0]->id, $__i, ['classes' => ['input', 'block'], 'id' => 'f-id']) . '</p>'
]);

?>