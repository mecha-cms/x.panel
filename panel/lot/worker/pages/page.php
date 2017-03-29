<aside class="s">
  <section class="s-search">
    <h3><?php echo $language->search; ?></h3>
    <form id="form.s.search" class="search" action="<?php echo $url->current; ?>" method="get">
      <p><?php echo Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]) . ' ' . Form::submit(null, null, $language->search, ['classes' => ['button']]); ?></p>
    </form>
  </section>
  <?php if ($__parent[0]): ?>
  <section class="s-parent">
    <h3><?php echo $language->parent; ?></h3>
    <ul>
      <li><?php echo HTML::a($__parent[1]->title, $__parent[0]->url . '/1'); ?></li>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($__kins[0]): ?>
  <section class="s-kin">
    <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
    <ul>
      <?php foreach ($__kins[0] as $k => $v): ?>
      <li><?php echo HTML::a($__kins[1][$k]->title, $v->url . '/1'); ?></li>
      <?php endforeach; ?>
      <?php if ($__is_kin_has_step): ?>
      <li><?php echo HTML::a('&#x2026;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]); ?></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($__pager[0]): ?>
  <section class="s-nav">
    <h3><?php echo $language->navigation; ?></h3>
    <p><?php echo $__pager[0]; ?></p>
  </section>
  <?php endif; ?>
</aside>
<main class="m">
  <?php echo $__message; ?>
  <section class="m-buttons">
    <p>
      <?php if (Request::get('q')): ?>
      <?php $__links = [HTML::a('&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_pages, false, ['classes' => ['button', 'reset']])]; ?>
      <?php else: ?>
      <?php $__links = [HTML::a('&#x2795; ' . $language->page, $__state->path . '/::s::/' . $__path, false, ['classes' => ['button', 'set']])]; ?>
      <?php endif; ?>
      <?php echo implode(' ', Hook::fire('panel.a.pages', [$__links])); ?>
    </p>
  </section>
  <section class="m-pages">
    <?php if ($__pages[0]): ?>
    <?php $p = strpos($__path, '/') !== false ? substr($__path, strpos($__path, '/')) : ""; ?>
    <?php foreach ($__pages[1] as $k => $v): ?>
    <?php

    $s = $__pages[0][$k]->url;
    $__is_parent = !!g(LOT . explode('::' . $__sgr . '::', $s, 2)[1], 'draft,page,archive', "", false);

    $g = $__pages[0][$k]->path;
    $gg = Path::X($g);
    $ggg = Path::D($g);
    $gggg = Path::N($g) === Path::N($ggg) && file_exists($ggg . '.' . $gg); // fade out the placeholder page

    ?>
    <article class="page on-<?php echo $v->state . ($__is_parent ? ' is-parent' : "") . ($gggg ? ' as-placeholder' : "") . ($site->path === ltrim($p . '/' . $v->slug, '/') ? ' as-home' : ""); ?>" id="page-<?php echo $v->id; ?>">
      <header>
        <?php if ($__pages[0][$k]->state === 'draft'): ?>
        <h3><?php echo $v->title; ?></h3>
        <?php else: ?>
        <h3><?php echo HTML::a($v->title, $v->url, true); ?></h3>
        <?php endif; ?>
      </header>
      <section>
        <p><?php echo To::snippet($v->description, true, $__state->snippet); ?></p>
      </section>
      <footer>
        <p>
        <?php

        $__links = [
            HTML::a($language->edit, $s),
            HTML::a($language->delete, str_replace('::g::', '::r::', $s) . HTTP::query(['token' => $__token]))
        ];

        if ($__is_parent) {
            $__links[] = HTML::a($language->open, $s . '/1');
        }

        if ($v->link) {
            $__links[] = HTML::a($language->link, $v->link, true);
        }

        echo implode(' &#x00B7; ', Hook::fire('panel.a.page', [$__links, $v]));

        ?>
        </p>
      </footer>
    </article>
    <?php endforeach; ?>
    <?php else: ?>
    <?php if ($q = Request::get('q')): ?>
    <p><?php echo $language->message_error_search('<em>' . $q . '</em>'); ?></p>
    <?php else: ?>
    <p><?php echo $language->message_info_void($language->pages); ?></p>
    <?php endif; ?>
    <?php endif; ?>
  </section>
</main>