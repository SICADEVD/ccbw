@extends('staff.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
                    {!! Form::open(array('route' => ['staff.traca.producteur.store'],'method'=>'POST','class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data')) !!} 
                        
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Selectionner une localite')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="localite" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($localites as $localite)
                                        <option value="{{ $localite->id }}" @selected(old('localite'))>
                                            {{ __($localite->nom) }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>  
                       
 
<div class="form-group row">
    <?php echo Form::label(__('Accord de consentement du producteur'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
           <?php echo Form::select('consentement',['oui'=>'Oui','non'=>'Non'] , null, array('class' => 'form-control')); ?>
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(__('Statut'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
           <?php echo Form::select('statut',['Certifie'=>'Certifie','Candidat'=>'Candidat'] , null, array('class' => 'form-control statut')); ?>
    </div>
</div>
<div id="certificat">

<div class="form-group row">
    <?php echo Form::label(__('Année de certification'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
           <?php echo Form::number('certificat', null, array('class' => 'form-control certificat','min'=>'1990', 'max'=>gmdate('Y'))); ?>
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(__('Code producteur'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
    <?php echo Form::text('codeProd', null, array('placeholder' => __('Code producteur'),'class' => 'form-control')); ?>
    </div>
</div>

</div>

    <div class="form-group row">
    <?php echo Form::label(__('Nom du producteur'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
    <?php echo Form::text('nom', null, array('placeholder' => __('Nom du producteur'),'class' => 'form-control', 'required')); ?>
    </div>
</div>

    <div class="form-group row">
    <?php echo Form::label(__('Prenoms du producteur'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
    <?php echo Form::text('prenoms', null, array('placeholder' => __('Prenoms du producteur'),'class' => 'form-control', 'required')); ?>
    </div>
</div>


    <div class="form-group row">
        <?php echo Form::label(__('Genre'), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
        <?php echo Form::select('sexe', ['Homme' => 'Homme', 'Femme' => 'Femme'], null,array('class' => 'form-control', 'required')); ?>

    </div>
</div>



    <div class="form-group row">
    <?php echo Form::label(__('Nationalité'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
    <?php echo Form::select('nationalite', ["Afghane"=>"Afghane","Albanaise"=>"Albanaise","Algerienne"=>"Algerienne","Allemande"=>"Allemande","Americaine"=>"Americaine","Andorrane"=>"Andorrane","Angolaise"=>"Angolaise","Antiguaise-Et-Barbudienne"=>"Antiguaise-Et-Barbudienne","Argentine"=>"Argentine","Armenienne"=>"Armenienne","Australienne"=>"Australienne","Autrichienne"=>"Autrichienne","Azerbaidjanaise"=>"Azerbaidjanaise","Bahamienne"=>"Bahamienne","Bahreinienne"=>"Bahreinienne","Bangladaise"=>"Bangladaise","Barbadienne"=>"Barbadienne","Belge"=>"Belge","Belizienne"=>"Belizienne","Beninoise"=>"Beninoise","Bhoutanaise"=>"Bhoutanaise","Bielorusse"=>"Bielorusse","Birmane"=>"Birmane","Bissau-Guineenne"=>"Bissau-Guineenne","Bolivienne"=>"Bolivienne","Bosnienne"=>"Bosnienne","Botswanaise"=>"Botswanaise","Bresilienne"=>"Bresilienne","Britannique"=>"Britannique","Bruneienne"=>"Bruneienne","Bulgare"=>"Bulgare","Burkinabee"=>"Burkinabee","Burundaise"=>"Burundaise","Cambodgienne"=>"Cambodgienne","Camerounaise"=>"Camerounaise","Canadienne"=>"Canadienne","Cap-Verdienne"=>"Cap-Verdienne","Centrafricaine"=>"Centrafricaine","Chilienne"=>"Chilienne","Chinoise"=>"Chinoise","Chypriote"=>"Chypriote","Colombienne"=>"Colombienne","Comorienne"=>"Comorienne","Congolaise"=>"Congolaise","Congolaise"=>"Congolaise","Cookienne"=>"Cookienne","Costaricaine"=>"Costaricaine","Croate"=>"Croate","Cubaine"=>"Cubaine","Danoise"=>"Danoise","Djiboutienne"=>"Djiboutienne","Dominicaine"=>"Dominicaine","Dominiquaise"=>"Dominiquaise","Egyptienne"=>"Egyptienne","Emirienne"=>"Emirienne","Equato-Guineenne"=>"Equato-Guineenne","Equatorienne"=>"Equatorienne","Erythreenne"=>"Erythreenne","Espagnole"=>"Espagnole","Est-Timoraise"=>"Est-Timoraise","Estonienne"=>"Estonienne","Ethiopienne"=>"Ethiopienne","Fidjienne"=>"Fidjienne","Finlandaise"=>"Finlandaise","Francaise"=>"Francaise","Gabonaise"=>"Gabonaise","Gambienne"=>"Gambienne","Georgienne"=>"Georgienne","Ghaneenne"=>"Ghaneenne","Grenadienne"=>"Grenadienne","Guatemalteque"=>"Guatemalteque","Guineenne"=>"Guineenne","Guyanienne"=>"Guyanienne","Haitienne"=>"Haitienne","Hellenique"=>"Hellenique","Hondurienne"=>"Hondurienne","Hongroise"=>"Hongroise","Indienne"=>"Indienne","Indonesienne"=>"Indonesienne","Irakienne"=>"Irakienne","Iranienne"=>"Iranienne","Irlandaise"=>"Irlandaise","Islandaise"=>"Islandaise","Israelienne"=>"Israelienne","Italienne"=>"Italienne","Ivoirienne"=>"Ivoirienne","Jamaicaine"=>"Jamaicaine","Japonaise"=>"Japonaise","Jordanienne"=>"Jordanienne","Kazakhstanaise"=>"Kazakhstanaise","Kenyane"=>"Kenyane","Kirghize"=>"Kirghize","Kiribatienne"=>"Kiribatienne","Kittitienne"=>"Kittitienne","Koweitienne"=>"Koweitienne","Laotienne"=>"Laotienne","Lesothane"=>"Lesothane","Lettone"=>"Lettone","Libanaise"=>"Libanaise","Liberienne"=>"Liberienne","Libyenne"=>"Libyenne","Liechtensteinoise"=>"Liechtensteinoise","Lituanienne"=>"Lituanienne","Luxembourgeoise"=>"Luxembourgeoise","Macedonienne"=>"Macedonienne","Malaisienne"=>"Malaisienne","Malawienne"=>"Malawienne","Maldivienne"=>"Maldivienne","Malgache"=>"Malgache","Maliennes"=>"Maliennes","Maltaise"=>"Maltaise","Marocaine"=>"Marocaine","Marshallaise"=>"Marshallaise","Mauricienne"=>"Mauricienne","Mauritanienne"=>"Mauritanienne","Mexicaine"=>"Mexicaine","Micronesienne"=>"Micronesienne","Moldave"=>"Moldave","Monegasque"=>"Monegasque","Mongole"=>"Mongole","Montenegrine"=>"Montenegrine","Mozambicaine"=>"Mozambicaine","Namibienne"=>"Namibienne","Nauruane"=>"Nauruane","Neerlandaise"=>"Neerlandaise","Neo-Zelandaise"=>"Neo-Zelandaise","Nepalaise"=>"Nepalaise","Nicaraguayenne"=>"Nicaraguayenne","Nigeriane"=>"Nigeriane","Nigerienne"=>"Nigerienne","Niueenne"=>"Niueenne","Nord-Coreenne"=>"Nord-Coreenne","Norvegienne"=>"Norvegienne","Omanaise"=>"Omanaise","Ougandaise"=>"Ougandaise","Ouzbeke"=>"Ouzbeke","Pakistanaise"=>"Pakistanaise","Palaosienne"=>"Palaosienne","Palestinienne"=>"Palestinienne","Panameenne"=>"Panameenne","Papouane-Neo-Guineenne"=>"Papouane-Neo-Guineenne","Paraguayenne"=>"Paraguayenne","Peruvienne"=>"Peruvienne","Philippine"=>"Philippine","Polonaise"=>"Polonaise","Portugaise"=>"Portugaise","Qatarienne"=>"Qatarienne","Roumaine"=>"Roumaine","Russe"=>"Russe","Rwandaise"=>"Rwandaise","Saint-Lucienne"=>"Saint-Lucienne","Saint-Marinaise"=>"Saint-Marinaise","Saint-Vincentaise"=>"Saint-Vincentaise","Salomonaise"=>"Salomonaise","Salvadorienne"=>"Salvadorienne","Samoane"=>"Samoane","Santomeenne"=>"Santomeenne","Saoudienne"=>"Saoudienne","Senegalaise"=>"Senegalaise","Serbe"=>"Serbe","Seychelloise"=>"Seychelloise","Sierra-Leonaise"=>"Sierra-Leonaise","Singapourienne"=>"Singapourienne","Slovaque"=>"Slovaque","Slovene"=>"Slovene","Somalienne"=>"Somalienne","Soudanaise"=>"Soudanaise","Sri-Lankaise"=>"Sri-Lankaise","Sud-Africaine"=>"Sud-Africaine","Sud-Coreenne"=>"Sud-Coreenne","Sud-Soudanaise"=>"Sud-Soudanaise","Suedoise"=>"Suedoise","Suisse"=>"Suisse","Surinamaise"=>"Surinamaise","Swazie"=>"Swazie","Syrienne"=>"Syrienne","Tadjike"=>"Tadjike","Tanzanienne"=>"Tanzanienne","Tchadienne"=>"Tchadienne","Tcheque"=>"Tcheque","Thailandaise"=>"Thailandaise","Togolaise"=>"Togolaise","Tonguienne"=>"Tonguienne","Trinidadienne"=>"Trinidadienne","Tunisienne"=>"Tunisienne","Turkmene"=>"Turkmene","Turque"=>"Turque","Tuvaluane"=>"Tuvaluane","Ukrainienne"=>"Ukrainienne","Uruguayenne"=>"Uruguayenne","Vanuatuane"=>"Vanuatuane","Vaticane"=>"Vaticane","Venezuelienne"=>"Venezuelienne","Vietnamienne"=>"Vietnamienne","Yemenite"=>"Yemenite","Zambienne"=>"Zambienne","Zimbabweenne"=>"Zimbabweenne"], null,array('class' => 'form-control','placeholder' => __('Selectionner une option'), 'required')); ?>

    </div>
</div>

    <div class="form-group row">
    <?php echo Form::label(__('Date de naissance'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
         <?php echo Form::date('dateNaiss', null,array('class' => 'form-control naiss', 'id'=>'datenais', 'required') ); ?>
    </div>
</div>

    <div class="form-group row">
        <?php echo Form::label(__('Numero de téléphone 1'), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('phone1',  null, array('class' => 'form-control phone', 'required')); ?>
    </div>
</div>

    <div class="form-group row">
        <?php echo Form::label(__('Numero de téléphone 2'), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::text('phone2',  null, array('class' => 'form-control phone')); ?>
    </div>
</div>

<div class="form-group row">
    <?php echo Form::label(__("Niveau d'étude"), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
           <?php echo Form::select('niveau_etude', ["Primaire (Minimum CE1)"=>"Primaire (Minimum CE1)","Secondaire 1er cycle"=>"Secondaire 1er cycle","Secondaire 2e cycle"=>"Secondaire 2e cycle","Supérieur"=>"Supérieur","Ecole professionnelle"=>"Ecole professionnelle","Aucun"=>"Aucun"], null, array('placeholder' => __('Selectionner une option'),'class' => 'form-control', 'required')); ?>
    </div>
</div>

    <div class="form-group row">
    <?php echo Form::label(__('Type de pièces'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
         <?php echo Form::select('type_piece', ["CNI"=>"CNI","Carte Consulaire"=>"Carte Consulaire","Passeport"=>"Passeport","Attestation"=>"Attestation","Extrait de naissance"=>"Extrait de naissance"], null, array('placeholder' => __('Selectionner une option'),'class' => 'form-control','required')); ?>
    </div>
</div>

    <div class="form-group row">
    <?php echo Form::label(__('N° de la pièce'), null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
      <?php echo Form::text('numPiece', null, array('placeholder' => __('N° de la pièce'),'class' => 'form-control','required')); ?>
    </div>
</div>

<div class="form-group row">
<?php echo Form::label(__("Pièce d'identité Recto"), null, ['class' => 'col-sm-4 control-label']); ?>
<div class="col-xs-12 col-sm-8">
     <input type="file" name="copiecarterecto" value="" accept="image/*" class="form-control dropify-fr" placeholder="Choisir une fichier" id="copiecarterecto">

</div>
</div>
<div class="form-group row">
<?php echo Form::label(__("Pièce d'identité verso"), null, ['class' => 'col-sm-4 control-label']); ?>
<div class="col-xs-12 col-sm-8">
     <input type="file" name="copiecarteverso" value="" accept="image/*" class="form-control dropify-fr" placeholder="Choisir une fichier" id="copiecarteverso">

</div>
</div>
<div class="form-group row">

<?php echo Form::label(__('Photo du producteur'), null, ['class' => 'col-sm-4 control-label']); ?>
<div class="col-xs-12 col-sm-8">
      <input type="file" name="picture" accept="image/*" class="form-control dropify-fr" placeholder="Choisir une image" id="image">
</div>
</div> 
<hr class="panel-wide">
 
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45"> @lang('Envoyer')</button>
                        </div>
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.traca.producteur.index') }}" />
@endpush

@push('script')
<script type="text/javascript">
   
$('#listecultures,#gardePapiersChamps,#numeroCompteMM').hide();

$('.statut').change(function(){
var statut= $('.statut').val();
  if(statut=='Candidat')
  {
   $('#certificat').hide('slow');
   $('.certificat').val('');
  }
  else{
   $('#certificat').show('slow');

  }
});

$('.autresCultures').change(function(){
var autresCultures= $('.autresCultures').val();
  if(autresCultures=='oui')
  {
   $('#listecultures').show('slow');
  }
  else{
   $('#listecultures').hide('slow');
   $('.listecultures').val('');
  }
});

$('.papiersChamps').change(function(){
var papiersChamps= $('.papiersChamps').val();
  if(papiersChamps=='oui')
  {
   $('#gardePapiersChamps').show('slow');
  }
  else{
   $('#gardePapiersChamps').hide('slow');
   $('.gardePapiersChamps').val('');
  }
});

$('.mobileMoney').change(function(){
var mobileMoney= $('.mobileMoney').val();
  if(mobileMoney=='oui')
  {
   $('#numeroCompteMM').show('slow');
  }
  else{
   $('#numeroCompteMM').hide('slow');
   $('.numeroCompteMM').val('');
  }
});

 </script>
 <script type="text/javascript">



 $('#superficie').hide();
 $('.foretsjachere').change(function(){
 var foretsjachere= $('.foretsjachere').val();
   if(foretsjachere=='oui')
   {
    $('#superficie').show('slow');
   }
   else{
    $('#superficie').hide('slow');
    $('.superficie').val('');
   }
 });

 </script>
@endpush