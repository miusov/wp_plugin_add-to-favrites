jQuery( document ).ready(function( $ ) {

    $(document).on('click','a.favorites-link', function (e) {

        $.ajax({
            type:'POST',
            // url:'/wp-admin/admin-ajax.php',
            url: mac_obj.url,
            data:{
                post_id:mac_obj.post_id,
                security:mac_obj.nonce,
                action:'mac_atf'
            },
            beforeSend:function(){
                $(".mac-preloader").show();
            },
            success:function (data) {
                $("p a.favorites-link").text('Remove from favorites');
                $(".mac-preloader").hide();
            },
            error:function () {
                alert('ERROR');
            }
        });
        e.preventDefault();
    })

});