"use strict";
/*
 * CustomMarker, custom google marker
 * @param {Object} latlng - marker postion from new google.maps.LatLng()
 * @param {Object} map - google map
 * @param {string} marker_inner - inner HTML for custom marker
 * @param {Object} callback - calback events for custom marker (click)
 * @param {Object} args - arguments for google marker (title)
 * @returns {Object} - custom marker
 */

if (typeof sw_infoBox == 'undefined'){
    var sw_infoBox = new InfoBox();
}

var CustomMarker;
function CustomMarker(latlng, map, marker_inner, callback, args) {
	this.latlng = latlng;	
	this.marker_inner = marker_inner;
        if (typeof callback == 'undefined') callback = [];
	this.callback = callback;
        if (typeof args == 'undefined') args = [];
	this.args = args;	
	this.position = latlng;	
	this.setMap(map);
	this.activemarker = false;
        return this;
}

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.draw = function() {
	
	var self = this;
	var div = this.div;
	if (!div) {
	
		div = this.div = document.createElement('div');
                div.className = "google_marker";
		
		div.style.position = 'absolute';
		div.style.cursor = 'pointer';
                
                if (typeof(self.args.marker_classes) !== 'undefined') {
                    div.className += ' '+ self.args.marker_classes;
                }
                
                if (typeof(self.marker_inner) !== 'undefined') {
                    div.innerHTML = self.marker_inner;
                }
                if (typeof(self.args.title) !== 'undefined') {
                    div.title = self.args.title;
                }
		
		google.maps.event.addDomListener(div, "click", function(event) {
                    if (typeof(self.callback.click) !== 'undefined') {
                        self.callback.click(self.map, self);	
                    }
		});
		
		var panes = this.getPanes();
		panes.overlayImage.appendChild(div);
	}
	
	var point = this.getProjection().fromLatLngToDivPixel(this.latlng);
	
	if (point) {
            div.style.left = (point.x - 20) + 'px';
            div.style.top = (point.y - 62) + 'px';
	}
};

CustomMarker.prototype.remove = function() {
	if (this.div) {
		this.div.parentNode.removeChild(this.div);
		this.div = null;
	}	
        this.setMap(null);
};

CustomMarker.prototype.clickMarker = function() {
        var self = this;
	var div = this.div;
        
        var limits = new google.maps.LatLngBounds();
        limits.extend(self.getPosition());
        map.fitBounds(limits);
        map.panTo(self.getPosition());
        if (typeof(self.callback.click) !== 'undefined') {
            setTimeout(function(){self.callback.click(self.map, self);},300);
        } else{
            self.callback.click(self.map, self);	
        }
};

CustomMarker.prototype.getPosition = function() {
	return this.latlng;	
};

CustomMarker.prototype.getDraggable = function() {
    return false;
};

function deleteMarkers(markers) {
    //Loop through all the markers and remove
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
};