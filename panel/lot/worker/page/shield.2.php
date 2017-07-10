  <?php if (count($__chops) > 2): ?>
  <section class="s-parent">
    <h3><?php echo $language->parent; ?></h3>
    <ul>
      <li><?php $__p = Path::D($url->current); echo HTML::a('<i class="i i-0"></i> ' . (count($__chops) === 3 ? '..' : Path::B($__p)), $__p); ?></li>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($__kins[0]): ?>
  <section class="s-kin">
    <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
    <ul>
      <?php foreach ($__kins[0] as $__k => $__v): ?>
      <li><?php echo HTML::a($__kins[1][$__k]->title, $__v->url); ?></li>
      <?php endforeach; ?>
      <?php if ($__is_has_step_kin): ?>
      <li><?php echo HTML::a('&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]); ?></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if (count($__chops) > 2 && $__f = glob(SHIELD . DS . $__chops[1] . DS . Path::D($__page[0]->name) . DS . '*')): ?>
  <?php $__kins = [[]]; ?>
  <?php $__p = Path::D($url->current); $__n = Path::B($__page[0]->name); foreach ($__f as $__v): ?>
  <?php if (Path::B($__v) === $__n || Path::X($__v) === 'trash') continue; ?>
  <?php $__kins[0][] = $__v; ?>
  <?php endforeach; ?>
  <?php if ($__kins[0]): ?>
  <section class="s-kin">
    <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
    <ul>
	  <?php foreach ($__kins[0] as $__v): ?>
      <?php if (Path::X($__v) === 'trash') continue; ?>
      <li><?php echo HTML::a('<i class="i i-' . (is_dir($__v) ? '0' : '1') . '"></i> ' . Path::B($__v), $__p . '/' . Path::B($__v)); ?></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($__page[0]->is->folder): ?>
  <section class="s-child">
    <h3><?php echo $language->{count($__datas[0]) === 1 ? 'child' : 'childs'}; ?></h3>
    <ul>
      <?php foreach ($__datas[0] as $__k => $__v): ?>
      <?php if ($__v->extension === 'trash') continue; ?>
      <li><?php echo HTML::a('<i class="i i-' . (is_dir($__v->path) ? '0' : '1') . '"></i> ' . $__datas[1][$__k]->title, $__v->url); ?></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php endif; ?>