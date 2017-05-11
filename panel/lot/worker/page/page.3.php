<?php if (substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false): ?>
<?php else: ?>
    <section class="s-data">
<?php

$__a = [
    'title' => 1,
    'description' => 1,
    'author' => 1,
    'type' => 1,
    'link' => 1,
    'content' => 1,
    'time' => 1,
    'kind' => 1,
    'slug' => 1,
    'state' => 1
];

$__aparts = Page::apart($__sgr === 'g' ? file_get_contents($__page[0]->path) : "");
foreach ($__aparts as $__k => $__v) {
    if (isset($__a[$__k])) {
        unset($__aparts[$__k]);
        continue;
    }
    $__aparts[$__k] = is_array($__v) ? json_encode($__v) : s($__v);
}

?>
      <h3><?php echo $language->{count($__datas[0]) + count($__aparts) === 1 ? 'data' : 'datas'}; ?></h3>
      <?php if ($__sgr === 'g'): ?>
      <ul>
        <?php foreach ($__datas[0] as $__k => $__v): ?>
        <li><?php echo HTML::a($__datas[1][$__k]->key, $__v->url); ?></li>
        <?php endforeach; ?>
        <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+', false, ['title' => $language->add]); ?></li>
      </ul>
      <?php endif; ?>
      <p><?php echo Form::textarea('__data', To::yaml($__aparts), $language->f_yaml, ['classes' => ['textarea', 'block', 'code']]); ?></p>
    </section>
    <?php if (count($__chops) > 1): ?>
    <section class="s-child">
      <h3><?php echo $language->{count($__childs[0]) === 1 ? 'child' : 'childs'}; ?></h3>
      <ul>
        <?php foreach ($__childs[0] as $__k => $__v): ?>
        <?php

        $__g = $__v->path;
        $__gg = Path::X($__g);
        $__ggg = Path::D($__g);
        $__gggg = Path::N($__g) === Path::N($__ggg) && file_exists($__ggg . '.' . $__gg);

        if ($__gggg) continue; // skip the placeholder page

        ?>
        <li><?php echo HTML::a($__childs[1][$__k]->title, $__v->url); ?></li>
        <?php endforeach; ?>
        <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . $__path, false, ['title' => $language->add]); ?><?php echo $__is_has_step_child ? ' ' . HTML::a('&#x22EF;', $__state->path . '/::g::/' . $__path . '/2', false, ['title' => $language->more]) : ""; ?></li>
      </ul>
    </section>
    <?php endif; ?>
<?php endif; ?>