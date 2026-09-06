document.addEventListener("DOMContentLoaded", function() {
    // Verificamos que Video.js y los datos globales existan
    if (typeof videojs !== 'undefined' && typeof vidchlog_videoExtendsData !== 'undefined' && Array.isArray(vidchlog_videoExtendsData)) {
        
        // Recorremos cada reproductor registrado en la página
        vidchlog_videoExtendsData.forEach(function(item) {
            var playerId = item.videoId;
            var logoUrl = item.logoUrl;
            var markersList = item.markers;

            var player = videojs(playerId);

            // 1. Configuración del logo
            if (typeof player.logo === 'function') {
                player.logo({
                    image: logoUrl, 
                    position: 'top-right',
                    width: 130,
                    fadeDelay: 3000
                });

                player.on('play', function() {
                    if (player.logo() && typeof player.logo().show === 'function') {
                        player.logo().show();
                    }
                });

                player.on('pause', function() {
                    if (player.logo() && typeof player.logo().show === 'function') {
                        player.logo().show();
                    }
                });
            }

            // 2. Configuración de los marcadores (capítulos)
            if (typeof player.markers === 'function' && markersList && markersList.length > 0) {
                player.markers({
                    markerTip: {
                        display: true,
                        text: function(marker) {
                            return marker.text;
                        }
                    },
                    markers: markersList
                });
            }
        });
    }
});