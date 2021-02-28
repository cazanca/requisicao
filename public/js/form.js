$(document).ready(function(){
    $(".ajax").unbind('submit').bind('submit', function(){
        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {

                if(response.success == true){
                    $(".ajax")[0].reset();
                   //$(".reload").ajax.reload(null, false);;
                }

                if (response.redirect) {
                    window.location.href = response.redirect;
                }

                $(".response").html(response.message);
                $(".floating-alert").delay(500).show(10, function(){
                    $(this).delay(3000).hide(10, function(){
                        $(this).remove();
                    })
                })
            }
        })

        return false;
    })
});