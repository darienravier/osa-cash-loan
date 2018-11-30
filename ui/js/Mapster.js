(function(window, google){

	var Mapster = (function(){
		function Mapster(element, opts) {
			this.gMap = new google.maps.Map(element, opts);
			this.markers = [];
			this.polygons = [];
		}
		Mapster.prototype = {
			zoom : function(level){
				if(level){
					this.gMap.setZoom(level);
				} else{
					return this.gMap.getZoom();
				}
			},

			_on : function(opts){
				var self = this;
				google.maps.event.addListener(opts.obj, opts.event, function(e){
					opts.callback.call(self, e);
				});
			},

			addPolygon : function(opts){
				var polygon;
				var infoWindow;
				polygon = this._createPolygon(opts);
				this._addPolygon(polygon);
				/*this._on({
					obj : polygon,
					event :  'mouseover',
					callback : function(e){
						infoWindow = new google.maps.InfoWindow({
							content: (opts.available)? 'This lot is available.': 'This lot is already reserved or taken.',
							position: {
								lat : (opts.paths[0].lat() + opts.paths[2].lat())/2,
								lng : (opts.paths[0].lng() + opts.paths[2].lng())/2
							},
						});
						infoWindow.open(map.gMap);
					}
				});*/

				this._on({
					obj : polygon,
					event :  'click',
					callback : function(e){
						if(deleteLot){
							deleteLot = false;
							if(confirm('Do you really want to remove this lot?')){
								this._removePolygon(polygon);
							}
						}
						else if(toggleLot){
							toggleLot = false;
							var action = (polygon.get('fillColor') == '#00ff00')? 'reserve': 'unreserve';
							if(confirm('Do you really want to '+action+' this lot?')){
								polygon.setOptions({fillColor: (polygon.get('fillColor') != '#00ff00')? '#00ff00': '#FF0000'});
								polygon.setOptions({strokeColor: (polygon.get('strokeColor') != '#00ff00')? '#00ff00': '#FF0000'});
							}
						}
						else{

							var area = google.maps.geometry.spherical.computeArea(polygon.getPath());
							var status = (polygon.get('fillColor') == '#00ff00')? 'Available': 'Reserved';

							$('.lot-info').html(
								'<table class="striped">'+
									'<tr><td>Area</td><td>'+Math.round(area, 4)+' sqm</td></tr>'+
									'<tr><td>Status</td><td>'+status+'</td></tr>'+
									'<tr><td>Details</td><td>No lot details has been entered yet</td></tr>'+
								'</table>'
							);
						}
					}
				});

				/*this._on({
					obj : polygon,
					event :  'mouseout',
					callback : function(e){
						infoWindow.close(map.gMap);
					}
				});*/
				return polygon;			},

			addMarker : function(opts){
				var marker;
				var infoWindow;
				opts.position = {
					lat : opts.lat,
					lng : opts.lng
				}
				marker = this._createMarker(opts);
				this._addMarker(marker);
				if(opts.event) {
					this._on({
						obj : marker,
						event :  opts.event.name,
						callback : opts.event.callback
					});
				}
				if(opts.content){
					this._on({
						obj : marker,
						event :  'mouseover',
						callback : function(e){
							infoWindow = new google.maps.InfoWindow({
								content: 'I like pizza.'
							});
							infoWindow.open(map.gMap, marker);
						}
					});


					this._on({
						obj : marker,
						event :  'mouseout',
						callback : function(e){
							
							infoWindow.close(map.gMap, marker);
						}
					});
				}

				return marker;
			},

			_addMarker : function(marker){
				this.markers.push(marker);
			},

			_addPolygon : function(polygon){
				this.polygons.push(polygon);
			},

			_removeMarker : function(marker){
				var indexOf = this.markers.indexOf(marker);
				if(indexOf != -1){
					this.markers.splice(indexOf, 1);
					marker.setMap(null);
				}
			},

			_removePolygon : function(polygon){
				var indexOf = this.polygons.indexOf(polygon);
				if(indexOf != -1){
					this.polygons.splice(indexOf, 1);
					polygon.setMap(null);
				}
			},

			_createMarker: function(opts){
				opts.map = this.gMap;
				return new google.maps.Marker(opts);
			},

			_createPolygon: function(opts){
				opts.map = this.gMap;
				opts.strokeColor = (opts.available)? '#00ff00': '#FF0000';
	    		opts.strokeOpacity = 0.8;
	    		opts.strokeWeight = 2;
	    		opts.fillColor = (opts.available)? '#00ff00': '#FF0000';
	    		opts.fillOpacity = 0.35;
				return new google.maps.Polygon(opts);
			},

		}
		return Mapster;
	}());

	Mapster.create = function(elements, opts){
		return new Mapster(elements, opts);
	}
 	
 	window.Mapster = Mapster;

}(window, google))