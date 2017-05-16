<?php include __DIR__ . DS . '-author.php'; ?>
<?php if ($__kins[0]): ?>
<section class="s-kin">
  <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
  <ul>
    <?php foreach ($__kins[0] as $k => $v): ?>
    <li><?php echo HTML::a($__kins[1][$k]->title, $v->url); ?></li>
    <?php endforeach; ?>
    <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . Path::D($__path), false, ['title' => $language->add]); ?><?php echo $__is_has_step_kin ? ' ' . HTML::a('&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]) : ""; ?></li>
  </ul>
</section>
<?php endif; ?>