<?php Shield::get('top'); ?>
<main>
  <?php foreach ($pages as $page): ?>
  <article id="page-<?php echo $page->id; ?>">
    <header>
      <h3><a href="<?php echo $page->link ?: $page->url; ?>"><?php echo $page->title; ?><?php if ($page->link): ?> &#x21E2;<?php endif; ?></a></h3>
    </header>
    <section><?php echo $page->description; ?></section>
    <footer></footer>
  </article>
  <?php endforeach; ?>
  <nav><?php echo $pager; ?></nav>
</main>
<?php Shield::get('bottom'); ?>