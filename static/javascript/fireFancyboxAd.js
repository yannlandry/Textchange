/* Script qui lance Fancybox pour les offres */

$(document).ready(function() {
    $("#book-picture").fancybox({
    	openEffect	: 'elastic',
    	closeEffect	: 'elastic',

    	helpers : {
    		title : {
    			type : 'inside'
    		}
    	}
    });
});