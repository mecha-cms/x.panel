<?php if (!empty($__f = Config::get('panel.m.before'))): // [1] ?>
  <?php if (is_string($__f) && is_file($__f)): // [2] ?>
    <?php require $__f; ?>
  <?php else: // [2] ?>
    <?php echo $__f; ?>
  <?php endif; // [2] ?>
<?php endif; // [1] ?>
<?php if ($__t = array_filter(a(Config::get('panel.m.t', [])), function($__v) {
    return isset($__v) && isset($__v['stack']) && is_numeric($__v['stack']);
})): // [1] ?>
  <?php if (count($__t) > 1): // [2] ?>
  <?php $__t = Anemon::eat($__t)->sort([1, 'stack'], "")->vomit(); ?>
  <nav class="t">
    <?php $__1 = array_keys($__t); ?>
    <?php $__1 = array_shift($__1); ?>
    <?php foreach ($__t as $__k => $__v): // [3] ?>
      <?php if (!isset($__v['title'])) $__v['title'] = $language->{$__k}; ?>
      <?php echo HTML::a($__v['title'], '#t:' . $__k, false, ['class' => Request::get('m.t:v', Config::get('panel.m.t:v', $__1)) === $__k ? 'is-active' : null]); ?>
    <?php endforeach; // [3] ?>
  </nav>
  <?php endif; // [2] ?>
  <?php $__hiddens = []; $__submit = false; ?>
  <?php foreach ($__t as $__k => $__v): // [2] ?>
    <?php if (!isset($__v['title'])) $__v['title'] = $language->{$__k}; ?>
    <section class="t-c" id="t:<?php echo $__k; ?>">
      <fieldset>
        <?php if (!isset($__v['legend']) || $__v['legend'] !== false): // [3] ?>
          <legend><?php echo isset($__v['legend']) ? $__v['legend'] : $__v['title']; ?></legend>
        <?php endif; // [3] ?>
        <?php if (!isset($__v['content']) && $__w = File::exist(__DIR__ . DS . '..' . DS . 'page' . DS . $__chops[0] . '.m.t.' . $__k . '.php')): // [3] ?>
          <?php $__v['content'] = include $__w; ?>
        <?php elseif (isset($__v['content']) && is_string($__v['content']) && is_file($__v['content'])): // [3] ?>
          <?php $__v['content'] = include $__v['content']; // [4] ?>
        <?php endif; // [3] ?>
        <?php if (is_array($__v['content'])): // [3] ?>
        <?php if ($__a = a(Config::get('panel.f.' . $__k, []))): // [4] ?>
          <?php $__v['content'] = array_replace_recursive($__v['content'], $__a); ?>
        <?php endif; // [4] ?>
          <?php foreach (Anemon::eat($__v['content'])->is(function($__v) {
              return isset($__v) && isset($__v['stack']) && is_numeric($__v['stack']);
          })->sort([1, 'stack'], "")->vomit() as $__kk => $__vv): // [4] ?>
            <?php if (isset($__vv['type'])): // [5] ?>
              <?php if (strpos(X . 'submit' . X . 'submit[]' . X, X . $__vv['type'] . X) !== false): // [6] ?>
                <?php $__button = [$__kk, $__vv]; continue; ?>
              <?php elseif ($__vv['type'] === 'hidden'): // [6] ?>
                <?php $__hiddens[$__kk] = $__vv; ?>
              <?php endif; // [6] ?>
            <?php endif; // [5] ?>
          <?php echo __panel_f__($__kk, $__vv); ?>
        <?php endforeach; // [4] ?>
        <?php elseif (isset($__v['content'])): // [3] ?>
          <?php echo $__v['content']; ?>
        <?php endif; // [3] ?>
      </fieldset>
      <?php if (!empty($__v['description'])): // [2] ?>
        <?php $__s = $__v['description']; ?>
        <div class="h p"><?php echo stripos($__s, '</p>') === false ? '<p>' . $__s . '</p>' : $__s; ?></div>
      <?php endif; // [2] ?>
    </section>
  <?php endforeach; // [2] ?>
<?php else: // [1] ?>
  <p>:(</p>
<?php endif; // [1] ?>
<?php if (Config::get('panel.c:f', Config::get('panel.m:f', false))): // [1] ?>
  <?php if (!empty($__button)): // [2] ?>
    <?php echo call_user_func_array('__panel_f__', $__button); ?>
  <?php elseif (!empty($__hiddens)): // [2] ?>
    <?php foreach ($__hiddens as $__k => $__v): // [3] ?>
      <?php echo __panel_f__($__k, $__v); ?>
    <?php endforeach; // [3] ?>
  <?php else: // [2] ?>
    <?php echo __panel_f__('x', [
        'type' => 'submit',
        'title' => $language->submit,
        'value' => 'php'
    ]); ?>
  <?php endif; // [2] ?>
<?php endif; // [1] ?>
<?php if (!empty($__f = Config::get('panel.m.content'))): // [1] ?>
  <?php if (is_string($__f) && is_file($__f)): // [2] ?>
    <?php require $__f; ?>
  <?php else: // [2] ?>
    <?php echo $__f; ?>
  <?php endif; // [2] ?>
<?php endif; // [1] ?>
<?php if (!empty($__f = Config::get('panel.m.after'))): // [1] ?>
  <?php if (is_string($__f) && is_file($__f)): // [2] ?>
    <?php require $__f; ?>
  <?php else: // [2] ?>
    <?php echo $__f; ?>
  <?php endif; // [2] ?>
<?php endif; // [1] ?>