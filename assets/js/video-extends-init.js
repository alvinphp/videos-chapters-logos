(function($) {
    $(function() {
         
        if (typeof vidchlog_videoExtendsData !== 'undefined' && Array.isArray(vidchlog_videoExtendsData)) {
            vidchlog_videoExtendsData.forEach(function(video) {
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
            console.warn('');
        }
    });
})(jQuery);
