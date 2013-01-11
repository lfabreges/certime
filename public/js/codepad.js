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
    var codepadResultFrame = $('#codepadResultFrame'),
        codepadSaveActionSnippetInput = $('#codepadSaveActionSnippetInput'),
        codepadSaveActionThemeInput = $('#codepadSaveActionThemeInput'),
        codepadSaveActionSubmitButton = $('#codepadSaveActionSubmitButton'),
        editor = ace.edit('codepadEditor'),
        alert = $('#alert')
    ;
    
    editor.setTheme('ace/theme/github');
    editor.setShowPrintMargin(false);
    editor.getSession().setMode('ace/mode/php');
    
    var codepadEvalFunction = function() {
        codepadResultFrame.attr(
            'src',
            'index.php?controller=codepad&action=eval&code=' + encodeURIComponent(editor.getValue())
        );
    }
        
    editor.getSession().on('change', codepadEvalFunction);
    editor.getSession().on('paste', codepadEvalFunction);
    editor.focus();
    
    if ('' === editor.getValue()) {
        editor.insert("<?php\n\n");
    }
    
    codepadSaveActionSubmitButton.one(
        'click',
        function codepadSaveActionSubmitButtonOnClick(e) {
            $(this).attr('disabled', 'disabled');
            $.ajax({
                url: 'index.php?controller=codepad&action=save',
                data: {
                    theme: codepadSaveActionThemeInput.val(),
                    snippet: codepadSaveActionSnippetInput.val(),
                    code: editor.getValue()
                },
                context: $(this),
                error: function() {
                    alert.showAlert(
                        "Une erreur interne empêche le bon fonctionnement de l'enregistrement.",
                        'alert-error'
                    );
                },
                success: function(data) {
                    if (data === '') {
                        alert.showAlert(
                            'Le snippet a été enregistré avec succès.',
                            'alert-success'
                        );
                    } else {
                        alert.showAlert(data, 'alert-error');
                    }
                },
                complete: function() {
                    $(this).removeAttr('disabled')
                        .one('click', codepadSaveActionSubmitButtonOnClick)
                    ;
                }
            });
            e.preventDefault();
        }
    );
});
