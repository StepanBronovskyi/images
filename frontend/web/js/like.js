/**
 * Created by Степан on 06.04.2018.
 */
$(document).ready(function () {

    $('.button-like').click(function () {
        var id = {
            'id':$(this).attr('id')
        }
        $.post('/post/default/like', id, function(data) {
            $('#like-count').html(data);
        });
        return false;
    });
    $('.button-unlike').click(function () {
        var id = {
            'id':$(this).attr('id')
        }
        $.post('/post/default/unlike', id, function(data) {
            $('#like-count').html(data);
        });
        return false;
    });
})