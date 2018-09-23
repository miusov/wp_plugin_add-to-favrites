jQuery( document ).ready(function( $ ) {

    $(document).on('click','p.favorites-link a', function (e) {
        var action = $(this).data('action');
        $.ajax({
            type:'POST',
            // url:'/wp-admin/admin-ajax.php',
            url: mac_obj.url,
            data:{
                post_id:mac_obj.post_id,
                security:mac_obj.nonce,
                post_action: action,
                action:'mac_action'
            },
            beforeSend:function(){
                $(".mac-preloader").show();
            },
            success:function (data) {
                $("p.favorites-link a").html(data);
                $(".mac-preloader").hide();
            },
            error:function () {
                alert('ERROR');
            }
        });
        e.preventDefault();
    })

});