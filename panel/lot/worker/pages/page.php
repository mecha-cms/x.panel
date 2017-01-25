<aside class="secondary">
  <?php Hook::NS('panel.secondary.1.before'); ?>
  <section class="secondary-search">
    <h3><?php echo $language->search; ?></h3>
    <form id="form.secondary.1" class="search" action="<?php echo $url->current; ?>" method="get">
      <p><?php echo Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]) . ' ' . Form::submit(null, null, $language->search, ['classes' => ['button']]); ?></p>
    </form>
  </section>
  <?php if ($__parents[0] || count($__chops) === 2): ?>
  <section class="secondary-parent">
    <h3><?php echo $language->{count($__parents[0]) === 1 || count($__chops) === 2 ? 'parent' : 'parents'}; ?></h3>
    <ul>
      <?php if (count($__chops) > 2): ?>
      <li class="state-<?php echo $__parents[0][0]->state; ?>"><a href="<?php echo $__parents[0][0]->url . '/1'; ?>"><?php echo $__parents[1][0]->title; ?></a></li>
      <?php else: ?>
      <li class="state-page"><a href="<?php echo $url . '/' . $__state->path . '/::g::/' . $__chops[0]; ?>">./</a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($__kins[0]): ?>
  <section class="secondary-kin">
    <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
    <ul>
      <?php foreach ($__kins[0] as $k => $v): ?>
      <li class="state-<?php echo $v->state; ?>"><a href="<?php echo $v->url; ?>/1"><?php echo $__kins[1][$k]->title; ?></a></li>
      <?php endforeach; ?>
      <?php if ($__is_kin_has_step): ?>
      <li><a href="<?php echo $url . '/' . $__state->path . '/::g::/' . Path::D($__path) . '/2'; ?>" title="<?php echo $language->more; ?>">&#x2026;</a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php Hook::NS('panel.secondary.1.after'); ?>
  <section class="secondary-nav">
    <h3><?php echo $language->navigation; ?></h3>
    <p><?php echo $__pager[0]; ?></p>
  </section>
</aside>
<main class="main">
  <section class="main-buttons">
    <p><?php echo HTML::a($language->add . ' ' . $language->page, $__state->path . '/::s::/' . $__path, false, ['classes' => ['button', 'set']]); ?></p>
  </section>
  <?php echo $__message; ?>
  <?php Hook::NS('panel.main.before'); ?>
  <section class="main-pages">
    <?php if ($__pages[0]): ?>
    <?php $p = strpos($__path, '/') !== false ? substr($__path, strpos($__path, '/')) : ""; ?>
    <?php foreach ($__pages[1] as $k => $v): ?>
    <?php $s = $__pages[0][$k]->url; $__is_parent = !!g(LOT . explode('::' . $__sgr . '::', $s, 2)[1], 'draft,page,archive', "", false); ?>
    <article class="page <?php echo 'on-' . $v->state; ?><?php if ($__is_parent): ?> is-parent<?php endif; ?><?php if ($site->path === ltrim($p . '/' . $v->slug, '/')): ?> as-home<?php endif; ?>" id="page-<?php echo $v->id; ?>">
      <header>
        <h3><a href="<?php echo $v->url; ?>" target="_blank"><?php echo $v->title; ?></a></h3>
      </header>
      <section><p><?php echo To::snippet($v->description, true, $__state->snippet); ?></p></section>
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

        echo implode(' &#x00B7; ', $__links);

        ?>
        </p>
      </footer>
    </article>
    <?php endforeach; ?>
    <?php else: ?>
    <p><?php echo $language->message_info_void($language->pages); ?></p>
    <?php endif; ?>
  </section>
  <?php Hook::NS('panel.main.after'); ?>
<?php Shield::get([
    $__path_shield . DS . $site->type . DS . '_footer.php',
    __DIR__ . DS . '_footer.php'
]); ?>
</main>