@extends('manager.layouts.app')
@section('panel')
<?php
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
                                        <option value="{{ $local->id }}" data-chained="{{ $local->section_id }}" {{ request()->localite == $local->id ? 'selected' : '' }}>{{ $local->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Producteur')</label>
                                <select name="producteur" class="form-control" id="producteur">
                                    <option value="">@lang('Tous')</option>
                                    @foreach ($producteurs as $local)
                                        <option value="{{ $local->id }}" data-chained="{{ $local->localite_id }}" {{ request()->producteur == $local->id ? 'selected' : '' }}>{{ $local->nom }} {{ $local->prenoms }} ({{ $local->codeProd }})</option>
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
$mappingparcellle ='';
$pointsPolygon = array();
$seriescoordonates=array();
$a=1;

if(isset($parcelles) && count($parcelles)){

    foreach ($parcelles as $data) {
        
         
        if($data->waypoints !=null)
        {
            $lat = $data->latitude;
    $long= $data->longitude;
    $points=  $lat.','.$long;
    $producteur = htmlentities($data->producteur->nom, ENT_QUOTES | ENT_IGNORE, "UTF-8").' '.htmlentities($data->producteur->prenoms, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $code=$data->producteur->codeProd;
    $parcelle =$data->codeParc;
    $localite=htmlentities($data->producteur->localite->nom, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $annee=$data->anneeCreation;
    $culture=$data->culture;
    $superficie=$data->superficie;
     $polygon ='';

        $coords = explode(',0', $data->waypoints);
          
        foreach($coords as $data2) { 
            if($data2)
            {

                $coords2 = explode(',', $data2); 
                $polygon .='{ lat: ' . $coords2[1] . ', lng: ' . $coords2[0] . ' },';
            }
        }
        
        $polygonCoordinates ='['.$polygon.']';
        
        }
        $seriescoordonates[]= $polygonCoordinates;
        $pointsPolygon[] = "['".$producteur."',".$long.",".$lat.",'".$code."','".$localite."','".$parcelle."','".$annee."','".$culture."',".$superficie."]";
    }
   
$pointsPolygon = Str::replace('"','',json_encode($pointsPolygon));
 $pointsPolygon = Str::replace("''","'Aucun'",$pointsPolygon);
  
}
?>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins') 
    <x-back route="{{ route('manager.traca.parcelle.mapping') }}" />
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
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_VVwtAhchqsINCTqin22MG1AzMn7d6gk&callback=initMap&v=terrain" async></script>  
@endpush
@push('script')
    <script>  
    let map;
let infoWindow;
var locations = <?php echo $pointsPolygon; ?>;
function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 9,
    center: { lat: 5.940008, lng: -5.642579 },
    mapTypeId: "terrain",
  });

  // Define the LatLng coordinates for the polygon.
  const triangleCoords = <?php echo Str::replace('"','',json_encode($seriescoordonates)); ?>; 
  const polygons = [];

// Construct polygons
for (let i = 0; i < locations.length; i++) { 

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

function getInfoWindowContent(location) {
        return `Producteur: ${location[0]}<br>Code producteur: ${location[3]}<br>Latitude: ${location[2]}<br>Longitude: ${location[1]}<br>Localite: ${location[4]}<br>Parcelle: ${location[5]}<br>Année creation: ${location[6]}<br>Culture: ${location[7]}<br>Superficie: ${location[8]} ha`;
    }

} 

window.initMap = initMap;

    </script>
@endpush
