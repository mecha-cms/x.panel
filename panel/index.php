<?php

// Common file type(s) allowed to be uploaded by the file manager
!defined('AUDIO_X') && define('AUDIO_X', 'aif,mid,mov,mpa,mp3,m3u,m4a,ogg,wav,wma');
!defined('FONT_X') && define('FONT_X', 'eot,fnt,fon,otf,svg,ttf,woff,woff2');
!defined('IMAGE_X') && define('IMAGE_X', 'bmp,cur,gif,ico,jpeg,jpg,png,svg');
!defined('PACKAGE_X') && define('PACKAGE_X', 'cbr,gz,iso,pkg,rar,rpm,tar,zip,zipx,7z');
!defined('TEXT_X') && define('TEXT_X', 'archive,cache,cfg,css,csv,data,draft,htaccess,html,js,json,log,page,php,srt,stack,tex,trash,txt,xml,yaml,yml');
!defined('VIDEO_X') && define('VIDEO_X', 'avi,flv,mkv,mov,mpg,mp4,m4a,m4v,ogv,rm,swf,vob,webm,wmv,3gp,3g2');
!defined('BINARY_X') && define('BINARY_X', AUDIO_X . ',' . PACKAGE_X . ',' . VIDEO_X . ',doc,docx,odt,pdf,ppt,pptx,rtf,xlr,xls,xlsx');

require __DIR__ . DS . 'engine' . DS . 'f.php';

require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'language.php';

Hook::set('content', function($content) {
    if (empty($GLOBALS['SVG'])) {
        return $content;
    }
    $icons = '<svg xmlns="http://www.w3.org/2000/svg" display="none">';
    foreach ($GLOBALS['SVG'] as $k => $v) {
        $icons .= '<symbol id="i:' . $k . '" viewBox="0 0 24 24">';
        $icons .= strpos($v, '<') === 0 ? $v : '<path d="' . $v . '"></path>';
        $icons .= '</symbol>';
    }
    $icons .= '</svg>';
    return str_replace('<body>', '<body>' . $icons, $content);
});

// Test
Route::set('panel', 200, function() {
    Asset::let();
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'panel.css');
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . '@media.css');
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'panel' . DS . 'construction.css');
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 'panel' . DS . 'drop.js');
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 'panel' . DS . 'tab.js');
    $this->content(__DIR__ . DS . 'engine' . DS . 'r' . DS . 'content' . DS . 'test.field.php');
}, 0);