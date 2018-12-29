<?php

// Disable `folder` and `blob` field(s)
Config::set('panel.desk.body.tab.folder.hidden', true);
Config::set('panel.desk.body.tab.blob.hidden', true);

if (HTTP::get('view') === 'file') return;

// Set custom `file` tab’s title
Config::set('panel.desk.body.tab.file.title', $language->common);

if ($c === 's') {
    Config::set('panel.desk.body.tab.file.field.file[content]', [
        'syntax' => 'application/x-httpd-php',
        'value' => "<?php\n\nreturn [\n    // ...\n];"
    ]);
    Config::set('panel.desk.body.tab.file.field.name.placeholder', Path::N($language->field_hint_name) . '.php');
}

// Automatic field(s)
if ($file && Path::X($file) === 'php') {
    call_user_func(function() use($file) {
        extract(Lot::get(), EXTR_SKIP);
        $fields = [];
        $i = 0;
        foreach (require $file as $k => $v) {
            $fields[$key = 'file[?][' . $k . ']'] = [
                'key' => $k,
                'type' => 'text',
                'value' => $v,
                'width' => true,
                'stack' => 10 + $i
            ];
            if (is_array($v)) {
                $fields[$key]['type'] = 'source';
                $fields[$key]['syntax'] = 'application/json';
                $fields[$key]['value'] = json_encode($v);
            } else if ($v === true || $v === false) {
                $fields[$key]['type'] = 'radio[]';
                $fields[$key]['values'] = [
                    'true' => $language->yes,
                    'false' => $language->no
                ];
                unset($fields[$key]['width']);
            } else if (is_int($v) || is_float($v)) {
                $fields[$key]['type'] = 'number';
                unset($fields[$key]['width']);
            }
            $i += .1;
        }
        Config::set('panel.desk.body.tab.file.field', $fields);
        Config::set('panel.desk.body.tab.file.field.name.type', 'hidden');
        Config::reset('panel.desk.body.tab.file.field.file[content]');
    });
}