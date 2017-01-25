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
    <?php if ($__menus = glob(LOT . DS . '*', GLOB_ONLYDIR)): ?>
      <?php foreach ($__menus as $__menu): ?>
      <?php $__menu = Path::N($__menu); ?>
      <?php $c = strpos($url->path . '/', '::/' . $__menu . '/') !== false ? ' is-current' : ""; ?>
      --><li class="nav-<?php echo $__menu . $c; ?>">
        <a href="<?php echo $url . '/' . $s . '/::g::/' . $__menu; ?>"><?php echo $language->{$__menu}; ?></a>
      </li><!--
      <?php endforeach; ?>
    <?php endif; ?>
    --><li>
      <a href="">&#x2026;</a>
      <ul>
        <?php if ($__error = File::exist(ENGINE . DS . 'log' . DS . 'error.log')): ?>
        <li><a href="<?php echo $url . '/' . $s . '/::g::/error'; ?>"><?php echo $language->error; ?></a></li>
        <?php endif; ?>
        <li><a href="<?php echo $url . '/' . $s . '/::g::/exit'; ?>"><?php echo $language->exit; ?></a></li>
      </ul>
    </li><!--
  --></ul>
</nav>