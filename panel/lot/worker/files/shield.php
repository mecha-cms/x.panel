<?php

if ($chops) {
    if (count($chops) === 1) {
        if (file_exists($f = $file . DS . 'state' . DS . 'config.php')) {
            Config::set('panel.desk.header.tool.state', [
                'title' => false,
                'description' => $language->state,
                'icon' => [['M3,17V19H9V17H3M3,5V7H13V5H3M13,21V19H21V17H13V15H11V21H13M7,9V11H3V13H7V15H9V9H7M21,13V11H11V13H21M15,9H17V7H21V5H17V3H15V9Z']],
                'path' => Path::R($f, LOT, '/'),
                'stack' => 10.11
            ]);
        }
        Config::set('panel.desk.header.tool.+.+', [
            'r' => ['x' => $config->shield === basename($file)],
            'status' => null // TODO
        ]);
    }
    if (!HTTP::is('get', 'q')) {
        Hook::set('on.ready', function() {
            extract(Lot::get());
            $s = $file . DS . 'about.';
            if ($f = File::exist([
                $s . 'page',
                $s . DS . $config->language . '.page'
            ])) {
                $page = new Page($f);
                $title = $page->title . ' <code>' . $page->version . '</code>';
                $d = strpos($content = $page->content, '<!-- block:donate -->') !== false ? '<hr><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="TNVGH7NQ7E4EU"><p><input type="image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAAAaCAMAAAANMMsbAAABQVBMVEUAAAD/mTP/qigAM2b/tUL/wWH/7Mj/79L/8tv/zYD/9uX/2Z7/+e7+6cD/5b3+57r//fj/8dv+5rT/rzP/rC3+5K/+4KX+4ar+36H+0oT/sTj/sz0gSW/+tUJgdH5AUVb/t0i+uaCOlIpAXnUQPmtwgIW+qnx/g3X/x2wQOmKAb0fu266uq5een49/i4kgQl//vln/u06vhj3PljjvpjL/6MPOv5dQan0QPmr/w2W/jjv/4rLe0Knu1qPOwJ3+2pe+tZaeoZRAYHv+zXowVHMwUWwwSltAUVhQWVTu3rr/3KjOxajey5+epJ7/1JN/iYT+zn2OjHdgb3FAW24gR2ogQl5gYFBwaE2ff0SPdkO/kEDfnzn/3Kfu1J1/jpO+tJKuqpF/jZCelnlweXNQZW9wZ0uPeUmffT//sju/jDjfmzAzmEmSAAAAAXRSTlMAQObYZgAAAilJREFUeNq1lmlX2kAUhgmvKNpaW7KRGmNYJWHfQUAQUHC37t339f//gN4JSIFUPyXPOblz5705z5kTPgyeCZxjeObh6u/8zx1hpZHgZtWJpacO4l/mptzyisOUuYm74XecRW7sVpZcoDGy8+VFN8hbB/et2jkBoX44WH2cg5OHZ9aPKtTX7MSgdrtRVNce5Uj9/PDwXKCD/8rLARtdhAKBZhRHbBMKJcdxMtQMTCVVpKyQ3rXjW+c83EY+IdoAklRjCIvibhRAzMoy1KbGya4oskUTWag2bQYlv0FyXdd78hw7UNmSQUZOo5ZO9ZGmDP2UhojcQi0VRk0+jgGZnTD6HQ3VeUM7r+vs5IQvKM0QhsYWOrmkoiNJn1CV0lBbVGhAVABJ6iAiSTUcSy3azdJjVpKvMxYS7eAUGsJsUVGpQKXmIyKUnY0G7zUVoCB4hliwQl0kAgSnaPd0S0ryl2P0xLlyzx7iVF/hSonj0uourMwql/gW38JXRbnAF5pfbTGUCYncvZHkAm8H4PnSD+AN/xbZEl8yqAPGA/YMcMfzWRTZnOeLBf4/CCT3Cja2MeKO+gH2zSxuKNsXRiWL4RDYFgTgtiAMMbg1UBTseDkP2W0UNhnmH9aXvhu4Nln2elx+XxumuVn0ek0DP72lGwO0sUNuJncJkpN9wRXIbcl9LkDykT237Dg57t/t/MRh6tOXqH74zEEO9bn7P3dafuEI5dPJJ3H1T9Ff6nuMWGU5acwAAAAASUVORK5CYII=" name="submit" alt="PayPal &ndash; The safer, easier way to pay online!" title="Using an open source project is incredibly fun and cheap, but we also need costs to maintain and keep them exist in the `www`."><img alt="" src="https://www.paypalobjects.com/id_ID/i/scr/pixel.gif" width="1" height="1"></p></form>' : "";
                $content = str_replace('//127.0.0.1', trim($url->host . '/' . $url->directory, '/'), $content);
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
    }
} else {
    // Force `view` value to `page`
    require __DIR__ . DS . '..' . DS . ($panel->v = ($panel->view = 'page') . 's') . DS . 'shield.php';
}