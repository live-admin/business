(function (window, undefined) {
    if (window.KindEditorCopy) {
        return;
    }
    if (!window.console) {
        window.console = {};
    }
    if (!console.log) {
        console.log = function () {
        };
    }
    var K = window.KindEditor;

    function _getEditor(id) {
        for (var n in K.instances) {
            var src = K.instances[n].srcElement.get();
            if (src.id == id)
                return K.instances[n];
        }
        return null;
    }

    function _copy(editorId, textAreaId) {
        var editor = _getEditor(editorId);
        if (editor != null)
            K('#' + textAreaId).val(K.trim(editor.html().replace(/<.*?>/ig, '').replace(/&nbsp;/ig, ' ')));
    }

    function _copyConfirm(editorId, textAreaId, title, message) {
        var dialog = K.dialog({
            width: 500,
            title: title,
            body: '<div style="margin:10px;"><strong>' + message + '</strong></div>',
            closeBtn: {
                name: '关闭',
                click: function (e) {
                    dialog.remove();
                }
            },
            yesBtn: {
                name: '是',
                click: function (e) {
                    _copy(editorId, textAreaId);
                    dialog.remove();
                }
            },
            noBtn: {
                name: '否',
                click: function (e) {
                    dialog.remove();
                }
            }
        });
    };
    window.KindEditorCopy = function (editorId, textAreaId, title, message) {
        _copyConfirm(editorId, textAreaId, title, message);
    };
})(window);