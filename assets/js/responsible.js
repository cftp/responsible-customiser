(function($){
    $(document).ready(function(){

        // Add the device links
        $("#customize-theme-controls").before(responsible.html);

        // Deal with a clicked device link
        $(".responsible-size").click(function(e){
            e.preventDefault();

            // Pull the keys out of the sizes object
            var keys = [];
            for(var k in responsible.sizes) keys.push(k);

            // Loop through the provided sizes
            for (i = 0; i < keys.length; ++i) {

                // Is this size applicable to the clicked link?
                if ( $(e.currentTarget).hasClass( keys[i] ) ) {

                    // Get current state of the link
                    var st = {
                        cl: keys[i],
                        hr: $(e.currentTarget).attr('href'),
                        ro: $(e.currentTarget).find('.fa').hasClass('fa-rotate-270')
                    };
                    // We may already be rotated from a previous selection
                    if ( st.ro ) {

                        // We are,so take the dimensions from the 'alt'
                        var new_w = responsible.sizes[ responsible.sizes[ st.cl ].alt ].width;
                        var new_h = responsible.sizes[ responsible.sizes[ st.cl ].alt ].height;

                        // Switch the href
                        $(e.currentTarget).attr('href', st.cl );

                    } else {

                        var new_w = responsible.sizes[ st.cl ].width;
                        var new_h = responsible.sizes[ st.cl ].height;

                        // Switch the href
                        $(e.currentTarget).attr('href', responsible.sizes[ st.cl ].alt );

                    }

                    // Change iframe dimensions regardless
                    $("#customize-preview iframe").animate(
                        {
                            width: new_w,
                            height: new_h
                        },
                        500
                    );

                    // Set an active flag for use the hover action
                    $(".responsible-size").removeClass('active'); // clear existing active links
                    $(e.currentTarget).addClass('active');
                    $(e.currentTarget).addClass('norotate');

                    // Is there an alternative?
                    if ( responsible.sizes[ keys[i] ].hasOwnProperty('alt') ) {

                        // Yes, but do we need to change the link?
                        $(e.currentTarget).attr('href', responsible.sizes[ keys[i] ].alt );
                    }

                    // Don't cycle anymore else we'll get jumpy nonsense
                    break;
                }

            }
        });

        // Make the icons rotate on hover
        $(".responsible-size").hover(function(e){

            // Only rotate icons that have alternative aspects
            if ( responsible.sizes[ $(e.currentTarget).attr('href') ].hasOwnProperty('alt') ) {

                // Don't rotate unless we're hovering on the currently active device size
                // nor if this is the first mouseout since clicking a device size
                if ( $(e.currentTarget).hasClass('active') && ! $(e.currentTarget).hasClass('norotate') ){

                    if ( $(e.currentTarget).find('.fa').hasClass('fa-rotate-270') ) {
                        $(e.currentTarget).find('.fa').removeClass('fa-rotate-270')
                    } else {
                        $(e.currentTarget).find('.fa').addClass('fa-rotate-270')
                    }

                } else if ( $(e.currentTarget).hasClass('norotate') ) {
                    $(e.currentTarget).removeClass('norotate');
                }

            }

        });
    });
})(jQuery);