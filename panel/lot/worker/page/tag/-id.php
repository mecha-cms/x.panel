<?php

$__id = 0;

call_user_func(function() use(&$__id, $__path) {
    foreach (glob(LOT . DS . $__path . DS . '*' . DS . 'id.data', GLOB_NOSORT) as $__v) {
        $__ = (int) file_get_contents($__v);
        if ($__ > $__id) $__id = $__;
    }
    ++$__id;
});

return '<p>' . Form::text('!+[id]', $__command === 's' ? $__id : $__page[0]->id, $__id, ['class[]' => ['input', 'width'], 'id' => 'f-id', 'ondblclick' => 'this.removeAttribute(\'readonly\'),this.focus(),this.select();', 'onblur' => 'this.setAttribute(\'readonly\',!0);']) . '</p>';