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
    var codepadResultContainer = $('#codepadResultContainer'),
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
        if ('undefined' !== typeof codepadEvalFunction.ajaxRequest) {
            return;
        }
        codepadEvalFunction.ajaxRequest = $.ajax({
            url: 'index.php?controller=codepad&action=eval',
            data: {
                code: editor.getValue()
            },
            success: function(data) {
                codepadResultContainer.html(data);
            },
            complete: function() {
                delete codepadEvalFunction.ajaxRequest;
            }
        });
    }
        
    editor.getSession().on('change', codepadEvalFunction);
    editor.getSession().on('paste', codepadEvalFunction);
    editor.focus();
    
    if ('' === editor.getValue()) {
        editor.insert("<?php\n\n");
    }
    
    var showAlert = function(message, type) {
        if ('undefined' !== typeof showAlert.timeout) {
            clearTimeout(showAlert.timeout);
        }
        alert.html(message)
            .removeClass('alert-error alert-success alert-info')
            .addClass(type)
        ;
        showAlert.timeout = setTimeout(
            function() {
                alert.fadeOut();
            },
            5000
        );
    };
    
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
                    showAlert(
                        "Une erreur interne empêche le bon fonctionnement de l'enregistrement.",
                        'alert-error'
                    );
                },
                success: function(data) {
                    if (data === '') {
                        showAlert(
                            'Le snippet a été enregistré avec succès.',
                            'alert-success'
                        );
                    } else {
                        showAlert(data, 'alert-error');
                    }
                    alert.fadeIn();
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
