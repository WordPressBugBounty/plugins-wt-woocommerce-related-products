(function ($) {
    'use strict';

    $(function () {
        const wtrp_bfcm_twenty_twenty_six_banner = {
            init: function () { 
                const data_obj = {
                    _wpnonce: wtrp_bfcm_twenty_twenty_six_banner_js_params.nonce,
                    action: wtrp_bfcm_twenty_twenty_six_banner_js_params.action,
                    wtrp_bfcm_twenty_twenty_six_banner_action_type: '',
                };

                // Only the close button hides it permanently.
                $(document).on('click', '.wtrp-bfcm-banner-2026 .notice-dismiss', function(e) {
                    e.preventDefault();
                    data_obj['wtrp_bfcm_twenty_twenty_six_banner_action_type'] = 2; /** Closed by user. */
                    
                    $.ajax({
                        url: wtrp_bfcm_twenty_twenty_six_banner_js_params.ajax_url,
                        data: data_obj,
                        type: 'POST',
                    });
                });
            }
        };
        wtrp_bfcm_twenty_twenty_six_banner.init();
    });

})(jQuery);