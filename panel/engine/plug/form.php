<?php

Form::plug('token', function($value = null) {
    return HTML::unite('input', false, [
        'name' => 'token',
        'type' => 'hidden',
        'value' => $value ?: Guardian::token()
    ]);
});