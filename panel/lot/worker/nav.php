<nav class="nav">
  <ul><!--
    <?php if ($__menus = glob(LOT . DS . '*', GLOB_ONLYDIR)): ?>
      <?php foreach ($__menus as $__menu): ?>
      <?php $__menu = Path::N($__menu); ?>
      <?php $c = strpos($url->path . '/', '::/' . $__menu . '/') !== false ? ' is-current' : ""; ?>
      --><li class="nav-<?php echo $__menu . $c; ?>">
        <a href="<?php echo $url . '/' . $__state->path . '/::g::/' . $__menu; ?>"><?php echo $language->{$__menu}; ?></a>
      </li><!--
      <?php endforeach; ?>
    <?php endif; ?>
    --><li>
      <a href="">&#x2026;</a>
      <?php if ($__sn): ?>
      <ul>
        <?php foreach ($__sn as $k => $v): ?>
        <li class="nav--<?php echo strpos($url->path . '/', '::/' . $k . '/') !== false ? ' is-current' : ""; ?>">
          <?php if (is_string($v)): ?>
          <?php echo $v; ?>
          <?php else: ?>
          <?php $i = !empty($v['i']) ? ' <i>' . $v['i'] . '</i>' : ""; ?>
          <?php echo HTML::a($v['text'] . $i, null, false, $v['attributes']); ?>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </li><!--
  --></ul>
</nav>