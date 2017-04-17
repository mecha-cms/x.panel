<aside class="s">
  <section class="s-search">
    <h3><?php echo $language->search; ?></h3>
    <form id="form.s.search" class="search" action="<?php echo $url->current; ?>" method="get">
      <p><?php echo Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]) . ' ' . Form::submit(null, null, $language->search, ['classes' => ['button']]); ?></p>
    </form>
  </section>
  <section class="s-kin">
    <h3><?php echo $language->kins; ?></h3>
    <ul>
      <li><a href="">file-1.jpg</a></li>
      <li><a href="">file-2.jpg</a></li>
      <li><a href="">file-3.jpg</a></li>
      <li><a href="">file-4.jpg</a></li>
      <li><a href="">file-5.jpg</a></li>
      <li><a href="">file-6.jpg</a></li>
    </ul>
  </section>
</aside>
<main class="m">
  <section class="m-buttons">
    <p><?php echo HTML::a('&#x2795; ' . $language->file, $__state->path . '/::s::/' . $__path . '/' . $config->shield, false, ['classes' => ['button', 'set']]); ?></p>
  </section>
  <?php echo $__message; ?>
  <fieldset>
    <legend><?php echo $language->upload; ?></legend>
	<p><?php echo Form::file('file', ['classes' => ['input']]); ?></p>
  </fieldset>
  <section class="m-pages">
    <?php if ($__pages[1]): ?>
    <?php foreach ($__pages[1] as $_k => $_v): ?>
    <article class="page file">
    </article>
    <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>