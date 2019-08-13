<?php

namespace _\lot\x\panel\content\desk {
    function body($in, $key, $type) {
        $out = \_\lot\x\panel\content($in, $key, $type);
        $out[0] = 'main';
        return $out;
    }
    function footer($in, $key, $type) {
        $out = \_\lot\x\panel\content($in, $key, $type);
        $out[0] = 'footer';
        return $out;
    }
    function header($in, $key, $type) {
        $out = \_\lot\x\panel\content($in, $key, $type);
        $out[0] = 'header';
        return $out;
    }
}

namespace _\lot\x\panel\content {
    function desk($in, $key, $type) {
        return \_\lot\x\panel\content($in, $key, $type);
    }
    function li($in, $key, $type) {
        $out = \_\lot\x\panel\content($in, $key, $type);
        $out[0] = 'li';
        return $out;
    }
    function nav($in, $key, $type) {
        $out = \_\lot\x\panel\content($in, $key, $type);
        $out[0] = 'nav';
        return $out;
    }
    function ol($in, $key, $type) {
        $out = \_\lot\x\panel\content($in, $key, $type);
        $out[0] = 'ol';
        return $out;
    }
    function ul($in, $key, $type) {
        $out = \_\lot\x\panel\content($in, $key, $type);
        $out[0] = 'ul';
        return $out;
    }
}