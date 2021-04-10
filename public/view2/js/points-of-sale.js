(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */
	
	var pos_form_filters			= $('form#pos_filters');

	var pos_dropdown_states 		= $('select#pos_states');
	var pos_dropdown_cities 		= $('select#pos_cities');
	var pos_dropdown_neighborhoods  = $('select#pos_neighborhoods');

	var pos_loading  				= $('.pos_loading');	

	var pos_results					= $('#pos_results');	


	/* ------------------------------------ */
	/*  BUILD
	/* ------------------------------------ */    
    var states = pos_data.pos_states;      

    pos_dropdown_cities.hide();
    pos_dropdown_neighborhoods.hide();
    pos_loading.hide();

    $.each( pos_data.pos_states, function(index, val) {
    	pos_dropdown_states.append( $("<option />").val( val._pos_state ).text( val._pos_state ) );
    });

	
	/* ------------------------------------ */
	/*  STATES DOPDOWN
	/* ------------------------------------ */
	pos_dropdown_states.on('change', function() {

		// limpa outros
		pos_dropdown_cities.value = null;
		pos_dropdown_cities.children('option').remove();
		pos_dropdown_cities.hide();
		
		pos_dropdown_neighborhoods.value = null;
		pos_dropdown_neighborhoods.children('option').remove();
		pos_dropdown_neighborhoods.hide();

		pos_loading.show();
		pos_results.hide();



		$.ajax({
			url: pos_data.ajaxurl,
			type: 		'POST',			
			dataType: 	'json',
			data: {
				action: 	'pos_getpoints',
				pos_state:  pos_dropdown_states.val()
			},
		})
		.done(function( response ) {
			// console.log( response );

			if( response.cities.length != pos_dropdown_cities.length ){

				pos_dropdown_cities.children('option').remove();

				$.each( response.cities, function(index, val) {
			    	pos_dropdown_cities.append( $("<option />").val( val._pos_city ).text( val._pos_city ) );
			    });

			    pos_dropdown_cities.prepend( $("<option />").val( null ).text( 'Selecione a cidade:' ) );

			    pos_loading.hide();
			    pos_dropdown_cities.show();
			}
				

		})
		.fail(function() {
			console.log("Erro!");
		});			
		
	});	


	/* ------------------------------------ */
	/*  CITIES DOPDOWN
	/* ------------------------------------ */
	pos_dropdown_cities.on('change', function() {

		pos_dropdown_neighborhoods.value = null;
		pos_dropdown_neighborhoods.children('option').remove();
		pos_dropdown_neighborhoods.hide();

		pos_loading.show();
		pos_results.hide();

		// SHOW RESULTS
		pos_show_points();

		$.ajax({
			url: pos_data.ajaxurl,
			type: 		'POST',			
			dataType: 	'json',
			data: {
				action: 	'pos_getpoints',
				pos_state:  pos_dropdown_states.val(),
				pos_city:   pos_dropdown_cities.val()
			},
		})
		.done(function( response ) {			

			pos_dropdown_neighborhoods.children('option').remove();

			$.each( response.neighborhoods, function(index, val) {
		    	pos_dropdown_neighborhoods.append( $("<option />").val( val._pos_neighborhood ).text( val._pos_neighborhood ) );
		    });

		    pos_dropdown_neighborhoods.prepend( $("<option />").val( null ).text( 'Selecione o bairro:' ) );

		    pos_loading.hide();
		    
		    if( Object.keys( response.neighborhoods ).length > 1 ){
		    	pos_dropdown_neighborhoods.show();
		    };
		    
		    pos_show_points();
			
		})
		.fail(function() {
			console.log("Erro!");
		});			
		
	});	


	/* ------------------------------------ */
	/*  NEIGHBORHOODS DOPDOWN
	/* ------------------------------------ */
	pos_dropdown_neighborhoods.on('change', function() {

		pos_loading.show();

		pos_show_points();

		$.ajax({
			url: pos_data.ajaxurl,
			type: 		'POST',			
			dataType: 	'json',
			data: {
				action: 			'pos_getpoints',
				pos_state:  		pos_dropdown_states.val(),
				pos_city:   		pos_dropdown_cities.val(),
				pos_neighborhood:   pos_dropdown_neighborhoods.val()
			},
		})
		.done(function( response ) {			

			// pos_results.html('');

			// $.each( response.points_of_sale, function(index, val) {
				
			// 	var pos_add_cont  = "<strong>"+val.post_title+"</strong><br />";
			// 		pos_add_cont += "<em>"+val._pos_city+"</em><br />";
			// 	var pos_address	= $('<address>').html( pos_add_cont );

			// 	pos_results.append( pos_address );
		    	
		 //    });

		 //    pos_loading.hide();
		 //    pos_results.show();
			
		})
		.fail(function() {
			console.log("Erro!");
		});			




						


		
	});	


		/**
		 * 	GET AND SHOW POINTS
		 */
		var pos_show_points = function(){
			 /**
			 * GET LIST OF POINTS VIA AJAX
			 * @type html
			 */
			$.ajax({
				url: pos_data.ajaxurl,
				type: 		'POST',			
				dataType: 	'html',
				data: {
					action: 			'pos_listpoints',
					pos_state:  		pos_dropdown_states.val(),
					pos_city:   		pos_dropdown_cities.val(),
					pos_neighborhood:   pos_dropdown_neighborhoods.val()
				},
			})
			.done(function( response ) {	

				// console.log( response );		

	   			pos_results.html( response  );
	   			pos_results.show();
				pos_loading.hide();

				// $.each( response.points_of_sale, function(index, val) {
				// 	pos_results.append( val.point_of_sale );
				// });
				
			})
			.fail(function() {
				console.log("Erro on GET LIST OF POINTS VIA AJAX!");
			});
		}
		
		

})( jQuery );