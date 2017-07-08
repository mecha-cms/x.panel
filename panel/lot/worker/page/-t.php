<?php if ($__t = a(Config::get('panel.t'))): ?>
<?php if (count($__t) > 1): ?>
<?php $__t = Anemon::eat($__t)->sort([1, 'stack'], 10)->vomit(); ?>
<nav class="t">
<?php $__1 = array_keys($__t); ?>
<?php $__1 = array_shift($__1); ?>
<?php foreach ($__t as $__k => $__v): ?>
<?php if (!isset($__v['title'])) $__v['title'] = $language->{$__k}; ?>
<?php echo HTML::a($__v['title'], '#t:' . $__k, false, ['class' => Config::get('panel.t:active', $__1) === $__k ? 'is-active' : null]); ?>
<?php endforeach; ?>
</nav>
<?php endif; ?>
<?php foreach ($__t as $__k => $__v): ?>
<?php if (!isset($__v['title'])) $__v['title'] = $language->{$__k}; ?>
<section class="t-c" id="t:<?php echo $__k; ?>">
  <fieldset>
    <?php if (!isset($__v['legend']) || $__v['legend'] !== false): ?>
    <legend><?php echo isset($__v['legend']) ? $__v['legend'] : $__v['title']; ?></legend>
    <?php if (is_file($__v['content'])): ?>
    <?php $__v['content'] = include $__v['content']; ?>
    <?php endif; ?>
    <?php if (is_array($__v['content'])): ?>
    <?php foreach (Anemon::eat($__v['content'])->sort([1, 'stack'], "")->vomit() as $__kk => $__vv): ?>
    <?php if (!isset($__vv['stack']) || $__vv['stack'] === "" || $__vv['stack'] === false) continue; ?>
    <?php echo __panel_f__($__kk, $__vv); ?>
    <?php endforeach; ?>
    <?php else: ?>
    <?php echo $__v['content']; ?>
    <?php endif; ?>
    <?php endif; ?>
  </fieldset>
</section>
<?php endforeach; ?>
<?php else: ?>
<p>:(</p>
<?php endif; ?>