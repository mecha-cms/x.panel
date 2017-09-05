<?php if ($__s = array_filter((array) a(Config::get('panel.s.' . (isset($__index) ? $__index : '1'), [])), function($__v) {
    return isset($__v) && isset($__v['stack']) && is_numeric($__v['stack']);
})): ?>
<?php foreach (Anemon::eat($__s)->sort([1, 'stack'], "")->vomit() as $__k => $__v): ?>
<?php if (isset($__v['begin']) && is_string($__v['begin']) && is_file($__v['begin'])): ?>
<?php $__v['begin'] = include $__v['begin']; ?>
<?php endif; ?>
<?php if (isset($__v['content']) && is_string($__v['content']) && is_file($__v['content'])): ?>
<?php $__v['content'] = include $__v['content']; ?>
<?php endif; ?>
<?php if (isset($__v['end']) && is_string($__v['end']) && is_file($__v['end'])): ?>
<?php $__v['end'] = include $__v['end']; ?>
<?php endif; ?>
<?php $__w = [$__k, $__v]; ?>
<?php if (!empty($__v['lot'])) $__w = array_merge($__w, (array) $__v['lot']); ?>
<?php echo call_user_func_array('_s', $__w); ?>
<?php endforeach; ?>
<?php endif; ?>