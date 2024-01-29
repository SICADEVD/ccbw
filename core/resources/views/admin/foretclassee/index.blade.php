@extends('admin.layouts.app')
@section('panel')
<?php
use Illuminate\Support\Arr;
use Illuminate\Support\Str; 
?>
    <div class="row">
        <div class="col-lg-12">
        <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <input type="hidden" name="table" value="foretclassees" /> 
                            <div class="flex-grow-1">
                                <label>@lang('Localité')</label>
                                <select name="localite" class="form-control" id="localite">
                                    <option value="">@lang('Toutes')</option>
                                   
                                </select> 
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i>
                                    @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 ">
                <div class="card-body  p-0">
                    <div class="table-responsive--sm table-responsive" id="map" style="height: 800px;">
                         
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <?php
  
  $lat = '';
  $long = '';
  $totalF = 0; 
  $pointsPolygonF = array();
  $seriescoordonatesF=array();
  $a=1;

if(isset($foretclassees) && count($foretclassees)){

    $totalF = count($foretclassees);

    foreach ($foretclassees as $data) {
        
         
        if($data->waypoints !=null)
        {
            $lat = htmlentities($data->latitude, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $long= htmlentities($data->longitude, ENT_QUOTES | ENT_IGNORE, "UTF-8"); 
    $producteur = htmlentities($data->nomForet, ENT_QUOTES | ENT_IGNORE, "UTF-8"); 
    $region= htmlentities($data->region, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $superficie= htmlentities($data->superficie, ENT_QUOTES | ENT_IGNORE, "UTF-8");
     $polygon ='';

        $coords = explode(' ', $data->waypoints);
        
        $coords = Arr::where($coords, function ($value, $key) {
            if($value !="")
            {
                return  $value;
            }
            
        });
        
   
         $nombre = count($coords); 
         
         $i=0; 
        foreach($coords as $data2) {
             
                $i++;
                $coords2 = explode(',', $data2); 
                if(isset($coords2[1]) && isset($coords2[0]))
                {
                    $polygon .='{ lat: ' . $coords2[1] . ', lng: ' . $coords2[0] .'},';
                } 
            
        }
        
        $polygonCoordinates ='['.$polygon.']';
        
        }
        $seriescoordonatesF[]= $polygonCoordinates;
        $pointsPolygonF[] = "['".$producteur."','".$long."','".$lat."','".$region."','".$superficie."']";
    }
   
$pointsPolygonF = Str::replace('"','',json_encode($pointsPolygonF));
 $pointsPolygonF = Str::replace("''","'Aucun'",$pointsPolygonF);
  
} 

// Chargement Zones Tampons
$totalZT = 0; 
  $pointsPolygonZT = array();
  $seriescoordonatesZT=array();
  $a=1;

if(isset($foretclasseetampons) && count($foretclasseetampons)){

    $totalZT = count($foretclasseetampons);

    foreach ($foretclasseetampons as $data) {
        
         
        if($data->waypoints !=null)
        {
            $lat = htmlentities($data->latitude, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $long= htmlentities($data->longitude, ENT_QUOTES | ENT_IGNORE, "UTF-8"); 
    $producteur = htmlentities($data->nomZToret, ENT_QUOTES | ENT_IGNORE, "UTF-8"); 
    $region= htmlentities($data->region, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $superficie= htmlentities($data->superficie, ENT_QUOTES | ENT_IGNORE, "UTF-8");
     $polygon ='';

        $coords = explode(' ', $data->waypoints);
        
        $coords = Arr::where($coords, function ($value, $key) {
            if($value !="")
            {
                return  $value;
            }
            
        });
        
   
         $nombre = count($coords); 
         
         $i=0; 
        foreach($coords as $data2) {
             
                $i++;
                $coords2 = explode(',', $data2); 
                if(isset($coords2[1]) && isset($coords2[0]))
                {
                    $polygon .='{ lat: ' . $coords2[1] . ', lng: ' . $coords2[0] .'},';
                } 
            
        }
        
        $polygonCoordinates ='['.$polygon.']';
        
        }
        $seriescoordonatesZT[]= $polygonCoordinates;
        $pointsPolygonZT[] = "['".$producteur."','".$long."','".$lat."','".$region."','".$superficie."']";
    }
   
$pointsPolygonZT = Str::replace('"','',json_encode($pointsPolygonZT));
 $pointsPolygonZT = Str::replace("''","'Aucun'",$pointsPolygonZT);
  
} 
?>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')  
    <a href="{{ route('admin.foretclassee.create') }}" class="btn  btn-outline--primary h-45"><i
            class="las la-map-marker"></i> Importation KML des Forêts Classées</a>
            <a href="{{ route('admin.foretclassee.createTampon') }}" class="btn  btn-outline--info h-45"><i
            class="las la-map-marker"></i> Importation Zones Tampons</a>
@endpush
@push('style')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/vendor/datepicker.min.css') }}">
@endpush
@push('script')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
 <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_VVwtAhchqsINCTqin22MG1AzMn7d6gk" ></script>  
@endpush
@push('script')
    <script>  
    let map;
let infoWindow;
var locationsF = <?php echo $pointsPolygonF; ?>;
var totalF = <?php echo $totalF; ?>;
var locationsZT = <?php echo $pointsPolygonZT; ?>;
var totalZT = <?php echo $totalZT; ?>;
window.onload = function () {
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 7,
    center: { lat: 6.881703, lng: -5.500461 },
    mapTypeId: "hybrid",
  });
 // Afichage Zones Tampons
const triangleCoordsZT = <?php echo Str::replace('"','',json_encode($seriescoordonatesZT)); ?>; 
  const polygonsZT = []; 
for (let i = 0; i < totalZT; i++) {   

    const polygon = new google.maps.Polygon({
        paths: triangleCoordsZT[i],
        strokeColor: "#FFFFFF",
        strokeOpacity: 0.2,
        strokeWeight: 2,
        fillColor: "#FFFFFF",
        fillOpacity: 0.2,
        clickable: true
    });

    polygonsZT.push(polygon);
 
    // google.maps.event.addListener(polygon, 'click', function (event) {
    //     const infoWindow = new google.maps.InfoWindow({
    //         content: getInfoWindowContentZT(locationsZT[i])
    //     });

    //     infoWindow.setPosition(event.latLng);
    //     infoWindow.open(map);
    // });

    polygon.setMap(map);
}

// Afichage Forets Classées
  const triangleCoordsF = <?php echo Str::replace('"','',json_encode($seriescoordonatesF)); ?>; 
  const polygonsF = []; 
for (let i = 0; i < totalF; i++) {   

    const polygon = new google.maps.Polygon({
        paths: triangleCoordsF[i],
        strokeColor: "#FFFF00",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#1A281A",
        fillOpacity: 0.8,
        clickable: true
    });

    polygonsF.push(polygon);
 
    google.maps.event.addListener(polygon, 'click', function (event) {
        const infoWindow = new google.maps.InfoWindow({
            content: getInfoWindowContentF(locationsF[i])
        });

        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
    });

    polygon.setMap(map);
} 

} 
function getInfoWindowContentF(location) {
        return `Region: ${location[3]}<br>Nom: ${location[0]}<br>Latitude: ${location[2]}<br>Longitude: ${location[1]}<br>Superficie: ${location[4]} ha`;
    }
    function getInfoWindowContentZT(location) {
        return `Region: ${location[3]}<br>Nom: ${location[0]}<br>Latitude: ${location[2]}<br>Longitude: ${location[1]}<br>Superficie: ${location[4]} ha`;
    }
function getRandomElement(array) {
    return array[Math.floor(Math.random() * array.length)];
  }


 
$('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
@endpush
