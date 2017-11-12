/**
 * Created by jakson on 17.02.17.
 */
$(document).ready(function() {

    $('body').on('click', '.show_more', function(event){
        event.preventDefault();
        var self = $(this);
        var offset = self.data('offset');
        var data = {
            action: 'show_more',
            offset: offset
        };
        $.post( 'ajax.php', data, function(response) {
            self.attr('data-offset', offset+5);
            $(response).insertAfter($('.container article:last'));
            console.log(response);
        });
    });

    $('body').on('click', '.show_more_cabinet', function(event){
        event.preventDefault();
        var self = $(this);
        var offset = self.data('offset');
        var data = {
            action: 'show_more_cabinet',
            offset: offset
        };
        $.post( 'ajax.php', data, function(response) {
            self.attr('data-offset', offset+5);
            $(response).insertAfter($('.container article:last'));
            console.log(response);
        });
    });

    $('body').on('click', '.set_like', function(event){
        event.preventDefault();
        var self = $(this);
        var data = {
            action: 'set_like',
            article: self.data('article'),
            user: self.data('user')
        };
        $.post( 'ajax.php', data, function(response) {
            if(response == 'pluse'){
                var old = self.find('.like').text();
                self.find('.like').text(parseInt(old)+1);
            }else if(response == 'mines'){
                var old = self.find('.like').text();
                self.find('.like').text(parseInt(old)-1);
            }
            console.log(response);
        });
    });



    $('#myModal').modal('show');
});