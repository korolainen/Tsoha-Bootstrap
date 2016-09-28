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
	
	flash_line = function(elem, color){
		elem.parent().parent().find('td')
			.stop()
			.css("background-color", color)
			.animate({ backgroundColor: "#ffffff"}, 
 			   {
	         	     duration: 1500,
	         	     complete: function(){
	         	    	 elem.parent().parent().find('td').removeAttr('style');
	         	     }
         	    }
		);
    	elem.removeAttr('style');
    	elem.parent().parent().find('td').removeAttr('style');
	};

	flash_success = function(elem){
		flash_line(elem, "#90EE90");
	};
	flash_error = function(elem){
		flash_line(elem, "#FFC0CB");
	};
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
        //http://stackoverflow.com/questions/15173965/serializing-and-submitting-a-form-with-jquery-post-and-php
        var values = {};
        $(this).parent().parent().find('.serialize-value').each(function(){
        	values[$(this).attr('name')] = $(this).val();
        });
        //$('body').append('<form id="hid" action="'+action+'" method="post"><input type="hidden" name="price" value="0"></form>');
        //$('#hid').submit();
        $.ajax({
            type: "POST",
            url: action,
            data: values,
            success: function(data) {
            	//console.log(data);
            	flash_success(elem);
            	var target = elem.parent().parent().find('i[aria-hidden=true]').first();
            	if(data!=target.attr('class')){
            		window.location.reload();
            	}
            },
            error: function(data) {
            	flash_error(elem);
            	console.log('Epäonnistui!');
            }
        });
    });
	
	$('.remove-inline-button').click(function(){
		
		var elem = $(this);
		elem.focusout();
        var loader_src = elem.attr('data-loader');
        elem.css({'background':'url("'+loader_src+'") 0 0 no-repeat','border':'0'});
        var action = elem.attr('data-action');
        $.ajax({url: action,
            success: function(data) {
            	flash_success(elem);
            	elem.parent().parent().remove();
            },
            error: function(data) {
            	flash_error(elem);
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
    
    $('input.new-item-focus').focus();
    
    accounts_check = function(){
    	var action = $('#accounts').attr('data-action');
    	var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    	$('#accounts input').off('input');
    	$('#accounts input').on('input', function(){
    		var elem = $(this);
    		var val = $(this).val();
    		if (val.length<=0){
    			$(this).css("background-color", "#ffffff");
    		}else if(pattern.test(val)){
    	    	$(this).css("background-color", "#ffffff");
    	    	var address = action+'?account='+encodeURIComponent(val);
    			$.ajax({url: address,
    	            success: function(data) {
    	            	if(data=='ok'){
    	            		elem.css("background-color", "#90EE90");
    	            	}else{
    	            		elem.css("background-color", "#FFC0CB");
    	            	}
    	            },
    	            error: function(data) {
    	            	console.log('Epäonnistui!');
    	            }
    	        });
    	    }else{
    	    	$(this).css("background-color", "#FFC0CB");
    	    }
    	});
    };
    
    accounts_check();
    $('#add-accountline').click(function(){
    	$('#accounts').append('<div class="add-account"><input type="text" class="form-control" name="account_name[]" value="" /></div>');
    	accounts_check();
    });
    
    
});