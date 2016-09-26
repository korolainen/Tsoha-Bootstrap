/**
 * http://bootsnipp.com/snippets/featured/panel-tables-with-filter
*   I don't recommend using this plugin on large tables, I just wrote it to make the demo useable. It will work fine for smaller tables 
*   but will likely encounter performance issues on larger tables.
*
*		<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers" />
*		$(input-element).filterTable()
*		
*	The important attributes are 'data-action="filter"' and 'data-filters="#table-selector"'
*/
(function(){
    'use strict';
	var $ = jQuery;
	$.fn.extend({
		filterTable: function(){
			return this.each(function(){
				$(this).on('keyup', function(e){
					$('.filterTable_no_results').remove();
					var $this = $(this), 
                        search = $this.val().toLowerCase(), 
                        target = $this.attr('data-filters'), 
                        $target = $(target), 
                        $rows = $target.find('tbody tr');
					if(search == '') {
						$rows.show(); 
					} else {
						$rows.each(function(){
							var $this = $(this);
							var $target = $(this).find('a[data-filter="target"]');
							$target.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
						})
						if($target.find('tbody tr:visible').size() === 0) {
							var col_count = $target.find('tr').first().find('td').size();
							var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'"> - </td></tr>')
							$target.find('tbody').append(no_results);
						}
					}
				});
			});
		}
	});
})(jQuery);

$(document).ready(function(){
	$('.pickdatehere').datepicker({
		dateFormat: "dd.mm.yy"
	});
	$('[data-action="filter"]').filterTable();
	$('button.edit-item-toggle').click(function(){
        $(".edit-toggle-hidden").slideToggle("slow");
        $(".edit-toggle-block").slideToggle("slow");
    });
	$('.save-inline-button').click(function(){
		var elem = $(this);
		elem.focusout();
        var loader_src = elem.attr('data-loader');
        elem.css({'background':'url("'+loader_src+'") 0 0 no-repeat','border':'0'});
        var action = elem.attr('data-action');
        /*http://stackoverflow.com/questions/15173965/serializing-and-submitting-a-form-with-jquery-post-and-php*/
        var message = $.param($(this).parent().parent().find(':input'));
        $.ajax({
            type: "POST",
            url: action,
            data: message,
            success: function(data) {
            	console.log(data);
            	elem.parent().parent().find('td').stop().css("background-color", "#90EE90").animate({ backgroundColor: "#ffffff"}, 
            			   {
		            	     duration: 1500,
		            	     complete: function(){
		            	    	 elem.parent().parent().find('td').removeAttr('style');
		            	     }
		            	    });
            	elem.removeAttr('style');
            },
            error: function() {
            	elem.parent().parent().find('td').stop().css("background-color", "#FFC0CB").animate({ backgroundColor: "#ffffff"}, 
         			   {
		           	     duration: 1500,
		           	     complete: function(){
		           	    	 elem.parent().parent().find('td').removeAttr('style');
		           	     }
		           	    });
            	elem.removeAttr('style');
            	elem.parent().parent().find('td').removeAttr('style');
            	console.log('Epäonnistui!');
            }
        });
    });
    $('input.inline-edit').on('input', function(){
    	$(this).parent().parent().find('td').css("background-color", "#FFFF9C");
    });
    $('#item-search').on('input', function(){
    	var val = $(this).val();
    	$($(this).attr('data-target')).val(val);
    	if(val.length>0) {
    		$('#add-item-button').removeClass('btn-default');
    		$('#add-item-button').addClass('btn-success');
    	}else{
    		$('#add-item-button').removeClass('btn-success');
    		$('#add-item-button').addClass('btn-default');
    	}
    });
    $('button#add-item-button').click(function(){
    	var id = $(this).attr('data-focus');
        $(".add-toggle-hidden").slideToggle("fast", "swing", function(){
        	$(".add-toggle-block").slideToggle("fast", "swing", function(){
        		$(id).focus();
        	});
        });
        
        
    });
});