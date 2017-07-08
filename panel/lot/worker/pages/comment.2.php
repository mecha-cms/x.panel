<?php include __DIR__ . DS . '-search.php'; ?>
<?php echo __panel_s__('nav', [
    'title' => $language->navigation,
    'content' => '<p>' . $__pager[0] . '</p>',
    'if' => $__pager[0]
]); ?>