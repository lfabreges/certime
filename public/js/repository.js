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
    var snippets = $('#snippets'),
        snippetCode = $('#snippetCode'),
        alert = $('#alert')
    ;
    
    snippets.on(
        'click',
        'a',
        function(e) {
            $(this).closest('li').addClass('active')
                .siblings('li').removeClass('active')
            ;
            $.ajax({
                url: 'index.php?controller=repository&action=snippet',
                data: $(this).attr('href').substring(1),
                success: function(data) {
                    snippetCode.html(data).show();
                }
            });
            e.preventDefault();
        }
    );
    
    snippetCode.on(
        'click',
        'a',
        function() {
            $(this).attr('disabled', 'disabled');
        }
    );
    
    var showSnippetDeleteAlertErrorMessage = function() {
        alert.showAlert(
            'Une erreur interne empêche le bon fonctionnement de la suppression.',
            'alert-error'
        );
    };
    
    snippetCode.on(
        'click',
        'a.snippetDeleteButton',
        function(e) {
            var snippetQuery = $(this).attr('href').substring(1);
            $.ajax({
                url: 'index.php?controller=repository&action=delete',
                data: snippetQuery,
                context: $(this),
                error: function() {
                    showSnippetDeleteAlertErrorMessage();
                    $(this).removeAttr('disabled');
                },
                success: function(data) {
                    if ('1' === data) {
                        var li = snippets.find('a[href="#' + snippetQuery + '"]').closest('li')
                            liPrev = li.prev('li.nav-header')
                        ;
                        snippetCode.hide().empty();
                        if (1 === liPrev.length && 0 === li.next(':not(li.nav-header)').length) {
                            liPrev.remove();
                        }
                        li.remove();
                        if (0 === snippets.find('li').length) {
                            $('#repositoryContainer').remove();
                            $('#emptyRepositoryAlertInfo').show();
                        }
                    } else {
                        showSnippetDeleteAlertErrorMessage();
                        $(this).removeAttr('disabled');
                    }
                }
            });
            e.preventDefault();
        }
    );
});
