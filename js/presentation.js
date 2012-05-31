
function flowsplit_reward(id,option){

    var data = {
    		action: 'flowsplit_reward',
    		id: id,
            option : option
    	};

    jQuery.post(flowsplit_ajax.ajaxurl, data, function(response) {
    });

    return true;

}

jQuery(document).ready(function($) {

    var flowsplit_content = [];
    $('.flowsplit_content').each(function(index) {

        var id = $(this).data('flowsplit_id');

        if ($.inArray(id,flowsplit_content)==-1){

            flowsplit_content.push(id);

            //How many of them?
            var flowsplit_options = [];
            $('.flowsplit_content[data-flowsplit_id='+id+']').each(function(index) {
                flowsplit_options.push($(this).data('flowsplit_option'));
            });

            var data = {
            		action: 'flowsplit_show_content',
            		id: id,
                    options : flowsplit_options
            	};

            $.post(flowsplit_ajax.ajaxurl, data, function(response) {

                eval(response);

            });

        }

    });

});

