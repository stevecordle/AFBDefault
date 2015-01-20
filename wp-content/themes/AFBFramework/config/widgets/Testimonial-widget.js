(function($){
    $(function () {
        var list_slideshow = $(".afb-testimonials-list"),
            listItems = list_slideshow.children('li'),
            listLen = listItems.length,
            i = 0,
            changeList = function () {
                listItems.eq(i).fadeOut(300, function () {
                    i += 1;
                    if (i === listLen) {
                        i = 0;
                    }
                    listItems.eq(i).fadeIn(300);
                });
            };
        listItems.not(':first').hide();
        setInterval(changeList, 6000);
    });
})(jQuery);