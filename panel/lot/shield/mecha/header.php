<nav class="nav">
  <ul><!--
    --><li>
      <?php if (!$url->path || $url->path === $site->slug): ?>
      <span><?php echo $language->home; ?></span>
      <?php else: ?>
      <a href="<?php echo $url; ?>"><?php echo $language->home; ?></a>
      <?php endif; ?>
    </li><!--
    <?php if ($menus = glob(LOT . DS . '*', GLOB_ONLYDIR)): ?>
      <?php foreach ($menus as $menu): ?>
      <?php $menu = Path::N($menu); ?>
      --><li>
        <?php if ($url->path === $menu || strpos('/' . $url->path . '/', '/' . $menu . '/') === 0): ?>
        <span><?php echo $language->{$menu}; ?></span>
        <?php else: ?>
        <a href="<?php echo $url . '/' . $site->slug . '/' . $menu; ?>"><?php echo $language->{$menu}; ?></a>
        <?php endif; ?>
      </li><!--
      <?php endforeach; ?>
    <?php endif; ?>
  --></ul>
</nav>