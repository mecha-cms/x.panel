<aside class="secondary">
  <?php Hook::NS('panel.secondary.1.before'); ?>
  <section class="secondary-search">
    <h3><?php echo $language->search; ?></h3>
    <form id="form.secondary.1" class="search" action="<?php echo $url->current; ?>" method="get">
      <p><?php echo Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]) . ' ' . Form::submit(null, null, $language->search, ['classes' => ['button']]); ?></p>
    </form>
  </section>
  <?php Hook::NS('panel.secondary.1.after'); ?>
  <section class="secondary-nav">
    <h3><?php echo $language->navigation; ?></h3>
    <p><?php echo $__pager[0]; ?></p>
  </section>
</aside>
<main class="main">
  <section class="main-buttons">
    <p><?php echo HTML::a('&#x2795; ' . $language->shield, $__state->path . '/::s::/' . $__path . '/' . $config->shield, false, ['classes' => ['button', 'set']]); ?></p>
  </section>
  <?php echo $__message; ?>
  <?php Hook::NS('panel.main.before'); ?>
  <section class="main-pages">
    <?php if ($__pages[0]): ?>
    <?php foreach ($__pages[1] as $k => $v): ?>
    <?php $s = $__pages[0][$k]->url; ?>
    <article class="page <?php echo 'on-' . $v->state; ?><?php if ($config->shield === $v->id): ?> is-current<?php endif; ?>" id="page-<?php echo $v->id; ?>">
      <header>
        <h3><?php echo $v->title; ?></h3>
      </header>
      <section><p><?php echo To::snippet($v->description, true, $__state->snippet); ?></p></section>
      <footer>
        <p>
        <?php

        $__links = [
            HTML::a($language->edit, $s),
            HTML::a($language->delete, str_replace('::g::', '::r::', $s) . HTTP::query(['token' => $__token]))
        ];

        echo implode(' &#x00B7; ', $__links);

        ?>
        </p>
      </footer>
    </article>
    <?php endforeach; ?>
    <?php else: ?>
    <p><?php echo $language->message_info_void($language->shields); ?></p>
    <?php endif; ?>
  </section>
  <?php Hook::NS('panel.main.after'); ?>
<?php Shield::get([
    $__path_shield . DS . $site->type . DS . '_footer.php',
    __DIR__ . DS . '_footer.php'
]); ?>
</main>