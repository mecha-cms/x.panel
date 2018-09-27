    <?php foreach (Config::get('panel.$.menu[]', [], true) as $k => $v): ?>
    <?php echo panel\menus($v, $k, [
        'data[]' => ['js-enter' => '#js:' . $k]
    ]); ?>
    <?php endforeach; ?>
    <?php echo Asset::js(EXTEND . '/panel/lot/asset/js/panel.js'); ?>
  </body>
</html>