<?php

$__parents = [];
$__s = end($__chops);
foreach (g(LOT . DS . Path::D($__path), 'draft,page') as $__v) {
    $__vv = new Date(Path::N($__v));
    $__parents[$__vv->slug] = HTML::a($__vv->F2, $__state->path . '/::g::/' . $__chops[0] . '/' . ltrim(To::url(Path::F($__v, COMMENT)),'/'), true);
}

echo __panel_s__('parent', [
    'title' => $language->parent,
    'content' => '<p>' . Form::radio('parent', $__parents, (new Date($__page[0]->parent))->slug, ['classes' => ['input']]) . '</p>'
]);

?>