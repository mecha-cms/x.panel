<?php if (substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false): ?>
    <?php if ($__source[0]): ?>
    <section class="s-source">
      <h3><?php echo $language->source; ?></h3>
      <ul>
        <li><?php echo HTML::a($__source[1]->title, $__source[0]->url); ?></li>
      </ul>
    </section>
    <?php endif; ?>
    <section class="s-kin">
      <h3><?php echo $language->{count($__datas[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
      <ul>
        <?php foreach ($__datas[0] as $__k => $__v): ?>
        <li><?php echo HTML::a($__datas[1][$__k]->key, $__v->url); ?></li>
        <?php endforeach; ?>
        <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+', false, ['title' => $language->add]); ?></li>
      </ul>
    </section>
<?php else: ?>
    <section class="s-author">
      <h3><?php echo $language->author; ?></h3>
      <p>
<?php

$__authors = [];
$__select = $__page[0]->author;
foreach (g(USER, 'page') as $__v) {
    $__v = new User(Path::N($__v));
    $__k = $__v->key;
    if ($__user->status !== 1 && $__k !== $__user->key) continue;
    $__authors[User::ID . $__k] = $__v->author;
}
echo Form::select('author', $__user->status !== 1 && $__sgr !== 's' ? [User::ID . $__page[0]->author => $__page[1]->author] : $__authors, $__select, ['classes' => ['select', 'block'], 'id' => 'f-author']);

?>
      </p>
    </section>
    <?php if ($__parent[0]): ?>
    <section class="s-parent">
      <h3><?php echo $language->parent; ?></h3>
      <ul>
        <li><?php echo HTML::a($__parent[1]->title, $__parent[0]->url); ?></li>
      </ul>
    </section>
    <?php endif; ?>
    <?php if ($__kins[0]): ?>
    <section class="s-kin">
      <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
      <ul>
        <?php foreach ($__kins[0] as $__k => $__v): ?>
        <li><?php echo HTML::a($__kins[1][$__k]->title, $__v->url); ?></li>
        <?php endforeach; ?>
        <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . Path::D($__path), false, ['title' => $language->add]); ?><?php echo $__is_has_step_kin ? ' ' .  HTML::a('&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]) : ""; ?></li>
      </ul>
    </section>
    <?php endif; ?>
    <?php if ($__sgr !== 's'): ?>
    <section class="s-setting">
      <h3><?php echo $language->settings; ?></h3>
      <?php if ($__has_pages = Get::pages(LOT . DS . $__path, 'draft,page,archive')): ?>
      <h4><?php echo $language->sort; ?></h4>
      <table class="table">
        <thead>
          <tr>
            <th><?php echo $language->order; ?></th>
            <th><?php echo $language->by; ?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo Form::radio('sort[0]', $language->panel->sort[0], isset($__parent[0]->sort[0]) ? $__parent[0]->sort[0] : (isset($__page[0]->sort[0]) ? $__page[0]->sort[0] : null), ['classes' => ['input']]); ?></td>
            <td><?php echo Form::radio('sort[1]', $language->panel->sort[1], isset($__parent[0]->sort[1]) ? $__parent[0]->sort[1] : (isset($__page[0]->sort[1]) ? $__page[0]->sort[1] : null), ['classes' => ['input']]); ?></td>
          </tr>
        </tbody>
      </table>
      <h4><?php echo $language->chunk; ?></h4>
      <p><?php echo Form::number('chunk', $__page[0]->chunk, 7, ['classes' => ['input', 'block'], 'min' => 0, 'max' => 20]); ?></p>
      <?php endif; ?>
      <h4><?php echo $language->options; ?></h4>
      <p>
        <?php $__s = trim(To::url(Path::F($__path, 'page')), '/'); ?>
        <?php echo Form::checkbox('as_', $__s, $site->path === $__s, $language->panel->as_, ['classes' => ['input'], 'disabled' => $site->path === $__s ? true : null]); ?>
        <?php echo $__has_pages ? '<br>' . Form::checkbox('as_page', 1, file_exists(Path::F($__page[0]->path) . DS . $__page[0]->slug . '.' . $__page[0]->state), $language->panel->as_page, ['classes' => ['input']]) : ""; ?>
      </p>
      <?php echo Hook::fire('panel.h.page.options', ["", $__page]); ?>
    </section>
    <?php endif; ?>
<?php endif; ?>