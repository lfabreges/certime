$(document).ready(function () {
    var codepadResult = $('#codepadResult');
    var editor = ace.edit("codepadEditor");
    
    editor.setTheme("ace/theme/github");
    editor.setShowPrintMargin(false);
    editor.getSession().setMode("ace/mode/php");
    
    editor.getSession().on(
        'change',
        function codepadEval() {
            if (typeof codepadEval.ajaxRequest !== "undefined") {
                return;
            }
            codepadEval.ajaxRequest = $.ajax({
                url: "index.php?controller=codepad&action=eval",
                data: {
                    code: editor.getValue()
                },
                success: function (data) {
                    if (data === "") {
                        codepadResult.removeClass("well");
                    } else {
                        codepadResult.addClass("well");
                    }
                    codepadResult.html(data);
                },
                complete: function () {
                    delete codepadEval.ajaxRequest;
                }
            });
        }
    );
    
    editor.focus();
});
