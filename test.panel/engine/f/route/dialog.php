<?php namespace x\panel\route\__test;

function dialog($_) {
    $_['title'] = 'Dialog';
    $content = <<<HTML
<p>
        <button type="button" onclick="_.dialog.alert('Hey!').then(console.log);">Alert</button>
  <button type="button" onclick="_.dialog.confirm('Are you sure?').then(console.log);">Confirm</button>
  <button type="button" onclick="_.dialog.prompt('URL:', 'http://').then(console.log);">Prompt</button>
  <button type="button" onclick="_.dialog('&lt;p&gt;This is an example of dialog content.&lt;/p&gt;&lt;p&gt;Press the &lt;kbd&gt;Escape&lt;/kbd&gt; key to exit!&lt;/p&gt;').then(console.log);">Dialog</button>
</p>
<p>Output: <output>?</output></p>
HTML;
    $lot = [];
    $lot['content-0'] = [
        'content' => $content,
        'stack' => 10,
        'type' => 'content'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}