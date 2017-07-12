
<?php echo __panel_s__('child', [
    'content' => $__childs,
    'a' => [
        ['&#x2795;', $__state->path . '/::s::/' . Path::D($__path), false, ['title' => $language->add]],
        $__is_has_step_child ? ['&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]] : null
    ]
]); ?>