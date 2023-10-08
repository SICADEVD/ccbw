@extends('staff.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
                    {!! Form::open(array('route' => ['staff.suivi.ssrteclmrs.store'],'method'=>'POST','class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data')) !!} 
                    <div class="form-group row">
                    <div class="alert alert-success" role="alert">
                    <p class="mb-0"> <h4>Important!</h4> </p>
  <p>NB : Seules les personnes de<a href="#" class="alert-link"> 3 à 17 ans</a> partageant le même repas et la même maison du producteur sont concernées par cette enquête. Commencer du plus grand au plus petit.</p>
  <hr>
</div>
</div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Selectionner une localite')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="localite" id="localite" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($localites as $localite)
                                        <option value="{{ $localite->id }}" @selected(old('localite'))>
                                            {{ $localite->nom }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>  
                       
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Selectionner un producteur')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="producteur" id="producteur" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($producteurs as $producteur)
                                        <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}" @selected(old('producteur'))>
                                            {{ $producteur->nom }} {{ $producteur->prenoms }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
 
                            
                            <hr class="panel-wide">

<div class="form-group row">
    <?php echo  Form::label(__("Nom du membre"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::text('nomMembre', null, array('placeholder' => __("Nom du membre"),'class' => 'form-control nomMembre', 'id'=>'nomMembre', 'required')); ?>
    </div>
</div>

<div class="form-group row">
    <?php echo  Form::label(__("Prenom(s) du membre"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::text('prenomMembre', null, array('placeholder' => __("Prenom(s) du membre"),'class' => 'form-control prenomMembre', 'id'=>'prenomMembre', 'required')); ?>
    </div>
</div>

<div class="form-group row">
        <?php echo Form::label(__("Genre du membre"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('sexeMembre', ['Homme' => __('Homme'), 'Femme' => __('Femme')], null,array('class' => 'form-control', 'required')); ?>

    </div>
</div>

<div class="form-group row">
    <?php echo Form::label(__("Date de naissance"), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
         <?php echo Form::date('datenaissMembre', null,array('class' => 'form-control datenaissMembre naiss','id'=>'datenais', 'required') ); ?>
    </div>
</div>

    <div class="form-group row">
       <?php echo Form::label(__("Lien de parenté avec le producteur"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('lienParente', $lienParente, null, array('placeholder' => __('Selectionner une option'),'class' => 'form-control lienParente', 'id'=>'lienParente')); ?>
    </div>
</div>
<div class="form-group row" id="autreLienParente">
    <?php echo  Form::label(__("Autre lien de parenté"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::text('autreLienParente', null, array('placeholder' => '...','class' => 'form-control autreLienParente')); ?>
    </div>
</div>
<hr class="panel-wide">

<div class="form-group row">
        <?php echo Form::label(__("Va-t-il à l’école ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::select('frequente', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control frequente', 'required')); ?>
    </div>
</div>
<div id="frequentation">
<div class="form-group row">
       <?php echo Form::label(__("Quel niveau d’étude as-tu atteins ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <select class="form-control" name="niveauEtude" id="niveauEtude">
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($niveauEtude as $niveau)
                                        <option value="{{ $niveau->nom }}" @selected(old('niveauEtude'))>
                                            {{ $niveau->nom }}</option>
                                    @endforeach
                        </select>
    </div>
</div>
<div class="form-group row" id="classe">
                                <label class="col-sm-4 control-label">@lang('Classe')</label>
                                <div class="col-xs-12 col-sm-8">
                                   
                                <select class="form-control" name="classe" id="classeEtude">
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->nom }}" data-chained="{{ $classe->niveau->nom }}"  @selected(old('classe'))>{{ $classe->nom }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
 
<div class="form-group row">
        <?php echo Form::label(__("Ton école est- elle située dans le village ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::select('ecoleVillage', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control ecoleVillage')); ?>
    </div>
</div>

<div class="form-group row" id="distanceEcole">
        <?php echo Form::label(__("A Combien de Km du village est-elle située ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::number('distanceEcole', null,array('class' => 'form-control distanceEcole')); ?>
    </div>
</div>

<div class="form-group row">
        <?php echo Form::label(__("Donne le nom de cette école"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::text('nomEcole',null,array('class' => 'form-control nomEcole')); ?>
    </div>
</div>

<div class="form-group row">
       <?php echo Form::label(__("Comment te rends-tu dans cette école ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('moyenTransport', $moyenTransport, null, array('placeholder' => __('Selectionner une option'),'class' => 'form-control moyenTransport', 'id'=>'moyenTransport')); ?>
    </div>
</div>

</div>

<div id="nonFrequentation">
<div class="form-group row">
        <?php echo Form::label(__("As-tu été à l’école par le passé ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::select('avoirFrequente', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control avoirFrequente')); ?>
    </div>
</div>
<div id="ecoleAvant">

<div class="form-group row" id="niveauEtudeAtteint">
       <?php echo Form::label(__("Quel niveau d’étude as-tu atteins ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('niveauEtudeAtteint', $niveauEtude, null, array('placeholder' => __('Selectionner une option'),'class' => 'form-control niveauEtudeAtteint')); ?>
    </div>
</div>

<div class="form-group row">
       <?php echo Form::label(__("Pourquoi ne vas-tu pas à l’école ou arrêté l’école ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('raisonArretEcole[]', $raisonArretEcole, [], array('class' => 'form-control raisonArretEcole select2-multi-select','multiple')); ?>
    </div>
</div>
</div>

</div>
<hr class="panel-wide">
<div class="form-group row">
       <?php echo Form::label(__("Au cours de ces 2 dernières années lequel de ces travaux as-tu effectués ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('travauxDangereux[]', $travauxDangereux, [], array('class' => 'form-control travauxDangereux select2-multi-select','multiple', 'required')); ?>
    </div>
</div>
<div class="form-group row">
       <?php echo Form::label(__("Où as-tu effectué ces travaux ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('lieuTravauxDangereux[]', $lieuTravaux, [], array('class' => 'form-control lieuTravauxDangereux select2-multi-select','multiple',  'required')); ?>
    </div>
</div>

<hr class="panel-wide">

<div class="form-group row">
       <?php echo Form::label(__("Au cours de ces 2 dernières années lequel de ces travaux a tu effectués ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('travauxLegers[]', $travauxLegers, [], array('class' => 'form-control travauxLegers select2-multi-select','multiple', 'required')); ?>
    </div>
</div>
<div class="form-group row">
       <?php echo Form::label(__("Où as-tu effectué ces travaux ?"), [], ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('lieuTravauxLegers[]', $lieuTravaux, null, array('class' => 'form-control lieuTravauxLegers select2-multi-select','multiple',  'required')); ?>
    </div>
</div>
    <hr class="panel-wide">

    <div class="form-group row">
        <?php echo Form::label(__("Date d'Enquête"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::date('date_enquete', null,array('class' => 'form-control dateactivite dateEnquete', 'required') ); ?>
        </div>
    </div>

<hr class="panel-wide">
 
                        <div class="form-group row">
                            <button type="submit" class="btn btn--primary w-100 h-45"> @lang('Envoyer')</button>
                        </div>
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('staff.suivi.ssrteclmrs.index') }}" />
@endpush

@push('script')
<script type="text/javascript">
      $(document).ready(function () {
 $('#autreLienParente,#frequentation,#ecoleAvant').hide();

 $('.lienParente').change(function(){
var lienParente= $('.lienParente').val();
  if(lienParente=='Autre')
  {
   $('#autreLienParente').show('slow');
   $('.autreLienParente').attr('required', true);
   $('.autreLienParente').css('display','block');
  }
  else{
   $('#autreLienParente').hide('slow');
   $('.autreLienParente').val('');
   $('.autreLienParente').attr('required', false);
  }
});


$('.frequente').change(function(){
var frequente= $('.frequente').val();
  if(frequente=='oui')
  {
   $('#frequentation').show('slow');
   $('#nonFrequentation').hide('slow');
   $('.niveauEtude').attr('required', true);
   $('.classe').attr('required', true);
   $('.ecoleVillage').attr('required', true);
   $('.nomEcole').attr('required', true);
   $('.moyenTransport').attr('required', true);
    if($('.ecoleVillage').val()=='oui'){
        $('#distanceEcole').hide('slow');
        $('.distanceEcole').attr('required', false);
    }else{
        $('#distanceEcole').show('slow');
        $('.distanceEcole').attr('required', true);
    }
  }
  else{
   $('#frequentation').hide('slow');
   $('#nonFrequentation').show('slow');
   $('.niveauEtude').attr('required', false);
   $('.classe').attr('required', false);
   $('.ecoleVillage').attr('required', false);
   $('.nomEcole').attr('required', false);
   $('.moyenTransport').attr('required', false);

   $('.niveauEtude').val('');
   $('.classe').val('');
   $('.ecoleVillage').val('');
   $('.nomEcole').val('');
   $('.moyenTransport').val('');
  }
});

$('.avoirFrequente').change(function(){
var avoirFrequente= $('.avoirFrequente').val();
  if(avoirFrequente=='oui')
  {
   $('#ecoleAvant').show('slow');
   $('.niveauEtudeAtteint').attr('required', true);
   $('.raisonArretEcole').attr('required', true);
  }
  else{
   $('#ecoleAvant').hide('slow');
   $('.niveauEtudeAtteint').attr('required', false);
   $('.raisonArretEcole').attr('required', false);
   $('.niveauEtudeAtteint').val('');
   $('.raisonArretEcole').val('');
  }
});
 
});
    $("#producteur").chained("#localite");
    $("#classeEtude").chained("#niveauEtude");
    
 </script>
@endpush