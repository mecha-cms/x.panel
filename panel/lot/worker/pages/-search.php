<section class="s-search">
  <h3><?php echo $language->search; ?></h3>
  <form id="form.s.search" class="search" action="" method="get">
    <p><?php echo Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]) . ' ' . Form::submit(null, null, $language->search, ['classes' => ['button', 'set']]); ?></p>
  </form>
</section>