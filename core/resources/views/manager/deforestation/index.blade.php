@extends('manager.layouts.app')
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
$total = 0;
$mappingparcellle ='';
$pointsPolygon = array();
$seriescoordonates=array();
$a=1;

if(isset($parcelles) && count($parcelles)){

    $total = count($parcelles);

    foreach ($parcelles as $data) {
        
         
        if($data->waypoints !=null)
        {
            $lat = htmlentities($data->latitude, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $long= htmlentities($data->longitude, ENT_QUOTES | ENT_IGNORE, "UTF-8"); 
    $producteur = htmlentities($data->producteur->nom, ENT_QUOTES | ENT_IGNORE, "UTF-8").' '.htmlentities($data->producteur->prenoms, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $code= htmlentities($data->producteur->codeProd, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $parcelle = htmlentities($data->codeParc, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $localite=htmlentities($data->producteur->localite->nom, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $annee= htmlentities($data->anneeCreation, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $culture= htmlentities($data->culture, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $superficie= htmlentities($data->superficie, ENT_QUOTES | ENT_IGNORE, "UTF-8");
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
        $pointsPolygon[] = "['".$producteur."','".$long."','".$lat."','".$code."','".$localite."','".$parcelle."','".$annee."','".$culture."','".$superficie."']";
    }
   
$pointsPolygon = Str::replace('"','',json_encode($pointsPolygon));
 $pointsPolygon = Str::replace("''","'Aucun'",$pointsPolygon);
  
}

// Chargement des forets classées
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
$lat = '';
$long = '';
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
            <div class="btn-group h-45" role="group" aria-label="Basic example">
  <button type="button" style="background-color:#FF0000;" class="btn text-white">Parcelles Producteurs</button>
  <button type="button" style="background-color:#FFFF00;" class="btn">Forêts Classées</button> 
</div>
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
var total = <?php echo $total; ?>;
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
function getInfoWindowContent(location) {
        return `Producteur: ${location[0]}<br>Code producteur: ${location[3]}<br>Latitude: ${location[2]}<br>Longitude: ${location[1]}<br>Localite: ${location[4]}<br>Parcelle: ${location[5]}<br>Année creation: ${location[6]}<br>Culture: ${location[7]}<br>Superficie: ${location[8]} ha`;
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
