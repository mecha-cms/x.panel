    <section class="s-language">
      <h3><?php echo $language->language; ?></h3>
      <ul>
        <?php foreach ($__kins[0] as $k => $v): ?>
        <li><?php echo HTML::a($__kins[1][$k]->title, $__state->path . '/::g::/' . $__chops[0] . '/' . $v->slug); ?></li>
        <?php endforeach; ?>
        <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . $__chops[0], false, ['title' => $language->add]); ?></li>
      </ul>
    </section>