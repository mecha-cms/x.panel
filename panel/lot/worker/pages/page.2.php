<?php include __DIR__ . DS . '-search.php'; ?>
<?php echo __panel_s__('parent', [
    'content' => [true],
    'a' => $__parent[0] ? [
        $__parent[1]->title => $__parent[0]->url . '/1'
    ] : [],
    'if' => $__parent[0]
]); ?>
<?php echo __panel_s__('kin', [
    'content' => $__kins,
    'a' => [
        $__is_has_step_kin ? HTML::a('&#x2026;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]) : null
    ],
    'if' => $__kins[0]
]); ?>
<?php echo __panel_s__('nav', [
    'title' => $language->navigation,
    'content' => '<p>' . $__pager[0] . '</p>',
    'if' => $__pager[0]
]); ?>