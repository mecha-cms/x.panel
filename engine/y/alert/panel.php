<?php if (class_exists('Alert')): ?>
  <?php foreach (Alert::get() as $v): ?>
    <div class="content content:alert p type:<?= $v[2]['type']; ?>" role="alert">
      <?= $v[1]; ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>