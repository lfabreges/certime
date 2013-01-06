$(document).ready(function () {
    var codepadResult = $('#codepadResult');
    $('#codepadText').one(
        'keyup',
        function codepadEval() {
            $.ajax({
                url: "index.php?controller=codepad&action=eval",
                data: $(this).serialize(),
                context: $(this),
                success: function (data) {
                    if (data === "") {
                        codepadResult.removeClass("well");
                    } else {
                        codepadResult.addClass("well");
                    }
                    codepadResult.html(data);
                },
                complete: function () {
                    $(this).one('keyup', codepadEval);
                }
            });
        }
    );
});
