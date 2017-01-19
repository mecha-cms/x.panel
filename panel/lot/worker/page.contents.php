<aside class="secondary">
  <?php Hook::NS('panel.secondary.1.before'); ?>
  <section class="secondary-search">
    <h3><?php echo $language->search; ?></h3>
    <form id="form.secondary.1" class="search" action="<?php echo $url->current; ?>" method="get">
      <p><?php echo Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]) . ' ' . Form::submit(null, null, $language->search, ['classes' => ['button']]); ?></p>
    </form>
  </section>
  <?php if ($parent[0] || count($chops) === 2): ?>
  <section class="secondary-parent">
    <h3><?php echo $language->parent; ?></h3>
    <ul>
      <?php if (count($chops) > 2): ?>
      <li class="state-<?php echo $parent[0]->state; ?>"><a href="<?php echo $parent[0]->url . '/1'; ?>"><?php echo $parent[1]->title; ?></a></li>
      <?php else: ?>
      <li class="state-page"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . $chops[0]; ?>">./</a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($kins[0]): ?>
  <section class="secondary-kin">
    <h3><?php echo $language->kins; ?></h3>
    <ul>
      <?php foreach ($kins[0] as $k => $v): ?>
      <li class="state-<?php echo $v->state; ?>"><a href="<?php echo $v->url; ?>"><?php echo $kins[1][$k]->title; ?></a></li>
      <?php endforeach; ?>
      <?php if ($kin_very_much): ?>
      <li><a href="<?php echo $url . '/' . $state->path . '/::g::/' . Path::D(implode('/', $chops)) . '/2'; ?>" title="<?php echo $language->more; ?>">&#x2026;</a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php Hook::NS('panel.secondary.1.after'); ?>
  <?php if ($pager[0]->previous || $pager[0]->next): ?>
  <section class="secondary-nav">
    <h3><?php echo $language->navigation; ?></h3>
    <p><?php echo $pager[0]; ?></p>
  </section>
  <?php endif; ?>
</aside>
<main class="main">
  <?php echo $message; ?>
  <?php Hook::NS('panel.main.before'); ?>
  <section>
    <?php foreach ($pages[1] as $k => $v): ?>
    <?php $s = $pages[0][$k]->url; $has_parent = !!g(LOT . explode('::' . $sgr . '::', $s, 2)[1], 'draft,page,archive', "", false); ?>
    <article class="page<?php if ($has_parent): ?> is-parent<?php endif; ?>" id="page-<?php echo $v->id; ?>">
      <header>
        <h3><a href="<?php echo $v->url; ?>" target="_blank"><?php echo $v->title; ?></a></h3>
      </header>
      <section><p><?php echo To::snippet($v->description, true, $state->snippet[0], $state->snippet[1]); ?></p></section>
      <footer>
        <p>
        <?php

        $links = [
            HTML::a($language->edit, $s),
            HTML::a($language->delete, str_replace('::g::', '::r::', $s))
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