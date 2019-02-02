<?php

Config::set('panel.+.form.editor', true);

$c = $panel->c;
$x = $file ? pathinfo($file, PATHINFO_EXTENSION) : null;
$is_file = is_file($file) ? mime_content_type($file) : "";
$is_file_text = $is_file && ($is_file === 'inode/x-empty' || strpos($is_file, 'text/') === 0 || strpos(',' . TEXT_X . ',', ',' . $x . ',') !== false);

if ($c !== 's' && !file_exists($file)) {
    Config::set('panel.error', true);
    return;
}

Config::set('panel.desk', [
    'header' => null,
    'body' => [
        'tab' => [
            'file' => [
                'field' => [
                    'file[content]' => $c === 's' || $is_file_text ? [
                        'key' => 'content',
                        'type' => 'source',
                        'value' => $is_file_text ? file_get_contents($file) : null,
                        'placeholder' => $language->field_hint_file_content,
                        'width' => true,
                        'height' => true,
                        'stack' => 10
                    ] : [
                        'key' => 'content',
                        'title' => false,
                        'type' => 'content',
                        'value' => ($is_file && strpos($is_file, 'image/') === 0 ? HTML::img($file, "", ['style[]' => ['display' => 'block']]) : '<pre><code class="language-yaml">TODO</code></pre>') . Form::hidden('file[read-only]', 1),
                        'stack' => 10
                    ],
                    'file[consent]' => [
                        'key' => 'consent',
                        'type' => 'text',
                        'hidden' => true,
                        'stack' => 0
                    ],
                    'name' => [
                        'type' => 'text',
                        'pattern' => '^[_.-]?[a-z\\d]+(-[a-z\\d]+)*' . ($is_file || $c === 's' ? '\\.[a-z\\d]+' : "") . '$',
                        'value' => $c === 'g' ? basename($file) : null,
                        'placeholder' => $c === 's' ? $language->field_hint_name : null,
                        'width' => true,
                        'stack' => 10.1
                    ]
                ],
                'stack' => 10
            ],
            'folder' => [
                'field' => [
                    'directory' => [
                        'title' => $language->path,
                        'description' => $language->field_description_directory[$c === 's' && HTTP::get('tab.0') === 'folder' ? 0 : 1],
                        'type' => 'text',
                        'pattern' => '^[_.-]?[a-z\\d]+(-[a-z\\d]+)*([\\\/][_.-]?[a-z\\d]+(-[a-z\\d]+)*)*$',
                        'value' => null,
                        'placeholder' => strtr($language->field_hint_directory, '/', DS),
                        'width' => true,
                        'stack' => 10
                    ]
                ],
                'stack' => 10.1
            ],
            'blob' => !$is_file ? [
                'title' => $language->upload,
                'field' => [
                    'blob' => [
                        'key' => 'file',
                        'type' => 'blob',
                        'stack' => 10
                    ],
                    'package' => Extend::exist('package') ? [
                        'title' => false,
                        'type' => 'toggle[]',
                        'block' => true,
                        'values' => (array) $language->o_package,
                        'stack' => 10.1
                    ] : null
                ],
                'stack' => 10.2
            ] : null
        ]
    ],
    'footer' => [
        'tool' => [
            's' => [
                'title' => $language->{$c === 's' ? 'create' : 'update'},
                'name' => 'a',
                'value' => 1,
                'stack' => 10
            ],
            // Only user with status `1` that has delete access
            'r' => $c === 'g' && $user->status === 1 ? [
                'title' => $language->delete,
                'name' => 'a',
                'value' => -2,
                'stack' => 10.1
            ] : null
        ]
    ]
]);

if (HTTP::get('tab.0') === 'blob' && (HTTP::is('get', 'tabs.0') && !HTTP::get('tabs.0'))) {
    Config::set('panel.desk.footer.tool.s.title', $language->upload);
}