<?php include __DIR__ . DS . '-search.php'; ?>
<?php echo __panel_s__('parent', [
    'content' => $__parent[0] ? [[$__parent[0]], [$__parent[1]]] : [],
    'if' => $__parent[0]
]); ?>
<?php echo __panel_s__('kin', [
    'content' => $__kins,
    'a' => [
        $__is_has_step_kin ? HTML::a('&#x2026;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]) : null
    ],
    'if' => $__kins[0]
], '/1'); ?>
<?php include __DIR__ . DS . '-nav.php'; ?>