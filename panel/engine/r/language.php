<?php

Language::set([
    'alert-error-*-exist' => '%s %s already exists',
    'alert-success-*-let' => '%s %s successfully deleted.',
    'alert-success-*-set' => '%s %s successfully created.',
    'alert-success-*-update' => '%s %s successfully updated.',
    'field-alt-author' => 'John Doe',
    'field-alt-content' => 'Content goes here…',
    'field-alt-css' => 'CSS goes here…',
    'field-alt-description' => 'Description goes here…',
    'field-alt-folder' => 'foo\\bar\\baz',
    'field-alt-js' => 'JavaScript goes here…',
    'field-alt-name' => 'foo-bar.baz',
    'field-alt-title' => 'Title Here',
    'field-description-author' => 'Display name.',
    'field-description-blob-to' => 'Upload to %s',
    'field-description-blob-size' => 'Maximum file size allowed to upload is %2$s.',
    'field-description-file-to' => 'Save to %s',
    'field-description-folder-to' => 'Save to %s',
    'field-description-folder-kick' => 'Redirect to folder',
    'field-description-locale' => 'This value determines the translation for date and time format. Every country has different locale name, and it might be vary on every operating system. Please consult to the administrator or search for query like &#x201C;PHP locale name for country XYZ&#x201D; with your favorite search engine.',
    'field-description-locale-error' => 'Please enable the internationalization extension on your PHP server.',
    'field-description-path-panel' => 'Select the main page that will open after you log in.',
    'field:page-type' => [
        'lot' => [
            'HTML' => 'HTML',
            'Markdown' => State::get('x.markdown') !== null ? 'Markdown' : null
        ]
    ],
    'field:user-status' => [
        'lot' => [
            0 => 'Pending',
            1 => 'Administrator',
            2 => 'Editor',
            3 => 'Member'
        ]
    ],
    'info' => ['Info', 'Info', 'Infos'],
    'license' => ['License', 'License', 'Licenses'],
    'panel' => ['Panel', 'Panel', 'Panels'],
    'slug' => ['Slug', 'Slug', 'Slugs'],
]);