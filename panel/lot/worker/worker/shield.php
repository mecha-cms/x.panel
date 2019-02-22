<?php $error = Config::get('panel.error'); ?>
<?php HTTP::status($error ? 404 : 200); ?>
<!DOCTYPE html>
<html lang="<?php echo $site->language; ?>" dir="ltr" class="<?php echo $error ? 'is-error error-404' : 'is-' . $panel->v; ?><?php echo Config::get('panel.+.form.editor') === 1 ? ' form' : ""; ?> status-<?php echo $user->status; ?> ltr">
<head>
<meta charset="<?php echo $site->charset; ?>">
<meta name="viewport" content="width=device-width">
<title><?php echo To::text($site->trace); ?></title>
<link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
<script>!function(e){e.className+=" js"}(document.documentElement);</script>
</head>
<body spellcheck="false">
<?php if (!empty($GLOBALS['SVG'])): ?>
<!-- Begin SVG -->
<?php echo $icons; ?>
<!-- End SVG -->
<?php endif; ?>
<?php static::message(); ?>
<?php echo $nav; ?>
<?php

$g = "";
if ($panel->c === 's') {
    foreach (['slug', 'key'] as $k) {
        if ($n = (array) Config::get('panel.+.' . $k, true)) {
            $g .= ' data-generator-' . $k . '="' . implode(' ', $n) . '"';
        }
    }
}

?>
<?php if ($v = Config::get('panel.+.form.editor')): ?>
<form name="editor" class="form m0 p0" action="" method="post" enctype="multipart/form-data"<?php echo $g; ?>>
<?php endif; ?>
<?php if ($error): ?>
<p class="m0 p2"><?php echo is_string($error) ? $error : '&#x0CA0;&#x005F;&#x0CA0;'; ?></p>
<?php else: ?>
<?php echo $desk; ?>
<?php endif; ?>
<?php if ($v): ?>
<input name="view" value="<?php echo HTTP::get('view') ?? $panel->v; ?>" type="hidden">
<input name="token" value="<?php echo $user->token; ?>" type="hidden">
</form>
<?php endif; ?>
<!-- Begin Menu(s) -->
<?php echo $menus; ?>
<!-- End Menu(s) -->
</body>
</html>