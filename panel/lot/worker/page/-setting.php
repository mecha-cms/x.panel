<?php

$__options = [];

call_user_func(function() use($language, &$__options, $__page) {
    if ($__o = a(Config::get('panel.o.page.setting.option', []))) {
        foreach ($__o as $__k => $__v) {
            if (!isset($__v)) continue;
            $__options[] = Form::checkbox($__k, isset($__v['value']) ? $__v['value'] : 1, isset($__v['is']['active']) && $__v['is']['active'], isset($__v['title']) ? $__v['title'] : (isset($language->__->panel->{$__k}) ? $language->__->panel->{$__k} : $language->{$__k}), ['classes' => ['input']]);
        }
    }
});

return implode("", [
    $__action === 'g' && count($__chops) > 1 && Get::kin($__chops[0] . 's') && call_user_func('Get::' . $__chops[0] . 's', LOT . DS . $__path, 'draft,page,archive') ? '<h4>' . $language->sort . '</h4>
<table class="table">
  <thead>
    <tr>
      <th>' . $language->order . '</th>
      <th>' . $language->by . '</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>' . Form::radio('+[sort][0]', $language->o_sort[0], isset($__parent[0]->sort[0]) ? $__parent[0]->sort[0] : (isset($__page[0]->sort[0]) ? $__page[0]->sort[0] : null), ['classes' => ['input']]) . '</td>
      <td>' . Form::radio('+[sort][1]', $language->o_sort[1], isset($__parent[0]->sort[1]) ? $__parent[0]->sort[1] : (isset($__page[0]->sort[1]) ? $__page[0]->sort[1] : null), ['classes' => ['input']]) . '</td>
    </tr>
  </tbody>
</table>
<h4>' . $language->__->panel->chunk . '</h4>
<p>' . Form::number('+[chunk]', $__page[0]->chunk, 7, ['classes' => ['input', 'block'], 'min' => 0, 'max' => 50]) . '</p>' : "",
    '<h4>' . $language->options . '</h4>
<p>' . ($__options ? implode('<br>', $__options) : $language->message_info_void($language->options)) . '</p>'
]);