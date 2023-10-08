@extends('staff.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
                    {!! Form::open(array('route' => ['staff.suivi.menage.store'],'method'=>'POST','class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data')) !!} 
                        
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
            {{ Form::label(__('Quartier'), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('quartier', null, array('placeholder' => '...','class' => 'form-control quartier', 'id'=>'quartier','required')); ?>
        </div>
    </div> 
    <div class="form-group row">
            {{ Form::label(__('Source Energie du ménage'), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('sources_energies', ["Bois de chauffe"=>"Bois de chauffe","Gaz"=>"Gaz","Four électrique"=>"Four électrique","Four à pétrole"=>"Four à pétrole","Charbon"=>"Charbon"], null, array('placeholder' => __('Selectionner une reponse'),'class' => 'form-control sources_energies', 'id'=>'sources_energies','required')); ?>
        </div>
    </div>


        <div class="form-group row" id="boisChauffe">
            {{ Form::label(__("Combien de bois par semaine?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8" >
            <?php echo Form::number('boisChauffe',null, array('placeholder' => __('qty'),'class' => 'form-control boisChauffe','min'=>'1')); ?>
        </div>
    </div>




        <div class="form-group row">
            {{ Form::label(__("Comment jetez-vous les ordures du ménage ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('ordures_menageres', ["Dépotoirs Publique"=>"Dépotoirs Publique","Poubelle de Maison"=>"Poubelle de Maison","Ramassage ordures organisé"=>"Ramassage ordures organisé","Rien de Prévu"=>"Rien de Prévu"], null, array('placeholder' => __('Selectionner une reponse'),'class' => 'form-control', 'id'=>'ordures_menageres','required')); ?>
        </div>
    </div>


        <div class="form-group row">
            <?php echo Form::label(__("Pratiquez-vous la séparation des déchets ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('separationMenage', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control separationMenage','required')); ?>
        </div>
    </div>


        <div class="form-group row">
            {{ Form::label(__("Comment gérez-vous l'eau de toilette ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('eauxToillette', ["Air Libre"=>"Air Libre","Fosse Septique"=>"Fosse Septique"], null, array('placeholder' => __('Selectionner une reponse'),'class' => 'form-control', 'id'=>'eauxToillette','required')); ?>
        </div>
    </div>



        <div class="form-group row">
            {{ Form::label(__("Comment gérez-vous l'eau de Vaisselle ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                <?php echo Form::select('eauxVaisselle', ["Air Libre"=>"Air Libre","Fosse Septique"=>"Fosse Septique"], null, array('placeholder' => __('Selectionner une reponse'),'class' => 'form-control', 'id'=>'eauxVaisselle','required')); ?>
        </div>
    </div>



        <div class="form-group row">
            <?php echo Form::label(__("Existe-t-il un WC pour le ménage ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
                 <?php echo Form::select('wc', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control wc','required')); ?>
        </div>
    </div>


        <div class="form-group row">
            {{ Form::label(__("Où procurez-Vous l'eau potable ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                  <?php echo Form::select('sources_eaux', ["Pompe Hydraulique Villageoise"=>"Pompe Hydraulique Villageoise","SODECI"=>"SODECI","Marigot"=>"Marigot","Puits Individuel"=>"Puits Individuel"], null, array('placeholder' =>__('Selectionner une reponse'),'class' => 'form-control', 'id'=>'sources_eaux','required')); ?>
        </div>
    </div>

    <hr class="panel-wide">

    <div class="form-group row">
            <?php echo Form::label(__("Traitez toujours vous-même vos champs "), null, ['class' => 'col-sm-4 control-label','required']); ?>
            <div class="col-xs-12 col-sm-8">
                  <?php echo Form::select('traitementChamps', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control traitementChamps','required')); ?>
        </div>
    </div>

<div id="avoirMachine">

        <div class="form-group row">
            {{ Form::label(__("Quel type de machine ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                <?php echo Form::select('type_machines', ["FECA FECA"=>"FECA FECA","Atomiseur"=>"Atomiseur"], null, array('placeholder' =>__('Selectionner une reponse'),'class' => 'form-control type_machines', 'id'=>'type_machines')); ?>
        </div>
    </div>
    <div class="form-group row" id="etatatomiseur">
            <?php echo Form::label(__("L'Atomiseur est-il en bon état?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
                <?php echo Form::select('etatatomiseur', ['oui' => __('oui'),'non' => __('non')], null,array('class' => 'form-control etatatomiseur')); ?>
        </div>
    </div>
        <div class="form-group row">
            {{ Form::label(__("Où gardez-vous cette machine ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                <?php echo Form::select('garde_machines', ["Dans la maison"=>"Dans la maison","Dans un magasin à la maison"=>"Dans un magasin à la maison","Au Champs"=>"Au Champs"], null, array('placeholder' =>__('Selectionner une reponse'),'class' => 'form-control', 'id'=>'garde_machines')); ?>
        </div>
    </div>


</div>
<div id="personneTraitant">
<div class="form-group row">
        <?php echo Form::label(__("Donnez le nom de la personne qui traite vos champs"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::text('nomPersonneTraitant', null, array('placeholder' => __('-----------'),'class' => 'form-control nomPersonneTraitant', 'id'=>'nomPersonneTraitant')); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Donnez son numéro de téléphone"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::text('numeroPersonneTraitant', null, array('placeholder' => __('-----------'),'class' => 'form-control numeroPersonneTraitant phone', 'id'=>'numeroPersonneTraitant')); ?>
        </div>
    </div>

</div>

<div class="form-group row">
            <?php echo Form::label(__("Avez-vous des Equipements de Protection Individuel ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
                 <?php echo Form::select('equipements', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control equipements','required')); ?>
        </div>
    </div>

<hr class="panel-wide">


        <div class="form-group row">
            <?php echo Form::label(__("Votre conjoint(e) fait une activité qui produit de l'argent ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
                 <?php echo Form::select('activiteFemme', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control activiteFemme','required')); ?>
        </div>
    </div>

        <div class="form-group row" id="nomActiviteFemme">
        <?php echo Form::label(__("Quelle Activité ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
              <input type="text" name="nomActiviteFemme" placeholder="" class="form-control nomActiviteFemme" value="{{ old('nomActiviteFemme') }}" >
        </div>
    </div>

        <div class="form-group row">
        <?php echo Form::label(__("Superficie Cacao de votre conjoint(e)(ha)"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
            <input type="number" name="superficieCacaoFemme" placeholder="Nombre" class="form-control superficieCacaoFemme" value="{{ old('superficieCacaoFemme') }}" required min="0">
        </div>
    </div>

        <div class="form-group row" id="champFemme">
            <?php echo Form::label(__("Es-tu prêt à donner une partie de ton champ à votre conjoint(e) ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8" >
                   <?php echo Form::select('champFemme', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control champFemme')); ?>
        </div>
    </div>

        <div class="form-group row" id="nombreHectareFemme">
        <?php echo Form::label(__("Combien d'hectare ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
               <input type="text" name="nombreHectareFemme" placeholder="Ex: 2 ha" class="form-control nombreHectareFemme" value="{{ old('nombreHectareFemme') }}" >
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
    <x-back route="{{ route('staff.suivi.menage.index') }}" />
@endpush

@push('script')
<script type="text/javascript">
    $("#producteur").chained("#localite");

    $(document).ready(function () {
 $('#avoirMachine,#boisChauffe,#etatatomiseur, #nomActiviteFemme,#nombreHectareFemme,#champFemme').hide();

 $('.traitementChamps').change(function(){
var traitementChamps= $('.traitementChamps').val();
  if(traitementChamps=='oui')
  {
   $('#avoirMachine').show('slow');
   $('#personneTraitant').hide('slow');
   $('.nomPersonneTraitant').val('');
   $('.numeroPersonneTraitant').val('');
  }
  else{
   $('#avoirMachine').hide('slow');
   $('#personneTraitant').show('slow');
  }
});

$('.type_machines').change(function(){
var type_machines= $('.type_machines').val();
  if(type_machines=='Atomiseur')
  {
   $('#etatatomiseur').show('slow');
  }
  else{
   $('#etatatomiseur').hide('slow');
  }
});


$('.sources_energies').change(function(){
var sources_energies= $('.sources_energies').val();
  if(sources_energies=='Bois de chauffe')
  {
   $('#boisChauffe').show('slow');
   $('.boisChauffe').css('display','block');
  }
  else{
   $('#boisChauffe').hide('slow');
   $('.boisChauffe').val('');
  }
});

$('.activiteFemme').change(function(){
var activiteFemme= $('.activiteFemme').val();
  if(activiteFemme=='oui')
  {
   $('#nomActiviteFemme').show('slow');
   $('.nomActiviteFemme').css('display','block');
  }
  else{
   $('#nomActiviteFemme').hide('slow');
   $('.nomActiviteFemme').val('');
  }
});

$('.superficieCacaoFemme').change(function(){
var superficieCacaoFemme= $('.superficieCacaoFemme').val();
  if(superficieCacaoFemme ==0)
  {
   $('#champFemme').show('slow');
   $('.champFemme').css('display','block');
  }
  else{
   $('#champFemme').hide('slow');
   $('.champFemme').val('');
  }
});

$('.champFemme').change(function(){
    var champFemme= $('.champFemme').val();
    if(champFemme=='oui')
    {
    $('#nombreHectareFemme').show('slow');
    $('.nombreHectareFemme').css('display','block');
    
    }
    else{
    $('#nombreHectareFemme').hide('slow');
    $('.nombreHectareFemme').val('');
    }
    });

});

 </script>
@endpush