jQuery(function() {
        jQuery('#dropdown-selector').change(function(){
            jQuery('.dropdown-selector').hide();
            jQuery('#default-text').hide();
            jQuery('#' + jQuery(this).val()).show();
        });
});