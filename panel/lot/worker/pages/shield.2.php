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
    <?php

    $__s = $__pages[0][$__k]->url;

    ?>
    <article class="page on-<?php echo $__v->state . ($__v->id === $config->shield ? ' is-active' : ""); ?>" id="page-<?php echo $__v->id; ?>">
      <figure>
        <img>
      </figure>
      <header>
        <h3><?php echo $__v->title; ?></h3>
      </header>
      <section>
        <p><?php echo To::snippet($__v->description, true, $__state->snippet); ?></p>
      </section>
      <footer>
        <p>
        <?php

        $__links = [HTML::a($language->open, $__s)];

        if ($__v->id !== $config->shield) {
            $__links[] = HTML::a($language->attach, str_replace('::g::', '::s::', $__s) . HTTP::query(['token' => $__token]));
        }

        $__links[] = HTML::a($language->delete, str_replace('::g::', '::r::', $__s) . HTTP::query(['token' => $__token]));

        echo implode(' &#x00B7; ', Hook::fire('panel.a.' . $__chops[0], [$__links, $__v, $__pages]));

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