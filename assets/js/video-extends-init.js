(function($) {
    $(function() {
        if (typeof videoExtendsData !== 'undefined' && Array.isArray(videoExtendsData)) {
            videoExtendsData.forEach(function(video) {
                var $el = $('#' + video.videoId);

                if ($el.length && typeof $el.videoExtend === 'function') {
                    $el.videoExtend({
                        logo: video.logoUrl,
                        markers: video.markers
                    });
                } else {
                    console.warn('Video element or videoExtend plugin not found for ID:', video.videoId);
                }
            });
        } else {
            console.warn('videoExtendsData is not defined or invalid.');
        }
    });
})(jQuery);
