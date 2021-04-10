(function( $ ) {
	'use strict';

	/**
	 * All of the code for your Dashboard-specific JavaScript source
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

	$(document).ready(function($) {

		//Initialize Global Variables		
		var marker;
		var markers = [];
		var infoWindow;
		var geocoder;
		var map = null;
		var lat;
		var lon;

		var pos_state 			= $('#_pos_state');
		var pos_city 			= $('#_pos_city');
		var pos_neighborhood 	= $('#_pos_neighborhood'); 
		var pos_street 			= $('#_pos_street');   
		var pos_number 			= $('#_pos_number');   
		var pos_postal_code 	= $('#_pos_postal_code');    

		var pos_lat 			= $('#_pos_latitude');
		var pos_long 			= $('#_pos_longitude');
 
		var mapOptions = {
	        zoom: 16,
	        mapTypeId: google.maps.MapTypeId.ROADMAP,
	        streetViewControl: false,
	        panControl: false,
	        mapTypeControl: true,
	        mapTypeControlOptions: {
	            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
	            position: google.maps.ControlPosition.BOTTOM_CENTER
	        },
	        zoomControl: true,
	        zoomControlOptions: {
	            style: google.maps.ZoomControlStyle.SMALL,
	            position: google.maps.ControlPosition.LEFT_CENTER
	        }
	    };
	    map = new google.maps.Map( document.getElementById('pos_locationpicker'), mapOptions);
		    
	    lat = -28.78586119043712;
	    lon = -51.59428596496582;
	    
	    // Determine your initial location through GPS
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(function(position) {
	            lat = position.coords.latitude;
	            lon = position.coords.longitude;
	        });
	    }
	    
	    var latLng = new google.maps.LatLng(lat, lon);       
		
		// Create the search box and link it to the UI element.
		var input = ( document.getElementById('pos_search_address') );
		map.controls[ google.maps.ControlPosition.TOP_LEFT ].push(input);

		var searchBox = new google.maps.places.SearchBox((input));









		// Listen for the event fired when the user selects an item from the
		// pick list. Retrieve the matching places for that item.
	    google.maps.event.addListener(searchBox, 'places_changed', function() {
		    var places = searchBox.getPlaces();

		    if (places.length == 0) {
		      return;
		    }
		    for (var i = 0, marker; marker = markers[i]; i++) {
		      marker.setMap(null);
		    }

		    // For each place, get the icon, place name, and location.
		    markers = [];
		    var bounds = new google.maps.LatLngBounds();
		    for (var i = 0, place; place = places[i]; i++) {
		      // var image = {
		      //   url: place.icon,
		      //   size: new google.maps.Size(71, 71),
		      //   origin: new google.maps.Point(0, 0),
		      //   anchor: new google.maps.Point(17, 34),
		      //   scaledSize: new google.maps.Size(25, 25)
		      // };

		      // // Create a marker for each place.
		      // var marker = new google.maps.Marker({
		      //   map: map,
		      //   icon: image,
		      //   title: place.name,
		      //   position: place.geometry.location
		      // });
	    		var image = '/onde-encontrar/images/mapMark.png';
			    var marker = new google.maps.Marker({
			        position: place.geometry.location,
			        title: 'Arraste este pino',
			        animation: google.maps.Animation.DROP,
			        map: map,
			        icon: image,
			        draggable: true
			    });

		      	markers.push(marker);

		      	bounds.extend(place.geometry.location);
		    }

		    map.fitBounds(bounds);
		});

		// Bias the SearchBox results towards places that are within the bounds of the
		// current map's viewport.
		google.maps.event.addListener(map, 'bounds_changed', function() {
		    var bounds = map.getBounds();
		    searchBox.setBounds(bounds);
		});


        
		
		// function initialize() {
		    
		//     var mapOptions = {
		//         zoom: 16,
		//         mapTypeId: google.maps.MapTypeId.ROADMAP,
		//         streetViewControl: false,
		//         panControl: false,
		//         mapTypeControl: true,
		//         mapTypeControlOptions: {
		//             style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
		//             position: google.maps.ControlPosition.BOTTOM_CENTER
		//         },
		//         zoomControl: true,
		//         zoomControlOptions: {
		//             style: google.maps.ZoomControlStyle.SMALL,
		//             position: google.maps.ControlPosition.LEFT_CENTER
		//         }
		//     };
		    
		//     map = new google.maps.Map(document.getElementById('pos_locationpicker'), mapOptions);
		    
		//     lat = -28.78586119043712;
		//     lon = -51.59428596496582;
		    
		//     // Determine your initial location through GPS
		//     if (navigator.geolocation) {
		//         navigator.geolocation.getCurrentPosition(function(position) {
		//             lat = position.coords.latitude;
		//             lon = position.coords.longitude;
		//         });
		//     }
		    
		//     var latLng = new google.maps.LatLng(lat, lon);
		//     var image = '/onde-encontrar/images/mapMark.png';
		//     map.setCenter(latLng);
		    // marker = new google.maps.Marker({
		    //     position: latLng,
		    //     title: 'Arraste este pino',
		    //     animation: google.maps.Animation.DROP,
		    //     map: map,
		    //     icon: image,
		    //     draggable: true
		    // });
		    
		//     infoWindow = new google.maps.InfoWindow({
		//         content: "<div class='place'>Arraste este pino para o local do Ponto de Venda/Distribuidor</div>"
		//     });		    
		//     infoWindow.open(map, marker);



			
		    

		//     var markers = [];			
		// 	// var defaultBounds = new google.maps.LatLngBounds(
		// 	// 	new google.maps.LatLng(-33.8902, 151.1759),
		// 	// 	new google.maps.LatLng(-33.8474, 151.2631)
		// 	// );
		// 	// map.fitBounds(defaultBounds);

		// 	// Create the search box and link it to the UI element.
		// 	var input = (document.getElementById('pos_search_address'));
		// 	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

		// 	var searchBox = new google.maps.places.SearchBox((input));


		// 	// [START region_getplaces]
		// 	// Listen for the event fired when the user selects an item from the
		// 	// pick list. Retrieve the matching places for that item.
		// 	google.maps.event.addListener(searchBox, 'places_changed', function() {
		// 		var places = searchBox.getPlaces();

		// 		if (places.length == 0) {
		// 		  	return;
		// 		}	
		// 		for (var i = 0, marker; marker = markers[i]; i++) {
		// 		  	marker.setMap(null);
		// 		}

		// 		// For each place, get the icon, place name, and location.
		// 		markers = [];
		// 		var bounds = new google.maps.LatLngBounds();
		// 		for (var i = 0, place; place = places[i]; i++) {				 

		// 			// Create a marker for each place.
		// 			// var marker = new google.maps.Marker({
		// 			//   map: map,
		// 			//   icon: image,
		// 			//   title: place.name,
		// 			//   position: place.geometry.location
		// 			// });

		// 			var marker = new google.maps.Marker({
		// 		        position: place.geometry.location,
		// 		        title: place.name,
		// 		        animation: google.maps.Animation.DROP,
		// 		        map: map,
		// 		        icon: '/onde-encontrar/images/mapMark.png',
		// 		        draggable: true
		// 		    });
				    
		// 		  	markers.push(marker);
		// 		  	//Update postal address when the marker is dragged
		// 		    google.maps.event.addListener(marker, 'dragend', reloadAddress);
		// 		    // Close the marker window when being dragged
		// 		    google.maps.event.addListener(marker, 'dragstart', function() {
		// 		        infoWindow.close(map, marker);
		// 		    });
				    
		// 		  	bounds.extend(place.geometry.location);
		// 		}

		// 		map.panTo(marker.getPosition());
		// 		map.fitBounds(bounds);
		// 	});
		// 	// [END region_getplaces]




		// 	// Bias the SearchBox results towards places that are within the bounds of the
		// 	// current map's viewport.
		// 	google.maps.event.addListener(map, 'bounds_changed', function() {
		// 		var bounds = map.getBounds();
		// 		searchBox.setBounds(bounds);
		// 	});
		    
		//     //Update postal address when the marker is dragged
		//     google.maps.event.addListener(marker, 'dragend', reloadAddress );
		    
		//     // Close the marker window when being dragged
		//     google.maps.event.addListener(marker, 'dragstart', function() {
		//         infoWindow.close(map, marker);
		//     });
		// }
		 
		// google.maps.event.addDomListener(window, 'load', initialize);



		// function reloadAddress(){

		// 	geocoder = new google.maps.Geocoder();

	 //        geocoder.geocode({latLng: marker.getPosition()}, function(responses) {
	 //            if (responses && responses.length > 0) {

		//         	//console.log( responses );

	 //            	// PREENCHE OS CAMPOS
	 //            	pos_state.val( 			responses[0].address_components[5].long_name );
	 //            	pos_city.val( 			responses[0].address_components[3].long_name );
	 //            	pos_neighborhood.val( 	responses[0].address_components[2].long_name );
	 //            	pos_street.val( 		responses[0].address_components[1].long_name );
	 //            	pos_number.val( 		responses[0].address_components[0].long_name );
	 //            	if( 7 in responses[0].address_components ){
	 //            		pos_postal_code.val( 	responses[0].address_components[7].long_name );
	 //            	}


	 //            	pos_lat.val( 			responses[0].geometry['location'].D );
	 //            	pos_long.val( 			responses[0].geometry['location'].k );

	 //                infoWindow.setContent(
		//                 "<div class='place'>" + responses[0].formatted_address 
		//                 + "<br /> <small>" 
		//                 + "Latitude: " + marker.getPosition().lat() + "<br>" 
		//                 + "Longitude: " + marker.getPosition().lng() + "</small></div>"
	 //                );
	 //                infoWindow.open(map, marker);
	 //            } else {
	 //                alert('Erro: O Google Maps não conseguiu identificar o local informado.');
	 //            }
	 //        });
	 //        map.panTo(marker.getPosition());
		// }
		 
		// // Search for an address on Google Maps
		// function showAddress(address) {
		//     if (geocoder) {
		//         geocoder.geocode({'address': address}, function(results, status) {

		//             if (status == google.maps.GeocoderStatus.OK) {
		                
		//                 // For accurate addresses, the type is ROOFTOP else APPROXIMATE
		//                 if (results[0].geometry.location_type == "ROOFTOP")
		//                     map.setZoom(18);
		//                 else
		//                     map.setZoom(14);
		//                 map.setCenter(results[0].geometry.location);
		//                 marker.setPosition(results[0].geometry.location);
		//                 infoWindow.open(map, marker);
		//             } else {
		//                 alert("Error: " + address + " cannot be found on Google Maps.");
		//             }
		//         });
		//     }
		// }



    });



})( jQuery );
