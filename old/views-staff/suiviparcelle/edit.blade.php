@extends('staff.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
         {!! Form::model($suiviparcelle, ['method' => 'POST','route' => ['staff.suivi.parcelles.store', $suiviparcelle->id],'class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data']) !!}
                        <input type="hidden" name="id" value="{{ $suiviparcelle->id }}"> 
                        
                        <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Selectionner une localite')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="localite" id="localite" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($localites as $localite)
                                        <option value="{{ $localite->id }}" @selected($localite->id==$suiviparcelle->parcelle->producteur->localite->id)>
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
                                        <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}" @selected($producteur->id==$suiviparcelle->parcelle->producteur_id)>
                                            {{ $producteur->nom }} {{ $producteur->prenoms }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Parcelle')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="parcelle" id="parcelle" onchange="getSuperficie()" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($parcelles as $parcelle)
                                        <option value="{{ $parcelle->id }}" data-superficie="{{ $parcelle->superficie }}" data-chained="{{ $parcelle->producteur->id }}" @selected($parcelle->id==$suiviparcelle->parcelle_id)>
                                           {{ __('Parcelle')}} {{ $parcelle->codeParc }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
        <?php echo Form::label(__('Campagne'), null, ['class' => 'col-sm-4 control-label required']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::select('campagne', $campagnes, null, array('class' => 'form-control campagnes', 'id'=>'campagnes','required'=>'required')); ?>
        </div>
    </div>
    <hr class="panel-wide">
    <div class="form-group row">
            {{ Form::label(__("Variété de cacao se trouvant sur la Parcelle"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('varietes_cacao', ["Tout Venant"=>"Tout Venant","Mercedes"=>"Mercedes","Autre"=>"Autre"], $suiviparcelle->varietes_cacao, array('placeholder' => __("Selectionner une option"),'class' => 'form-control varietes_cacao', 'id'=>'varietes_cacao')); ?>
        </div>
    </div>

        <div class="form-group row" id="autreVariete">
            <?php echo Form::label(__("Préciser l'autre variété"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('autreVariete', $suiviparcelle->autreVariete,array('placeholder' => __("Préciser l'autre variété"),'class' => 'form-control autreVariete')); ?>
        </div>
    </div>

        <div class="form-group row">
            <?php echo Form::label(__("Y a-t-il un cours d’eau dans la parcelle ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('existeCoursEaux', ['oui' => __('oui'), 'non' => __('non')], $suiviparcelle->existeCoursEaux,array('placeholder' =>__("Selectionner une option"),'class' => 'form-control existeCoursEaux')); ?>
        </div>
    </div>


        <div class="form-group row" id="courseaux">
            {{ Form::label(__("Quel type de cours d'eau avez-vous?"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('cours_eaux', ["Bas-fond"=>"Bas-fond","Marigot"=>"Marigot","Rivière"=>"Rivière","Source d'eau Naturelle"=>"Source d'eau Naturelle"], $suiviparcelle->cours_eaux, array('placeholder' =>__("Selectionner une option"),'class' => 'form-control cours_eaux', 'id'=>'cours_eaux_id')); ?>
        </div>
    </div>




        <div class="form-group row">
            <?php echo Form::label(__("Y a-t-il une pente dans la Parcelle ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('pente', ['oui' => __('oui'), 'non' => __('non')], $suiviparcelle->pente,array('placeholder' =>__("Selectionner une option"),'class' => 'form-control pente')); ?>
        </div>
    </div>

    <hr class="panel-wide">

    <div class="form-group row">
            <?php echo Form::label(__("Nombre d'arbre a Ombrage observé dans la Parcelle"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
    <table class="table table-striped table-bordered">
    <tbody id="product_area">
    <?php

if($suiviparcelle->ombrage)
{ 
$i=0;
$a=1;
foreach($suiviparcelle->ombrage as $data) {
   ?>
<tr>
         <td class="row">
         <div class="col-xs-12 col-sm-12 bg-success"><badge  class="btn  btn-outline--warning h-45 btn-sm">@lang("Nombre d'arbre a Ombrage observé dans la Parcelle") <?php echo $a; ?></badge></div>
         <div class="col-xs-12 col-sm-6">
     <div class="form-group row">
         {{ Form::label(__('Variete'), null, ['class' => 'col-sm-4 control-label']) }}
         <input type="text" name="varietesOmbrage[]" placeholder="Variété arbre" id="varietesOmbrage-<?php echo $a; ?>" class="form-control" value="<?php echo $data->ombrage; ?>">
     </div>
     </div>

     <div class="col-xs-12 col-sm-6">
     <div class="form-group row">
         {{ Form::label(__('Nombre'), null, ['class' => 'col-sm-4 control-label']) }}
         <input type="number" name="nombreOmbrage[]" placeholder="Nombre d'arbre" id="nombreOmbrage-<?php echo $a; ?>" class="form-control" value="<?php echo $data->nombre; ?>" min="1">
     </div>
     </div>
     <?php if($a>1):?>
<div class="col-xs-12 col-sm-8"><button type="button" id="<?php echo $a; ?>" class="removeRow btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div>
<?php endif; ?>
     </td>
     </tr>
     <?php
   $a++;
    $i++;
}
}else{
?>
<tr>
         <td class="row">
         <div class="col-xs-12 col-sm-8 bg-success"><badge  class="btn  btn-outline--warning h-45 btn-sm">@lang("Information Arbre à Ombrage")</badge></div>
         <div class="col-xs-12 col-sm-8">
     <div class="form-group row">
         {{ Form::label(__('Variete'), null, ['class' => 'col-sm-4 control-label']) }}
         <input type="text" name="varietesOmbrage[]" placeholder="Variété arbre" id="varietesOmbrage-1" class="form-control">
     </div>
     </div>

     <div class="col-xs-12 col-sm-8">
     <div class="form-group row">
         {{ Form::label(__('Nombre'), null, ['class' => 'col-sm-4 control-label']) }}
         <input type="number" name="nombreOmbrage[]" placeholder="..." id="nombreOmbrage-1" class="form-control " min="1">
     </div>
     </div>

     </td>
     </tr>
<?php
}
?>
  

    </tbody>
    <tfoot style="background: #e3e3e3;">
      <tr>

        <td colspan="3">
        <button id="addRow" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
</td>
<tr>
    </tfoot>
</table>
    </div>
    </div>
    <hr class="panel-wide">
        <div class="form-group row">
            <?php echo Form::label(__("Quelle variété d’arbre ombrage souhaiterais-tu avoir ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('varieteAbres', $suiviparcelle->varieteAbres,array('placeholder' => __("Entrer le nom de la variété d'arbre"),'class' => 'form-control varieteAbres')); ?>
        </div>
    </div>


        <div class="form-group row">
            <?php echo Form::label(__("Nombre de sauvageons observé dans la parcelle"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::number('nombreSauvageons',$suiviparcelle->nombreSauvageons,array('placeholder' => __('Nombre'),'class' => 'form-control nombreSauvageons','min'=>'0')); ?>
        </div>
    </div>

    <div class="form-group row">
            <?php echo Form::label(__("As-tu bénéficié d’arbres agro-forestiers l’an dernier ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('arbresagroforestiers', ['non' => __('non'),'oui' => __('oui')], $suiviparcelle->arbresagroforestiers,array('class' => 'form-control arbresagroforestiers')); ?>
        </div>
    </div>

    <div class="form-group row" id="agroforestiersobtenus">
            <?php echo Form::label(__("Donner leur type et le nombre"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
    <table class="table table-striped table-bordered">
    <tbody id="agroforestiers_area">
    <?php
        if($suiviparcelle->agroforesterie)
        {
        ?>

        <?php
        $i=0;
        $a=1;
        foreach ($suiviparcelle->agroforesterie as $data) {
           ?>
 <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-success"><badge  class="btn  btn-outline--warning h-45 btn-sm">@lang("Arbre agro-forestier") <?php echo $a; ?></badge></div>
            <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Type'), null, ['class' => '']) }}
            <input type="text" name="agroforestiers[]" placeholder="..." id="agroforestiers-<?php echo $a; ?>" value="<?php echo $data->agroforesterie; ?>" class="form-control">
        </div>
        </div>

        <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Nombre'), null, ['class' => '']) }}
            <input type="number" name="nombreagroforestiers[]" placeholder="..." id="nombreagroforestiers-<?php echo $a; ?>" class="form-control " value="<?php echo $data->nombre; ?>" min="1">
        </div>
        </div>
        <?php if($a>1):?>
        <div class="col-xs-12 col-sm-8"><button type="button" id="<?php echo $a; ?>" class="removeRowagroforestiers btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div>
        <?php endif; ?>
        </td>
        </tr>
        <?php
           $a++;
            $i++;
        }
    }else{
        ?>
         <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-success"><badge  class="btn  btn-outline--warning h-45 btn-sm">@lang("Arbre agro-forestier")</badge></div>
            <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Type'), null, ['class' => '']) }}
            <input type="text" name="agroforestiers[]" placeholder="Type arbre" id="agroforestiers-1" class="form-control">
        </div>
        </div>

        <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Nombre'), null, ['class' => '']) }}
            <input type="number" name="nombreagroforestiers[]" placeholder="Nombre d'arbre" id="nombreagroforestiers-1" class="form-control " min="1">
        </div>
        </div>

        </td>
        </tr>
        <?php
        }
        ?>
 

    </tbody>
    <tfoot style="background: #e3e3e3;">
      <tr>

        <td colspan="3">
        <button id="addRowagroforestiers" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
</td>
<tr>
    </tfoot>
</table>
    </div>
    </div>
    <hr class="panel-wide">

        <div class="form-group row">
            <?php echo Form::label(__("Activité de Taille dans la Parcelle"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('activiteTaille', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Eleve' => __('Eleve')], $suiviparcelle->activiteTaille,array('class' => 'form-control activiteTaille')); ?>
        </div>
    </div>


        <div class="form-group row">
            <?php echo Form::label(__("Activité d’Egourmandage dans la Parcelle"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('activiteEgourmandage', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Eleve' => __('Eleve')], $suiviparcelle->activiteEgourmandage,array('class' => 'form-control activiteEgourmandage')); ?>
        </div>
    </div>


        <div class="form-group row">
            <?php echo Form::label(__("Activité de désherbage Manuel dans la Parcelle"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('activiteDesherbageManuel', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Eleve' => __('Eleve')], $suiviparcelle->activiteDesherbageManuel,array('class' => 'form-control activiteDesherbageManuel')); ?>
        </div>
    </div>


        <div class="form-group row">
            <?php echo Form::label(__("Activité de Récolte Sanitaire dans la Parcelle"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('activiteRecolteSanitaire', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Eleve' => __('Eleve')], $suiviparcelle->activiteRecolteSanitaire,array('class' => 'form-control activiteRecolteSanitaire')); ?>
        </div>
    </div>

    <hr class="panel-wide">

             <div class="form-group row">
                 <?php echo Form::label(__("Intrant NPK Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
                 <div class="col-xs-12 col-sm-8">
                 <?php echo Form::label(__("Nombre de sacs utilisé de NPK"), null, ['class' => 'control-label']); ?>
                 <?php echo Form::hidden('intrantNPK', 'NPK',array('class' => 'form-control intrant')); ?>
                 <?php echo Form::number('nombresacsNPK', $suiviparcelle->nombresacsNPK,array('placeholder' => 'Nombre de sacs utilisé...','class' => 'form-control','min'=>'0') ); ?>
             </div>
         </div>

             <div class="form-group row">
             <?php echo Form::label(__("Intrant Fiente Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
             <div class="col-xs-12 col-sm-8">
             <?php echo Form::label(__("Nombre de sacs utilisé de Fiente"), null, ['class' => 'control-label']); ?>
             <?php echo Form::hidden('intrantFiente', 'Fiente',array('class' => 'form-control intrant')); ?>
                 <?php echo Form::number('nombresacsFiente', $suiviparcelle->nombresacsFiente,array('placeholder' => 'Nombre de sacs utilisé...','class' => 'form-control','min'=>'0') ); ?>
             </div>
         </div>
         <div class="form-group row">
             <?php echo Form::label(__("Intrant Composte Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
             <div class="col-xs-12 col-sm-8">
             <?php echo Form::label(__("Nombre de sacs utilisé de Composte"), null, ['class' => 'control-label']); ?>
             <?php echo Form::hidden('intrantComposte', 'Composte',array('class' => 'form-control intrant')); ?>
                 <?php echo Form::number('nombresacsComposte', $suiviparcelle->nombresacsComposte,array('placeholder' => 'Nombre de sacs utilisé...','class' => 'form-control','min'=>'0') ); ?>
             </div>
         </div>

         <hr class="panel-wide">
         <div class="form-group row">
            <?php echo Form::label(__("Présence de Pourriture Brune"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('presencePourritureBrune', ['Assez' => __('Assez'), 'Moins' => __('Moins')], $suiviparcelle->presencePourritureBrune,array('class' => 'form-control presencePourritureBrune')); ?>
        </div>
    </div>

        <div class="form-group row">
            <?php echo Form::label(__("Présence de Bio-Agresseur"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('presenceBioAgresseur', ['Assez' =>__('Assez'), 'Moins' => __('Moins')], $suiviparcelle->presenceBioAgresseur,array('class' => 'form-control presenceBioAgresseur')); ?>
        </div>
    </div>


        <div class="form-group row">
            <?php echo Form::label(__("Présence d’Insectes Ravageurs"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('presenceInsectesRavageurs', ['Assez' =>__('Assez'), 'Moins' => __('Moins')], $suiviparcelle->presenceInsectesRavageurs,array('class' => 'form-control presenceInsectesRavageurs')); ?>
        </div>
    </div>

        <div class="form-group row">
            <?php echo Form::label(__("Présence de Fourmis Rouge"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('presenceFourmisRouge', ['Assez' => __('Assez'), 'Moins' => __('Moins')], $suiviparcelle->presenceFourmisRouge,array('class' => 'form-control presenceFourmisRouge')); ?>
        </div>
    </div>

        <div class="form-group row">
            <?php echo Form::label(__("Présence d’Araignée"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('presenceAraignee', ['Assez' => __('Assez'), 'Moins' => __('Moins')], $suiviparcelle->presenceAraignee,array('class' => 'form-control presenceAraignee')); ?>
        </div>
    </div>

        <div class="form-group row">
            <?php echo Form::label(__("Présence de Ver de Terre"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('presenceVerTerre', ['Assez' => __('Assez'), 'Moins' => __('Moins')], $suiviparcelle->presenceVerTerre,array('class' => 'form-control presenceVerTerre')); ?>
        </div>
    </div>

        <div class="form-group row">
            <?php echo Form::label(__("Présence de Mente Religieuse"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
                 <?php echo Form::select('presenceMenteReligieuse', ['Assez' => __('Assez'), 'Moins' => __('Moins')], $suiviparcelle->presenceMenteReligieuse,array('class' => 'form-control presenceMenteReligieuse')); ?>
        </div>
    </div>

    <div class="form-group row">
            <?php echo Form::label(__("Existe t'il la maladie du swollen shoot dans la parcelle ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('presenceSwollenShoot', ['oui' => __('oui'), 'non' => __('non')], $suiviparcelle->presenceSwollenShoot,array('class' => 'form-control presenceSwollenShoot')); ?>
        </div>
    </div>
    <hr class="panel-wide">
    <div class="form-group row">
            <?php echo Form::label(__("Présence d’insectes parasites ou ravageurs ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
                 <?php echo Form::select('presenceInsectesParasites', ['non' => __('non'), 'oui' => __('oui')], $suiviparcelle->presenceInsectesParasites,array('class' => 'form-control presenceInsectesParasites')); ?>
        </div>
    </div>
    <div class="form-group row" id="presenceInsectesParasitesRavageurs">
            <?php echo Form::label(__("Identification des insectes parasites ou ravageurs"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
    <table class="table table-striped table-bordered">
    <tbody id="insectesParasites_area">
    <?php
        if($suiviparcelle->parasite)
        { 
        $i=0;
        $a=1;
        foreach($suiviparcelle->parasite as $data) {
           ?>
 <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-success"><badge  class="btn  btn-outline--warning h-45 btn-sm">@lang("Insectes parasites ou ravageurs") <?php echo $a; ?></badge></div>
            <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Nom'), null, ['class' => '']) }}
            <input type="text" name="insectesParasites[]" placeholder="..." id="insectesParasites-<?php echo $a; ?>" class="form-control" value="<?php echo $data->parasite; ?>" >
        </div>
        </div>

        <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Quantite'), null, ['class' => '']) }}
            <select name="nombreinsectesParasites[]" id="nombreinsectesParasites-<?php echo $a; ?>" class="form-control nombreinsectesParasites" >
            <option value="Assez"  <?php if('Assez'==$data->nombre) echo 'selected'; ?>>@lang('Assez')</option>
            <option value="Moins" <?php if('Moins'==$data->nombre) echo 'selected'; ?>>@lang('Moins')</option>

                                                </select>
        </div>
        </div>
        <?php if($a>1):?>
        <div class="col-xs-12 col-sm-8"><button type="button" id="<?php echo $a; ?>" class="removeRowinsectesParasites btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div>
        <?php endif; ?>
        </td>
        </tr>
        <?php
           $a++;
            $i++;
        }
    }else{
        ?>
        <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-success"><badge  class="btn  btn-outline--warning h-45 btn-sm">@lang("Insectes parasites ou ravageurs")</badge></div>
            <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Nom'), null, ['class' => '']) }}
            <input type="text" name="insectesParasites[]" placeholder="..." id="insectesParasites-1" class="form-control">
        </div>
        </div>

        <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Quantite'), null, ['class' => '']) }}
            <?php echo Form::select('nombreinsectesParasites[]',['Assez' => __('Assez'), 'Moins' => __('Moins')], null,array('class' => 'form-control nombreinsectesParasites', 'id'=>'nombreinsectesParasites-1')); ?>
        </div>
        </div>

        </td>
        </tr>
        <?php
        }
        ?>
 

    </tbody>
    <tfoot style="background: #e3e3e3;">
      <tr>

        <td colspan="3">
        <button id="addRowinsectesParasites" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
</td>
<tr>
    </tfoot>
</table>
    </div>
    </div>
    <hr class="panel-wide">

    <div class="form-group row">
    <?php echo Form::label('Les insecticides utilisés sur la parcelle', null, ['class' => 'col-sm-4 control-label']); ?>
    <div class="col-xs-12 col-sm-8">
    <table class="table table-striped table-bordered">
    <tbody>

 <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-primary-800"><badge  class="btn btn-primary btn-sm">@lang("Insecticide utilisé dans la parcelle")</badge></div>
            <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Nom'), null, ['class' => '']) }}
            <?php echo Form::text('nomInsecticide', null,array('class' => 'form-control') ); ?>
        </div>
        </div>

        <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__("Nombre de Boite"), null, ['class' => '']) }}
            <?php echo Form::number('nombreInsecticide', null,array('class' => 'form-control','min'=>'1') ); ?>
        </div>
        </div>

        </td>
        </tr>
        <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-primary-800"><badge  class="btn btn-primary btn-sm">@lang("Fongicide Utilisé dans la parcelle")</badge></div>
            <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Nom'), null, ['class' => '']) }}
            <?php echo Form::text('nomFongicide', $suiviparcelle->nomFongicide,array('class' => 'form-control') ); ?>
        </div>
        </div>

        <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__("Nombre de Boite"), null, ['class' => '']) }}
            <?php echo Form::number('nombreFongicide', $suiviparcelle->nombreFongicide,array('class' => 'form-control','min'=>'1') ); ?>

        </div>
        </div>

        </td>
        </tr>
        <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-primary-800"><badge  class="btn btn-primary btn-sm">@lang("Herbicide Utilisé dans la parcelle")</badge></div>
            <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__('Nom'), null, ['class' => '']) }}
            <?php echo Form::text('nomHerbicide', $suiviparcelle->nomHerbicide,array('class' => 'form-control') ); ?>
        </div>
        </div>

        <div class="col-xs-12 col-sm-6">
        <div class="form-group row">
            {{ Form::label(__("Nombre de Boite"), null, ['class' => '']) }}
            <?php echo Form::number('nombreHerbicide', $suiviparcelle->nombreHerbicide,array('class' => 'form-control','min'=>'1') ); ?>

        </div>
        </div>

        </td>
        </tr>
    </tbody>

</table>
</div>
</div>

<hr class="panel-wide">
<div class="form-group row">
            {{ Form::label(__("Citez les animaux que vous rencontrez dans les champs"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <table class="table table-striped table-bordered">
    <tbody id="animauxRencontres_area">
    <?php
        if($suiviparcelle->animal)
        {
        $i=0;
        $a=1;
        foreach ($suiviparcelle->animal as $data) {
           ?>
 <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-success"><badge  class="btn  btn-outline--warning h-45 btn-sm">@lang('Animal')  <?php echo $a; ?></badge></div>
            <div class="col-xs-12 col-sm-12">
        <div class="form-group row">
            {{ Form::label(__('Nom'), null, ['class' => '']) }}
            <input type="text" name="animauxRencontres[]" placeholder="Nom animal" id="animauxRencontres-<?php echo $a; ?>" class="form-control" value="<?php echo $data->animal; ?>">
        </div>
        </div>
        <?php if($a>1):?>
        <div class="col-xs-12 col-sm-8"><button type="button" id="<?php echo $a; ?>" class="removeRowanimauxRencontres btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div>
        <?php endif; ?>
        </td>
        </tr>
        <?php
           $a++;
            $i++;
        }
    }else{
        ?>
 <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12 bg-success"><badge  class="btn  btn-outline--warning h-45 btn-sm">@lang('Animal')</badge></div>
            <div class="col-xs-12 col-sm-12">
        <div class="form-group row">
            {{ Form::label(__('Nom'), null, ['class' => '']) }}
            <input type="text" name="animauxRencontres[]" placeholder="..." id="animauxRencontres-1" class="form-control">
        </div>
        </div>

        </td>
        </tr>
<?php
        }
        ?>
  

    </tbody>
    <tfoot style="background: #e3e3e3;">
      <tr>

        <td colspan="3">
        <button id="addRowanimauxRencontres" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
</td>
<tr>
    </tfoot>
</table>
        </div>
    </div>
        
<hr class="panel-wide">
<div class="form-group row">
            {{ Form::label(__("Nombre de désherbage manuel dans l'année"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::number('nombreDesherbage', $suiviparcelle->nombreDesherbage,array('class' => 'form-control','min'=>'1') ); ?>
        </div>
    </div>
        <div class="form-group row">
            {{ Form::label(__("Date de la visite"), null, ['class' => 'col-sm-4 control-label required']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::date('dateVisite', $suiviparcelle->dateVisite,array('class' => 'form-control dateactivite required','required'=>'required') ); ?>
        </div>
    </div>
<hr class="panel-wide">


                        <div class="form-group row">
                            <button type="submit" class="btn btn--primary btn-block h-45 w-100">@lang('Envoyer')</button>
                        </div>
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('staff.suivi.parcelles.index') }}" />
@endpush

@push('script')
<script type="text/javascript"> 
 $(document).ready(function () {


var productCount = $("#product_area tr").length + 1;
    $(document).on('click', '#addRow', function(){

      //---> Start create table tr
      var html_table = '<tr>';
      html_table +='<td class="row"><div class="col-xs-12 col-sm-8 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Information Arbre à Ombrage ' + productCount + '</badge></div><div class="col-xs-12 col-sm-8"><div class="form-group row"><label for="varietesOmbrage" class="col-sm-4 control-label">Variété</label><input placeholder="Variété arbre..." class="form-control" id="varietesOmbrage-' + productCount + '" name="varietesOmbrage[]" type="text"></div></div><div class="col-xs-12 col-sm-8"><div class="form-group row"><label for="nombreOmbrage" class="col-sm-4 control-label">Nombre</label><input type="number" min="1" name="nombreOmbrage[]" placeholder="Nombre d\'arbre" id="nombreOmbrage-' + productCount + '" class="form-control " value=""></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' + productCount + '" class="removeRow btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

      html_table += '</tr>';
      //---> End create table tr

      productCount = parseInt(productCount) + 1;
      $('#product_area').append(html_table);

    });

      $(document).on('click', '.removeRow', function(){
      var row_id = $(this).attr('id');
      // delete only last row id
      if (row_id == $("#product_area tr").length) {
        $(this).parents('tr').remove();
        productCount = parseInt(productCount) - 1;

      }
    });

    var agroforestiersCount = $("#agroforestiers_area tr").length + 1;
    $(document).on('click', '#addRowagroforestiers', function(){

      //---> Start create table tr
      var html_table = '<tr>';
      html_table +='<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Arbre agro-forestier ' + agroforestiersCount + '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="agroforestiers" class="">Type</label><input placeholder="Type arbre..." class="form-control" id="agroforestiers-' + agroforestiersCount + '" name="agroforestiers[]" type="text"></div></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="nombreagroforestiers" class="">Nombre</label><input type="number" name="nombreagroforestiers[]" min="1" placeholder="Nombre d\'arbre" id="nombreagroforestiers-' + agroforestiersCount + '" class="form-control " value=""></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' + agroforestiersCount + '" class="removeRowagroforestiers btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

      html_table += '</tr>';
      //---> End create table tr

      agroforestiersCount = parseInt(agroforestiersCount) + 1;
      $('#agroforestiers_area').append(html_table);

    });

      $(document).on('click', '.removeRowagroforestiers', function(){
      var row_id = $(this).attr('id');
      if (row_id == $("#agroforestiers_area tr").length) {
        $(this).parents('tr').remove();
        agroforestiersCount = parseInt(agroforestiersCount) - 1;
      }
    });

    var insectesParasitesCount = $("#insectesParasites_area tr").length + 1;
    $(document).on('click', '#addRowinsectesParasites', function(){

      //---> Start create table tr
      var html_table = '<tr>';
      html_table +='<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Insectes parasites ou ravageurs ' + insectesParasitesCount + '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="insectesParasites" class="">Nom</label><input placeholder="Nom..." class="form-control" id="insectesParasites-' + insectesParasitesCount + '" name="insectesParasites[]" type="text"></div></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="nombreinsectesParasites" class="">Quantite</label><select name="nombreinsectesParasites[]" class="form-control nombreinsectesParasites" d="nombreinsectesParasites-' + insectesParasitesCount + '" ><option value="Assez">Assez</option><option value="Moins">Moins</option></select></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' + insectesParasitesCount + '" class="removeRowinsectesParasites btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

      html_table += '</tr>';
      //---> End create table tr

      insectesParasitesCount = parseInt(insectesParasitesCount) + 1;
      $('#insectesParasites_area').append(html_table);

    });

      $(document).on('click', '.removeRowinsectesParasites', function(){
      var row_id = $(this).attr('id');
      if (row_id == $("#insectesParasites_area tr").length) {
        $(this).parents('tr').remove();
        insectesParasitesCount = parseInt(insectesParasitesCount) - 1;
      }
    });

    var animauxRencontresCount = $("#animauxRencontres_area tr").length + 1;
    $(document).on('click', '#addRowanimauxRencontres', function(){

      //---> Start create table tr
      var html_table = '<tr>';
      html_table +='<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Animal ' + animauxRencontresCount + '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="animauxRencontres" class="">Animal</label><input placeholder="Nom animal..." class="form-control" id="animauxRencontres-' + animauxRencontresCount + '" name="animauxRencontres[]" type="text"></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' + animauxRencontresCount + '" class="removeRowanimauxRencontres btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

      html_table += '</tr>';
      //---> End create table tr

      animauxRencontresCount = parseInt(animauxRencontresCount) + 1;
      $('#animauxRencontres_area').append(html_table);

    });

      $(document).on('click', '.removeRowanimauxRencontres', function(){
      var row_id = $(this).attr('id');
      if (row_id == $("#animauxRencontres_area tr").length) {
        $(this).parents('tr').remove();
        animauxRencontresCount = parseInt(animauxRencontresCount) - 1;
      }
    });

    if($('.existeCoursEaux').val()=='non'){
     $('#courseaux').hide();
}
if($('.arbresagroforestiers').val()=='non'){
     $('#agroforestiersobtenus').hide();
}

$('.arbresagroforestiers').change(function(){
var arbresagroforestiers= $('.arbresagroforestiers').val();
  if(arbresagroforestiers=='oui')
  {
   $('#agroforestiersobtenus').show('slow');
  }
  else{
   $('#agroforestiersobtenus').hide('slow');
  }
});
$('.presenceInsectesParasites').change(function(){
var presenceInsectesParasites= $('.presenceInsectesParasites').val();
  if(presenceInsectesParasites=='oui')
  {
   $('#presenceInsectesParasitesRavageurs').show('slow');
  }
  else{
   $('#presenceInsectesParasitesRavageurs').hide('slow');
  }
});
$('.existeCoursEaux').change(function(){
var existeCoursEaux= $('.existeCoursEaux').val();
 if(existeCoursEaux=='oui')
 {
  $('#courseaux').show('slow');
 }
 else{
  $('#courseaux').hide('slow');

 }
});

if($('.varietes_cacao').val() !='Autre'){
$('#autreVariete').hide();
}

 $('.varietes_cacao').change(function(){
var varietes_cacao= $('.varietes_cacao').val();
  if(varietes_cacao=='Autre')
  {
   $('#autreVariete').show('slow');
  }
  else{
   $('#autreVariete').hide('slow');

  }
});
});
        $('#localite').change(function() { 
            $("#producteur").chained("#localite");  
        });
        $('#parcelle').change(function() {  
    $("#parcelle").chained("#producteur");
        });

    </script>
@endpush