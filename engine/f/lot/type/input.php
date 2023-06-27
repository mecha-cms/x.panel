<?php namespace x\panel\lot\type\input;

function button($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'button';
    return $out;
}

function checkbox($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'checkbox';
    return $out;
}

function color($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'color';
    return $out;
}

function date($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'date';
    if (isset($out['maxlength'])) {
        $out['max'] = $out['maxlength'];
        unset($out['maxlength']);
    }
    if (isset($out['minlength'])) {
        $out['min'] = $out['minlength'];
        unset($out['minlength']);
    }
    return $out;
}

function datetime_local($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'datetime-local';
    if (isset($out['maxlength'])) {
        $out['max'] = $out['maxlength'];
        unset($out['maxlength']);
    }
    if (isset($out['minlength'])) {
        $out['min'] = $out['minlength'];
        unset($out['minlength']);
    }
    return $out;
}

function email($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'email';
    return $out;
}

function file($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'file';
    return $out;
}

function hidden($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $tags = \explode(' ', (string) ($out['class'] ?? ""));
    foreach ($tags as $k => $v) {
        if (false !== \strpos(',are,as,can,has,is,not,of,with,', ',' . \strtok($v, ':') . ',')) {
            unset($tags[$k]);
        }
    }
    $out['class'] = $tags ? \implode(' ', $tags) : null;
    $out['type'] = 'hidden';
    return $out;
}

function image($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'image';
    return $out;
}

function month($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'month';
    return $out;
}

function number($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'number';
    return $out;
}

function password($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'password';
    return $out;
}

function radio($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'radio';
    return $out;
}

function range($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'range';
    return $out;
}

function reset($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'reset';
    return $out;
}

function search($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'search';
    return $out;
}

function submit($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'submit';
    return $out;
}

function tel($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'tel';
    return $out;
}

function text($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'text';
    return $out;
}

function time($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'time';
    if (isset($out['maxlength'])) {
        $out['max'] = $out['maxlength'];
        unset($out['maxlength']);
    }
    if (isset($out['minlength'])) {
        $out['min'] = $out['minlength'];
        unset($out['minlength']);
    }
    return $out;
}

function url($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'url';
    return $out;
}

function week($value, $key) {
    $out = \x\panel\lot\type\input($value, $key);
    $out['type'] = 'week';
    return $out;
}