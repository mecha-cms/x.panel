<form id="form.main" action="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops); ?>" method="post">
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.1.before'); ?>
    <section class="secondary-author">
      <h3><?php echo $language->author; ?></h3>
      <p>
<?php

if (Extend::exist('user')) {
    $authors = [];
    $select = $page[0]->author . "";
    foreach (g(ENGINE . DS . 'log' . DS . 'user', 'page') as $v) {
        $v = new User(Path::N($v));
        $k = User::ID . $v->key;
        $authors[($v->state !== 1 ? '.' : "") . $k] = $v->author;
        if ($select === $v->author) {
            $select = $k;
        }
    }
    echo Form::select('author', $authors, $select, [
        'classes' => ['select', 'block'],
        'id' => 'field-author'
    ]);
} else {
    echo Form::text('author', $page[0]->author, User::ID . l($language->user), ['classes' => ['input', 'block']]);
}

?>
      </p>
    </section>
    <?php if ($parent[0]): ?>
    <section class="secondary-parent">
      <h3><?php echo $language->parent; ?></h3>
      <ul>
        <li class="state-<?php echo $parent[0]->state; ?>"><a href="<?php echo $parent[0]->url; ?>"><?php echo $parent[1]->title; ?></a></li>
      </ul>
    </section>
    <?php endif; ?>
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
    <?php if (count($childs[0]) > 0): ?>
    <section class="secondary-config">
      <h3><?php echo $language->configs; ?></h3>
      <h4><?php echo $language->sort; ?></h4>
      <p>
<?php echo Form::radio('sort[0]', $language->panel->sort, isset($parent[0]->sort[0]) ? $parent[0]->sort[0] : (isset($page[1]->sort[0]) ? $page[1]->sort[0] : ""), ['classes' => ['input']]); ?>
      </p>
      <h4><?php echo $language->by; ?></h4>
      <p>
<?php echo Form::radio('sort[1]', [
    'time' => $language->time,
    'slug' => $language->slug,
    'title' => $language->title
], isset($parent[0]->sort[1]) ? $parent[0]->sort[1] : (isset($page[1]->sort[1]) ? $page[1]->sort[1] : ""), ['classes' => ['input']]); ?>
      </p>
      <h4><?php echo $language->chunk; ?></h4>
      <p><?php echo Form::number('chunk', isset($parent[0]->chunk) ? $parent[0]->chunk : (isset($page[1]->chunk) ? $page[1]->chunk : $site->chunk), $language->inherit, ['classes' => ['input', 'block'], 'min' => 0, 'max' => 100]); ?></p>
    </section>
    <?php endif; ?>
    <?php Hook::NS('panel.secondary.1.after'); ?>
  </aside>
  <main class="main">
    <?php echo $message; ?>
    <?php Hook::NS('panel.main.before'); ?>
    <fieldset>
      <legend><?php echo $language->editor; ?></legend>
      <p class="field expand">
        <label for="field-title"><?php echo $language->title; ?></label> <span>
<?php echo Form::text('title', $page[0]->title, $page[1]->title, [
    'classes' => ['input', 'block'],
    'id' => 'field-title'
]); ?>
        </span>
      </p>
      <p class="field expand">
        <label for="field-slug"><?php echo $language->slug; ?></label> <span>
<?php echo Form::text('slug', $page[0]->slug, $page[1]->slug, [
    'classes' => ['input', 'block'],
    'id' => 'field-slug'
]); ?>
        </span>
      </p>
      <div class="field expand p">
        <label for="field-content"><?php echo $language->content; ?></label>
        <div>
<?php echo Form::textarea('content', $page[0]->content, null, [
    'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
    'id' => 'field-content',
    'data' => ['type' => $page[0]->type]
]); ?>
        </div>
      </div>
      <p class="field">
        <label for="field-type"><?php echo $language->type; ?></label> <span>
<?php $types = a(Panel::get('page.types', [])); asort($types); ?>
<?php echo Form::select('type', $types, $page[0]->type, [
    'classes' => ['select'],
    'id' => 'field-type'
]); ?>
        </span>
      </p>
      <div class="field p">
        <label for="field-description"><?php echo $language->description; ?></label>
        <div>
<?php echo Form::textarea('description', $page[0]->description, $page[0]->description, [
    'classes' => ['textarea', 'block'],
    'id' => 'field-description'
]); ?>
        </div>
      </div>
      <p class="field">
        <label for="field-kind"><?php echo $language->kind; ?></label> <span>
<?php $kinds = $page[0]->kind === [0] ? "" : implode(', ', $page[0]->kind); ?>
<?php echo Form::text('kind', $kinds, 'foo, bar, baz', [
    'classes' => ['input', 'block', 'query'],
    'id' => 'field-kind'
]); ?>
        </span>
      </p>
    </fieldset>
    <?php Hook::NS('panel.main.after'); ?>
    <?php echo Form::hidden('token', $token); ?>
    <p class="field expand">
      <label for="field-x"><?php echo $language->state; ?></label> <span>
<?php

echo Form::submit('x', 'page', $language->publish, ['classes' => ['button', 'x-page'], 'id' => 'field-x:page']) . ' ';
echo Form::submit('x', 'draft', $language->save, ['classes' => ['button', 'x-draft'], 'id' => 'field-x:draft']) . ' ';
echo Form::submit('x', 'archive', $language->archive, ['classes' => ['button', 'x-archive'], 'id' => 'field-x:archive']) . ' ';
echo Form::submit('x', 'trash', $language->delete, ['classes' => ['button', 'x-trash'], 'id' => 'field-x:trash']);

?>
      </span>
    </p>
    <?php Shield::get(__DIR__ . DS . 'footer.content.php'); ?>
  </main>
  <aside class="secondary">
    <?php Hook::NS('panel.secondary.2.before'); ?>
    <?php if ($sgr === 'g'): ?>
    <section class="secondary-data">
      <h3><?php echo $language->datas; ?></h3>
      <ul>
        <?php foreach ($datas[0] as $k => $v): ?>
        <li class="data-<?php echo $v->key; ?>"><a href="<?php echo $url . '/' . $state->path . '/::g::/' . implode('/', $chops) . '/d:' . $v->key; ?>"><?php echo $datas[1][$k]->key; ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?php echo $url . '/' . $state->path . '/::s::/' . implode('/', $chops) . '/d+'; ?>" title="<?php echo $language->add; ?>">&#x2795;</a></li>
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
    <?php Hook::NS('panel.secondary.2.after'); ?>
  </aside>
</form>