<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<?php

$s = '<ul class="files" data-directory="' . str_replace([LOT, DS], ["", '/'], PAGE . DS . 'article') . '">';

$files = [];
foreach (File::explore(PAGE . DS . 'article') as $k => $v) {
    $files[$v . $k] = $k;
}

ksort($files);

foreach (array_values($files) as $v) {
    $v = File::inspect($v);
    $name = Path::B($v['path']);
    $s .= '<li class="file is-' . ($v['is']['file'] ? 'file' : 'folder') . '" data-name="' . $name . '">';
    $s .= '<h3 class="title">' . $name . '</h3>';
    $s .= '</li>';
}

echo $s . '</ul>';

?>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>