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
<section class="m-page">
  <?php if ($__pages[0]): ?>
  <?php

  $__c = Shield::state($config->shield, 'path', Extend::state('page', 'path'));
  $__current = strpos($__path, '/') !== false ? substr($__path, strpos($__path, '/')) : "";

  ?>
    <?php foreach($__pages[1] as $__k => $__v): ?>
  <?php

  $__vv = $__pages[0][$__k];
  $__uu = $__vv->url;

  $__pp = $__vv->path;
  $__ppp = explode('/', Path::F($__pp, null, '/'));
  $__pppp = array_pop($__ppp) === array_pop($__ppp) && file_exists(Path::D($__pp) . '.' . Path::X($__pp));

  $__as = [
      $__pppp ? null : [$language->edit, $__uu]
  ];

  if ($__is_has_child = !!g(LOT . explode('::' . $__action . '::', $__uu, 2)[1], 'draft,page,archive', "", false)) {
      $__as[] = [$language->open, $__uu . '/1'];
  }

  $__as[] = [$language->delete, str_replace('::g::', '::r::', $__uu) . HTTP::query(['token' => $__token]), false, ['title' => $__pppp ? $language->__->panel->as_pages : null]];

  $__as = Hook::fire('panel.a.' . $__chops[0], [$__as, [$__vv, $__v], $__pages]);

  ?>
  <?php echo __panel_a__($__chops[0], [
      'id' => $__chops[0] . '-' . $__v->id,
      'title' => $__v->state === 'draft' ? $__v->title : [$__v->title, $__v->url],
      'description' => $__v->description,
      'a' => $__as,
      'is' => [
          'parent' => $__is_has_child
      ],
      'as' => [
          "" => $__c === ltrim($__current . '/' . $__v->slug, '/'),
          'page' => !$__is_has_child || file_exists(Path::F($__pp) . DS . Path::N($__pp)),
          'pages' => $__is_has_child,
          'placeholder' => $__pppp
      ],
      'on' => [
          'draft' => $__v->state === 'draft'
      ]
  ]); ?>
  <?php endforeach; ?>
  <?php else: ?>
  <?php if ($__q = Request::get('q')): ?>
  <p><?php echo $language->message_error_search('<em>' . $__q . '</em>'); ?></p>
  <?php else: ?>
  <p><?php echo $language->message_info_void($language->{$__chops[0] . 's'}); ?></p>
  <?php endif; ?>
  <?php endif; ?>
</section>