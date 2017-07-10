<?php if (substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false): ?>
<?php echo __panel_s__('source', [
    'content' => $__source[0] ? [[$__source[0]], [$__source[1]]] : [],
    'if' => $__source[0]
]); ?>
<?php echo __panel_s__('kin', [
    'content' => $__datas,
    'a' => [
        HTML::a('&#x2795;', $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+', false, ['title' => $language->add])
    ]
]); ?>
<?php else: ?>
<?php include __DIR__ . DS . '-author.php'; ?>
<?php

$__ = [
    'url' => str_replace('::s::', '::g::', $url->path),
    'title' => '..'
];

echo __panel_s__('parent', [
    'content' => $__parent[0] ? [[$__parent[0]], [$__parent[1]]] : [[$__], [$__]]
]);

?>
<?php echo __panel_s__('kin', [
    'content' => $__kins,
    'a' => [
        HTML::a('&#x2795;', $__state->path . '/::s::/' . (Path::D($__path) ?: $__path), false, ['title' => $language->add]),
        $__is_has_step_kin ? HTML::a('&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]) : null
    ]
]); ?>
<?php if ($__action !== 's'): ?>
<section class="s-setting">
  <h3><?php echo $language->settings; ?></h3>
  <?php if ($__has_pages = Get::pages(LOT . DS . $__path, 'draft,page,archive')): ?>
  <h4><?php echo $language->sort; ?></h4>
  <table class="table">
    <thead>
      <tr>
        <th><?php echo $language->order; ?></th>
        <th><?php echo $language->by; ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo Form::radio('sort[0]', $language->o_sorts[0], isset($__parent[0]->sort[0]) ? $__parent[0]->sort[0] : (isset($__page[0]->sort[0]) ? $__page[0]->sort[0] : null), ['classes' => ['input']]); ?></td>
        <td><?php echo Form::radio('sort[1]', $language->o_sorts[1], isset($__parent[0]->sort[1]) ? $__parent[0]->sort[1] : (isset($__page[0]->sort[1]) ? $__page[0]->sort[1] : null), ['classes' => ['input']]); ?></td>
      </tr>
    </tbody>
  </table>
  <h4><?php echo $language->panel->chunk; ?></h4>
  <p><?php echo Form::number('chunk', $__page[0]->chunk, 7, ['classes' => ['input', 'block'], 'min' => 0, 'max' => 50]); ?></p>
  <?php endif; ?>
  <h4><?php echo $language->options; ?></h4>
  <p><?php

$__options = [];
foreach (a(Config::get('panel.f.page.options', [])) as $__k => $__v) {
    if (!isset($__v)) continue;
    $__options[] = Form::checkbox($__k, isset($__v['value']) ? $__v['value'] : 1, isset($__v['is']['active']) && $__v['is']['active'], isset($__v['title']) ? $__v['title'] : (isset($language->panel->{$__k}) ? $language->panel->{$__k} : $language->{$__k}), ['classes' => ['input']]);
}

echo implode('<br>', $__options);

?></p>
  <?php echo Hook::fire('panel.h.page.options', ["", $__page]); ?>
</section>
<?php endif; ?>
<?php endif; ?>