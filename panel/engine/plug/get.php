<?php

Get::plug('zone', function($id = null, $fail = false, $format = '%{1}% &#x00B7; %{2}% (UTC%{0}%)') {
    // http://pastebin.com/vBmW1cnX
    $regions = [
        \DateTimeZone::AFRICA,
        \DateTimeZone::AMERICA,
        \DateTimeZone::ANTARCTICA,
        \DateTimeZone::ASIA,
        \DateTimeZone::ATLANTIC,
        \DateTimeZone::AUSTRALIA,
        \DateTimeZone::EUROPE,
        \DateTimeZone::INDIAN,
        \DateTimeZone::PACIFIC
    ];
    $zones = $zones_o = $a = $b = [];
    foreach ($regions as $region) {
        $zones = array_merge($zones, \DateTimeZone::listIdentifiers($region));
    }
    foreach ($zones as $zone) {
        $tz = new \DateTimeZone($zone);
        $zones_o[$zone] = $tz->getOffset(new \DateTime);
    }
    foreach ($zones_o as $zone => $offset) {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_f = gmdate('H:i', abs($offset));
        $offset_pretty = $offset_prefix . $offset_f;
        $t = new \DateTimeZone($zone);
        $c = new \DateTime(null, $t);
        $current_time = $c->format('g:i A');
        $text = __replace__($format, [$offset_pretty, str_replace('_', ' ', $zone), $current_time]);
        if ($offset < 0) {
            $b[$zone] = $text;
        } else {
            $a[$zone] = $text;
        }
    }
    asort($a);
    arsort($b);
    $zones = $b + $a;
    if (isset($id)) {
        return isset($zones[$id]) ? $zones[$id] : $fail;
    }
    return $zones;
});