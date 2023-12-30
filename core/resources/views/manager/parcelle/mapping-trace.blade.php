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
                                <label>@lang('Recherche par Mot(s) clé(s)')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Localité')</label>
                                <select name="localite" class="form-control">
                                    <option value="">@lang('Toutes')</option>
                                    @foreach ($localites as $local)
                                        <option value="{{ $local->id }}">{{ $local->nom }}</option>
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
                    <div class="table-responsive--sm table-responsive" id="map">
                         
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
$proprietaire = $mappingparcellle ='';
$points = array();
$seriescoordonates=array();
$a=1;

if(isset($parcelles) && count($parcelles)){

  foreach($parcelles as $data){

   
     $i=1;
     if($data->waypoints !=null)
        {
$waypoints=explode(',0,',$data->waypoints);
     foreach($waypoints as $data2) {
      $separator = ',';
      $latlong = explode(',',$data2);
      // if(count($waypoints)>$i){ $separator = ',';}
      // else {$separator = ''; }

      $newCoord .="\n".' { lat: '.$latlong[1].', lng: '.$latlong[0].' } '.$separator."\n";

      $seriescoordonates['num'.$i]= $data2;

  }
}
  $i++;
  if(count($parcelles)>$a){ $separator = ',';}
  else {$separator = ''; }
  $arrData .= '['.$newCoord.']'.$separator;
  $lat = $data->latitude;
  $long= $data->longitude;
  $points=  $lat.','.$long;
  $producteur = $data->nomProd.' '.$data->prenomsProd;
  $code=$data->codeProdapp;
  $parcelle =$data->codeParc;
  $localite=$data->nomLocal;
  $annee=$data->anneeCreation;
  $culture=$data->culture;
  $superficie=$data->superficie;
  $proprietaire = 'Producteur : '.$producteur.'<br>Code producteur:'. $code.'<br>Localite:'. $localite;
  $a++;


  }

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
let proprietaire = '<?php echo @$proprietaire;?>';
var gpolygons = [];
function initMap() {

  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 14,
    //center: { lat: <?php // echo $lat; ?>, lng: <?php // echo $long; ?> },
    center: { lat: 5.3599517, lng: -4.0082563 },
    mapTypeId: "terrain",
  });
<?php

if(isset($parcelles) && count($parcelles)){
  $a=1;
  foreach($parcelles as $data){
    $lat = $data->latitude;
    $long= $data->longitude;
    $points=  $lat.','.$long;
    $producteur = $data->nomProd.' '.$data->prenomsProd;
    $code=$data->codeProdapp;
    $parcelle =$data->codeParc;
    $localite=$data->nomLocal;
    $annee=$data->anneeCreation;
    $culture=$data->culture;
    $superficie=$data->superficie;
    $proprietaire = 'Producteur : '.$producteur.'<br>Code producteur:'. $code.'<br>Localite:'. $localite.'<br>Parcelle:'. $parcelle.'<br>Année creation:'. $annee.'<br>Culture:'. $culture;
?>
  // Define the LatLng coordinates for the polygon.
  var coords<?php echo $a; ?> = [
    <?php
    //$waypoints=unserialize($data->waypoints);
    if($data->waypoints !=null)
    {
    $waypoints=explode(',0,',$data->waypoints);
   foreach($waypoints as $data2) {
      ?>
      new google.maps.LatLng(<?php echo Str::before($data2, ','); ?>, <?php echo Str::after($data2, ','); ?>),
     <?php
  }
}
    ?>
    ];
  // Construct the polygon.
  var polygons<?php echo $a; ?> = new google.maps.Polygon({
    paths: coords<?php echo $a; ?>,
    strokeColor: "#FF0000",
    strokeOpacity: 0.8,
    strokeWeight: 3,
    fillColor: "#FF0000",
    fillOpacity: 0.35,
  });

  polygons<?php echo $a; ?>.setMap(map);

  // Add a listener for the click event.
  polygons<?php echo $a; ?>.addListener("click", showArrays);
  <?php
  $a++;
}
}
?>
  infoWindow = new google.maps.InfoWindow();
}
      function showArrays(event) {
  // Since this polygon has only one path, we can call getPath() to return the
  // MVCArray of LatLngs.
  const polygon = this;
  const vertices = polygon.getPath();
  let contentString =
    "<b>"+proprietaire+"</b><br>" +
    "Locatisation visitée: <br>" +
    event.latLng.lat() +
    "," +
    event.latLng.lng() +
    "<br>";

  // Iterate over the vertices.
  // for (let i = 0; i < vertices.getLength(); i++) {
  //   const xy = vertices.getAt(i);

  //   contentString +=
  //     "<br>" + "Coordinate " + i + ":<br>" + xy.lat() + "," + xy.lng();
  // }

  // Replace the info window's content and position.
  infoWindow.setContent(contentString);
  infoWindow.setPosition(event.latLng);
  infoWindow.open(map);
}
        $('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
@endpush
