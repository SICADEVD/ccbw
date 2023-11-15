@extends('layouts.app')
@section('title', @$pageTitle)
@section('content')

<section class="content-header">
      <h1>
        <i class="fa fa-braille"></i> @lang('common.edit_archive')<small>@lang('common.gestion_archivage')</small>
      </h1>
    </section>
<section class="content">
<div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                <a class="btn btn-primary" href="{{ route('archivages.index') }}"> @lang('common.retour')</a>
                </div>
            </div>
        </div>

        <div class="row">
<div class="col-md-12">
        <div class="box">

        <div class="box-body">


@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>@lang('common.oups')</strong> @lang('common.probleme_saisi')<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif

{!! Form::model($archivages, ['method' => 'PATCH','route' => ['archivages.update', $archivages->id],'enctype'=>'multipart/form-data', 'id'=>'flocal','class'=>'form-horizontal']) !!}

        <div class="form-group">
        <?php echo Form::label(__('common.cooperative'), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::select('cooperatives_id', $cooperatives, null, array('placeholder' => __('common.select_coop'),'class' => 'form-control cooperatives', 'id'=>'cooperatives','required'=>'required')); ?>
        </div>
    </div>


    <div class="form-group">
        <?php echo Form::label(__('common.type_archive'), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::select('type_archives', $type_archives, null, array('placeholder' => __('common.select_type'),'class' => 'form-control typearchives', 'id'=>'typearchives','required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label(__('common.type_programe'), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::select('type_programmes', $type_programmes, null, array('placeholder' => __('common.select_type'),'class' => 'form-control typeprogrammes', 'id'=>'typeprogrammes','required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group" id="autreprogramme">
            <?php echo Form::label(__('common.nom_programme'), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('autreprogramme', null,array('placeholder' => __('common.entre_nom'),'class' => 'form-control autreprogramme')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label(__('common.domaine_appl'), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
             <?php echo Form::select('domaine_archivages', $domaine_archivages, null, array('placeholder' => __('common.select_type'),'class' => 'form-control domainearchivages', 'id'=>'domainearchivages','required'=>'required')); ?>
        </div>
    </div>

    <hr class="panel-wide">
    <div class="form-group">
            <?php echo Form::label(__('common.titre_doc'), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('titre', null,array('placeholder' => __('common.entre_titredoc'),'class' => 'form-control titre')); ?>
        </div>
    </div>
    <div class="form-group">
            <?php echo Form::label(__('common.point_controle'), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo Form::text('pointControlePrincipal', null,array('placeholder' => '...','class' => 'form-control pointControlePrincipal')); ?>
        </div>
    </div>
    <div class="form-group">
            <?php echo Form::label(__('common.point_controlemax'), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo  Form::textarea('pointControleSecondaire', null, ['pointControleSecondaire' => 'resume', 'rows' => 4, 'cols' => 40, 'style' => 'resize:none','class' => 'form-control resume','maxlength' => 500]); ?>
        </div>
    </div>
<div class="form-group">
            <?php echo Form::label(__('common.resume_carac'), null, ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-xs-12 col-sm-8">
            <?php echo  Form::textarea('resume', null, ['id' => 'resume', 'rows' => 4, 'cols' => 54, 'style' => 'resize:none','class' => 'form-control','maxlength' => 500]); ?>
            <div id="count">
            <span id="current_count">0</span>
            <span id="maximum_count">/ 500</span>
        </div>
        </div>
    </div>

    <div class="form-group">
                    <?php echo Form::label(__('common.joindre_taille'), null, ['class' => 'col-sm-4 control-label']); ?>
                    <div class="col-xs-12 col-sm-8">
                    <input type="file" name="document" value="" class="form-control document dropify-fr" data-default-file="{{asset('storage/app/'.$archivages->document) }}">
		        </div>
	</div>

    <hr class="panel-wide">
    <div class="col-xs-12 col-sm-8 text-center"><br><br>
        <button type="submit" class="btn btn-primary" id="submit">@lang('common.modifier')</button>
    </div>
{!! Form::close() !!}
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
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

if($('.type_programmes').val() !='Autre'){ $('#autreprogramme').hide('slow');}

$('.typeprogrammes').change(function(){
var typeprogrammes= $('.typeprogrammes').val();
  if(typeprogrammes=='Autre')
  {
   $('#autreprogramme').show('slow');
  }
  else{
   $('#autreprogramme').hide('slow');
   $('.autreprogramme').val('');
  }
});


 $('.producteurs').change(function(){

var producteurs= $('.producteurs').val();

 $.ajax({
              type:'GET',
              url: urlbase+'getParcelles/'+producteurs,
              success:function(html){

                if(html)
                {
                $('#parcelles_id').show();
                $("#parcelles").html(html);
                }
                else{
                $('#parcelles_id').hide();
                }

              }
          });
});


});

 </script>
 <script type="text/javascript">
   var racine = '<?php echo url(''); ?>';
    var urlbase= racine+'/producteurs/';

    $('.cooperatives').change(function(){

   var cooperatives= $('.cooperatives').val();

    $.ajax({
                 type:'GET',
                 url: urlbase+'getLocalites',
                 data: $('#flocal').serialize(),
                 success:function(html){

                   if(html)
                   {
                   $('#localites_id').show();
                   $("#localites").html(html);
                   $('#submit').show();
                   }
                   else{
                   $('#localites_id').hide();
                   $('#submit').hide();

                   $('#apte').html("<div class='alert alert-danger text-center'><h1>Cette coopérative n\'a pas de localités. Veuillez choisir une autre coopérative.</h1></div>");
                 $('#myModal').modal('show');
                   }

                 }
             });
 });

   $('.localites').change(function(){

  var localites= $('.localites').val();

   $.ajax({
                type:'GET',
                url: urlbase+'getProducteurs',
                data: $('#flocal').serialize(),
                success:function(html){

                  if(html)
                  {
                  $('#producteurs_id').show();
                  $("#producteurs").html(html);
                  }
                  else{
                  $('#producteurs_id').hide();
                  }

                }
            });
 });
 </script>
 <script type="text/javascript">
$('textarea').keyup(function() {
    var characterCount = $(this).val().length,
        current_count = $('#current_count'),
        maximum_count = $('#maximum_count'),
        count = $('#count');
        current_count.text(characterCount);
});
</script>
@endsection
