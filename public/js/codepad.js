/*
 * This file is part of Certime.
 *
 * Certime is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Certime is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Certime. If not, see <http://www.gnu.org/licenses/>.
 */

$(document).ready(function() {
    var codepadResult = $('#codepadResult'),
        editor = ace.edit("codepadEditor");
    
    editor.setTheme("ace/theme/github");
    editor.setShowPrintMargin(false);
    editor.getSession().setMode("ace/mode/php");
    
    var codepadEvalFunction = function()
    {
        if (typeof codepadEvalFunction.ajaxRequest !== "undefined") {
            return;
        }
        
        codepadEvalFunction.ajaxRequest = $.ajax({
            url: "index.php?controller=codepad&action=eval",
            data: {
                code: editor.getValue()
            },
            success: function(data) {
                codepadResult.html(data);
            },
            complete: function() {
                delete codepadEvalFunction.ajaxRequest;
            }
        });
    }
        
    editor.getSession().on('change', codepadEvalFunction);
    editor.getSession().on('paste', codepadEvalFunction);
    
    editor.focus();
    editor.insert("<?php\n\n");
});
