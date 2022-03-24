<?php namespace x\panel\route\__test;

function dialog($_) {
    $_['title'] = 'Dialog';
    $content = <<<HTML
<p>
  <button type="button" onclick="_.dialog.alert('Hey!').then(v =&gt; document.querySelector('output').value = v).catch(v =&gt; document.querySelector('output').value = v);">Alert</button>
  <button type="button" onclick="_.dialog.confirm('Are you sure?').then(v =&gt; document.querySelector('output').value = v).catch(v =&gt; document.querySelector('output').value = v);">Confirm</button>
  <button type="button" onclick="_.dialog.prompt('URL:', 'http://').then(v =&gt; document.querySelector('output').value = v).catch(v =&gt; document.querySelector('output').value = v);">Prompt</button>
  <button type="button" onclick="_.dialog('&lt;p&gt;This is an example of dialog content using raw HTML string.&lt;/p&gt;&lt;p&gt;Press the &lt;kbd&gt;Escape&lt;/kbd&gt; key to exit!&lt;/p&gt;').then(v =&gt; document.querySelector('output').value = v).catch(v =&gt; document.querySelector('output').value = v);">Dialog</button>
</p>
<p><b>Output:</b></p>
<pre><code><output>null</output></code></pre>
HTML;
    $lot = [];
    $lot['tasks'] = [
        'lot' => [
            0 => [
                '2' => ['onclick' => "return _.dialog('" . htmlspecialchars('<p>This is a sample dialog content with custom <abbr title="Hyper Text Markup Language">HTML</abbr> markup. You can insert anything here, for example, you can insert a container to load <abbr title="Hyper Text Markup Language">HTML</abbr> response from <abbr title="Asynchronous JavaScript And XML">AJAX</abbr>, etc.</p><p>Press <kbd>Escape</kbd> to cancel the dialog.</p>') . "').then(v => document.querySelector('output').value = v).catch(v => document.querySelector('output').value = v), false;"],
                'name' => false,
                'stack' => 10,
                'title' => 'Dialog (base)',
                'type' => 'button'
            ],
            1 => [
                '2' => ['onclick' => "return _.dialog.alert('Hey!').then(v => document.querySelector('output').value = v).catch(v => document.querySelector('output').value = v), false;"],
                'name' => false,
                'stack' => 20,
                'title' => 'Alert',
                'type' => 'button'
            ],
            2 => [
                '2' => ['onclick' => "return _.dialog.confirm('Are you sure?').then(v => document.querySelector('output').value = v).catch(v => document.querySelector('output').value = v), false;"],
                'name' => false,
                'stack' => 30,
                'title' => 'Confirm',
                'type' => 'button'
            ],
            3 => [
                '2' => ['onclick' => "return _.dialog.prompt('URL:', 'http://').then(v => document.querySelector('output').value = v).catch(v => document.querySelector('output').value = v), false;"],
                'name' => false,
                'stack' => 40,
                'title' => 'Prompt',
                'type' => 'button'
            ]
        ],
        'stack' => 10,
        'type' => 'tasks'
    ];
    $lot['values'] = [
        'content' => '<pre><code class="js"><output></output></code></pre>',
        'stack' => 20,
        'type' => 'content'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}