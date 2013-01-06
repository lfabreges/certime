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
        code = $('#code');
    
    snippets.on(
        'click',
        'a',
        function(e) {
            var li = $(this).closest('li');
            li.siblings('li').removeClass('active');
            li.addClass('active');
            
            $.ajax({
                url: 'index.php?controller=repository&action=snippet',
                data: {
                    path: $(this).attr('href').substring(1)
                },
                success: function(data) {
                    code.addClass('well').html(data);
                }
            });
            
            e.preventDefault();
        }
    );
});
