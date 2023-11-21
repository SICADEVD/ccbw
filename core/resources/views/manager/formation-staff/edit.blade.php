@extends('manager.layouts.app')
@section('panel')
<?php use Carbon\Carbon; ?>
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
         {!! Form::model($formation, ['method' => 'POST','route' => ['manager.formation-staff.store', $formation->id],'class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data']) !!}
                        <input type="hidden" name="id" value="{{ $formation->id }}"> 
                         
                       
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Selectionner un membre')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control select2-multi-select" name="user[]" id="user" multiple required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($staffs as $user)
                                        <option value="{{ $user->id }}" @selected(in_array($user->id,$dataUser))>
                                            {{ $user->lastname }} {{ $user->firstname }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
        <?php echo Form::label(__("Nom des visiteurs ayant participer à la formation"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
            <select name="visiteurs[]" id="visiteurs" class="form-control select2-auto-tokenize" multiple>
            @if(@$visiteurStaff->count())
                                            @foreach($visiteurStaff as $visiteur)
                                                <option value="{{ $visiteur->visiteur }}" selected>{{ __($visiteur->visiteur) }}</option>
                                            @endforeach
                                        @endif
                                                </select>
        </div>
    </div>
      <hr class="panel-wide">
    <div class="form-group row">
        <?php echo Form::label(__("Lieu de la formation"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
            <?php echo Form::select('lieu_formation', ["Dans le ménage"=>"Dans le ménage","Place Publique"=>"Place Publique","Champs Ecole"=>"Champs Ecole","En salle"=>"En salle"], $formation->lieu_formation, array('placeholder' => __('Selectionner une option'),'class' => 'form-control', 'id'=>'lieu_formations','required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Modules de formations"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8">
               <?php echo Form::select('module_formation', $ModuleFormationStaffs, $formation->module_formation_staff_id, array('placeholder' => __('Selectionner une option'),'class' => 'form-control type_formations','id'=>'typeformation','required'=>'required')); ?>
        </div>
    </div>

             <div class="form-group row">
                 
             <?php echo Form::label(__("Thème de la formation"), null, ['class' => 'col-sm-4 control-label']); ?>
             <div class="col-xs-12 col-sm-8">
             <select class="form-control select2-multi-select" name="theme[]" id="theme" multiple required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($themes as $theme)
                                        <option value="{{ $theme->id }}" data-chained="{{ $theme->module_formation_staff_id }}" @selected(in_array($theme->id, $dataTheme))>
                                            {{ $theme->nom }} </option>
                                    @endforeach
                                </select> 
             </div>
    </div>

    <hr class="panel-wide">
    <div class="form-group row">
        <?php echo Form::label(__("Formateur"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8"> 
        <?php echo Form::text('formateur', $formation->formateur, ['class' => 'form-control','id'=>'formateur', 'required' => 'required']); ?>
            
        </div>
    </div>
    <hr class="panel-wide"> 
    <div class="form-group row">  
    <?php echo Form::label(__('Date de Début & Fin de la formation'), null, ['class' => 'col-sm-4 control-label required']); ?>
        <div class="col-xs-12 col-sm-8">
         <?php 
         $datedebut = Carbon::parse($formation->date_debut_formation)->format('m/d/Y'); 
         $datefin = Carbon::parse($formation->date_fin_formation)->format('m/d/Y');
         
         ?>
        <?php echo Form::text('multi_date', $datedebut.' - '.$datefin, ['class' => 'form-control','id'=>'multi_date', 'required' => 'required']); ?>
        </div>
    </div>
    <div class="form-group row">  
    <?php echo Form::label(__('Durée de la formation'), null, ['class' => 'col-sm-4 control-label required']); ?>
        <div class="col-xs-12 col-sm-8 bootstrap-timepicker timepicker">
        <?php echo Form::text('duree_formation', $formation->duree_formation, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Ex : 04:10']); ?>
         
        </div>
    </div>
    <hr class="panel-wide">
    <div class="form-group row">
                     <?php echo Form::label(__('Photo de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                     <div class="col-xs-12 col-sm-8">
                           <input type="file" name="photo_formation" class="form-control dropify-fr"  data-default-file="{{ asset('core/storage/app/' . $formation->photo_formation) }}" data-allowed-file-extensions="jpg jpeg png">
                 </div>
     </div>
     <div class="form-group row">
                     <?php echo Form::label(__('Rapport de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                     <div class="col-xs-12 col-sm-8">
                           <input type="file" name="rapport_formation" class="form-control dropify-fr"  data-default-file="{{ asset('core/storage/app/' . $formation->rapport_formation) }}" data-allowed-file-extensions="pdf docx doc xls xlsx">
                 </div>
     </div>
     <div class="form-group row">
                        <?php echo Form::label(__('Observation de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::textarea('observation_formation', $formation->observation_formation, ['class' => 'form-control duree_formation', 'rows' => 4]); ?>
                        </div>
                    </div>
     <input type="hidden" name="multiStartDate" id="multiStartDate" value="{{ Carbon::parse($formation->date_debut_formation)->format('Y-m-d') }}">
<input type="hidden" name="multiEndDate" id="multiEndDate" value="{{ Carbon::parse($formation->date_fin_formation)->format('Y-m-d') }}">
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
    <x-back route="{{ route('manager.formation-staff.index') }}" />
@endpush

@push('script')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/daterangepicker.css') }}">
<script src="{{ asset('assets/vendor/jquery/daterangepicker.min.js')}}"></script>
<script type="text/javascript"> 
$("#theme").chained("#typeformation");
$("#producteur").chained("#localite");
$('#duree_formation').timepicker({
            showMeridian: (false)
        });
    $('#multi_date').daterangepicker({
            linkedCalendars: false,
            multidate: true,
            todayHighlight: true,
            format: 'yyyy-mm-d'
        });
        $('#multi_date').change(function() {
            var dates = $(this).val();

            var startDate = moment(new Date(dates.split(' - ')[0]));
            var endDate = moment(new Date(dates.split(' - ')[1]));
            var totalDays = endDate.diff(startDate, 'days')+1;

            startDate = startDate.format('YYYY-MM-DD');
             
            endDate = endDate.format('YYYY-MM-DD');

            var multiDate = [];
            multiDate = [startDate, endDate];
            $('#multi_date').val(multiDate);

            $('#multiStartDate').val(startDate);
            $('#multiEndDate').val(endDate);
            $('.date-range-days').html(totalDays +' Jours sélectionnés');
        })
    </script>
@endpush