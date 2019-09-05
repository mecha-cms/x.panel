<?php

/*
// Common file type(s) allowed to be uploaded by the file manager
!defined('AUDIO_X') && define('AUDIO_X', 'aif,mid,mov,mpa,mp3,m3u,m4a,ogg,wav,wma');
!defined('FONT_X') && define('FONT_X', 'eot,fnt,fon,otf,svg,ttf,woff,woff2');
!defined('IMAGE_X') && define('IMAGE_X', 'bmp,cur,gif,ico,jpeg,jpg,png,svg');
!defined('PACKAGE_X') && define('PACKAGE_X', 'cbr,gz,iso,pkg,rar,rpm,tar,zip,zipx,7z');
!defined('TEXT_X') && define('TEXT_X', 'archive,cache,cfg,css,csv,data,draft,htaccess,html,js,json,log,page,php,srt,stack,tex,trash,txt,xml,yaml,yml');
!defined('VIDEO_X') && define('VIDEO_X', 'avi,flv,mkv,mov,mpg,mp4,m4a,m4v,ogv,rm,swf,vob,webm,wmv,3gp,3g2');
!defined('BINARY_X') && define('BINARY_X', AUDIO_X . ',' . PACKAGE_X . ',' . VIDEO_X . ',doc,docx,odt,pdf,ppt,pptx,rtf,xlr,xls,xlsx');
*/

$path = state('panel')['//'];
$p = trim($url->path, '/');
if (strpos($p . '/', $path . '/') === 0) {
    $panel = ['i' => $url->i];
    $a = explode('/', $p);
    $path = array_shift($a);
    $task = $a[0] && strpos($a[0], '::') === 0 && substr($a[0], -2) === '::' ? substr(array_shift($a), 2, -2) : null;
    if (count($a) === 1 && $task === 'g' && !isset($panel['i'])) {
        Guard::kick($url->clean . '/1' . $url->query . $url->hash);
    }
    $panel['//'] = $path;
    $panel['task'] = $task;
    $panel['directory'] = $task ? array_shift($a) : null;
    $panel['path'] = $task ? implode('/', $a) : null;
    $panel['chunk'] = 20;
    $GLOBALS['panel'] = $panel;
    require __DIR__ . DS . 'engine' . DS . 'f.php';
    require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'asset.php';
    require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'language.php';
    require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'route.php';
}