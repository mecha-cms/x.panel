<?php foreach (alert() as $v): ?>
  <div class="content content:alert p type:<?= $v[2]['type']; ?>" role="alert">
    <?= $v[1]; ?>
  </div>
<?php endforeach; ?>