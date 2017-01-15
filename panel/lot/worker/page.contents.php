<aside class="secondary">
  <?php Hook::NS('panel.secondary.1.before'); ?>
  <section>
    <h3><?php echo $language->search; ?></h3>
    <form class="search">
      <p><input class="input" name="q" type="text"> <button class="button"><?php echo $language->search; ?></button>
    </form>
  </section>
  <?php if ($parent[0] || count($chops) === 2): ?>
  <section>
    <h3><?php echo $language->parent; ?></h3>
    <ul>
      <?php if (count($chops) > 2): ?>
      <li class="state-<?php echo $parent[0]->state; ?>"><a href="<?php echo $parent[0]->url . '/1'; ?>"><?php echo $parent[1]->title; ?></a></li>
      <?php else: ?>
      <li class="state-page"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . $chops[0]; ?>"><b>/</b></a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php if ($kins[0]): ?>
  <section>
    <h3><?php echo $language->kins; ?></h3>
    <ul>
      <?php foreach ($kins[0] as $k => $v): ?>
      <li class="state-<?php echo $v->state; ?>"><a href="<?php echo $v->url; ?>"><?php echo $kins[1][$k]->title; ?></a></li>
      <?php endforeach; ?>
      <?php if ($kin_very_much): ?>
      <li><a href="<?php echo $url . '/' . $state->path . '/::g::/' . Path::D(implode('/', $chops)) . '/2'; ?>" title="<?php echo $language->more; ?>"><b>&#x2026;</b></a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php Hook::NS('panel.secondary.1.after'); ?>
  <?php if ($pager[0]->previous || $pager[0]->next): ?>
  <section>
    <h3><?php echo $language->navigation; ?></h3>
    <p><?php echo $pager[0]; ?></p>
  </section>
  <?php endif; ?>
</aside>
<main class="main">
  <?php Hook::NS('panel.main.before'); ?>
  <section>
  <?php foreach ($pages[1] as $k => $v): ?>
    <article id="page-<?php echo $v->id; ?>">
      <header>
        <h3><a href="<?php echo $v->url; ?>"><?php echo $v->title; ?></a></h3>
      </header>
      <section><p><?php echo To::snippet($v->description, true, $state->snippet[0], $state->snippet[1]); ?></p></section>
      <footer>
        <p>
        <?php

        $s = $pages[0][$k]->url;
        $links = [HTML::a($language->edit, $s)];
        if (g(LOT . explode('::' . $sgr . '::', $s, 2)[1], 'draft,page,archive', "", false)) {
            $links[] = HTML::a($language->more, $s . '/1');
        }

        echo implode(' &#x00B7; ', $links);
          
        ?>
        </p>
      </footer>
    </article>
    <?php endforeach; ?>
  </section>
  <?php Hook::NS('panel.main.after'); ?>
  <?php Shield::get($shield_path . DS . 'footer.php'); ?>
</main>