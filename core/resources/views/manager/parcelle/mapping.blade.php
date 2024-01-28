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
                    <div class="table-responsive--sm table-responsive" id="googleMap" style="height: 800px;">
                         
                    </div>
                </div>
                
            </div>
        </div>
    </div>
     
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins') 
    <x-back route="{{ route('manager.traca.parcelle.index') }}" />
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
@endpush
@push('script')
<script type="text/javascript">
 $("#localite").chained("#section");
 $("#producteur").chained("#localite");
var lgt='-5.627491';
    var ltt='6.149013';
    var z=9; 
    
    var locations = [    <?php
    if(count($parcelles))
    {
    $total = count($parcelles);  
$i=1;
foreach ($parcelles as  $res) {
    $producteur = htmlentities($res->producteur->nom, ENT_QUOTES | ENT_IGNORE, "UTF-8").' '.htmlentities($res->producteur->prenoms, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $code=$res->producteur->codeProd;
    $parcelle =$res->codeParc;
    $localite=$res->producteur->localite->nom;
    $annee=$res->anneeCreation;
    $typedocument=$res->typeDoc;
    $superficie=$res->superficie;
    $proprietaire = 'Producteur : '.$producteur.'<br>Code producteur:'. $code.'<br>Localite:'. $localite.'<br>Parcelle:'. $parcelle.'<br>Année creation:'. $annee.'<br>Type document:'. $typedocument;
 ?>
  ['<?php echo $proprietaire; ?>', <?php echo $res->longitude; ?>, <?php echo $res->latitude; ?>, 7]
  
 <?php
  if($total>$i){echo ',';}
 $i++;
}
}
 
?>];

    var map = new google.maps.Map(document.getElementById('googleMap'), {
      zoom: z,
      center: new google.maps.LatLng(ltt,lgt), 
      mapTypeId: google.maps.MapTypeId.HYBRID
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) { 
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][2],locations[i][1]),
        map: map,
       // animation: google.maps.Animation.BOUNCE,
        icon: new google.maps.MarkerImage("<?php echo asset('assets/img/map-marker.png'); ?>")
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    } 
    
$('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
@endpush
