@extends('staff.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
         {!! Form::model($application, ['method' => 'POST','route' => ['staff.suivi.application.store', $application->id],'class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data']) !!}
         <input type="hidden" name="id" value="{{ $application->id }}"> 
                        
                        <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Selectionner une localite')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="localite" id="localite" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($localites as $localite)
                                        <option value="{{ $localite->id }}" @selected($localite->id==$application->parcelle->producteur->localite->id)>
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
                                        <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}" @selected($producteur->id==$application->parcelle->producteur_id)>
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
                                        <option value="{{ $parcelle->id }}" data-superficie="{{ $parcelle->superficie }}" data-chained="{{ $parcelle->producteur->id }}" @selected($parcelle->id==$application->parcelle_id)>
                                           {{ __('Parcelle')}} {{ $parcelle->codeParc }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Applicateur')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="applicateur" id="applicateur" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}"  @selected($staff->id==$application->applicateur_id)>
                                            {{ $staff->lastname }} {{ $staff->firstname }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>    
                            <hr class="panel-wide">
    <div class="form-group row">
            <?php echo Form::label(__("Superficie Pulvérisée"), null, ['class' => 'col-sm-4 control-label required']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('superficiePulverisee', null,array('placeholder' => __("Superficie Pulvérisée"),'class' => 'form-control superficiePulverisee','required','min'=>'0.1')); ?>
        </div>
    </div>

    <div class="form-group row">
            <?php echo Form::label(__("Marque du Produit Pulvérisé"), null, ['class' => 'col-sm-4 control-label required']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('marqueProduitPulverise', null,array('placeholder' => __("Marque du Produit Pulvérisé"),'class' => 'form-control marqueProduitPulverise','required')); ?>
        </div>
    </div>


    <hr class="panel-wide">

    <div class="form-group row">
            <?php echo Form::label(__("Matières actives"), null, ['class' => 'col-sm-4 control-label required']); ?>
            <div class="col-xs-12 col-sm-8">
    <table class="table table-striped table-bordered">
    <tbody id="product_area">
    <?php
        if($application->matiereactives)
        {  
        $i=0;
        $a=1;
        foreach($application->matiereactives as $data) {
           ?>
           <tr>
            <td class="row">
            <div class="col-xs-12 col-sm-12">
        <div class="form-group">
        <?php echo Form::label(__("Nom matière Active").$a, null, ['class' => '']); ?>
            <input type="text" name="matieresActives[]" placeholder="..." id="matieresActives-<?php echo $a; ?>" value="<?php echo $data->matiereactive; ?>" class="form-control">
        </div>
        </div>
        <?php if($a>1):?>
        <div class="col-xs-12 col-sm-12 col-md-12"><button type="button" id="<?php echo $a; ?>" class="removeRow btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div>
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
            <div class="col-xs-12 col-sm-12">
        <div class="form-group row">
            {{ Form::label(__("Nom matière Active"), null, ['class' => '']) }}
            <input type="text" name="matieresActives[]" placeholder="..." id="matieresActives-1" class="form-control"  required >
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

    <div class="form-group row">
            <?php echo Form::label(__("Degré de dangerosité"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('degreDangerosite', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Eleve' => __('Eleve')], null,array('class' => 'form-control degreDangerosite')); ?>
        </div>
    </div>

    <div class="form-group row">
            <?php echo Form::label(__("Raison de l'application"), null, ['class' => 'col-sm-4 control-label required']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo  Form::textarea('raisonApplication', null, ['id' => 'raisonApplication', 'rows' => 4, 'cols' => 54, 'style' => 'resize:none','class' => 'form-control','required']); ?>
        </div>
    </div>
    <hr class="panel-wide">

<div class="form-group row">
        <?php echo Form::label(__("Nom de(s) insecte(s) cible(s) ou parasite(s) cible(s)"), null, ['class' => 'col-sm-4 control-label required']); ?>
        <div class="col-xs-12 col-sm-8">
<table class="table table-striped table-bordered">
<tbody id="product_area_insect">
<?php
        if($application->insectes)
        {
        $i=0;
        $a=1;
        foreach($application->insectes as $data) {
           ?>
<tr>
        <td class="row">
        <div class="col-xs-12 col-sm-12">
    <div class="form-group">
        <?php echo Form::label(__('Nom').$a, null, ['class' => '']); ?>
        <input type="text" name="nomInsectesCibles[]" placeholder="..." id="nomInsectesCibles-<?php echo $a; ?>" value="<?php echo $data->insecte; ?>" class="form-control">
    </div>
    </div>
    <?php if($a>1):?>
        <div class="col-xs-12 col-sm-12 col-md-12"><button type="button" id="<?php echo $a; ?>" class="removeRowInsect btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div>
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
        <div class="col-xs-12 col-sm-12">
    <div class="form-group row">
        {{ Form::label(__('Nom'), null, ['class' => '']) }}
        <input type="text" name="nomInsectesCibles[]" placeholder="..." id="nomInsectesCibles-1" class="form-control"  required>
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
    <button id="addRowInsect" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
</td>
<tr>
</tfoot>
</table>
</div>
</div>

<div class="form-group row">
            <?php echo Form::label(__("Délais de Réentrée du produit en jours"), null, ['class' => 'col-sm-4 control-label required']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo  Form::number('delaisReentree', null, ['id' => 'delaisReentree','class' => 'form-control','required','min'=>'1']); ?>
        </div>
    </div>

    <hr class="panel-wide">

    <div class="form-group row">
            <?php echo Form::label(__("Existe-il une zone tampons ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('zoneTampons', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control zoneTampons')); ?>
        </div>
    </div>

    <div class="form-group row" id="photoZoneTampons">
                    <?php echo Form::label(__("Photo de la zone tampon"), null, ['class' => 'col-sm-4 control-label']); ?>
                    <div class="col-xs-12 col-sm-8">
                    <input type="file" name="photoZoneTampons" class="form-control photoZoneTampons"> 
		        </div>
	</div>

    <div class="form-group row">
            <?php echo Form::label(__("Présence de douche pour l'applicateur ?"), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('presenceDouche', ['non' => __('non'),'oui' => __('oui')], null,array('class' => 'form-control presenceDouche')); ?>
        </div>
    </div>

    <div class="form-group row" id="photoDouche">
                    <?php echo Form::label(__("Photo de la douche"), null, ['class' => 'col-sm-4 control-label']); ?>
                    <div class="col-xs-12 col-sm-8">
                    <input type="file" name="photoDouche" class="form-control photoDouche"> 
                     
		        </div>
	</div>
 
    <hr class="panel-wide">
    <div class="form-group row">
            {{ Form::label(__("Date d'application"), null, ['class' => 'col-sm-4 control-label required']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::date('date_application', null,array('class' => 'form-control dateactivite required','required'=>'required') ); ?>
        </div>
    </div>
    <div class="form-group row">
            {{ Form::label(__("Heure d'application"), null, ['class' => 'col-sm-4 control-label required']) }}
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::time('heure_application', null,array('class' => 'form-control dateactivite required','required'=>'required') ); ?>
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
    <x-back route="{{ route('staff.suivi.application.index') }}" />
@endpush

@push('script')
<script type="text/javascript"> 
$(document).ready(function () {

var productCount = $("#product_area tr").length + 1;
$(document).on('click', '#addRow', function(){

//---> Start create table tr
var html_table = '<tr>';
html_table +='<td class="row"> <div class="col-xs-12 col-sm-12"><div class="form-group"><label for="matieresActives" class="">Nom matière Active ' + productCount + '</label><input placeholder="Nom matière Active..." class="form-control" id="matieresActives-' + productCount + '" name="matieresActives[]" type="text"></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' + productCount + '" class="removeRow btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

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

//    console.log($("#product_area tr").length);

//  productCount--;

}
});



var productCountInsect = $("#product_area_insect tr").length + 1;
$(document).on('click', '#addRowInsect', function(){

//---> Start create table tr
var html_table = '<tr>';
html_table +='<td class="row"> <div class="col-xs-12 col-sm-12"><div class="form-group"><label for="nomInsectesCibles" class="">Nom ' + productCountInsect + '</label><input placeholder="Nom..." class="form-control" id="nomInsectesCibles-' + productCountInsect + '" name="nomInsectesCibles[]" type="text"></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' + productCountInsect + '" class="removeRowInsect btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

html_table += '</tr>';
//---> End create table tr

productCountInsect = parseInt(productCountInsect) + 1;
$('#product_area_insect').append(html_table);

});

$(document).on('click', '.removeRowInsect', function(){

var row_id = $(this).attr('id');

// delete only last row id
if (row_id == $("#product_area_insect tr").length) {

$(this).parents('tr').remove();

productCountInsect = parseInt(productCountInsect) - 1;

//    console.log($("#product_area_insect tr").length);

//  productCountInsect--;

}
});

if($('.presenceDouche').val() !='oui'){ $('#photoDouche').hide('slow');}
if($('.zoneTampons').val() !='oui'){ $('#photoZoneTampons').hide('slow');}


$('.zoneTampons').change(function(){
var zoneTampons= $('.zoneTampons').val();
if(zoneTampons=='oui')
{
$('#photoZoneTampons').show('slow');
}
else{
$('#photoZoneTampons').hide('slow');
$('.photoZoneTampons').val('');
}
});

$('.presenceDouche').change(function(){
var presenceDouche= $('.presenceDouche').val();
if(presenceDouche=='oui')
{
$('#photoDouche').show('slow');
}
else{
$('#photoDouche').hide('slow');
$('.photoDouche').val('');
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