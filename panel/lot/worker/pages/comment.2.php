<section class="buttons">
  <p>
    <?php if (Request::get('q')): ?>
    <?php $__links = [HTML::a('&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_has_step, false, ['classes' => ['button', 'reset']])]; ?>
    <?php else: ?>
    <?php $__links = [HTML::a('&#x2795; ' . $language->{$__chops[0]}, $__state->path . '/::s::/' . $__path, false, ['classes' => ['button', 'set']])]; ?>
    <?php endif; ?>
    <?php echo implode(' ', Hook::fire('panel.a.' . $__chops[0] . 's', [$__links])); ?>
  </p>
</section>
<section class="pages">
  <?php if ($__pages[0]): ?>
  <?php foreach ($__pages[1] as $__k => $__v): ?>
  <?php $__s = $__pages[0][$__k]->url; ?>
  <article class="page <?php echo 'on-' . $__v->state; ?>" id="page-<?php echo $__v->id; ?>">
    <header>
      <?php if ($__pages[0][$__k]->state === 'draft'): ?>
      <h3><?php echo $__v->url; ?></h3>
      <?php else: ?>
      <h3><?php echo HTML::a($__v->author, $__v->url, true); ?></h3>
      <?php endif; ?>
    </header>
    <section><p><?php echo To::snippet($__v->content, true, $__state->snippet); ?></p></section>
    <footer>
      <p>
      <?php

      $__links = [
          HTML::a($language->edit, $__s),
          HTML::a($language->delete, str_replace('::g::', '::r::', $__s) . HTTP::query(['token' => $__token]))
      ];

      echo implode(' &#x00B7; ', Hook::fire('panel.a.' . $__chops[0], [$__links]));

      ?>
      </p>
    </footer>
  </article>
  <?php endforeach; ?>
  <?php else: ?>
  <?php if ($__q = Request::get('q')): ?>
  <p><?php echo $language->message_error_search('<em>' . $__q . '</em>'); ?></p>
  <?php else: ?>
  <p><?php echo $language->message_info_void($language->{$__chops[0] . 's'}); ?></p>
  <?php endif; ?>
  <?php endif; ?>
</section>