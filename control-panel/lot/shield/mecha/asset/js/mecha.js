(function(win, doc) {
  var target, i, j;
  function apply_CodeMirror(node) {
    var w = node.offsetWidth,
        h = node.offsetHeight;
    var editor = CodeMirror.fromTextArea(node, {
      lineNumbers: true,
      matchBrackets: true,
      matchTags: {
        bothTags: true
      },
      extraKeys: {
        "Ctrl-J": "toMatchingTag",
        "F11": function (cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function (cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        }
      },
      addModeClass: true,
      autoCloseTags: true,
      autoCloseBrackets: true,
      styleActiveLine: true,
      mode: "application/x-httpd-php",
      lineWrapping: true
    });
    editor.setSize(w, h);
    win.onresize = function() {
      node.style.display = "";
      editor.display.wrapper.style.width = "auto";
      editor.setSize(node.offsetWidth, area.offsetHeight);
      node.style.display = "none";
    };
  }
  target = doc.querySelectorAll('textarea.code');
  for (i = 0, j = target.length; i < j; ++i) {
    apply_CodeMirror(target[i]);
  }
  function apply_TIB(node) {
    (new TIB(node)).create();
  }
  target = doc.querySelectorAll('input.query');
  for (i = 0, j = target.length; i < j; ++i) {
    apply_TIB(target[i]);
  }
})(window, document);