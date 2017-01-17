<form id="form.main" action="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops); ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
    <?php if ($parent[0]): ?>
    <section class="secondary-parent">
      <h3><?php echo $language->parent; ?></h3>
      <ul>
        <li class="state-<?php echo $parent[0]->state; ?>"><a href="<?php echo $parent[0]->url; ?>"><?php echo $parent[1]->title; ?></a></li>
      </ul>
    </section>
    <?php endif; ?>
    <section class="secondary-child">
      <h3><?php echo $language->childs; ?></h3>
      <ul>
        <?php foreach ($childs[0] as $k => $v): ?>
        <li class="state-<?php echo $v->state; ?>"><a href="<?php echo $v->url; ?>"><?php echo $childs[1][$k]->title; ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops); ?>" title="<?php echo $language->add; ?>">&#x2795;</a><?php if ($child_very_much): ?> <a href="<?php echo $url . '/' . $state->path . '/::g::/' . implode('/', $chops) . '/2'; ?>" title="<?php echo $language->more; ?>">&#x2026;</a><?php endif; ?></li>
      </ul>
    </section>
    <?php if ($kins[0]): ?>
    <section class="secondary-kin">
      <h3><?php echo $language->kins; ?></h3>
      <ul>
        <?php foreach ($kins[0] as $k => $v): ?>
        <li class="state-<?php echo $v->state; ?>"><a href="<?php echo $v->url; ?>"><?php echo $kins[1][$k]->title; ?></a></li>
        <?php endforeach; ?>
        <?php if ($kin_very_much): ?>
        <li><a href="<?php echo $url . '/' . $state->path . '/::g::/' . Path::D(implode('/', $chops)) . '/2'; ?>" title="<?php echo $language->more; ?>">&#x2026;</a></li>
        <?php endif; ?>
      </ul>
    </section>
    <?php endif; ?>
    <?php Hook::NS('panel.secondary.1.after'); ?>
  </aside>
  <main class="main">
    <?php Hook::NS('panel.main.before'); ?>
    <section>
      <?php echo $message; ?>
      <fieldset>
        <legend><?php echo $language->editor; ?></legend>
        <p class="control expand">
          <label for="control-page-title"><?php echo $language->title; ?></label> <span>
<?php echo Form::text('title', $page[0]->title, $page[1]->title, [
    'classes' => ['input', 'block'],
    'id' => 'control-page-title'
]); ?>
          </span>
        </p>
        <p class="control expand">
          <label for="control-page-slug"><?php echo $language->slug; ?></label> <span>
<?php echo Form::text('slug', $page[0]->slug, $page[1]->slug, [
    'classes' => ['input', 'block'],
    'id' => 'control-page-slug'
]); ?>
          </span>
        </p>
        <p class="control expand">
          <label for="control-page-content"><?php echo $language->content; ?></label> <span>
<?php echo Form::textarea('content', $page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'control-page-content',
    'data' => ['type' => $page[0]->type]
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-page-type"><?php echo $language->type; ?></label> <span>
<?php $types = a(Panel::get('page.types', [])); asort($types); ?>
<?php echo Form::select('type', $types, $page[0]->type, [
    'classes' => ['select'],
    'id' => 'control-page-type'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-page-description"><?php echo $language->description; ?></label> <span>
<?php echo Form::textarea('description', $page[0]->description, $page[0]->description, [
    'classes' => ['textarea', 'block'],
    'id' => 'control-page-description'
]); ?>
          </span>
        </p>
        <p class="control">
          <label for="control-page-kind"><?php echo $language->kind; ?></label> <span>
<?php $kinds = $page[0]->kind === [0] ? "" : implode(', ', $page[0]->kind); ?>
<?php echo Form::text('kind', $kinds, 'foo, bar, baz, qux', [
    'classes' => ['input', 'block', 'query'],
    'id' => 'control-page-kind'
]); ?>
          </span>
        </p>
      </fieldset>
      <p class="control expand">
        <label for="control-page-x"><?php echo $language->state; ?></label>
        <span>
<?php

echo Form::button('x', 'page', $language->publish, ['classes' => ['button', 'x-page'], 'id' => 'control-page-x:page']) . ' ';
echo Form::button('x', 'draft', $language->save, ['classes' => ['button', 'x-draft'], 'id' => 'control-page-x:draft']) . ' ';
echo Form::button('x', 'archive', $language->archive, ['classes' => ['button', 'x-archive'], 'id' => 'control-page-x:archive']) . ' ';
echo Form::button('x', 'trash', $language->delete, ['classes' => ['button', 'x-trash'], 'id' => 'control-page-x:trash']);

?>
        </span>
      </p>
      <?php echo Form::hidden('token', $token); ?>
    </section>
    <?php Hook::NS('panel.main.after'); ?>
    <?php Shield::get($shield_path . DS . 'footer.php'); ?>
  </main>
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.2.before'); ?>
    <?php if ($sgr === 'g'): ?>
    <section class="secondary-data">
      <h3><?php echo $language->datas; ?></h3>
      <ul>
        <?php foreach ($datas[0] as $k => $v): ?>
        <li class="data-<?php echo $v->slug; ?>"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . implode('/', $chops) . '/d:' . $v->slug; ?>"><?php echo $datas[1][$k]->title; ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops) . '/d:'; ?>" title="<?php echo $language->add; ?>">&#x2795;</a></li>
      </ul>
    </section>
    <?php endif; ?>
    <?php if (count($childs[0]) > 0): ?>
    <section class="secondary-config">
      <h3><?php echo $language->configs; ?></h3>
      <section class="secondary-config-sort">
        <h4><?php echo $language->sort; ?></h4>
        <p>
<?php echo Form::radio('sort[0]', array_replace((array) $language->panel->sort, ["" => $language->inherit]), isset($parent[0]->sort[0]) ? $parent[0]->sort[0] : (isset($page[1]->sort[0]) ? $page[1]->sort[0] : ""), ['classes' => ['input']]); ?>
        </p>
        <h4><?php echo $language->by; ?></h4>
        <p>
<?php echo Form::radio('sort[1]', [
    'time' => $language->time . ' (' . l($language->panel->slower) . ')',
    'slug' => $language->slug,
    'title' => $language->title . ' (' . l($language->panel->slowest) . ')',
    "" => $language->inherit
], isset($parent[0]->sort[1]) ? $parent[0]->sort[1] : (isset($page[1]->sort[1]) ? $page[1]->sort[1] : ""), ['classes' => ['input']]); ?>
        </p>
        <h4><?php echo $language->chunk; ?></h4>
        <p><?php echo Form::number('chunk', isset($parent[0]->chunk) ? $parent[0]->chunk : (isset($page[1]->chunk) ? $page[1]->chunk : ""), $language->inherit, ['classes' => ['input', 'block'], 'min' => 0, 'max' => 100]); ?></p>
      </section>
    </section>
    <?php endif; ?>
    <?php Hook::NS('panel.secondary.2.after'); ?>
  </aside>
</form>