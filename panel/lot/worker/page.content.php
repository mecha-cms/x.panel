
<aside class="secondary">
  <?php Hook::NS('panel.secondary.1.before'); ?>
  <?php if ($parent[0]): ?>
  <section>
    <h3><?php echo $language->parent; ?></h3>
    <ul>
      <li class="state-<?php echo $parent[0]->state; ?>"><a href="<?php echo $parent[0]->url; ?>"><?php echo $parent[1]->title; ?></a></li>
    </ul>
  </section>
  <?php endif; ?>
  <section>
    <h3><?php echo $language->childs; ?></h3>
    <ul>
      <?php foreach ($childs[0] as $k => $v): ?>
      <li class="state-<?php echo $v->state; ?>"><a href="<?php echo $v->url; ?>"><?php echo $childs[1][$k]->title; ?></a></li>
      <?php endforeach; ?>
      <li><a href="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops) . '/page-' . time(); ?>" title="<?php echo $language->add; ?>">&#x2795;</a><?php if ($child_very_much): ?> <a href="<?php echo $url . '/' . $state->path . '/::g::/' . implode('/', $chops) . '/2'; ?>" title="<?php echo $language->more; ?>"><b>&#x2026;</b></a><?php endif; ?></li>
    </ul>
  </section>
  <?php if ($kins[0]): ?>
  <section>
    <h3><?php echo $language->kins; ?></h3>
    <ul>
      <?php foreach ($kins[0] as $k => $v): ?>
      <li class="state-<?php echo $v->state; ?>"><a href="<?php echo $v->url; ?>"><?php echo $kins[1][$k]->title; ?></a></li>
      <?php endforeach; ?>
      <?php if ($kin_very_much): ?>
      <li><a href="<?php echo $url . '/' . $state->path . '/::g::/' . Path::D(implode('/', $chops)) . '/2'; ?>" title="<?php echo $language->more; ?>"><b>&#x2026;</b></a></li>
      <?php endif; ?>
    </ul>
  </section>
  <?php endif; ?>
  <?php Hook::NS('panel.secondary.1.after'); ?>
</aside>
<main class="main">
  <?php Hook::NS('panel.main.before'); ?>
  <section>
    <?php echo Message::get(); ?>
    <form id="form.main" action="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops); ?>" method="post">
      <fieldset>
        <legend><?php echo $language->editor; ?></legend>
        <p class="control expand">
          <label for="control-<?php echo $chops[0]; ?>-data-title"><?php echo $language->title; ?></label> <span>
<?php echo Form::text('title', $page[0]->title, 'Title Here', [
    'classes' => ['input', 'block'],
    'id' => 'control-' . $chops[0] . '-data-title'
]); ?>
          </span>
        </p>
        <p class="control expand">
          <label for="control-<?php echo $chops[0]; ?>-data-slug"><?php echo $language->slug; ?></label> <span>
<?php echo Form::text('slug', $page[0]->slug, 'title-here', [
    'classes' => ['input', 'block'],
    'id' => 'control-' . $chops[0] . '-data-slug'
]); ?>
          </span>
        </p>
        <p class="control expand">
          <label for="control-<?php echo $chops[0]; ?>-data-content"><?php echo $language->content; ?></label> <span>
<?php echo Form::textarea('content', $page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'control-' . $chops[0] . '-data-content'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-data-type"><?php echo $language->type; ?></label> <span>
<?php $types = a(Panel::get('page.types', [])); asort($types); ?>
<?php echo Form::select('type', $types, $page[0]->type, [
    'classes' => ['select'],
    'id' => 'control-' . $chops[0] . '-data-type'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-data-description"><?php echo $language->description; ?></label> <span>
<?php echo Form::textarea('description', $page[0]->description, null, [
    'classes' => ['textarea', 'block'],
    'id' => 'control-' . $chops[0] . '-data-description'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-<?php echo $chops[0]; ?>-data-kind"><?php echo $language->kind; ?></label> <span>
<?php echo Form::text('kind', $page[0]->kind === [0] ? "" : implode(', ', $page[0]->kind), null, [
    'classes' => ['input', 'block', 'query'],
    'id' => 'control-' . $chops[0] . '-data-kind'
]); ?>
          </span>
        </p>
      </fieldset>
      <p>
      <?php

      echo Form::button('state', 'page', $language->publish, ['classes' => ['button', 'set', 'page']]) . ' ';
      echo Form::button('state', 'draft', $language->save, ['classes' => ['button', 'set', 'draft']]) . ' ';
      echo Form::button('state', 'archive', $language->archive, ['classes' => ['button', 'set', 'archive']]) . ' ';
      echo Form::button('state', 'trash', $language->delete, ['classes' => ['button', 'reset', 'trash']]);

      ?>
      </p>
    </form>
  </section>
  <?php Hook::NS('panel.main.after'); ?>
  <?php Shield::get($shield_path . DS . 'footer.php'); ?>
</main>
<aside class="secondary">
  <?php Hook::NS('panel.secondary.2.before'); ?>
  <?php if ($sgr === 'g'): ?>
  <section>
    <h3><?php echo $language->datas; ?></h3>
    <ul>
      <?php foreach ($datas[0] as $k => $v): ?>
      <li class="data-<?php echo $v->slug; ?>"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . implode('/', $chops) . '/:data/' . $v->slug; ?>"><?php echo $datas[1][$k]->title; ?></a></li>
      <?php endforeach; ?>
      <li><a href="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops) . '/:data'; ?>" title="<?php echo $language->add; ?>">&#x2795;</a></li>
    </ul>
  </section>
  <?php endif; ?>
  <?php Hook::NS('panel.secondary.2.after'); ?>
</aside>