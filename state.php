<?php

return [
    // The redirect target after log-in
    'kick' => 'get/page/1',
    // The default value is `state('x.user.guard.route')` or `state('x.user.route')`
    'route' => '/panel',
    // Sync every day
    'sync' => 60 * 60 * 24,
    // Move deleted file(s) to the trash folder?
    'trash' => true
];