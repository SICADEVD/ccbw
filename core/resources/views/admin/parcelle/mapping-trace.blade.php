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
                            <input type="hidden" name="table" value="parcelles" />
                            <div class="flex-grow-1">
                                <label>@lang('Coopérative')</label>
                                <select name="cooperative" class="form-control" id="cooperative">
                                    <option value="">@lang('Toutes')</option>
                                    @foreach ($cooperatives as $local)
                                        <option value="{{ $local->id }}" {{ request()->cooperative == $local->id ? 'selected' : '' }}>{{ $local->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Section')</label>
                                <select name="section" class="form-control" id="section">
                                    <option value="">@lang('Toutes')</option>
                                    @foreach ($sections as $local)
                                        <option value="{{ $local->id }}" {{ request()->section == $local->id ? 'selected' : '' }}>{{ $local->libelle }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Localité')</label>
                                <select name="localite" class="form-control" id="localite">
                                    <option value="">@lang('Toutes')</option>
                                    @foreach ($localites as $local)
                                        <option value="{{ $local->id }}" {{ request()->localite == $local->id ? 'selected' : '' }}>{{ $local->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Producteur')</label>
                                <select name="producteur" class="form-control" id="producteur">
                                    <option value="">@lang('Tous')</option>
                                    @foreach ($producteurs as $local)
                                        <option value="{{ $local->id }}" {{ request()->producteur == $local->id ? 'selected' : '' }}>{{ $local->nom }} {{ $local->prenoms }} ({{ $local->codeProd }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="dates form-control"
                                    placeholder="@lang('Date de début - Date de fin')" autocomplete="off" value="{{ request()->date }}">
                            </div>
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
$arrData = '';
$newCoord = '';
$lat = '';
$long = '';
$total = 0;
$mappingparcellle ='';
$pointsPolygon = $pointsWaypoints = array();
$seriescoordonates=array();
$a=1;

if(isset($parcelles) && count($parcelles)){

    $total = count($parcelles);

    foreach ($parcelles as $data) {
        
         
        if($data->waypoints !=null)
        {
            $lat = isset($data->latitude) ? htmlentities($data->latitude, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $long= isset($data->longitude) ? htmlentities($data->longitude, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible'; 
            $producteur = isset($data->producteur->nom) ? htmlentities($data->producteur->nom, ENT_QUOTES | ENT_IGNORE, "UTF-8").' '.htmlentities($data->producteur->prenoms, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $code= isset($data->producteur->codeProd) ? htmlentities($data->producteur->codeProd, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non defini';
            $parcelle = isset($data->codeParc) ? htmlentities($data->codeParc, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $localite=isset($data->producteur->localite->nom) ? htmlentities($data->producteur->localite->nom, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $section=isset($data->producteur->localite->section->libelle) ? htmlentities($data->producteur->localite->section->libelle, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $cooperative=isset($data->producteur->localite->section->cooperative->name) ? htmlentities($data->producteur->localite->section->cooperative->name, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $annee = isset($data->anneeCreation) ? htmlentities($data->anneeCreation, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $culture= isset($data->culture) ? htmlentities($data->culture, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $superficie= isset($data->superficie) ? htmlentities($data->superficie, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $proprietaire = 'Coopérative:'. $cooperative.'<br>Section:'. $section.'<br>Localite:'. $localite.'<br>Producteur : '.$producteur.'<br>Code producteur:'. $code.'<br>Code Parcelle:'. $parcelle.'<br>Année creation:'. $annee.'<br>Latitude:'. $lat.'<br>Longitude:'. $long.'<br>Superficie:'. $superficie.' ha';
           
            $pointsCoordinates = "['".$proprietaire."',".$long.",".$lat."]";
     $polygon ='';

        $coords = explode(',0', $data->waypoints);
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
                if($i==$nombre){
                    $polygon .='{ lat: ' . $coords2[1] . ', lng: ' . $coords2[0] . ' }';
                }else{
                    $polygon .='{ lat: ' . $coords2[1] . ', lng: ' . $coords2[0] . ' },';
                } 
            
        }
        
        $polygonCoordinates ='['.$polygon.']';
        
        }
        $seriescoordonates[]= $polygonCoordinates;
        $pointsPolygon[] = "['".$proprietaire."']";
        $pointsWaypoints[] = $pointsCoordinates;
    }
   
$pointsPolygon = Str::replace('"','',json_encode($pointsPolygon));
 $pointsPolygon = Str::replace("''","'Non Disponible'",$pointsPolygon);
 $pointsWaypoints = Str::replace('"','',json_encode($pointsWaypoints));
 $pointsWaypoints = Str::replace("''","'Non Disponible'",$pointsWaypoints);
  
} 
 
?>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')  
    <a href="{{ route('admin.traca.parcelle.mapping') }}" class="btn  btn-outline--primary h-45"><i
            class="las la-map-marker"></i> Mapping Waypoints</a>
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
var locations = <?php echo $pointsPolygon; ?>;
var locationsWaypoints = <?php echo $pointsWaypoints; ?>;
var total = <?php echo $total; ?>;
window.onload = function () {
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 8,
    center: { lat: 6.8817026, lng: -5.5004615 },
    mapTypeId: "hybrid",
  });

  // Define the LatLng coordinates for the polygon.
  const triangleCoords = <?php echo Str::replace('"','',json_encode($seriescoordonates)); ?>; 
  const polygons = [];
// Construct polygons
for (let i = 0; i < total; i++) { 
    const arrayColor = ["#622F22","#5C3317","#644117","#654321","#704214","#804A00","#6F4E37","#835C3B","#7F5217","#7F462C","#A0522D","#8B4513","#8A4117","#7E3817","#7E3517","#954535","#9E4638","#C34A2C","#B83C08","#C04000","#EB5406","#C35817","#B86500","#B5651D","#B76734","#C36241","#CB6D51","#C47451","#D2691E","#CC6600","#E56717","#E66C2C","#FF6700","#FF5F1F","#FE632A","#F87217","#FF7900","#F88017","#FF8C00","#F87431","#FF7722","#E67451","#FF8040","#FF7F50","#F88158","#F9966B","#FFA07A","#F89880","#E9967A","#E78A61","#DA8A67","#FF8674","#FA8072","#F98B88","#F08080","#F67280","#E77471","#F75D59","#E55451","#CD5C5C","#FF6347","#E55B3C","#FF4500","#FF0000","#FD1C03","#FF2400","#F62217","#F70D1A","#F62817","#E42217","#E41B17","#DC381F","#C24641","#C11B17","#B22222","#B21807","#A52A2A","#A70D2A","#9F000F","#931314","#990000","#990012","#8B0000","#8F0B0B","#800000","#8C001A","#7E191B","#800517","#733635","#660000","#551606","#560319","#550A35","#810541","#7D0541","#7D0552","#872657","#7E354D","#E56E94","#DB7093","#D16587","#C25A7C","#C25283","#E75480","#F660AB","#FF69B4","#FC6C85","#F6358A","#F52887","#FF007F","#FF1493","#F535AA","#FF33AA","#FD349C","#E45E9D","#E759AC","#E3319D","#DA1884","#E4287C","#FA2A55","#E30B5D","#DC143C","#C32148","#C21E56","#C12869","#C12267","#CA226B","#CC338B","#C71585","#C12283","#B3446C","#B93B8F","#FF00FF","#E238EC"];
    
const randomColor = getRandomElement(arrayColor);

    const polygon = new google.maps.Polygon({
        paths: triangleCoords[i],
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        clickable: true
    });

    polygons.push(polygon);

    // Event listener for each polygon
    google.maps.event.addListener(polygon, 'click', function (event) {
        const infoWindow = new google.maps.InfoWindow({
            content: getInfoWindowContent(locations[i])
        });

        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
    });

    polygon.setMap(map);
}

// Affichage des waypoints
// var infowindow2 = new google.maps.InfoWindow();

//     var marker, i;
     
//     for (i = 0; i < total; i++) { 
//       marker = new google.maps.Marker({
//         position: new google.maps.LatLng(locationsWaypoints[i][2],locationsWaypoints[i][1]),  
//         map: map, 
//       });

//       google.maps.event.addListener(marker, 'click', (function(marker, i) {
//         return function() {
//         infowindow2.setContent(locationsWaypoints[i][0]);
//           infowindow2.open(map, marker);
//         }
//       })(marker, i));
//     } 

} 
function getInfoWindowContent(location) {
        return `${location[0]}`;
    }

function getRandomElement(array) {
    return array[Math.floor(Math.random() * array.length)];
  }


 
$('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
@endpush