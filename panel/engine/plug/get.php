<?php

Get::plug('zone', function($id = null, $fail = false, $format = '(UTC%1$s) %2$s &ndash; %3$s') {
    // http://pastebin.com/vBmW1cnX
    $regions = [
        DateTimeZone::AFRICA,
        DateTimeZone::AMERICA,
        DateTimeZone::ANTARCTICA,
        DateTimeZone::ASIA,
        DateTimeZone::ATLANTIC,
        DateTimeZone::AUSTRALIA,
        DateTimeZone::EUROPE,
        DateTimeZone::INDIAN,
        DateTimeZone::PACIFIC
    ];
    $zones = [];
    $zone_offsets = [];
    foreach ($regions as $region) {
        $zones = array_merge($zones, DateTimeZone::listIdentifiers($region));
    }
    foreach ($zones as $zone) {
        $tz = new DateTimeZone($zone);
        $zone_offsets[$zone] = $tz->getOffset(new DateTime);
    }
    $a = $b = [];
    foreach ($zone_offsets as $zone => $offset) {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate('H:i', abs($offset));
        $pretty_offset = $offset_prefix . $offset_formatted;
        $t = new DateTimeZone($zone);
        $c = new DateTime(null, $t);
        $current_time = $c->format('g:i A');
        $text = sprintf($format, $pretty_offset, str_replace('_', ' ', $zone), $current_time);
        if ($offset < 0) {
            $b[$zone] = $text;
        } else {
            $a[$zone] = $text;
        }
    }
    asort($a);
    arsort($b);
    $zone_list = $b + $a;
    if (isset($id)) {
        return isset($zone_list[$id]) ? $zone_list[$id] : $fail;
    }
    return $zone_list;
});