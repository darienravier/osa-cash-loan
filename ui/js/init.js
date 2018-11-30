pendingClicks = 0;
deleteLot = false;
toggleLot = false;
latLngArr = [];

//Initialize Select Statement
$(document).ready(function(){
   $('select').formSelect();
});

//initialize Datepicker in Forms
$(document).ready(function(){
    $('.datepicker').datepicker();
  });

//Information-Confirmed
//Time to generate a pdf
//Call pdf generator
$('#confirm-info-button').click(function(){
	alert('You Have Confirmed the Information!');

	$.ajax({
		url: "infoConfirmed",
		type: "POST",
		data: null,
	});
});


//Text Area materialize auto size changing
$('#reason').val('');
M.textareaAutoResize($('#reason'));

// Sidenav for the helpdesk
    $(document).ready(function(){
      $('.sidenav').sidenav({
      	edge: 'right',
      });
    });

$(document).ready(function(){
		$('.fixed-action-btn').floatingActionButton();
	});

(function(window, google, mapster){

	var options = mapster.MAP_OPTIONS;

	element = document.getElementById('map-canvas');

	var geocoder = new google.maps.Geocoder();
	map = new Mapster.create(element, options);

	$('.add').click(function(){
		pendingClicks = 4;
		deleteLot = false;
		toggleLot = false;
		Materialize.toast('Please plot four points by clicking on the map.', 3000);
	});

	$('.toggle').click(function(){
		pendingClicks = 0;
		deleteLot = false;
		toggleLot = true;
		Materialize.toast('Please click the lot you want to toggle.', 3000);
	});

	$('.delete').click(function(){
		pendingClicks = 0;
		deleteLot = true;
		toggleLot = false;
		Materialize.toast('Please click the lot you want to delete.', 3000);
	});

	/*map.addPolygon({
		paths: [
		    new google.maps.LatLng(14.161195, 121.246666),
		    new google.maps.LatLng(14.161250, 121.246770),
		    new google.maps.LatLng(14.161109, 121.246857),
		    new google.maps.LatLng(14.161048, 121.246745),
		    new google.maps.LatLng(14.161195, 121.246666)
		],
		available: true,
	});

	map.addPolygon({
		paths: [
		    new google.maps.LatLng(14.161038, 121.247073),
		    new google.maps.LatLng(14.161078, 121.247173),
		    new google.maps.LatLng(14.160827, 121.247293),
		    new google.maps.LatLng(14.160796, 121.247203),
		    new google.maps.LatLng(14.161038, 121.247073)
		],
		available: false,
	});*/

	
	map.addPolygon({
		paths: [
		    new google.maps.LatLng(14.166950488552535, 121.25000953674316),
		    new google.maps.LatLng(14.167153337925892, 121.25029385089874),
		    new google.maps.LatLng(14.165967446713655, 121.2512218952179),
		    new google.maps.LatLng(14.165764596280523, 121.25093758106232),
		    new google.maps.LatLng(14.166950488552535, 121.25000953674316)
		],
		available: true,
	});

	map.addPolygon({
		paths: [
		    new google.maps.LatLng(14.165681375537549, 121.25095727853477),
		    new google.maps.LatLng(14.165894628630317, 121.25127377919853),
		    new google.maps.LatLng(14.165275673978739, 121.25175121240318),
		    new google.maps.LatLng(14.165020809808153, 121.25142398290336),
		    new google.maps.LatLng(14.165681375537549, 121.25095727853477),
		],
		available: true,
	});

	map.addPolygon({
		paths: [
		    new google.maps.LatLng(14.167127331606117, 121.25041547231376),
		    new google.maps.LatLng(14.166315932931997, 121.25100555829704),
		    new google.maps.LatLng(14.166602003308396, 121.25134351663291),
		    new google.maps.LatLng(14.167345784599718, 121.25074806623161),
		    new google.maps.LatLng(14.167127331606117, 121.25041547231376),
		],
		available: false,
	});

	map.addPolygon({
		paths: [
		    new google.maps.LatLng(14.166196303394948, 121.25110211782157),
		    new google.maps.LatLng(14.165275673978739, 121.25183167867362),
		    new google.maps.LatLng(14.165551343065607, 121.25215890817344),
		    new google.maps.LatLng(14.166430361125817, 121.2514668982476),
		    new google.maps.LatLng(14.166196303394948, 121.25110211782157),
		],
		available: true,
	});





	map._on({
		obj : map.gMap,
		event :  'click',
		callback : function(e){
			console.log(e.latLng.lat());
			console.log(e.latLng.lng());
			if(pendingClicks > 0){
				//console.log(e.latLng.lng());
				latLngArr.push(e.latLng);
				pendingClicks--;
				if(pendingClicks == 0){
					map.addPolygon({
						paths: [
						    latLngArr[0],
						    latLngArr[1],
						    latLngArr[2],
						    latLngArr[3],
						    latLngArr[0]
						],
						available: true,
					});
					latLngArr = [];
				}
			}
		}
	});

}(window, google, window.Mapster || (window.Mapster = {})))


