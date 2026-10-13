
jQuery(document).ready(function ($) {

    $(document).on('click', '.vidchlog-eliminar-video', function (e) {
        e.preventDefault();

        const boton = $(this);
        const idVideo = boton.data('id');

        if (!confirm('¿Seguro que deseas eliminar este video?')) {
            return;
        }

        boton.prop('disabled', true);

        $.ajax({
            url: vidchlogAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'vidchlog_eliminar_video',
                nonce: vidchlogAjax.nonce,
                idvideo: idVideo
            },
            success: function (respuesta) {

                if (respuesta.success) {
                    boton.closest('tr').remove();
                } else {
                    alert(
                        respuesta.data.message ||
                        'No se pudo eliminar el video.'
                    );

                    boton.prop('disabled', false);
                }

            },
            error: function () {
                alert('Error de comunicación con el servidor.');
                boton.prop('disabled', false);
            }
        });

    });

});
