<aside class="secondary">
  <?php Hook::NS('panel.secondary.1.before'); ?>
  <section class="secondary-search">
    <h3><?php echo $language->search; ?></h3>
    <form id="form.secondary.1" class="search" action="<?php echo $url->current; ?>" method="get">
      <p><?php echo Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]) . ' ' . Form::submit(null, null, $language->search, ['classes' => ['button']]); ?></p>
    </form>
  </section>
  <?php if ($parents[0] || count($chops) === 2): ?>
  <section class="secondary-parent">
    <h3><?php echo $language->{count($parents[0]) === 1 ? 'parent' : 'parents'}; ?></h3>
    <ul>
      <?php if (count($chops) > 2): ?>
      <li class="state-<?php echo $parents[0][0]->state; ?>"><a href="<?php echo $parents[0][0]->url . '/1'; ?>"><?php echo $parents[1][0]->title; ?></a></li>
      <?php else: ?>
      <li class="state-page"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . $chops[0]; ?>">./</a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($kins[0]): ?>
  <section class="secondary-kin">
    <h3><?php echo $language->{count($kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
    <ul>
      <?php foreach ($kins[0] as $k => $v): ?>
      <li class="state-<?php echo $v->state; ?>"><a href="<?php echo $v->url; ?>/1"><?php echo $kins[1][$k]->title; ?></a></li>
      <?php endforeach; ?>
      <?php if ($is_kin_has_step): ?>
      <li><a href="<?php echo $url . '/' . $state->path . '/::g::/' . Path::D($path) . '/2'; ?>" title="<?php echo $language->more; ?>">&#x2026;</a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php Hook::NS('panel.secondary.1.after'); ?>
  <section class="secondary-navigation">
    <h3><?php echo $language->navigation; ?></h3>
    <p><?php echo $pager[0]; ?></p>
  </section>
</aside>
<main class="main">
  <section class="main-buttons">
    <p><?php echo HTML::a($language->add . ' ' . $language->page, $state->path . '/::s::/' . $path, false, ['classes' => ['button', 'set']]); ?></p>
  </section>
  <?php echo $message; ?>
  <?php Hook::NS('panel.main.before'); ?>
  <section class="main-pages">
    <?php $p = strpos($path, '/') !== false ? substr($path, strpos($path, '/')) : ""; ?>
    <?php foreach ($pages[1] as $k => $v): ?>
    <?php $s = $pages[0][$k]->url; $has_parent = !!g(LOT . explode('::' . $sgr . '::', $s, 2)[1], 'draft,page,archive', "", false); ?>
    <article class="page <?php echo 'on-' . $v->state; ?><?php if ($has_parent): ?> is-parent<?php endif; ?><?php if ($site->path === ltrim($p . '/' . $v->slug, '/')): ?> as-home<?php endif; ?>" id="page-<?php echo $v->id; ?>">
      <header>
        <h3><a href="<?php echo $v->url; ?>" target="_blank"><?php echo $v->title; ?></a></h3>
      </header>
      <section><p><?php echo To::snippet($v->description, true, $state->snippet); ?></p></section>
      <footer>
        <p>
        <?php

        $links = [
            HTML::a($language->edit, $s),
            HTML::a($language->delete, str_replace('::g::', '::r::', $s) . HTTP::query(['token' => $token]))
        ];

        if ($has_parent) {
            $links[] = HTML::a($language->open, $s . '/1');
        }

        echo implode(' &#x00B7; ', $links);

        ?>
        </p>
      </footer>
    </article>
    <?php endforeach; ?>
  </section>
  <?php Hook::NS('panel.main.after'); ?>
  <?php Shield::get(__DIR__ . DS . 'footer.content.php'); ?>
</main>