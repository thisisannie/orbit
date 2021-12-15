//trigger change - select staff default posts on page load
jQuery(window).load(function() {
    setTimeout(function(){ 
        jQuery('select#homepagecards-category-select').on('change',function(){
        var responseDiv = jQuery('#response-row').offset().top;
        jQuery('html, body').animate({
            scrollTop: responseDiv
        }, 1000);
     });
    },500);

    jQuery('select#category-select').trigger('change').find('option:eq(0)').prop('selected', true);
    jQuery('select#dropdown-selector').trigger('change').find('option:eq(0)').prop('selected', true);
    jQuery('select#event-category-select').trigger('change').find('option:eq(0)').prop('selected', true);
    jQuery('select#homepagecards-category-select').trigger('change').find('option:eq(0)').prop('selected', true);
})



//show/hide selected posts from home page header
/*jQuery(function() {
        jQuery('#dropdown-selector').change(function(){
            jQuery('.dropdown-selector').hide();
            jQuery('#' + jQuery(this).val()).show();
        });
});*/


//ajax call for staff category/city filter
jQuery(function($){
    $('select').change(function(){
        var filter = $('#filter');
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

//ajax call for event category/city filter
jQuery(function($){
    $('select').change(function(){
        var filter = $('#filter-event');
        $.ajax({
            url:filter.attr('action'),
            data:filter.serialize(), // form data
            type:filter.attr('method'), // POST
            beforeSend:function(xhr){
            
            },
            success:function(data){
                
                $('#event-response').html(data); // insert data
            }
        });
        return false;
    });
});


//ajax call for homepage cards filter
jQuery(function($){
    $('select').change(function(){
        var cardsfilter = $('#filter-cards');
        var optionSelected = $("option:selected", this);
        $.ajax({
            url:cardsfilter.attr('action'),
            data:cardsfilter.serialize(), // form data
            type:cardsfilter.attr('method'), // POST
            beforeSend:function(xhr){
            
            },
            success:function(data){
                $('#homepagecards-title').text(optionSelected.text());
                $('#homepagecards-response').html(data); // insert data
                
            }
        });
        return false;
    });
});