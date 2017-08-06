<?php

$__query = HTTP::query([
    'token' => false,
    'force' => false
]);

if (Request::get('q')) {
    $__links = ['do' => ['&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step . $__query]];
} else {
    $__links = ['set' => ['&#x2795; ' . Config::get('panel.n.' . $__chops[0] . '.text', $language->{$__chops[0]}), $__state->path . '/::s::/' . $__path . $__query]];
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
<section class="m-page">
  <?php if ($__pages[0]): ?>
  <?php

  $__c = Shield::state($config->shield, 'path', Extend::state('page', 'path'));
  $__current = strpos($__path, '/') !== false ? substr($__path, strpos($__path, '/')) : "";

  ?>
  <?php foreach($__pages[0] as $__k => $__v): $__vv = $__pages[1][$__k]; ?>
  <?php

  $__uu = $__v->url;

  $__pp = $__v->path;
  $__ppp = explode('/', Path::F($__pp, null, '/'));
  $__pppp = Config::get('panel.v.' . $__chops[0] . '.is.hidden', array_pop($__ppp)) === array_pop($__ppp) && file_exists(Path::D($__pp) . '.' . Path::X($__pp));

  $__as = [
      'edit' => $__pppp ? null : [$language->edit, $__uu . $__query]
  ];
  
  $__is_pages = !!g(LOT . explode('::' . $__command . '::', $__uu, 2)[1], 'draft,page,archive', "", false);  
  if ($__s = Config::get('panel.v.' . $__chops[0] . '.is.pages', "")) {
      $__is_pages = strpos(',' . $__s . ',', ',' . $__v->slug . ',') !== false;
  }

  if ($__is_pages) {
      $__as['get'] = [$language->open, $__uu . '/1' . $__query];
  }

  $__as['reset'] = [$language->delete, str_replace('::g::', '::r::', $__uu) . HTTP::query(['token' => $__token]), false, ['title' => $__pppp ? $language->__->panel->as_pages : null]];

  $__as = Hook::fire('panel.a.' . $__chops[0], [$__as, [$__v, $__vv], $__pages]);

  $__cc = $__chops[0] . ' as.' . $__v->state . ($__pppp ? ' is.hidden' : "") . ($__v->status !== null ? ' status.' . $__v->status : "");
  if (Config::get('panel.v.' . $__chops[0] . '.as', $__c) === ltrim($__current . '/' . $__v->slug, '/')) {
      $__cc .= ' as.';
  }
  if (!$__is_pages || file_exists(Path::F($__pp) . DS . Path::N($__pp))) {
      $__cc .= ' is.page';
  }
  if ($__is_pages) {
      $__cc .= ' is.pages';
  }

  ?>
  <article class="<?php echo $__cc; ?>" id="<?php echo $__chops[0] . '-' . $__v->id; ?>">
    <header>
      <h3>
      <?php if ($__v->state === 'draft' || $__vv->url === false): ?>
      <?php echo $__vv->title; ?>
      <?php else: ?>
      <?php echo HTML::a($__vv->title, $__vv->url, true); ?>
      <?php endif; ?>
      </h3>
    </header>
    <?php if ($__vv->description): ?>
    <section>
      <p><?php echo To::snippet($__vv->description, true, $__state->snippet); ?></p>
    </section>
    <?php endif; ?>
    <footer>
    <?php

    $__a = [];
    foreach ($__as as $__kk => $__vv) {
        if (!isset($__vv)) continue;
        if ($__vv && is_string($__vv) && $__vv[0] === '<' && strpos($__vv, '</') !== false && substr($__vv, -1) === '>') {
            $__a[$__kk] = $__vv;
        } else {
            $__a[$__kk] = call_user_func_array('HTML::a', $__vv);
        }
    }

    echo implode(' &#x00B7; ', (array) $__a);

    ?>
    </footer>
  </article>
  <?php endforeach; ?>
  <?php else: ?>
  <?php if ($__q = Request::get('q')): ?>
  <p><?php echo $language->message_error_search('<em>' . $__q . '</em>'); ?></p>
  <?php else: ?>
  <p><?php echo $site->__step === 1 ? $language->message_info_void($language->{$__chops[0] . 's'}) : To::sentence($language->_finded); ?></p>
  <?php endif; ?>
  <?php endif; ?>
</section>