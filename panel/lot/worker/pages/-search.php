<?php echo __panel_s__('search', [
    'content' => '<form id="form.s.search" class="search" action="" method="get"><p>' . Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]) . ' ' . Form::submit(null, null, $language->search, ['classes' => ['button', 'set']]) . '</p></form>'
]); ?>