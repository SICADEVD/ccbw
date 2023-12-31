@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
         {!! Form::model($inspection, ['method' => 'POST','route' => ['manager.suivi.inspection.store', $inspection->id],'class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data']) !!}
                        <input type="hidden" name="id" value="{{ $inspection->id }}"> 
                        
                        <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Localite')</label>
                                <div class="col-xs-12 col-sm-8">
                                {{ $inspection->producteur->localite->nom }} 
                                </div>
                            </div>  
                       
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Producteur')</label>
                                <div class="col-xs-12 col-sm-8">
                                {{ $inspection->producteur->nom }} {{ $inspection->producteur->prenoms }}({{ $inspection->producteur->codeProd }})
                                 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Encadreur')</label>
                                <div class="col-xs-12 col-sm-8">
                                {{ $inspection->user->lastname }} {{ $inspection->user->firstname }} 
                                 
                                </div>
                            </div>

    <hr class="panel-wide">
    <div class="form-group row">
    <table class="table-bordered table-striped"  id="myTable">
<tbody>
              <?php

              $note = 0;
              $total=0;
              foreach($categoriequestionnaire as $catquest){
                ?>
<tr><td colspan="3"><strong><?php echo $catquest->titre; ?></strong></td></tr>
              <?php 
               
              foreach($inspection->reponses as $q) {
                if($q->categorie_questionnaire_id == $catquest->id)
                {
                   ?>


                   <tr>
                   <td><?php echo $q->nom; ?>
              </td> 
              <td><?php echo $q->certificat; ?>
              </td> 
              <td>
              <?php
                          foreach($notations as $not)
                          { 
                               ?>
                               @if($not->point==$q->pivot->notation)
                                 <span class="badge @if($not->nom=='Conforme')badge-success @endif @if($not->nom=='Pas Conforme')badge-danger @endif @if($not->nom=='Non Applicable')badge-info @endif"><?php echo $not->nom; ?></span>
                               @endif 
                                            <?php
                          }
                         ?>
                   </td>
                   </tr>


                   <?php 
                  } 
                }
              }
              ?>
              </tbody>
         </table>
    </div>
    <hr class="panel-wide">
     
    <div class="form-group row">
        <?php echo Form::label(__("Taux de Conformité (%)"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        {{ $inspection->note }}%
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Total question"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        {{ $inspection->total_question }}
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Total question Conforme"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        {{ $inspection->total_question_conforme }} 
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Total question Non Conforme"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        {{ $inspection->total_question_non_conforme }} 
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Total question Non Applicable"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8" >
        {{ $inspection->total_question_non_applicable }} 
        </div>
    </div>
    <div class="form-group row">
            {{ Form::label(__("Date d'évaluation"), null, ['class' => 'col-sm-4 control-label']) }}
            <div class="col-xs-12 col-sm-8">
                {{ $inspection->date_evaluation }} 
        </div>
    </div>
<hr class="panel-wide">

 
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

 
$('#flocal').change(function() {
    update_amounts();
});

});

function update_amounts()
{
    var sum = 0;

    $('#myTable > tbody  > tr').each(function() {
        var qty = $(this).find('option:selected').val();

          if(qty =="-1" || qty =="0" || qty =="1" || qty =="2")
         {
            sum = parseFloat(sum)+parseFloat(qty);
         }

    });
    $('#note').val(sum);
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
 