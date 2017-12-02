<?php

$__options = [];

call_user_func(function() use($language, &$__options, $__page) {
    if ($__o = a(Config::get('panel.o.page.toggle', []))) {
        foreach ($__o as $__k => $__v) {
            if (!$__v || isset($__v['hidden']) && $__v['hidden']) {
                continue;
            }
            $__kk = ltrim($__k, '.!*');
            $__options[] = Form::checkbox($__k, isset($__v['value']) ? $__v['value'] : 1, isset($__v['active']) && $__v['active'], isset($__v['text']) ? $__v['text'] : (isset($language->o_toggle->{$__kk}) ? Language::get('o_toggle.' . $__kk) : $language->{$__kk}), ['class[]' => ['input']]);
        }
    }
});

return implode("", [
    $__command === 'g' && count($__chops) > 1 && Get::_($__chops[0] . 's') && call_user_func('Get::' . $__chops[0] . 's', LOT . DS . $__path, 'draft,page,archive') ? '<h4>' . $language->sort . '</h4>
<table class="table">
  <thead>
    <tr>
      <th>' . $language->order . '</th>
      <th>' . $language->by . '</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>' . Form::radio('+[sort][0]', $language->o_sort[0], isset($__parent[0]->sort[0]) ? $__parent[0]->sort[0] : (isset($__page[0]->sort[0]) ? $__page[0]->sort[0] : null), ['class[]' => ['input']]) . '</td>
      <td>' . Form::radio('+[sort][1]', $language->o_sort[1], isset($__parent[0]->sort[1]) ? $__parent[0]->sort[1] : (isset($__page[0]->sort[1]) ? $__page[0]->sort[1] : null), ['class[]' => ['input']]) . '</td>
    </tr>
  </tbody>
</table>
<h4>' . $language->chunk . '</h4>
<p>' . Form::number('+[chunk]', $__page[0]->chunk, 7, ['class[]' => ['input', 'width'], 'min' => 0, 'max' => 50]) . '</p>' : "",
    '<h4>' . $language->options . '</h4>
<p>' . ($__options ? implode('<br>', $__options) : $language->message_info_void($language->options)) . '</p>'
]);