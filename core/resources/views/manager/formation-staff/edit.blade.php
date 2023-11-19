@extends('manager.layouts.app')
@section('panel')
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
            <?php echo Form::select('lieu_formation', ["Dans le ménage"=>"Dans le ménage","Place Publique"=>"Place Publique","Champs Ecole"=>"Champs Ecole"], $formation->lieu_formation, array('placeholder' => __('Selectionner une option'),'class' => 'form-control', 'id'=>'lieu_formations','required'=>'required')); ?>
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
        <?php echo Form::label(__("Staff ayant dispensé la formation"), null, ['class' => 'col-sm-4 control-label']); ?>
        <div class="col-xs-12 col-sm-8"> 
             <select class="form-control" name="staff" id="staff" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}"  @selected($staff->id==$formation->user_id)>
                                            {{ $staff->lastname }} {{ $staff->firstname }}</option>
                                    @endforeach
                                </select>  
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(__("Date de la formation"), null, ['class' => 'col-sm-4 control-label required']); ?>
        <div class="col-xs-12 col-sm-8">
            <?php echo Form::date('date_formation', null,array('class' => 'form-control dateactivite','required'=>'required') ); ?>
        </div>
    </div>
    <div class="form-group row">
                     <?php echo Form::label(__('Photo de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                     <div class="col-xs-12 col-sm-8">
                           <input type="file" name="photo_formation" class="form-control dropify-fr"  data-default-file="{{ asset('core/storage/app/' . $formation->photo_formation) }}">
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
    <x-back route="{{ route('manager.formation-staff.index') }}" />
@endpush

@push('script')
<script type="text/javascript"> 
$("#theme").chained("#typeformation");
$("#producteur").chained("#localite");
          
    </script>
@endpush