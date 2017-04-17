<nav class="n">
  <ul><!--
    <?php if ($__menus = glob(LOT . DS . '*', GLOB_ONLYDIR)): ?>
      <?php foreach ($__menus as $__menu): ?>
      <?php $__menu = Path::N($__menu); ?>
      <?php $c = strpos($url->path . '/', '::/' . $__menu . '/') !== false ? ' is-current' : ""; ?>
      --><li class="n-<?php echo $__menu . $c; ?>">
        <a href="<?php echo $url . '/' . $__state->path . '/::g::/' . $__menu; ?>"><?php echo $language->{$__menu}; ?></a>
      </li><!--
      <?php endforeach; ?>
    <?php endif; ?>
    --><li class="n--">
      <a href="">&#x22EE;</a>
      <?php if ($__n): ?>
      <ul>
        <?php foreach ($__n as $k => $v): ?>
        <li class="n-<?php echo $k . (strpos($url->path . '/', '::/' . $k . '/') !== false ? ' is-current' : ""); ?>">
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
  <span>
    <?php echo HTML::img($__user->avatar($url->protocol . 'www.gravatar.com/avatar/' . md5($__user->email ?: $__user->key) . '?s=60&amp;d=monsterid')); ?>
    <?php echo User::ID . $__user->key; ?>
  </span>
</nav>