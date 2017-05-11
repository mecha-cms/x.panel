<nav class="n">
  <ul><!--
    <?php if ($__menus = array_replace(glob(LOT . DS . '*', GLOB_ONLYDIR), a(Config::get('panel.n', [])))): ksort($__menus); ?>
      <?php foreach ($__menus as $__key => $__value): ?>
      <?php if ($__key === 'n' || $__value === false) continue; ?>
      <?php $__value = Path::N($__value); ?>
      <?php $__c = strpos($url->path . '/', '::/' . $__value . '/') !== false ? ' is-current' : ""; ?>
      --><li class="n-<?php echo $__value . $__c; ?>">
        <a href="<?php echo $url . '/' . $__state->path . '/::g::/' . $__value; ?>"><?php echo $language->{$__value}; ?></a>
      </li><!--
      <?php endforeach; ?>
    <?php endif; ?>
    --><li class="n-n">
      <a href="">&#x22EE;</a>
      <?php if ($__n_n): ksort($__n_n); ?>
      <ul>
        <?php foreach ($__n_n as $__k => $__v): ?>
        <li class="n-n-<?php echo $__k . (strpos($url->path . '/', '::/' . $__k . '/') !== false ? ' is-current' : ""); ?>">
          <?php if (is_string($__v)): ?>
          <?php echo $__v; ?>
          <?php else: ?>
          <?php $__i = !empty($__v['i']) ? ' <i>' . $__v['i'] . '</i>' : ""; ?>
          <?php echo HTML::a($__v['text'] . $__i, null, false, $__v['attributes']); ?>
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