<?php echo __panel_s__('kin', [
    'title' => $language->{count($__kins[0]) === 1 ? 'config' : 'configs'},
    'content' => $__kins,
    'a' => [
        
    ],
    'if' => $__kins[0]
]); ?>