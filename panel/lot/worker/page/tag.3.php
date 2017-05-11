    <section class="s-id">
      <h3><?php echo $language->id; ?></h3>
      <?php

$__i = 0;
foreach (glob(TAG . DS . '*' . DS . 'id.data', GLOB_NOSORT) as $__v) {
    $__id = (int) file_get_contents($__v);
    if ($__id > $__i) $__i = $__id;
}
++$__i;

      ?>
      <p><?php echo Form::text('id', $__sgr === 's' ? $__i : $__page[0]->id, $__i, ['classes' => ['input', 'block'], 'id' => 'f-id', 'readonly' => true]); ?></p>
    </section>