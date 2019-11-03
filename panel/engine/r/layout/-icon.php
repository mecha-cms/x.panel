<?php

if (!empty($GLOBALS['SVG'])) {
    $out = '<svg xmlns="http://www.w3.org/2000/svg" display="none">';
    foreach ($GLOBALS['SVG'] as $k => $v) {
        $out .= '<symbol id="i:' . $k . '" viewBox="0 0 24 24">';
        $out .= 0 === strpos($v, '<') ? $v : '<path d="' . $v . '"></path>';
        $out .= '</symbol>';
    }
    $out .= '</svg>';
    return $out;
}

return "";