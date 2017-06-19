<?php if ($__kins[0]): ?>
<section class="s-user">
  <h3><?php echo $language->{count($__kins[0]) === 1 ? 'user' : 'users'}; ?></h3>
  <ul>
  <?php foreach ($__kins[0] as $__k => $__v): ?>
    <li><?php echo HTML::a($__v->author, $__v->url); ?></li>
    <?php endforeach; ?>
  </ul>
</section>
<?php endif; ?>