<?php $s = Extend::state('panel', 'path', 'panel'); ?>
<nav class="nav">
  <ul><!--
    --><li>
      <?php if (!$url->path || $url->path === $site->path): ?>
      <span><?php echo $language->home; ?></span>
      <?php else: ?>
      <a href="<?php echo $url; ?>"><?php echo $language->home; ?></a>
      <?php endif; ?>
    </li><!--
    <?php if ($menus = glob(LOT . DS . '*', GLOB_ONLYDIR)): ?>
      <?php foreach ($menus as $menu): ?>
      <?php $menu = Path::N($menu); ?>
      <?php $c = strpos($url->path . '/', '::/' . $menu . '/') !== false ? ' current' : ""; ?>
      --><li class="nav-<?php echo $menu . $c; ?>">
        <a href="<?php echo $url . '/' . $s . '/::g::/' . $menu; ?>"><?php echo $language->{$menu}; ?></a>
      </li><!--
      <?php endforeach; ?>
    <?php endif; ?>
    --><li>
      <a href="">&#x2026;</a>
    </li><!--
  --></ul>
</nav>