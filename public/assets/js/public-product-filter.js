jQuery( document ).ready(function($) {
    $( document ).on(
        'click',
        '.filter-product-btn',
        function(event) {
            event.preventDefault();
            var form_data  = $('#product-filter-form').serialize();
            var _nonce = $('.product-filter-nonce').val();
            if ( form_data ) {
                var data = {
                    'action': 'filter_product_data',
                    'form_data': form_data,
                    'product-filter-nonce': _nonce,
                };
                jQuery.post(
                    p_filter_obj.ajax_url,
                    data,
                    function(response) {
                        if ( response.status ) {
                           $('.product-filter-content-wrap').html(response.html);
                        } else {
                        }
                    }
                );
            }
        }
    );
});
