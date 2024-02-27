@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body" id="printFacture"> 
         {!! Form::model($inspection, ['method' => 'POST','route' => ['manager.suivi.inspection.store', $inspection->id],'class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data']) !!}
                        <input type="hidden" name="id" value="{{ $inspection->id }}"> 
                        <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Campagne')</label>
                                <div class="col-xs-12 col-sm-8">
                                {{ $inspection->campagne->nom }} 
                                </div>
                            </div> 
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
                                <label class="col-sm-4 control-label">@lang('Inspecteur')</label>
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
             $themeArray=array();

              foreach($inspection->reponsesInspection as $reponse){
                
                ?>
                @if(!in_array($reponse->questionnaire->categorieQuestion->titre,$themeArray))
<tr><td colspan="4"><strong><?php echo $reponse->questionnaire->categorieQuestion->titre; ?></strong></td></tr>
@php
$themeArray[] = $reponse->questionnaire->categorieQuestion->titre;
@endphp
@endif
              
                   <tr>
                   <td><?php echo $reponse->questionnaire->nom; ?>
              </td> 
              <td><?php echo $reponse->questionnaire->certificat; ?>
              </td> 
              <td>
              <span class="badge @if($reponse->notation=='Conforme')badge-success @endif @if($reponse->notation=='Pas Conforme')badge-danger @endif @if($reponse->notation=='Non Applicable')badge-info @endif"><?php echo $reponse->notation; ?></span>
                   </td>
                   <td> {{ $reponse->commentaire }}  </td>
                   </tr> 
                   <?php  
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
    <div class="row no-print">
                <div class="col-sm-12">
                    <div class="float-sm-end">
                        <button class="btn btn-outline--primary  printFacture"><i
                                class="las la-download"></i></i>@lang('Imprimer')</button>
                    </div>
                </div>
            </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.suivi.inspection.index') }}" />
@endpush
@push('script')
    <script>
        "use strict";
        $('.printFacture').click(function() {
            $('#printFacture').printThis();
        });
    </script>
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
 