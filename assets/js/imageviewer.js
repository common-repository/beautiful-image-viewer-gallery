;(function ($) {
    'use strict';

    var $image = $('#image');

    $image.viewer({
        inline: true,
        viewed: function () {
            $image.viewer('zoomTo', 1);
        }
    });

    // Get the Viewer.js instance after initialized
    var viewer = $image.data('viewer');

    // View a list of images
    $('#images').viewer();

})(jQuery);
