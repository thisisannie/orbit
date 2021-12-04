//trigger change - select staff default posts on page load
jQuery(window).load(function() {
    jQuery('select#category-select').trigger('change').find('option:eq(0)').prop('selected', true);
    
})

//show/hide selected posts from home page header
jQuery(function() {
        jQuery('#dropdown-selector').change(function(){
            jQuery('.dropdown-selector').hide();
            jQuery('#' + jQuery(this).val()).show();
        });
});


//ajax call for staff category/city filter
jQuery(function($){
    $('select').change(function(){
        var filter = $('#filter');
        jQuery('#default-response').empty();
        $.ajax({
            url:filter.attr('action'),
            data:filter.serialize(), // form data
            type:filter.attr('method'), // POST
            beforeSend:function(xhr){
            
            },
            success:function(data){
                
                $('#response').html(data); // insert data
            }
        });
        return false;
    });
});