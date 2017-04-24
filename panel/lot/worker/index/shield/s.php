<?php

if (!Request::get('token')) {
	Shield::abort(PANEL_404);
}
$__c = require STATE . DS . 'config.php';
$__c['shield'] = Path::B($__path);
if (!Message::$x) {
	File::export($__c)->saveTo(STATE . DS . 'config.php', 0600);
	Message::success(To::sentence($language->updateed));
	Guardian::kick(str_replace('::s::', '::g::', Path::D($url->current)));
}