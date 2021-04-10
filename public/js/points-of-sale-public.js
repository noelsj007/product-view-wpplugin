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


	 	$(document).ready (
			function() {
				(function setup_locations_dropdown() {
					var $states_dropdown = $('#map-pos-regions');
					var $cities_dropdown = $('#map-pos-cities');
					var $neighborhoods_dropdown = $('#map-pos-neighborhoods');
					// var $states_selectric;

					function attach_selectric_handlers($selectric) {
						$selectric.closest('.selectricWrapper').find('.selectricItems li').on (
							'click', function() {
								var $this = $(this);

								if($this.hasClass('selected')) {
									$this.closest('.selectricWrapper').find('select').change();
								}
							}
						);
					}

					function selectric_items_builder(item_data, element, index) {
						return $('<span>').text(item_data.text).html()
								+ $('<span class="ico">').text(element.attr('data-total-stores')).prop('outerHTML');
					}

					$states_dropdown.selectric({ optionsItemBuilder: selectric_items_builder });
					$cities_dropdown.selectric({ optionsItemBuilder: selectric_items_builder });
					$neighborhoods_dropdown.selectric({ optionsItemBuilder: selectric_items_builder });

					var $states_selectric = $states_dropdown.closest('.selectricWrapper').find('.selectric');
					var $cities_selectric = $cities_dropdown.closest('.selectricWrapper').find('.selectric');
					var $neighborhoods_selectric = $neighborhoods_dropdown.closest('.selectricWrapper').find('.selectric');

					attach_selectric_handlers($states_selectric);
					attach_selectric_handlers($cities_selectric);
					attach_selectric_handlers($neighborhoods_selectric);

					$states_selectric.click (
						function() {
							window.map_pos.last_clicked_dropdown = 'states';
						}
					);

					$states_dropdown.on (
						'change', function() {
							var $this = $(this);
							var value = $this.val();
							var $selected = $this.children('[value="' + value + '"]');
							var cities = JSON.parse($selected.attr('data-cities'));

							$cities_dropdown.empty();

							$.each (
								cities, function(name, city) {
									$cities_dropdown.append (
										$('<option>')
											.text(name)
											.val(name)
											.attr('data-total-stores', city['total-stores'])
											.attr('data-neighborhoods', JSON.stringify(city['neighborhoods']))
									);
								}
							);

							$cities_dropdown.selectric('refresh');
							attach_selectric_handlers($cities_selectric);

							$cities_dropdown.change();
						}
					);

					$cities_selectric.click (
						function() {
							window.map_pos.last_clicked_dropdown = 'cities';
						}
					);

					$cities_dropdown.on (
						'change', function() {
							var $this = $(this);
							var value = $this.val();
							var $selected = $this.children('[value="' + value + '"]');
							var neighborhoods = JSON.parse($selected.attr('data-neighborhoods'));

							$neighborhoods_dropdown.empty();

							$.each (
								neighborhoods, function(name, neighborhood) {
									$neighborhoods_dropdown.append (
										$('<option>')
											.text(name)
											.val(name)
											.attr('data-total-stores', neighborhood['total-stores']) 
											.attr('data-coordinates', neighborhood['coordinates'])
									);
								}
							);

							$neighborhoods_dropdown.selectric('refresh');
							attach_selectric_handlers($neighborhoods_selectric);

							$neighborhoods_dropdown.change();

							if($neighborhoods_dropdown.children().length < 2) {
								$neighborhoods_selectric.hide();
							}
							else {
								$neighborhoods_selectric.show();
							}
						}
					);

					$neighborhoods_selectric.click (
						function() {
							window.map_pos.last_clicked_dropdown = 'neighborhoods';
						}
					);

					$neighborhoods_dropdown.on (
						'change', function() {
							var $this = $(this);
							var value = $this.val();
							var $selected = $this.children('[value="' + value + '"]');
							var coordinates = $selected.attr('data-coordinates');
							var split_coordinates = coordinates.split(',', 2);


							
							$.ajax (						    
								pos_data.ajaxurl, {							
									type: 'post',

									data: { action: 'getlocations', position: coordinates, last_clicked: window.map_pos.last_clicked_dropdown },

									complete: function(xhr) {
										var response;
																				
										try {
											response = JSON.parse(xhr.responseText);

											if(!response.success) {
												throw response;
											}

											window.map_pos.setMarkers(response.markers);

											if(window.map_pos.last_clicked_dropdown === 'neighborhoods') {
												window.map_pos.setPin(new google.maps.LatLng(split_coordinates[0], split_coordinates[1]));
											}
											else {
												window.map_pos.setPin(null);
											}
										}
										catch(error) {
											console.error("Error getting markers or reconfiguring the map:", error, "Request object:", xhr);

											if(error.user_message) {
												console.error(error.user_message);
											}
											else {
												console.error("Ocorreu um erro ao buscar pontos de venda nessa região. Tente novamente mais tarde.");
											}
										}
									}
								}
							);
						}
					);
				})();

				(function setup_map_pos() {
					if( $('#maps .map').length ){

						var map_options = {
							disableDefaultUI: 	false,							
							pinIconUrl: 		pos_data.assetsurl+'images/loading.gif', // Fix this, please
							defaultIconUrl: 	pos_data.assetsurl+'images/pos-marker.png',
							defaultZoom: 		15,
							topLatitudeMargin: 	0.04,
							mapTypeControl: 	false,
							scrollwheel: 		false
						}
						window.map_pos 	= new google.maps.Map($('#maps .map')[0], map_options);

					}
					if( window.map_pos ){
						window.map_pos.infobox = new InfoBox ({
							alignBottom: true,
							pixelOffset: new google.maps.Size(-213, -82),
							closeBoxURL: ''
						});
					}else{
						return;
					}

					window.map_pos.setMarkers = function(markers_data, zoom) {
						var map = this;
						var bounds = new google.maps.LatLngBounds();
						var last_opened_marker_infobox = null;

						if(map.markers) {
							map.markers.forEach (
								function(marker) {
									marker.setMap(null);
								}
							);
						}

						map.markers = markers_data.map (
							function(marker_data) {
								var marker;
								var marker_latlng = new google.maps.LatLng(marker_data.lat, marker_data.lng);

								bounds.extend(marker_latlng);

								// console.log( marker_data.icon_url );
								//console.log( map.defaultIconUrl );

								marker = new google.maps.Marker ({
									icon: marker_data.icon_url || map.defaultIconUrl,
									position: marker_latlng,
									map: map
								});

								(function setup_infobox_opener() {
									var infobox_content_html;
									var $content_paragraph = $('<p>').html(marker_data.content);

									if(!marker_data.name && !marker_data.content) {
										return;
									}

									if(marker_data['more-info'] !== '') {
										//$content_paragraph.append($('<br>')).append( document.createTextNode(marker_data['more-info']));
										$content_paragraph.append($('<span>').html(marker_data['more-info']));
									}

									infobox_content_html =
											$('<div class="title">').text(marker_data.name).prop('outerHTML')
											+ $content_paragraph.prop('outerHTML');

									google.maps.event.addListener (
										marker, 'click', function() {
											if(marker !== last_opened_marker_infobox) {
												map.infobox.setContent(infobox_content_html);
												map.infobox.open(map, marker);
												map.panTo( marker_latlng );												
												last_opened_marker_infobox = marker;
											}
											else {
												map.infobox.close();
												last_opened_marker_infobox = null;
											}
										}
									);
								})();

								return marker;
							}
						);

						if(map.markers.length === 1) {
							map.setCenter(bounds.getCenter());
							map.setZoom(zoom || map.defaultZoom);
						}
						else
						if(map.markers.length >= 2) {
							//bounds.xa.j += map.topLatitudeMargin;
							map.fitBounds(bounds);
						}
					};

					window.map_pos.setPin = function(pin_coordinates) {
						var map = this;

						if(map.pin) {
							map.pin.setMap(null);
						}


						if(pin_coordinates !== null) {
							map.pin = new google.maps.Marker ({
								icon: map.pinIconUrl,
								position: pin_coordinates,
								map: map
							});

							map.setCenter(pin_coordinates);
							map.setZoom(map.defaultZoom);
						}
						// console.log( map );
					};
				})();

				$('#map-pos-regions').change();
			}
		);


})( jQuery );