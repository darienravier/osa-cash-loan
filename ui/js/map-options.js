(function(window, google, mapster){

	mapster.MAP_OPTIONS = {
		center: {
			lat:14.166544789261966, lng: 121.25171542167664
		},
		zoom: 18,
		disableDefaultUI: true,
		//scrollwheel:false,
		//draggable: false,
		//maxZoom: 40,
		//minZoom: 9,
		zoomControlOptions: {
			position: google.maps.ControlPosition.TOP_LEFT
		},
		mapTypeId: google.maps.MapTypeId.HYBRID	
	}

}(window, google, window.Mapster || (window.Mapster = {})))

