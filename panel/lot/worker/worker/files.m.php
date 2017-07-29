<section class="m-button">
  <p>
    <?php if (Request::get('q')): ?>
    <?php $__links = [HTML::a('&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step, false, ['classes' => ['button']])]; ?>
    <?php else: ?>
    <?php $__links = [HTML::a('&#x2795; ' . $language->{$__chops[0]}, $__state->path . '/::s::/' . $__path, false, ['classes' => ['button']])]; ?>
    <?php endif; ?>
    <?php echo implode(' ', Hook::fire('panel.a.' . $__chops[0] . 's', [$__links])); ?>
  </p>
</section>
<section class="m-file">
  <?php if ($__files[0]): $__u = $url . '/' . $__state->path . '/::g::/'; ?>
  <?php foreach ($__files[0] as $__k => $__v): $__vv = $__files[1][$__k]; ?>
  <article class="<?php echo $__chops[0]; ?> is.<?php echo ($__v->is->file ? 'file' : 'files is.folder') . ($__v->is->hidden ? ' is.hidden' : ""); ?>">
    <header>
      <h3>
      <?php if ($__v->is->file): ?>
      <span class="a" title="<?php echo $language->size . ': ' . $__v->size; ?>"><?php echo $__vv->title; ?></span>
      <?php else: ?>
      <a href="<?php echo $__v->url; ?>" title="<?php echo ($__i = count(glob($__v->path . DS . '*', GLOB_NOSORT))) . ' ' . $language->{$__i === 1 ? 'item' : 'items'}; ?>"><?php echo $__vv->title; ?></a><?php
  
      if ($__v->is->files && count(glob($__v->path . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT)) === 1 && $__g = File::explore($__v->path, true, true)) {
          $__uu = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v->path);
          foreach ($__g as $__kk => $__vv) {
              $__kk = Path::B($__kk);
              $__uu .= '/' . $__kk;
              if ($__vv === 0) {
                  echo ' / ' . HTML::a($__kk, $__uu . $__is_has_step);
              }
          }
      }
  
      ?>
      <?php endif; ?>
      </h3>
    </header>
    <!-- section></section -->
    <footer>
    <?php

    $__uu = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v->path);

    $__as = [
        $__v->is->file ? [$language->view, $__vv->url, true] : null,
        $__v->is->file ? null : [$language->add, str_replace('::g::', '::s::', $__uu)],
        [$language->edit, $__uu],
        [$language->delete, str_replace('::g::', '::r::', $__v->url . HTTP::query(['token' => $__token]))]
    ];

    $__as = Hook::fire('panel.a.' . $__chops[0], [$__as, [$__v, $__vv], $__files]);

    $__a = [];
    foreach ($__as as $__v) {
        if (!isset($__v)) continue;
        if ($__v && is_string($__v) && $__v[0] === '<' && strpos($__v, '</') !== false && substr($__v, -1) === '>') {
            $__a[] = $__v;
        } else {
            $__a[] = call_user_func_array('HTML::a', $__v);
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
      $__s .= ' / ' . HTML::a($__v, $__uu . $__is_has_step);
  }

  echo $__s . ' / ' . $__chops_e;
    
  ?>
  </nav>
  <?php endif; ?>
  <?php else: ?>
  <?php if ($__q = Request::get('q')): ?>
  <p><?php echo $language->message_error_search('<em>' . $__q . '</em>'); ?></p>
  <?php else: ?>
  <p><?php echo $site->__step === 1 ? $language->message_info_void($language->{$__chops[0] . 's'}) : To::sentence($language->_finded); ?></p>
  <?php endif; ?>
  <?php endif; ?>
</section>