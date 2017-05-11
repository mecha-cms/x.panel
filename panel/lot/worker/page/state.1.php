    <?php if ($__kins[0]): ?>
    <section class="s-kin">
      <h3><?php echo $language->{count($__kins[0]) === 1 ? 'config' : 'configs'}; ?></h3>
      <ul>
      <?php foreach ($__kins[0] as $__k => $__v): ?>
        <li><?php echo HTML::a('<i class="i i-1"></i> ' . Path::B($__v->path), $__state->path . '/::g::/' . $__chops[0] . '/' . $__v->name); ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php endif; ?>