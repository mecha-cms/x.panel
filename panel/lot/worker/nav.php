<?php $s = Extend::state('panel', 'path', 'panel'); ?>
<nav class="nav">
  <ul><!--
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
        <li><a href="<?php echo $url; ?>" target="_blank"><?php echo $language->view . ' ' . $language->site; ?></a></li>
        <?php if ($__error = File::exist(ENGINE . DS . 'log' . DS . 'error.log')): ?>
        <li><a href="<?php echo $url . '/' . $s . '/::g::/error'; ?>"><?php echo $language->errors; ?></a></li>
        <?php endif; ?>
        <li><a href="<?php echo $url . '/' . $s . '/::g::/exit'; ?>"><?php echo $language->log_out; ?></a></li>
      </ul>
    </li><!--
  --></ul>
</nav>