// trigger change - select staff default posts on page load
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

// Accordion maps on contact page
jQuery('#accordion-maps .fl-accordion-item .fl-accordion-button').each(function(){
    jQuery(this).on('click',function(e){
        e.preventDefault();
        var city = jQuery(this).children('.fl-accordion-button-label').text().toLowerCase();
        jQuery('#maps .fl-html div').each(function(){
            jQuery(this).addClass('hidden');
        })
        jQuery('#'+city+'-map').removeClass('hidden')
    })
})

// Accordion - Change picture on item click
jQuery(window).load(function() { 
    jQuery('#accordion-image-pairs').find('.accordion-items .fl-accordion-item').on('click', function () {
        jQuery('#accordion-image-pairs').find('.accordion-images .fl-module-photo').removeClass('selected')
        jQuery('#accordion-image-pairs').find('.accordion-images .fl-module-photo').eq(jQuery(this).index()).addClass('selected');
    })
})

// Contact form label show
jQuery(window).load(function() {
    jQuery('.orbit-contact-form').on('focus click', 'input', function(){
        jQuery('.orbit-contact-form').find('label').addClass('show')
    })
})

// Contact referral button - add page title to query string
jQuery(window).load(function() {
    if(jQuery('#contact-referral-button').length) {
        let link = jQuery('#contact-referral-button').find('a')
        link.attr('href', link.attr('href') + document.title)
    }
})

// Add slug subject to hidden field in Contact Form
jQuery(window).load(function() {
    if(jQuery('#wpforms-1233-field_7').length) {
        let searchParams = new URLSearchParams(window.location.search)
        jQuery('#wpforms-1233-field_7').val(searchParams.get('subject'))

        // if subject matches select dropdown option, select it
        var desiredOption = searchParams.get('subject');
        jQuery('#wpforms-1233-field_6 option[value="' +desiredOption+ '"]').prop("selected", true)
    }
})

// Shuffle collage photos to give random selection
jQuery(function($){ 
    $.fn.shuffle = function() { 
        var allElems = this.get(),
            getRandom = function(max) {
                return Math.floor(Math.random() * max);
            },
            shuffled = $.map(allElems, function(){
                var random = getRandom(allElems.length),
                    randEl = $(allElems[random]).clone(true)[0];
                allElems.splice(random, 1);
                return randEl;
           }); 
        this.each(function(i){
            $(this).replaceWith($(shuffled[i]));
        }); 
        return $(shuffled); 
    }; 
});
jQuery(function($) {
    $('.collage .fl-module-photo').shuffle().css('visibility', 'visible')

    // delay a moment to allow opacity to animate
    setTimeout(function(){$('.collage .fl-module-photo').find('img').addClass('show')}, 5)
})

// Cards slider
jQuery(document).ajaxComplete(function(){
    jQuery('.cards-slider').not('.slick-initialized').slick({
        dots: true,
        dotsClass:'slick-dots',
        arrows: true,
        slidesToShow: 2,
        autoplay: false,
        loop: false,
        infinite: false,
        responsive: [
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
              },
            }
        ],
    });
})
