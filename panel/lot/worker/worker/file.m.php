<section class="m-button">
  <p>
    <?php if (Request::get('q')): ?>
    <?php $__links = [HTML::a('&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step, false, ['classes' => ['button']])]; ?>
    <?php else: ?>
    <?php $__links = [HTML::a('&#x2795; ' . $language->{$__chops[0]}, $__state->path . '/::s::/' . $__path, false, ['classes' => ['button']])]; ?>
    <?php endif; ?>
    <?php echo implode(' ', Hook::fire('panel.a.' . $__chops[0] . 's', [$__links])); ?>
  </p>
</section>
<section class="m-file">
  <?php if ($__files[0]): ?>
  <ul>
  <?php foreach ($__files[0] as $__v): ?>
  <li><a href="<?php echo $__v->url; ?>"><?php echo $__v->title; ?></a><span><a href="">Edit</a> &middot; <a href="">Delete</a></span></li>
  <?php endforeach; ?>
  </ul>
  <?php else: ?>
  <?php if ($__q = Request::get('q')): ?>
  <p><?php echo $language->message_error_search('<em>' . $__q . '</em>'); ?></p>
  <?php else: ?>
  <p><?php echo $language->message_info_void($language->{$__chops[0] . 's'}); ?></p>
  <?php endif; ?>
  <?php endif; ?>
</section>