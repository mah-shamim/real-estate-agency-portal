/*
 * 
 * @param {type} $
 * @returns {undefined}
 */
!function ($) {

    "use strict";

    $.expr[":"].icontains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    var Near_Places = function (element, options, e) {
        this.$element = $(element);
        this.$map = null;
        this.$marker = null;
        this.$markers = new Array();
        this.$places_markers = new Array();
        this.$custom_places_markers = new Array();
        this.$custom_categories = new Array();
        this.$directionsDisplay = null;
        this.$directionsService = new google.maps.DirectionsService();
        this.$infowindow;
        this.$generic_icon;
        this.$placesService = null;
        this.settings = $.extend(true, {}, $.fn.near_places.defaults_settings, options);
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        this.init();
    };

    Near_Places.prototype = {
        constructor: Near_Places,
        init: function (options) {
            
            /* check config */
            var self = this;
            if(self.settings.mapOptions.zoom > 18)
                self.settings.mapOptions.zoom = 18;
            if(self.settings.mapOptions.zoom < 0)
                self.settings.mapOptions.zoom = 0;
            
            if(self.settings.mapOptions.radius > 50000)
                self.settings.mapOptions.radius = 50000;
            if(self.settings.mapOptions.radius < 0)
                self.settings.mapOptions.radius = 0;
            
            self.$custom_categories['default_health']=[];
            self.$custom_categories['default_park']=[];
            self.$custom_categories['default_petrolpump']=[];
            self.$custom_categories['default_atmbank']=[];
            self.$custom_categories['default_store']=[];
            
            this.generate_map();
            return self;
        },
        generate_map: function (map) {
            var self = this;
            var places = '';
            $.each(this.settings.places, function (index, place) {
                if (typeof place.type !== 'indefined' && typeof place.icon !== 'indefined' && typeof place.title !== 'indefined')
                    places += '<a data-index="default_' + place.title.toLowerCase() + '" class="btn btn-large" data-rel="' + place.type + '"><img src="' + place.icon + '" alt="' + place.type + '"/> ' + self.lang_check(place.title) + '</a>'
            })

            var html = '\n\
                <div class="np_places_select">' + places + '</div>\n\
                <div class="np_map_near_places" style="height: 385px;"></div>';

            this.$element.html(html);

            var mapOptions1 = {
                center: new google.maps.LatLng(this.settings.mapOptions.center[0], this.settings.mapOptions.center[1]),
                zoom: parseInt(this.settings.mapOptions.zoom, 10),
                mapTypeId: google.maps.MapTypeId[this.settings.mapOptions.mapTypeId],
                scrollwheel: this.settings.mapOptions.scrollwheel,
                styles: this.settings.mapOptions.styles,
            };
            this.$map = new google.maps.Map(this.$element.find('.np_map_near_places')[0], mapOptions1);
        },

        /* param = array('ltng': '', 'address': '') */
        setMarker: function (param) {
            var self = this;
            this.clearMap();
            if (typeof param.ltng === 'undefined') {
                console.log('Missing ltng, parametr must be param = array(ltng, address)')
                return false;
            }
            
            if (typeof param.address === 'undefined') {
                param.address = self.getAddress(param.ltng);
            }

            this.$marker = new google.maps.Marker({
                position: new google.maps.LatLng(param.ltng[0], param.ltng[1]),
                map: this.$map,
                //icon: 'assets/img/markers/house.png'
            });

            var myOptions2 = {
                content: "<div class='np_infobox'>" + self.lang_check('Address') + ": " + param.address + "</div>",
                disableAutoPan: false,
                maxWidth: 0,
                pixelOffset: new google.maps.Size(-138, -80),
                zIndex: null,
                closeBoxURL: "",
                infoBoxClearance: new google.maps.Size(1, 1),
                position: new google.maps.LatLng(param.ltng[0], param.ltng[1]),
                isHidden: false,
                pane: "floatPane",
                enableEventPropagation: false
            };

            this.$marker.infobox = new InfoBox(myOptions2);
            this.$marker.infobox.isOpen = false;
            this.$markers.push(this.$marker);

            // action        
            google.maps.event.addListener(self.$marker, "click", function (e) {
                var curMarker = this;

                $.each(self.$markers, function (index, marker) {
                    // if marker is not the clicked marker, close the marker
                    if (marker !== curMarker) {
                        marker.infobox.close();
                        marker.infobox.isOpen = false;
                    }
                });

                if (curMarker.infobox.isOpen === false) {
                    curMarker.infobox.open(self.$map, this);
                    curMarker.infobox.isOpen = true;
                    self.$map.panTo(curMarker.getPosition());
                } else {
                    curMarker.infobox.close();
                    curMarker.infobox.isOpen = false;
                }

            });

            var _callback = function (results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    for (var i = 0; i < results.length; i++) {
                        _createMarker(results[i]);
                    }
                }
            }

            var _calcRoute = function (source_place, dest_place) {
                var selectedMode = 'WALKING';
                var request = {
                    origin: source_place,
                    destination: dest_place,
                    // Note that Javascript allows us to access the constant
                    // using square brackets and a string value as its
                    // "property."
                    travelMode: google.maps.TravelMode[selectedMode]
                };

                self.$directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        self.$directionsDisplay.setDirections(response);
                        //console.log(response.routes[0].legs[0].distance.value);
                    }
                });
            }
            var _createMarker = function (place) {
                var placeLoc = place.geometry.location;
                var propertyLocation = new google.maps.LatLng(param.ltng[0], param.ltng[1]);

                if (place.icon.indexOf("generic") > -1)
                {
                    place.icon = self.$generic_icon;
                }

                var image = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };
                
                var marker = new google.maps.Marker({
                    map: self.$map,
                    icon: image,
                    position: place.geometry.location
                });

                self.$places_markers.push(marker);

                var distanceKm = (_calcDistance(propertyLocation, placeLoc) * 1.2).toFixed(2);
                var walkingTime = parseInt((distanceKm / 5) * 60 + 0.5, 10);

                google.maps.event.addListener(marker, 'click', function () {

                    //drawing route
                    _calcRoute(propertyLocation, placeLoc);

                    // Fetch place details
                    self.$placesService.getDetails({placeId: place.place_id}, function (placeDetails, statusDetails) {

                        //open popup infowindow
                        self.$infowindow.setContent(place.name + '<br />' + self.lang_check('Distance') + ': ' + distanceKm + 'Km' +
                                '<br />' + self.lang_check('WalkingTime') + ': ' + walkingTime + 'Min' +
                                '<br /><a target="_blank" href="' + placeDetails.url + '">' + self.lang_check('Details') + '</a>');
                        self.$infowindow.open(self.$map, marker);
                    });

                });
            }

            var _calcDistance = function (p1, p2) {
                return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
            }

            var _init_places = function (places_types, icon) {
                var pyrmont = new google.maps.LatLng(param.ltng[0], param.ltng[1]);
                _setAllMap(null);
                
                self.$generic_icon = icon;

                var places_type_array = places_types.split(',');

                var request = {
                    location: pyrmont,
                    radius: parseInt(self.settings.mapOptions.radius, 10),
                    types: places_type_array
                };
                self.clearRout();
                self.setCenterByMarker();
                self.$infowindow = new google.maps.InfoWindow();
                self.$placesService = new google.maps.places.PlacesService(self.$map);
                self.$placesService.nearbySearch(request, _callback);
            }

            var _setAllMap = function (map) {
                for (var i = 0; i < self.$places_markers.length; i++) {
                    self.$places_markers[i].setMap(map);
                }
            }

            // init_gmap_searchbox();
            this.$element.find(".np_places_select a").on('click', function () {
                //clear custom locatin markers
                self.clearCustomPlaces();
                //clear google locatin markers
                self.clearPlaces();
                _init_places($(this).attr('data-rel'), $(this).find('img').attr('src'));
                var  cat = $(this).attr('data-index')
                //add new locations via array
                if (typeof self.$custom_categories[cat] != 'undefined')
                $.each(self.$custom_categories[cat], function(key,val) {
                    self.addLocation(val);
                })
            });

            var selected_place_type = self.settings.mapOptions.default_place_id;

            this.$directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});

            this.$directionsDisplay.setMap(this.$map);
            _init_places(this.$element.find(".np_places_select a:eq(" + selected_place_type + ")").attr('data-rel'), this.$element.find(".np_places_select a:eq(" + selected_place_type + ") img").attr('src'));

            this.setCenterByMarker();
        },
        
        addLocation : function (param) {
            var placeLoc = new google.maps.LatLng(param.ltng[0], param.ltng[1])
            var propertyLocation = this.$marker.getPosition();

            var self = this;
            if (typeof param.ltng === 'undefined') {
                console.log('Missing ltng, parametr must be param = array(ltng, address)')
                return false;
            }
            
            if (typeof param.address === 'undefined') {
                param.address = self.getAddress(param.ltng);
            }
            if (typeof param.title === 'undefined') {
                param.title = '';
            }
            

            var marker = new google.maps.Marker({
                position: placeLoc,
                map: this.$map,
                icon: param.icon || ''
            });
            
            self.$custom_places_markers.push(marker);
             
            var _calcRoute = function (source_place, dest_place) {
                var selectedMode = 'WALKING';
                var request = {
                    origin: source_place,
                    destination: dest_place,
                    // Note that Javascript allows us to access the constant
                    // using square brackets and a string value as its
                    // "property."
                    travelMode: google.maps.TravelMode[selectedMode]
                };

                self.$directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        self.$directionsDisplay.setDirections(response);
                        //console.log(response.routes[0].legs[0].distance.value);
                    }
                });
            }
            
            var _calcDistance = function (p1, p2) {
                return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
            }
            var distanceKm = (_calcDistance(propertyLocation, placeLoc) * 1.2).toFixed(2);
            var walkingTime = parseInt((distanceKm / 5) * 60 + 0.5, 10);

            google.maps.event.addListener(marker, 'click', function () {

                //drawing route
                _calcRoute(propertyLocation, placeLoc);

                // Fetch place details

                //open popup infowindow
                self.$infowindow.setContent(param.title +'<br />' + param.address+'<br />' + self.lang_check('Distance') + ': ' + distanceKm + 'Km' +
                        '<br />' + self.lang_check('WalkingTime') + ': ' + walkingTime + 'Min' +
                        '<br /><a target="_blank" href="//maps.google.hr/maps?saddr=&daddr='+param.ltng[0]+','+param.ltng[1]+'">' + self.lang_check('Details') + '</a>');
                self.$infowindow.open(self.$map, marker);

            });
        },
        
        addCustomCategory : function(param){
            
            var self = this;
            if (typeof param.title === 'undefined') {
                console.log('Missing title in param')
                return false;
            }
            
            if (typeof param.index === 'undefined') {
                console.log('Missing index in param')
                return false;
            }
            
            if (typeof self.$custom_categories[param.index] != 'undefined') {
                console.log('Index exists')
                return false;
            }
            
            self.$custom_categories[param.index]=[];
            
            var img ='';
            if (typeof param.icon !== 'undefined') {
                img = '<img src="' + param.icon + '" alt="' + param.title + '" width="20px" height="20px"/>'
            }
            
            var place = '<a class="btn btn-large" data-rel="' + param.index + '">'+ img+ ' ' + param.title + '</a>'
            var list = self.$element.find('.np_places_select')
            $(place).appendTo(list).click(function(e){
                e.preventDefault();
                
                //clear custom locatin markers
                self.clearCustomPlaces();
                //clear google locatin markers
                self.clearPlaces();

                //add new locations via array
                $.each(self.$custom_categories[param.index], function(key,val) {
                    self.addLocation(val);
                })
            })
            
        },
        
        addLocationTo : function(param){
            
            var self = this;

            if (typeof param.index === 'undefined') {
                console.log('Missing index in param')
                return false;
            }

            if (typeof param.location === 'undefined') {
                console.log('Missing location in param')
                return false;
            }

            if (typeof self.$custom_categories[param.index] === 'undefined') {
                console.log('Missing category, please first add category via addCustomCategory')
                return false;
            }
            self.$custom_categories[param.index].push(param.location);
            
        },
        
        clearCustomCategory : function(param){
            var self = this;
            if (typeof param.index === 'undefined') {
                console.log('Missing index in param')
                return false;
            }
            if (typeof self.$custom_categories[param.index] === 'undefined') {
                console.log('Missing index in categories')
                return false;
            }
            self.$custom_categories[param.index]=[];
        },
        
        /* return addess */
        getAddress: function(gps){
            var latlng =gps[0]+","+gps[1];
            var address = '';
            var url = "//maps.googleapis.com/maps/api/geocode/json?latlng=" + latlng + "&sensor=false";
            $.ajax({
                url: url,
                async: false,
                dataType: 'json',
                success: function (data) {
                    if(typeof data.results != 'undefined' && typeof data.results[0] != 'undefined' && typeof data.results[0].formatted_address  != 'undefined' )
                        address = data.results[0].formatted_address;
                    else {
                        console.log(data.status)
                    }
                }
              });
            return address; 
        },
        
        setZoom: function (int) {
            if (typeof int === 'undefined')  return false
            this.$map.setZoom(parseInt(int,10));
            this.settings.mapOptions.zoom = int;
        },
        setCenter: function (ltng) {
            if (typeof ltng === 'undefined')  return false
            this.$map.setCenter(ltng);
        },
        setCenterByMarker: function () {
            this.$map.setCenter(this.$marker.getPosition());
            this.setZoom(this.settings.mapOptions.zoom)
        },
        clearMap: function () {
            this.$element.find(".np_places_select a").unbind('click');
            this.setAllMapPlaces(null);
            this.clearCustomPlaces(null);
            this.clearRout(null);
            this.setAllMap(null);
        },
        clearPlaces: function () {
            this.clearRout();
            this.setAllMapPlaces(null);
        },
        clearCustomPlaces: function () {
            this.clearRout();
            for (var i = 0; i < this.$custom_places_markers.length; i++) {
                this.$custom_places_markers[i].setMap(null);
            }
        },
        destroy: function () {
            this.$element.remove();
        },
        setAllMapPlaces: function (map) {
            for (var i = 0; i < this.$places_markers.length; i++) {
                this.$places_markers[i].setMap(map);
            }
        },
        setAllMap: function (map) {
            for (var i = 0; i < this.$markers.length; i++) {
                this.$markers[i].setMap(map);
            }
        },
        clearRout: function () {
            if(this.$directionsDisplay)
                this.$directionsDisplay.setDirections({routes: []});
            if (this.$infowindow) {
                this.$infowindow.close();
                this.$infowindow.isOpen = false;
            }
        },
        setSettings: function (object) {
            if (typeof object === 'undefined')  return false
            this.settings = $.extend(true, {}, this.settings, object);
        },
        lang_check: function (text) {
            if (typeof this.settings.translatable[text] !== 'undefined') {
                return this.settings.translatable[text];
            } else {
                return text;
            }
        }
    };

    $.fn.near_places = function (option, event) {
        //get the args of the outer function..
        var args = arguments;
        var value;
        var chain = this.each(function () {
            var $this = $(this),
                    data = $this.data('near_places'),
                    options = typeof option == 'object' && option;

            if (!data) {
                $this.data('near_places', (data = new Near_Places(this, options, event)));
            } else if (options) {
                for (var i in options) {
                    data.options[i] = options[i];
                }
            }

            if (typeof option == 'string') {
                //Copy the value of option, as once we shift the arguments
                //it also shifts the value of option.
                var property = option;
                if (data[property] instanceof Function) {
                    [].shift.apply(args);
                    value = data[property].apply(data, args);
                } else {
                    value = data.options[property];
                }
            }
        });

        if (value != undefined) {
            return value;
        } else {
            return chain;
        }
    };

    $.fn.near_places.defaults_settings = {
        'translatable': {
            'Distance': 'Distance'
        },
        'places': {
            'health': {
                'icon':  "data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlz"
                        +"AAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAW"
                        +"dEVYdENyZWF0aW9uIFRpbWUAMDkvMzAvMTTSky07AAABRUlEQVQ4jbWVMW7CMBSGv1gIWKJ2gTk3"
                        +"oELeGMoRepTcgLAycZQeITAgEVmo3IAjtFlADLiDTRMnTlAR/JIl5395Xyz7+SXQWlOTlBEQA1Ng"
                        +"VInugRRYotShmhrUgFImFvZS/5KjHwtN/EApX4FP4P0GqKoV8IFS3wCiFEjvgGFz0uuDsKtLKO/V"
                        +"ZgNZBtstDIdu+mBg/CyD9I8zsgyEPYCZkyREMXzyx2dIGQnMATSremi+qigUC0xpNMPy3PXyvA06"
                        +"7VCvs0JBAIsFnE5mrjX0embu16jTCLsCJ5PWV6pqB2oNux2cz4XX7cJ43LjK28A4huOx8Pp9WK8b"
                        +"gQJzN/0KAghD1wvDtj3cC0pV/gClAlg+ELgUtgXNHftyKYZP/vgcpQ7lbvNFW022a49Sb+B2mymm"
                        +"Ff1XK0q37YkN1oVG3PkL+AXwCnjdWp7bjwAAAABJRU5ErkJggg==",
                'type': 'hospital,health',
                'title': 'Health',
            },
            'park': {
                'icon': "data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlz"
                        +"AAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAW"
                        +"dEVYdENyZWF0aW9uIFRpbWUAMDkvMzAvMTTSky07AAACrklEQVQ4jZ2VTUgUYRjHfzM7O7sqq7uR"
                        +"B1NpF5NSQg2hSAIFwbKLESQe+hC6dAhyoYK6pFB0SLSPQ1J0CupgkXaoQ4SKIJQGboYSGAlmZobf"
                        +"7vfM22F213Xcleh/mZnnmec3zzPv8z6vJITArMXOQjfQAtQA5Sa3D+gH7rq801PmWMkMXOwsbI3B"
                        +"cuI2R+MLLDtLWeoqAz0aNy/HoK0pgYudhU6gB6gGkB27yKi6DJIFdV8DSBYik2/RV2cJjXej/f4S"
                        +"ZwwAJ1ze6SUAJQnen1yerewMaumpTdlb99QDYMktYbW7MW6ujsVWAMhJZW78K4uK7NydeNTmx4nO"
                        +"DCfVpSAptuRvlccYSAsdBW7ge7LXfuiSUS6gr82y/PggAI6mHpS8SgBCn5/if38dkzwyxgJskr74"
                        +"DbRwLBk7siMfyZaNZEusEyK4ZA4DaJEWOgpG2doaZNbexlZ22giOBkELI9myAYhM9bH26mwqoE9J"
                        +"BQMQkfXEvaTYQbFv+EIrqUIAypV0nuDQHbT5CeTsfDKqroDQCX56hPDPExp7li6MLUDZ6UYEFhCh"
                        +"FcITLw1bZi7q/iYCg7eMjDN2oORVIvQw2tzY9sCcc30ILUR0ZpjAQBvawiTBjw+wVTSjlpxELT6O"
                        +"1VMLskL4ay/rby5uTghjbyYUGnuOZM3C6q7B0fQaye4EiwoIso7dw1p0FGQFbW4M/7ur5nx8MkaX"
                        +"JxQYvEn016hRms2B/cB51L0NgJR4J/pzhLXeZkTEbwb2p2xsye4iq64da1Ed6FGEFib44T7ROR+E"
                        +"Vo1rankkIUR8690we2WnB7W4nowj11h+chh95Uc6EECbyzvdmjxtUjY4gPOCj+BIF8GRh+lgPpd3"
                        +"emM4xFSDMYq2KDDUjh74kw42EIsF/nHAptH2A9YEdfOfR8Bf/gQEnpFJtX8AAAAASUVORK5CYII=",
                'type': 'park',
                'title': 'Park',
            },
            'gas_station': {
                'icon': "data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlz"
                        +"AAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAW"
                        +"dEVYdENyZWF0aW9uIFRpbWUAMDkvMzAvMTTSky07AAACDklEQVQ4jbWVwWoTURSGv7mZSaoQGhOC"
                        +"pZUQk013KU52Co1LwUXBvoMbwYIggpt0qXYR8AHyCu7dpLgRnKFkERdCayApBlw0idZ2xpmMi0zC"
                        +"5N4pBsEfhpk5Z/7/3HPOvWe0IAiQUbXtIrAH1ICK5G4DLaBhmWZX5mqyYNW266HYqhJpEaNQtB4r"
                        +"WLXtDPAO2P6LkIxDYMcyzSGAHnG0iKT3olBgN59X2cMhz46Po6btkLs1FwzTXKjVDV3noNej7zho"
                        +"QADkDYMH2WzcKitV265bplnXTMsqAl/lL16XSrw9PaXnOHNbzjB4WSjw/uyMtWSS5mAg024Lpg2I"
                        +"hSc1zAsCVoTgw2jEbj7P3VWlb3s6062hIAAe5nJ8c915yjnDwA0CbqVS3EwmeVUqce/oKEqr6aj7"
                        +"DAAfeLy+rtg/jsf89H3cIKBzfi67K7rCCJEI789PThh5HteE4KBc5roQ9B2HT+MxjX5f4V0pOMN3"
                        +"12XoeawIwSRinwC6pi0vOCM3NzcX7F7kWcQICqZnc2nMOp9OJHAmE9ndFkx3+dIopFK8KZf5cnFB"
                        +"9/JSdrd0oAE8lT2/1egA/PB9moMBn9UOAzREOIL2o9YnGxtspdOxglldJ6vHln7fMs2uAAhH0LyW"
                        +"9zMZ1gyDX76vXDnD4I4arD0bY9FQNcLx9ajTiV3dFTgEdmYv/2/ASqJF/vEX8Af4cctKHZ/kdwAA"
                        +"AABJRU5ErkJggg==",
                'type': 'gas_station',
                'title': 'PetrolPump',
            },
            'food': {
                'icon': "data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlz"
                        +"AAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAW"
                        +"dEVYdENyZWF0aW9uIFRpbWUAMDkvMzAvMTTSky07AAACXElEQVQ4jbWVT0gUcRTHP/ObmZ1ZCf9W"
                        +"hopshKAVqIdIoj8rxUIHoUMIIZFBlwTDQ5ewg9a1f1BRdK5Llwrp4HZoLbKSIjcJRQL/HBK2KLVs"
                        +"dHf+dJjRZmdXQ6Hvbd77vc97b35v3kiO4xDUYjwcAbqAKFAfcCeBBHBDjxmTwVgpCFyMh3s8WFFO"
                        +"pmzNedCevMDFeLgYeAwc+gcoqAHgmB4zZgGEz5HYAAwvJrH8ILzqesh9V6tKrbuOKD3oN9V7DCSj"
                        +"X48AEysZSpuRK9qwU0+wUn05MLmyHbHlKNb0XZylGZyFMb97u8C9gL/Z6x8gV7ShNjxErmzPhlWd"
                        +"QZQdwZq6hVJzCSlUhlx52n+kS+COxorSrxqwv8Vd+K47yNUdXuVR1J03cebeoETOYY52otRcBrnA"
                        +"Hx6VjH49dxAlQajxEWJzDIDM2Hnsr0/R9o+ApGCOX0CUH8cc7cSe/5Admhe43H7tFaTifYjCRhxj"
                        +"ApQSsH4hKYUsvT2AszCeW8taQLdXHT06BUohdqqP9HCr26b1O//xtViSWoLWNAhyAXaqD7G1BbX2"
                        +"6qqwZWAyL0wrJ9T0GmlTHen3LaSHWzEnryFXd6DuvrcaL6ngTnnWUEt6FaG9L5G0baSHDmPPDgJg"
                        +"jneDZaDs6AYRJjNyChzbH5qQL55UxvDPotDQ9jwDtYjMUDP2/LusEuwfL3AysyiRLhAa9vfnfvcJ"
                        +"4a2g3hWTY2IvfMb8dBb758e8fVnTt7G+3Id0ym/u1WPGpH/bDAdbX4eSesxogOxbjuKuovVqAN/X"
                        +"9v8WbAAaYYO/gD/F0tyGMIgCWgAAAABJRU5ErkJggg==",
                'type': 'food',
                'title': 'Restaurant',
            },
            'atm': {
                'icon': "data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQI"
                        +"CAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvc"
                        +"mtzIENTNui8sowAAAAWdEVYdENyZWF0aW9uIFRpbWUAMDkvMzAvMTTSky07AAACOklEQVQ4jbWVz2"
                        +"vScRjHX1/tK6bbnOI21pqog/2gZEXQLtEUIoiCbB627NZfMCjoVq570GGHunTbqMDmYdUl0EFQFNj"
                        +"EDHdwyoikb4IaUyopO/jFvupHO0Tv05fn/Txvnofn/Xm+Ur1eRwtJklhb2HACy4AXmKUVCSAG3L3y"
                        +"5FIOQKshtQuuByIhVcxCb5RV0ZBQcD0QGQQiwPxfhNqxBfiDYX8JQKchYr3E3D4HI0fsImperf3ToTrmLVG2Z3Eat8+B"
                        +"ecgEQDFX5vVqnGK23J66Egz7Q5K6gKxI7MRVD1PnJ6hVa82YbJKpVWs8uxalolTbS1w6GgvoOibA"
                        +"i5svKWbLpDczKKkCsklm/OSoqGT5AA1rCCGbZICWTl6txjGYZdHIAF4dnT5rQkkVADh9Yw7zsKkp"
                        +"3kUMYFYfmFkKdWOLuTKHjo9gdVowmGVsLgv2SRtfP+3zrfRdWCOtLWzUhYwGbp8Dz+J0c9MAz69H"
                        +"hZ3qOiIC7Eb3qChVdp5m+PgmD8DUhQlhro7G2+yA1WXh4r2znLvja8Z+VGrsbGYA6NN0q0FCh8bl"
                        +"WlSUKuYhE1anpeWFDB9tfO9/6fAgQEwfmFlKI/Diz9ovDH0y9kkb43OjHLQaGRjr57Dqv/iDJJVO"
                        +"0cv68IeHpeTjtITAj/l3CkgwMNaPcdDY8F+uzNv7CfLbSnv6SjDsj2ivzTY9PHnm9ik+pwokH6VF"
                        +"dCIY9h+D1i17aZwiIXajeyjvCyJqSzvd/zuwzcA//gJ+A2OI4A5EuY7AAAAAAElFTkSuQmCC",
                'type': 'atm',
                'title': 'ATMBank',
            },
            'store': {
                'icon': "data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlz"
                        +"AAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAW"
                        +"dEVYdENyZWF0aW9uIFRpbWUAMDkvMzAvMTTSky07AAAB+klEQVQ4jbWVz2sTQRTHP7NJfyhCjIe2"
                        +"KJVEvHiwG0wohQoNePUgHvUiCGsvYv+E5D+IJ0lQD4UeW0QqWFowbenJ9ccqWIQ0jRWCiLbWQ402"
                        +"6Xp425LsZFta6Pcys/Pm+5333r55o1zXRUOqEAPGgDRg+qwOUARy2FbFT1WaYKqQ8cQi+kkt2PRE"
                        +"M+0FU4XTwDNg5AAhP+aBG9jWLwCjyVA8ghgep7j7YXjeZdjNVTYNtgUvbkHI0NgBMD0NlJvMx4DV"
                        +"PdPYEFzuAbMP7j4H59thvI0rN5nPAQ8006PrcPEMbDegVoeukKzX6nCqE35swcwKjDvNrIcGUho6"
                        +"FioQ7Yan7+BfA+bKMFuGhgv5N9Afgavn/ay0gV5ngqWvMg70wskOiEfhQlTmZq8c8vitn2WGA7Ox"
                        +"tgkbNSitQ6RLDnBdMBR8+A5XzoJd1WjBggCvVuFeEpSCRJ+sdYRkPlOCHf2W7S+4+AVuXoKpZfFO"
                        +"KRl3gCdauHuCDkF5tKtQ3oDBc3AiLN79/ithT36SP90KJ4xUeXvBP3UYnYbRFEwuw8o63B+Ezz8l"
                        +"tzqKBpDbN+zhfgn7Whw6Q3B7AO4kgnbnDK8FZQMFF9fgdRVelmBrW2px4mO7nVlsq9Lcbd4TFPrB"
                        +"cLCtBLR2mzTSig6LeZpu2zE22FbRGEd8Av4DFp2sa19UrgQAAAAASUVORK5CYII=",
                'type': 'store',
                'title': 'Store',
            },
        },
        'mapOptions': {
            'center': [45.812231, 15.920618],
            'zoom': '15',
            'mapTypeId': 'ROADMAP',
            'scrollwheel': false,
            'styles': '',
            'radius': '2000',
            'default_place_id': '4',
        }
    }

}(window.jQuery);
