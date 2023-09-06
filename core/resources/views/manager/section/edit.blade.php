@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
                    {!! Form::model($section, ['method' => 'POST','route' => ['manager.section.update', $section->id],'class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data']) !!}
                        <input type="hidden" name="id" value="{{ $section->id }}">
                            
                        <div class="form-group row">
                            <label class="col-xs-12 col-sm-4">@lang('Select localite')</label>
                            <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="localite_id"> 
                                    @foreach ($localites as $localite)
                                        <option value="{{ $localite->id }}"  @selected($localite->id==$section->localite_id) >{{ __($localite->nom) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(__('Nom de la section'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('libelle', null, array('placeholder' => __('Nom de la section'),'class' => 'form-control', 'required')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block h-45 w-100">@lang('Envoyer')</button>
                        </div>
                    {!! Form::close() !!}
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.section.index') }}" />
@endpush

@push('script')
<script type="text/javascript">
$(document).ready(function () {

         var maladiesCount = $("#maladies tr").length + 1;
         $(document).on('click', '#addRowMal', function(){

           //---> Start create table tr
           var html_table = '<tr>';
           html_table +='<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn btn-warning btn-sm">Nom Ecole Primaire ' + maladiesCount + '</badge></div><div class="col-xs-12 col-sm-11"><div class="form-group"><input placeholder=" ..." class="form-control" id="nomecolesprimaires-' + maladiesCount + '" name="nomecolesprimaires[]" type="text"></div></div><div class="col-xs-12 col-sm-1"><button type="button" id="' + maladiesCount + '" class="removeRowMal btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

           html_table += '</tr>';
           //---> End create table tr

           maladiesCount = parseInt(maladiesCount) + 1;
           $('#maladies').append(html_table);

         });

           $(document).on('click', '.removeRowMal', function(){

           var row_id = $(this).attr('id');

           // delete only last row id
           if (row_id == $("#maladies tr").length) {

             $(this).parents('tr').remove();

             maladiesCount = parseInt(maladiesCount) - 1;

           }
         });

     });


     if($('.marche').val() =='oui'){
    $('#kmmarcheproche').hide('slow');
    $('.kmmarcheproche').css('display','block');
}else{
    $('#kmmarcheproche').show('slow');
}
     $('.marche').change(function(){
var marche= $('.marche').val();
  if(marche=='non')
  {
   $('#kmmarcheproche').show('slow');
   $('.kmmarcheproche').css('display','block');
  }
  else{
   $('#kmmarcheproche').hide('slow');
  }
});
// CENTRE DE SANTE
if($('.centresante').val() =='oui'){
    $('#kmCentresante').hide('slow');
    $('.kmCentresante').css('display','block');
}else{
    $('#kmCentresante').show('slow');
}
$('.centresante').change(function(){
var centresante= $('.centresante').val();
  if(centresante=='non')
  {
   $('#kmCentresante').show('slow');
   $('.kmCentresante').css('display','block');
  }
  else{
   $('#kmCentresante').hide('slow');
  }
});

// ECOLE
if($('.ecole').val()=='oui'){
    $('#nombrecole').show('slow');
   $('.kmEcoleproche').hide('slow');
   $('.nombrecole').css('display','block'); 
}else{
    $('.kmEcoleproche').show('slow');
   $('#nombrecole').hide('slow');
   $('.kmEcoleproche').css('display','block'); 
}

$('.ecole').change(function(){
var ecole= $('.ecole').val();
  if(ecole=='oui')
  {
   $('#nombrecole').show('slow');
   $('.nombrecole').css('display','block'); 
   $('.kmEcoleproche').hide('slow');
  }
  else{
   $('#kmEcoleproche').show('slow');
   $('#nombrecole').hide('slow');
   $('.kmEcoleproche').css('display','block'); 
  }
});

// EAU HYDRAULIQUE
if($('.sourceeau').val()=='Pompe Hydraulique Villageoise'){
    $('#etatpompehydrau').show('slow');
    $('.etatpompehydrau').css('display','block');
}else{
    $('#etatpompehydrau').hide('slow');
}
$('.sourceeau').change(function(){
var sourceeau= $('.sourceeau').val();
  if(sourceeau=='Pompe Hydraulique Villageoise')
  {
   $('#etatpompehydrau').show('slow');
   $('.etatpompehydrau').css('display','block');
  }
  else{
   $('#etatpompehydrau').hide('slow');
  }
});
</script>
@endpush