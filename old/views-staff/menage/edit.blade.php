@extends('staff.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
         {!! Form::model($menage, ['method' => 'POST','route' => ['staff.suivi.menage.store', $menage->id],'class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data']) !!}
                        <input type="hidden" name="id" value="{{ $menage->id }}"> 
                        
                        <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Selectionner une localite')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="localite" id="localite" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($localites as $localite)
                                        <option value="{{ $localite->id }}" @selected($localite->id==$menage->producteur->localite->id)>
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
                                        <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}" @selected($producteur->id==$menage->producteur_id)>
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
            <?php echo Form::number('boisChauffe',$menage->boisChauffe, array('placeholder' => __('qty'),'class' => 'form-control boisChauffe','min'=>'1')); ?>
        </div>
    </div>




        <div class="form-group row">
            {{ Form::label(__("Comment jetez-vous les ordures du ménage ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('ordures_menageres', ["Dépotoirs Publique"=>"Dépotoirs Publique","Poubelle de Maison"=>"Poubelle de Maison","Ramassage ordures organisé"=>"Ramassage ordures organisé","Rien de Prévu"=>"Rien de Prévu"], $menage->ordures_menageres, array('placeholder' => __('Selectionner une reponse'),'class' => 'form-control', 'id'=>'ordures_menageres','required')); ?>
        </div>
    </div>


        <div class="form-group row">
            <?php echo Form::label(__("Pratiquez-vous la séparation des déchets ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('separationMenage', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control separationMenage','required')); ?>
        </div>
    </div>


        <div class="form-group row">
            {{ Form::label(__("Comment gérez-vous l’eau de toilette ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('eauxToillette', ["Air Libre"=>"Air Libre","Fosse Septique"=>"Fosse Septique"], $menage->eauxToillette, array('placeholder' => __('Selectionner une reponse'),'class' => 'form-control', 'id'=>'eauxToillette','required')); ?>
        </div>
    </div>



        <div class="form-group row">
            {{ Form::label(__("Comment gérez-vous l’eau de Vaisselle ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                <?php echo Form::select('eauxVaisselle', ["Air Libre"=>"Air Libre","Fosse Septique"=>"Fosse Septique"], $menage->eauxVaisselle, array('placeholder' => __('Selectionner une reponse'),'class' => 'form-control', 'id'=>'eauxVaisselle','required')); ?>
        </div>
    </div>



        <div class="form-group row">
            <?php echo Form::label(__("Existe-t-il un WC pour le ménage ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
                 <?php echo Form::select('wc', ['non' => __('non'),'oui' => __('oui')], $menage->wc,array('class' => 'form-control wc','required')); ?>
        </div>
    </div>


        <div class="form-group row">
            {{ Form::label(__("Où procurez-Vous l’eau potable ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                  <?php echo Form::select('sources_eaux', ["Pompe Hydraulique Villageoise"=>"Pompe Hydraulique Villageoise","SODECI"=>"SODECI","Marigot"=>"Marigot","Puits Individuel"=>"Puits Individuel"], $menage->sources_eaux, array('placeholder' =>__('Selectionner une reponse'),'class' => 'form-control', 'id'=>'sources_eaux','required')); ?>
        </div>
    </div>

    <hr class="panel-wide">

    <div class="form-group row">
            <?php echo Form::label(__("Traitez toujours vous-même vos champs "), null, ['class' => 'col-sm-4 control-label','required']); ?>
            <div class="col-xs-12 col-sm-8">
                  <?php echo Form::select('traitementChamps', ['non' => __('non'),'oui' => __('oui')], $menage->traitementChamps,array('class' => 'form-control traitementChamps','required')); ?>
        </div>
    </div>

<div id="avoirMachine">

        <div class="form-group row">
            {{ Form::label(__("Quel type de machine ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                <?php echo Form::select('type_machines', ["FECA FECA"=>"FECA FECA","Atomiseur"=>"Atomiseur"], $menage->type_machines, array('placeholder' =>__('Selectionner une reponse'),'class' => 'form-control type_machines', 'id'=>'type_machines')); ?>
        </div>
    </div>
    <div class="form-group row" id="etatatomiseur">
            <?php echo Form::label(__("L'Atomiseur est-il en bon état?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
                <?php echo Form::select('etatatomiseur', ['oui' => __('oui'),'non' => __('non')], $menage->etatatomiseur,array('class' => 'form-control etatatomiseur')); ?>
        </div>
    </div>
        <div class="form-group row">
            {{ Form::label(__("Où gardez-vous cette machine ?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                <?php echo Form::select('garde_machines', ["Dans la maison"=>"Dans la maison","Dans un magasin à la maison"=>"Dans un magasin à la maison","Au Champs"=>"Au Champs"], $menage->garde_machines, array('placeholder' =>__('Selectionner une reponse'),'class' => 'form-control', 'id'=>'garde_machines')); ?>
        </div>
    </div>


</div>
<div id="personneTraitant">
<div class="form-group row">
        <?php echo Form::label(__("Donnez le nom de la personne qui traite vos champs"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::text('nomPersonneTraitant', $menage->nomPersonneTraitant, array('placeholder' => __('-----------'),'class' => 'form-control nomPersonneTraitant', 'id'=>'nomPersonneTraitant')); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Donnez son numéro de téléphone"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::text('numeroPersonneTraitant', $menage->numeroPersonneTraitant, array('placeholder' => __('-----------'),'class' => 'form-control numeroPersonneTraitant phone', 'id'=>'numeroPersonneTraitant')); ?>
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
              <input type="text" name="nomActiviteFemme" placeholder="" class="form-control nomActiviteFemme" value="{{ $menage->nomActiviteFemme }}" >
        </div>
    </div>

        <div class="form-group row">
        <?php echo Form::label(__("Superficie Cacao de votre conjoint(e)(ha)"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
            <input type="number" name="superficieCacaoFemme" placeholder="Nombre" class="form-control superficieCacaoFemme" value="{{ $menage->superficieCacaoFemme }}" required min="0">
        </div>
    </div>

        <div class="form-group row" id="champFemme">
            <?php echo Form::label(__("Es-tu prêt à donner une partie de ton champ à votre conjoint(e) ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8" >
                   <?php echo Form::select('champFemme', ['non' => __('non'),'oui' => __('oui')], $menage->champFemme,array('class' => 'form-control champFemme')); ?>
        </div>
    </div>

        <div class="form-group row" id="nombreHectareFemme">
        <?php echo Form::label(__("Combien d'hectare ?"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
               <input type="text" name="nombreHectareFemme" placeholder="Ex: 2 ha" class="form-control nombreHectareFemme" value="{{ $menage->nombreHectareFemme }}" >
        </div>
    </div>
<hr class="panel-wide">


                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block h-45 w-100">@lang('Envoyer')</button>
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

        $('#localite').change(function() {
            $("#producteur").chained("#localite");
        });


        $(document).ready(function () {

        if($('.traitementChamps').val() =='non'){ $('#avoirMachine').hide('slow');}

        if($('.type_machines').val() !='Atomiseur'){ $('#etatatomiseur').hide('slow');}
   if($('.traitementChamps').val() !='non'){ $('#personneTraitant').hide('slow');}

   if($('.sources_energies_id').val() !='Bois de chauffe'){ $('#boisChauffe').hide('slow');}

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
$('.sources_energies_id').change(function(){
var sources_energies_id= $('.sources_energies_id').val();
  if(sources_energies_id=='Bois de chauffe')
  {
   $('#boisChauffe').show('slow');
   $('.boisChauffe').css('display','block');
  }
  else{
   $('#boisChauffe').hide('slow');
  }
});

if($('.activiteFemme').val()=='non'){
    $('#nomActiviteFemme').hide('slow');
}
$('.activiteFemme').change(function(){
var activiteFemme= $('.activiteFemme').val();
  if(activiteFemme=='oui')
  {
   $('#nomActiviteFemme').show('slow');
   $('.nomActiviteFemme').css('display','block');
  }
  else{
   $('#nomActiviteFemme').hide('slow');
  }
});


if($('.superficieCacaoFemme').val()>0){
    $('#champFemme').hide('slow');
    $('.champFemme').css('display','block');
}
$('.superficieCacaoFemme').change(function(){
var superficieCacaoFemme= $('.superficieCacaoFemme').val();
  if(superficieCacaoFemme =='non')
  {
   $('#champFemme').show('slow');

  }
  else{
   $('#champFemme').hide('slow');
  }
});

if($('.champFemme').val()=='non'){
    $('#nombreHectareFemme').hide('slow');
}
$('.champFemme').change(function(){
    var champFemme= $('.champFemme').val();
    if(champFemme=='oui')
    {
    $('#nombreHectareFemme').show('slow');
    $('.nombreHectareFemme').css('display','block');
    }
    else{
    $('#nombreHectareFemme').hide('slow');
    }
    });


});
    </script>
@endpush