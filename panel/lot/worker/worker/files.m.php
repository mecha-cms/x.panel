<?php

$__query = HTTP::query([
    'token' => false,
    'force' => false
]);

if (Request::get('q')) {
    $__links = ['do' => ['&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step . $__query]];
} else {
    $__links = ['set' => ['&#x2795; ' . $language->{count($__chops) === 1 ? $__chops[0] : 'file'}, $__state->path . '/::s::/' . $__path . $__query]];
}

$__links = Hook::fire('panel.a.' . $__chops[0] . 's', [$__links]);

foreach ($__links as $__k => $__v) {
    if (!isset($__v)) continue;
    if (is_array($__v)) {
        $__links[$__k] = call_user_func_array('HTML::a', array_replace_recursive([null, null, false, ['classes' => ['button', 'button:' . $__k]]], $__v));
    }
}

?>
<?php if ($__links): ?>
<section class="m-button">
  <p><?php echo implode(' ', $__links); ?></p>
</section>
<?php endif; ?>
<section class="m-file">
  <?php if ($__files[0]): ?>
  <?php foreach ($__files[0] as $__k => $__v): $__vv = $__files[1][$__k]; ?>
  <article class="<?php echo $__chops[0]; ?> is.<?php echo ($__v->is->file ? 'file' : 'files is.folder') . ($__v->is->hidden ? ' is.hidden' : ""); ?>">
    <?php

    $__u = $url . '/' . $__state->path . '/::g::/';
    $__uu = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v->path);

    ?>
    <header>
      <h3>
      <?php if ($__v->is->file): ?>
      <span class="a" title="<?php echo $language->size . ': ' . $__v->size; ?>"><?php echo $__vv->title; ?></span>
      <?php else: ?>
      <a href="<?php echo $__v->url . $__query; ?>" title="<?php echo ($__ii = count(glob($__v->path . DS . '*', GLOB_NOSORT))) . ' ' . $language->{$__ii === 1 ? 'item' : 'items'}; ?>"><?php echo $__vv->title; ?></a><?php
  
      /*
      if ($__v->is->files && count(glob($__v->path . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT)) === 1 && $__g = File::explore($__v->path, true, true)) {
          $__dd = $__ff = [];
          foreach ($__g as $__kkk => $__vvv) {
              $__kkkk = basename($__kkk);
              if ($__vvv === 0) {
                  $__uu .= '/' . $__kkkk;
                  if (count(glob($__kkk . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT)) <= 1) {
                      $__dd[] = $__uu;
                      echo ' / ' . HTML::a($__kkkk, $__uu . $__is_has_step);
                  }
              } else {
                  $__ff[] = $__uu . '/' . $__kkkk;
              }
          }
          if (count($__ff) === 1 && $__dd && dirname(end($__dd)) !== dirname($__ff[0])) {
              $__fff = basename($__ff[0]);
              $__uu .= '/' . $__fff;
              echo ' / ' . HTML::a($__fff, $__ff[0]);
              $__v->is->file = true;
          }
          $__v->url = $__uu;
          $__vv->url = To::url(str_replace($__u, LOT . DS, $__uu));
      }
      */
  
      ?>
      <?php endif; ?>
      </h3>
    </header>
    <section></section>
    <footer>
    <?php

    $__as = [
        'view' => $__v->is->file ? [$language->view, $__vv->url, true] : null,
        'set' => $__v->is->file ? null : [$language->add, str_replace('::g::', '::s::', $__uu) . $__query],
        'edit' => [$language->edit, $__uu . $__query],
        'reset' => [$language->delete, str_replace('::g::', '::r::', $__v->url . HTTP::query(['token' => $__token]))]
    ];

    $__as = Hook::fire('panel.a.' . $__chops[0], [$__as, [$__v, $__vv], $__files]);

    $__a = [];
    foreach ($__as as $__k => $__v) {
        if (!isset($__v)) continue;
        if ($__v && is_string($__v) && $__v[0] === '<' && strpos($__v, '</') !== false && substr($__v, -1) === '>') {
            $__a[$__k] = $__v;
        } else {
            $__a[$__k] = call_user_func_array('HTML::a', $__v);
        }
    }

    echo implode(' &#x00B7; ', (array) $__a);

    ?>
    </footer>
  </article>
  <?php endforeach; ?>
  <?php if (count($__chops) > 1): ?>
  <nav>
  <?php

  $__chops_c = $__chops;
  $__chops_e = array_pop($__chops_c);
  $__uu = $__u . array_shift($__chops_c);
  $__s = HTML::a($__chops[0], $__uu);
  foreach ($__chops_c as $__k => $__v) {
      $__uu .= '/' . $__v;
      $__s .= ' / ' . HTML::a($__v, $__uu . $__is_has_step . $__query);
  }

  echo $__s . ' / ' . $__chops_e;

  ?>
  </nav>
  <?php endif; ?>
  <?php else: ?>
  <?php if ($__q = Request::get('q')): ?>
  <p><?php echo $language->message_error_search('<em>' . $__q . '</em>'); ?></p>
  <?php else: ?>
  <p><?php echo is_dir(LOT . DS . $__path) || ($site->__step === 1 && count($__chops) === 1) ? $language->message_info_void($language->{(count($__chops) === 1 ? $__chops[0] : 'file') . 's'}) : To::sentence($language->_finded); ?></p>
  <?php endif; ?>
  <?php endif; ?>
</section>