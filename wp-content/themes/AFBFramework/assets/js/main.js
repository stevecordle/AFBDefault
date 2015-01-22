;(function($){
    $(document).ready(function(){
        var App = {};
        
        App.init = function(){ 
            //Setup mobile nav
            $("#mmobile-nav").mmenu({
                offCanvas: {
                    position  : "left",
                    zposition : "front"
                }
            });
        };
        
        App.init();
        
    });
})(jQuery);