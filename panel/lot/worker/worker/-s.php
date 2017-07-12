<?php if ($__s = array_filter(a(Config::get('panel.s.' . (isset($__i) ? $__i : '1'), [])), function($__v) {
    return isset($__v) && isset($__v['stack']) && is_numeric($__v['stack']);
})): ?>
<?php foreach (Anemon::eat($__s)->sort([1, 'stack'], "")->vomit() as $__k => $__v): ?>
<?php if (isset($__v['before']) && is_string($__v['before']) && is_file($__v['before'])): ?>
<?php $__v['before'] = include $__v['before']; ?>
<?php endif; ?>
<?php if (isset($__v['content']) && is_string($__v['content']) && is_file($__v['content'])): ?>
<?php $__v['content'] = include $__v['content']; ?>
<?php endif; ?>
<?php if (isset($__v['after']) && is_string($__v['after']) && is_file($__v['after'])): ?>
<?php $__v['after'] = include $__v['after']; ?>
<?php endif; ?>
<?php $__w = [$__k, $__v]; ?>
<?php if (!empty($__v['lot'])) $__w = array_merge($__w, (array) $__v['lot']); ?>
<?php echo call_user_func_array('__panel_s__', $__w); ?>
<?php endforeach; ?>
<?php endif; ?>