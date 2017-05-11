    <section class="s-author">
      <h3><?php echo $language->author; ?></h3>
      <p>
<?php

$__authors = [];
$__select = $__page[0]->author;
foreach (g(ENGINE . DS . 'log' . DS . 'user', 'page') as $__v) {
    $__v = new User(Path::N($__v));
    $__k = $__v->key;
    if ($__user->status !== 1 && $__k !== $__user->key) continue;
    $__authors[User::ID . $__k] = $__v->author;
}
echo Form::select('author', $__user->status !== 1 && $__sgr !== 's' ? [User::ID . $__page[0]->author => $__page[1]->author] : $__authors, $__select, ['classes' => ['select', 'block'], 'id' => 'f-author']);

?>
      </p>
    </section>
    <?php if ($__kins[0]): ?>
    <section class="s-kin">
      <h3><?php echo $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'}; ?></h3>
      <ul>
        <?php foreach ($__kins[0] as $k => $v): ?>
        <li><?php echo HTML::a($__kins[1][$k]->title, $v->url); ?></li>
        <?php endforeach; ?>
        <li><?php echo HTML::a('&#x2795;', $__state->path . '/::s::/' . Path::D($__path), false, ['title' => $language->add]); ?><?php echo $__is_has_step_kin ? ' ' . HTML::a('&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]) : ""; ?></li>
      </ul>
    </section>
    <?php endif; ?>