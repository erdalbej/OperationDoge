$(document).ready(function(){

        if (!Modernizr.touch && !Modernizr.inputtypes.date) {
            $('input[type=date]').datepicker({ dateFormat: 'yy-mm-dd'}).on('change', function(){
                console.log(this);
                $(this).attr('value', this.value)
                console.log("change");
            });

            $('input[type=date]').datepicker('setDate', new Date());
        }

       $("#updatedTime").html("Sidan uppdaterades senast: " + document.lastModified);
       $("#mobile-menu").click(function() {
          $( "nav" ).toggle();
        });
       $(window).on('resize', function(){
            var win = $(this);
            if (win.width() >= 768) { $("nav").show(); }
        });
   });