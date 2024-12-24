jQuery(document).ready(function ($) {
    // Quick View Button Click
    $.fn.showInline = function () {
        return this.css('display', 'inline-block').show();
    };
    $(document).on('click', '.awp-wc-quick-view-button, .awp-wc-nextprev-product', function (e) {
        e.preventDefault();        
        var product_id = $(this).data('product-id');
        $('#product_' + product_id).hide();
        $('#product_' + product_id + '_loader').showInline();
        $('#algo_wc_qv_container').find('#algo_wc_loader_overlay').addClass("algo_wc_qc_loader_show");

        $.ajax({
            url: awp_wc_qv_ajax.ajaxurl,
            type: 'POST',
            data: {
                action: 'awp_wc_quick_view',
                nonce: awp_wc_qv_ajax.nonce,
                product_id: product_id
            },
            success: function (response) {
                $('#product_' + product_id).show();
                $('#product_' + product_id + '_loader').hide();
                if (response.success) {
                    let data = '';
                    $('#algo_wc_qv_container').find('#algo_wc_loader_overlay').removeClass("algo_wc_qc_loader_show");
                    if (awp_wc_qv_ajax && awp_wc_qv_ajax.algo_wc_quick_view_mode && awp_wc_qv_ajax.algo_wc_quick_view_mode == "drawer" && awp_wc_qv_ajax.algo_wc_right_arrow) {

                        data = '<div class="awp-wc-close-drawer"><i class="fa fa-chevron-right" aria-hidden="true" style="font-size:12px;"></i></div><div class="awp-wc-quick-view-drawer-scroll"><div id="algo_wc_loader_overlay" class="awp-wc-loader-overlay"><div class="awp-wc-loader"></div></div>' + response.data + '</div>';
                        $('.awp-wc-quick-view-drawer').html(data).addClass('open');

                    } else if (awp_wc_qv_ajax && awp_wc_qv_ajax.algo_wc_quick_view_mode && awp_wc_qv_ajax.algo_wc_quick_view_mode == "popup" && awp_wc_qv_ajax.algo_wc_cross_arrow) {
                        document.documentElement.classList.add('awp-wc-open-modal'); //For stop background scrolling
                        data = '<div class="awp-wc-quick-view-overlay"></div>' +
                            '<div class="awp-wc-quick-view-popup-content">' +
                            '<div class="awp-wc-quick-view-popup-scroll"><div class="awp-wc-close-popup"><i class="fas fa-times"></i></div><div id="algo_wc_loader_overlay" class="awp-wc-loader-overlay"><div class="awp-wc-loader"></div></div>' + response.data + '</div>' +
                            '</div>';
                        $('.awp-wc-quick-view-popup').html(data).addClass('open');                        
                    }
                    //flexslider
                    if(awp_wc_qv_ajax && awp_wc_qv_ajax.flexslider && awp_wc_qv_ajax.flexslider!="0"){
                        $('.flexslider').flexslider({
                            animation: "slide",
                            // controlNav: "thumbnails",
                            // animation: "slide", // Choose animation type: "fade" or "slide"
                            // controlNav: true,   // Enable navigation dots
                            // directionNav: true, // Enable next/prev arrows
                            // animationSpeed: 600, // Animation speed in ms
                            // slideshow: true,    // Auto-slide
                            // itemMargin: 5       // Margin between items
                        });
                    }
                } else {
                    alert(response.data);
                }
            }
        });
    });

    // Close Drawer and Popup
    $(document).on('click', '.awp-wc-close-drawer, .awp-wc-close-popup, .awp-wc-quick-view-overlay', function () {
        $('.awp-wc-quick-view-drawer, .awp-wc-quick-view-popup').removeClass('open');
        document.documentElement.classList.remove('awp-wc-open-modal'); //For stop background scrolling
    });

});
