<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <!-- checkbox -->
            <div class="form-group clearfix">
                <div class="icheck-primary orderCheckbox-wrapper inline-block">
                    <input type="checkbox" id="orderCheckbox1" class="orderCheckbox" data-type="undispatch" checked>
                    <label for="orderCheckbox1">
                        Undispatch(new)
                    </label>
                </div>
                <div class="icheck-primary orderCheckbox-wrapper inline-block">
                    <input type="checkbox" id="orderCheckbox2" class="orderCheckbox" data-type="dispatch" >
                    <label for="orderCheckbox2">
                        Dispatched
                    </label>
                </div>
                <div class="icheck-primary orderCheckbox-wrapper inline-block">
                    <input type="checkbox" id="orderCheckbox3" class="orderCheckbox" data-type="completed" >
                    <label for="orderCheckbox3">
                        Completed
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="mapit_dv"></div>
        </div>
    </div>
</section>

<script>
    var order_list = <?php echo json_encode($orders); ?>;
</script>


<!--4684 Clairton Blvd.Pittsburgh, PA 15236-->
<script>
    var def_lat=40.370036;
    var def_lng=-79.979034;
    def_lat=40.3547615;
    def_lng=-79.9793897;
    /*def_lat=40.354763;
    def_lng=-79.97932;*/

    var default_latLng = {lat: parseFloat(def_lat), lng: parseFloat(def_lng)};
    var map = new google.maps.Map(document.getElementById('mapit_dv'), {
        zoom: 9,
        center: default_latLng
    });

    var marker_list = [];
    var myLatLng = {lat: def_lat, lng: def_lng};
    var icon_size=32;
    var icon_url = BASE_URL + "public/dist/img/center-pin.png";
    var icon = {
        url: icon_url,
        scaledSize: new google.maps.Size(icon_size, icon_size * 1.75), // scaled size
        origin: new google.maps.Point(0,0), // origin
        anchor: new google.maps.Point(parseInt(icon_size/2),parseInt(icon_size)), // anchor
        labelOrigin:new google.maps.Point(parseInt(icon_size/2),-10)
    };
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: '4684 Clairton Blvd.Pittsburgh, PA 15236',
        icon:icon
    });

    var geocoder = new google.maps.Geocoder();
    //marker_list.push(marker);

    var order_icon_size=24;
    var order_icon = {
        url: BASE_URL + "public/dist/img/car.png",
        scaledSize: new google.maps.Size(order_icon_size, order_icon_size), // scaled size
        origin: new google.maps.Point(0,0), // origin
        anchor: new google.maps.Point(parseInt(order_icon_size/2),parseInt(order_icon_size)), // anchor
        labelOrigin:new google.maps.Point(parseInt(order_icon_size/2),-10)
    };

    function codeAddress(geocoder, map, address) {
        geocoder.geocode({'address': address}, function(results, status) {
            if (status === 'OK') {
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    title: address
                    /*icon: order_icon*/
                });
                marker_list.push(marker);
            } else {
                console.log('Geocode was not successful for the following reason: ' + status);
            }
        });
    }
    function drawOrderMap(data_list){
        console.log('order_list', order_list);
        deleteMarkers();
        for(var i=0; i < data_list.length; i++){
            var order_info = data_list[i];
            var orderedaddress = order_info['orderedaddress'];
            /*if(orderedaddress != ''){
                codeAddress(geocoder, map, orderedaddress);
            }*/
            if(order_info.lat!=undefined && order_info.lon != undefined){
                var order_lat = parseFloat(order_info.lat);
                var order_lon = parseFloat(order_info.lon);

                var marker = new google.maps.Marker({
                    map: map,
                    position: {lat: order_lat, lng: order_lon},
                    title: orderedaddress
                    /*icon: order_icon*/
                });
                marker_list.push(marker);
                console.log('valid order info', order_info);
            }
        }
    }
    function setMapOnAll(map) {
        for (let i = 0; i < marker_list.length; i++) {
            marker_list[i].setMap(map);
        }
    }
    function clearMarkers() {
        setMapOnAll(null);
    }

    // Shows any markers currently in the array.
    function showMarkers() {
        setMapOnAll(map);
    }

    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
        clearMarkers();
        marker_list = [];
    }

    drawOrderMap(order_list);

</script>
<script>
    $(document).ready(function(){
        function get_checked_order_types(){
            var order_types = [];
            $('.orderCheckbox').each(function(){
                if($(this).prop('checked')){
                    order_types.push($(this).attr('data-type'));
                }
            });
            return order_types;
        }
        $("body").on("change", '.orderCheckbox', function(){
            var id = $(this).attr('id');
            var order_types = get_checked_order_types();
            console.log('order_types', order_types);
            if(order_types.length > 0){
                show_loading(true);
                $.ajax({
                    url: BASE_URL + "admin/export/get_order_list",
                    type:"POST",
                    data:{
                        order_types: order_types
                    },
                    success:function(res){
                        order_list = JSON.parse(res);
                        drawOrderMap(order_list);
                        show_loading(false);
                    }
                });
            }else{
                clearMarkers();
            }
        });
    });

</script>


<!--old part-->
<script>

    function mapit_proc_func()
    {
        var machine_oil_state = [
            {
                "lat": 40,
                "lng": 119,
                "oil": "California:California:100"
            },
            {
                "lat": 36,
                "lng": 110,
                "oil": "California:California:100"
            },
            {
                "lat": 12,
                "lng": 10,
                "oil": "California:California:100"
            }
        ];

        var map;
        function initMap() {
            var mapOptions={
                center: new google.maps.LatLng(55.87,-101.5),
                zoom: 4,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL,
                    position: google.maps.ControlPosition.RIGHT_BOTTOM
                },
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.TERRAIN],
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,  // HORIZONTAL_BAR DROPDOWN_MENU
                    position: google.maps.ControlPosition.TOP_LEFT
                },
                mapTypeId: 'roadmap',
                streetViewControl: true,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_BOTTOM
                },
                fullscreenControl:true,
                fullscreenControlOptions:{
                    position:google.maps.ControlPosition.TOP_RIGHT
                }
                //scaleControl:false
                //disableDefaultUI: true
            };
            map = new google.maps.Map(document.getElementById('mapit_dv'), mapOptions);

            // machine_oil_state defined in dashboard_map php file
            createMarkers(machine_oil_state);
        }
        function createMarkers(json){
            var markers=[];

            var bounds = new google.maps.LatLngBounds();
            for(var i = 0; i < json.length;i++){
                var lat = parseFloat(json[i].lat);
                var lng = parseFloat(json[i].lng);
                console.log(lat);
                if(isNaN(lat) || lat==0 || isNaN(lng) || lng==0) continue;
                var point = new google.maps.LatLng(lat, lng);
                bounds.extend(point);
                var icon_size=24;
                var icon = {
                    url: BASE_URL + "public/dist/img/car.png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0,0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size/2),parseInt(icon_size)), // anchor
                    labelOrigin:new google.maps.Point(parseInt(icon_size/2),-10)
                };
                var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    icon:icon,
                    title: json[i].oil
                    //label: 'test'
                });

                addMarkerEvent(marker);
                markers.push(marker);
            }
            map.fitBounds(bounds);
        }
        function addMarkerEvent(marker){
            google.maps.event.addListener(marker, 'click', function (e) {
                //setSelection(marker);
                //setInfowindow(marker);
            });
        }
        google.maps.event.addDomListener(window, 'load', initMap);
    }

    //mapit_proc_func();
</script>
