$(document).ready(function(){
    $("#saveRequest").unbind('submit').bind('submit', function(){
        var form = $(this);
        var qty = []
        var product_id = []

        $('.qty').each(function(){
            qty.push($(this).val());
        });

        $('.product_id').each(function(){
            product_id.push($(this).val());
        });

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: {qty: qty, product_id: product_id},
            dataType: 'json',
            success: function(response){

                if (response.success == true) {
                    alert("Requisição submetida com sucesso.");
                }

                if (response.redirect) {
                    window.location.href = response.redirect;
                }

                $(".ajax_response").html(response.message);
                $(".message").delay(500).show(10, function(){
                    $(this).delay(3000).hide(10, function(){
                        $(this).remove();
                    })
                })
                
            }
        });
        
        return false;
    })
});
