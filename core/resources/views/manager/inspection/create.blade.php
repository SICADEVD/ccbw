@extends('manager.layouts.app')
@section('panel')
<style type="text/css">
.colorSelect_1{ 
    background-color: #FF0000;
}
.colorSelect_2{
    background-color: #9E9E9E;
}
.colorSelect_3{
    background-color: #00BF00;
}
</style>
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
                    {!! Form::open(array('route' => ['manager.suivi.inspection.store'],'method'=>'POST','class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data')) !!} 
                        
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
                            <div class="form-group row">
                                <label for="" class="col-sm-4 control-label">@lang('Certificat')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control select2-multi-select" name="certificat[]" id="certificat"
                                required multiple>
                                <option value="">@lang('Selectionner un certificat')</option>
                                
                            </select>
                                </div>
                            </div>
                          
    <hr class="panel-wide">
    <div class="form-group row">
    <table class="table-bordered table-striped"  id="myTable">
<tbody id="myListe">
               
              </tbody>
         </table>
    </div>
    <hr class="panel-wide">
    <div class="form-group row">
        <?php echo Form::label(__("Taux de Conformité (%)"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::number('note', null, array('placeholder' => __('00'),'class' => 'form-control', 'id'=>'note', 'readonly'=>'readonly')); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Total question"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::number('total_question', null, array('placeholder' => __('00'),'class' => 'form-control', 'id'=>'totalquestion', 'readonly'=>'readonly')); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Total question Conforme"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::number('total_question_conforme', null, array('placeholder' => __('00'),'class' => 'form-control', 'id'=>'totalquestionconforme', 'readonly'=>'readonly')); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Total question Non Conforme"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::number('total_question_non_conforme', null, array('placeholder' => __('00'),'class' => 'form-control', 'id'=>'totalquestionnonconforme', 'readonly'=>'readonly')); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Total question Non Applicable"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        <?php echo Form::number('total_question_non_applicable', null, array('placeholder' => __('00'),'class' => 'form-control', 'id'=>'totalquestionnonapplicable', 'readonly'=>'readonly')); ?>
        </div>
    </div>
    <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Encadreur')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="encadreur" id="encadreur" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}"  @selected(old('encadreur'))>
                                            {{ $staff->lastname }} {{ $staff->firstname }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
    <div class="form-group row">
            {{ Form::label(__("Date d'évaluation"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
            {!! Form::date('date_evaluation', null, array('placeholder' => __("Date d'évaluation"),'class' => 'form-control','id'=>'anneeCreation-1' ,'required')) !!}
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
    <x-back route="{{ route('manager.suivi.inspection.index') }}" />
@endpush

@push('script')
<script type="text/javascript">
      $(document).ready(function(){

update_amounts();
$('#flocal').change(function() {
    update_amounts();
});

});

$('#producteur').change(function() {

$.ajax({
    type: 'GET',
    url: "{{ route('manager.suivi.inspection.getcertificat') }}",
    data: $('#flocal').serialize(),
    success: function(html) {
        init_question();
        $('#certificat').html(html);    
    }
});
});

$('#certificat').change(function() {

$.ajax({
    type: 'GET',
    url: "{{ route('manager.suivi.inspection.getquestionnaire') }}",
    data: $('#flocal').serialize(),
    success: function(html) {
        init_question();
        $('#myListe').html(html.contents);  
        $('#totalquestion').val(html.total); 
    }
});
});
function init_question(){
    $('#note').val(0);
    $('#totalquestion').val(0); 
    $('#totalquestionnonconforme').val(0);
    $('#totalquestionnonapplicable').val(0);
    $('#totalquestionconforme').val(0);
}
function update_amounts()
{
    var sum = conforme = nonconforme = nonapplicable = tauxconformite =  0;

    $('#myTable > tbody  > tr').each(function() {
        var qty = $(this).find('option:selected').val();

          if(qty =="-1" || qty =="0" || qty =="1" || qty =="2")
         {
            sum = parseFloat(sum)+parseFloat(qty);
         }
         if(qty =="-1")
         {
            nonconforme = parseFloat(nonconforme)+1;
         }
         if(qty =="0")
         {
            nonapplicable = parseFloat(nonapplicable)+1;
         }
         if(qty =="1")
         {
            conforme = parseFloat(conforme)+1;
         }

    });
    //$('#note').val(sum);
    $('#totalquestionnonconforme').val(nonconforme);
    $('#totalquestionnonapplicable').val(nonapplicable);
    $('#totalquestionconforme').val(conforme); 
    tauxconformite = (100*conforme)/$('#totalquestion').val()-nonapplicable;
    $('#note').val(Math.round(tauxconformite));
    //just update the total to sum
}

    $("#producteur").chained("#localite");
 </script>
@endpush

@push('style')
<style>
    #myTable td{
    font-size: 0.8125rem;
    color: #5b6e88; 
    font-weight: 500;
    padding: 15px 25px;
    vertical-align: middle;  
    border: 1px solid #f4f4f4;
    min-width: 200px;
}
</style>
 