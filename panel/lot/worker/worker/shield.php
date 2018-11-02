<!DOCTYPE html>
<html lang="<?php echo $site->language; ?>" dir="<?php echo $site->direction; ?>" class="<?php echo $error ? 'is-error error-404' : 'is-' . $panel->v; ?>">
<head>
<meta charset="<?php echo $site->charset; ?>">
<meta name="viewport" content="width=device-width">
<title><?php echo To::text($site->trace); ?></title>
<link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
</head>
<body spellcheck="false">
<?php echo $message; ?>
<?php echo $nav; ?>
<?php

$g = "";
if ($panel->c === 's') {
    foreach (['slug', 'key'] as $k) {
        if ($n = (array) Config::get('panel.$.' . $k, [], true)) {
            $g .= ' data-generator-' . $k . '="' . implode(' ', $n) . '"';
        }
    }
}

?>
<?php if ($v = has(['file', 'page', 'data'], $panel->v)): ?>
<form name="editor" class="form m0 p0" action="<?php echo HTTP::query(['token' => $token]); ?>" method="post" enctype="multipart/form-data"<?php echo $g; ?>>
<?php endif; ?>
<?php if ($error): ?>
<p class="m0 p2">&#x0CA0;&#x005F;&#x0CA0;</p>
<?php else: ?>
<?php echo $desk; ?>
<?php endif; ?>
<?php if ($v): ?>
<input name="view" value="<?php echo HTTP::get('view', $panel->v); ?>" type="hidden">
</form>
<?php endif; ?>
<?php

foreach ((array) Config::get('panel.menu', [], true) as $k => $v) {
    echo fn\panel\menus($v, $k, [
        'data[]' => ['js-enter' => '#js:' . $k]
    ]);
}

?>
</body>
</html>