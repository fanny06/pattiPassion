$(document).ready(function() {
    $('.commentdel').click(function(){
        $.ajax({
           url: $('.commentdel').data('path'),
           type: 'POST',
           data: {comment: $('.commentdel').data('comment')},
           success: function(data){
               $('#comment'+$('.commentdel').data('comment')).remove();
           }
        });
    });
});