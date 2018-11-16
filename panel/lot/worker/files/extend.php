<?php

if ($chops) {
    if (count($chops) === 1) {
        $active = file_exists($file . DS . 'index.php');
        $x = has(['asset', 'page', 'plugin', 'shield', 'user'], basename($file));
        Config::set('panel.desk.header.tool.+.+', [
            'r' => ['x' => $x],
            'status' => [
                'x' => $x,
                'title' => $language->{$active ? 'eject' : 'attach'},
                'icon' => [[$active ? 'M13,9.86V11.18L15,13.18V9.86C17.14,9.31 18.43,7.13 17.87,5C17.32,2.85 15.14,1.56 13,2.11C10.86,2.67 9.57,4.85 10.13,7C10.5,8.4 11.59,9.5 13,9.86M14,4A2,2 0 0,1 16,6A2,2 0 0,1 14,8A2,2 0 0,1 12,6A2,2 0 0,1 14,4M18.73,22L14.86,18.13C14.21,20.81 11.5,22.46 8.83,21.82C6.6,21.28 5,19.29 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V16.27L2,5.27L3.28,4L13,13.72L15,15.72L20,20.72L18.73,22Z' : 'M18,6C18,7.82 16.76,9.41 15,9.86V17A5,5 0 0,1 10,22A5,5 0 0,1 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V9.86C11.23,9.4 10,7.8 10,5.97C10,3.76 11.8,2 14,2C16.22,2 18,3.79 18,6M14,8A2,2 0 0,0 16,6A2,2 0 0,0 14,4A2,2 0 0,0 12,6A2,2 0 0,0 14,8Z']],
                'path' => Path::R($file, LOT, '/') . '/index.' . ($active ? 'php' : 'x'),
                'task' => ['2eca1f34', ['index.' . ($active ? 'x' : 'php')]],
                'stack' => 10.2
            ]
        ]);
    }
    Hook::set('on.ready', function() use($file) {
        extract(Lot::get(null, []));
        $s = $file . DS . 'about.';
        if ($f = File::exist([
            $s . 'page',
            $s . DS . $site->language . '.page'
        ])) {
            $page = new Page($f);
            $title = $page->title . ' <code>' . $page->version . '</code>';
            $d = strpos($content = $page->content, '<!-- block:donate -->') !== false ? '<hr><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="TNVGH7NQ7E4EU"><p><input type="image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAAAaCAMAAAANMMsbAAABQVBMVEUAAAD/mTP/qigAM2b/tUL/wWH/7Mj/79L/8tv/zYD/9uX/2Z7/+e7+6cD/5b3+57r//fj/8dv+5rT/rzP/rC3+5K/+4KX+4ar+36H+0oT/sTj/sz0gSW/+tUJgdH5AUVb/t0i+uaCOlIpAXnUQPmtwgIW+qnx/g3X/x2wQOmKAb0fu266uq5een49/i4kgQl//vln/u06vhj3PljjvpjL/6MPOv5dQan0QPmr/w2W/jjv/4rLe0Knu1qPOwJ3+2pe+tZaeoZRAYHv+zXowVHMwUWwwSltAUVhQWVTu3rr/3KjOxajey5+epJ7/1JN/iYT+zn2OjHdgb3FAW24gR2ogQl5gYFBwaE2ff0SPdkO/kEDfnzn/3Kfu1J1/jpO+tJKuqpF/jZCelnlweXNQZW9wZ0uPeUmffT//sju/jDjfmzAzmEmSAAAAAXRSTlMAQObYZgAAAilJREFUeNq1lmlX2kAUhgmvKNpaW7KRGmNYJWHfQUAQUHC37t339f//gN4JSIFUPyXPOblz5705z5kTPgyeCZxjeObh6u/8zx1hpZHgZtWJpacO4l/mptzyisOUuYm74XecRW7sVpZcoDGy8+VFN8hbB/et2jkBoX44WH2cg5OHZ9aPKtTX7MSgdrtRVNce5Uj9/PDwXKCD/8rLARtdhAKBZhRHbBMKJcdxMtQMTCVVpKyQ3rXjW+c83EY+IdoAklRjCIvibhRAzMoy1KbGya4oskUTWag2bQYlv0FyXdd78hw7UNmSQUZOo5ZO9ZGmDP2UhojcQi0VRk0+jgGZnTD6HQ3VeUM7r+vs5IQvKM0QhsYWOrmkoiNJn1CV0lBbVGhAVABJ6iAiSTUcSy3azdJjVpKvMxYS7eAUGsJsUVGpQKXmIyKUnY0G7zUVoCB4hliwQl0kAgSnaPd0S0ryl2P0xLlyzx7iVF/hSonj0uourMwql/gW38JXRbnAF5pfbTGUCYncvZHkAm8H4PnSD+AN/xbZEl8yqAPGA/YMcMfzWRTZnOeLBf4/CCT3Cja2MeKO+gH2zSxuKNsXRiWL4RDYFgTgtiAMMbg1UBTseDkP2W0UNhnmH9aXvhu4Nln2elx+XxumuVn0ek0DP72lGwO0sUNuJncJkpN9wRXIbcl9LkDykT237Dg57t/t/MRh6tOXqH74zEEO9bn7P3dafuEI5dPJJ3H1T9Ff6nuMWGU5acwAAAAASUVORK5CYII=" name="submit" alt="PayPal &ndash; The safer, easier way to pay online!" title="Using an open source project is incredibly fun and cheap, but we also need costs to maintain and keep them exist in the `www`."><img alt="" src="https://www.paypalobjects.com/id_ID/i/scr/pixel.gif" width="1" height="1"></p></form>' : "";
            $content = trim(str_replace('<!-- block:donate -->', "", $content));
            $s = '<div class="page">';
            $s .= '<h2>' . $title . '</h2>';
            $s .= '<div class="p">' . $content . '</div>';
            $s .= $d;
            $s .= '</div>';
            Config::set('panel.desk.body.tab.info', [
                'title' => $language->info,
                'content' => $s,
                'stack' => 9
            ]);
        }
    }, 1);
} else {
    // Force `view` value to `page`
    require __DIR__ . DS . '..' . DS . ($panel->v = ($panel->view = 'page') . 's') . DS . 'extend.php';
}