@extends('manager.layouts.app')
@section('panel')
  <div class="row mb-none-30">

    <div class="col-lg-12 mb-30">
      <div class="card">
        <div class="card-body"> 
          {!! Form::open(array('route' => ['manager.section.store'],'method'=>'POST','class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data')) !!}   

            <div class="form-group row">
              <label class="col-xs-12 col-sm-4">@lang('Select Cooperative')</label>
              <div class="col-xs-12 col-sm-8">
                <select class="form-control" name="cooperative_id" required> 
                  @foreach($cooperatives as $cooperative)
                    <option value="{{ $cooperative->id }}" @selected(old('cooperative'))>
                      {{ __($cooperative->name) }}
                    </option>
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

            <div class="form-group row">
              <?php echo Form::label(__('Sous Préfecture'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                <div class="col-xs-12 col-sm-8">
                  <?php echo Form::text('sousPrefecture', null, array('placeholder' => __('Sous préfecture'),'class' => 'form-control', 'required')); ?>
                </div>
            </div>
            
            <div class="form-group">
              <button type="submit" class="btn btn--primary w-100 h-45"> @lang('Envoyer')</button>
            </div>
          {!! Form::close() !!}
      </div>
    </div>  
  </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.section.index') }}" />
@endpush

@push('script')
  <script type="text/javascript">
    $(document).ready(function () {
    });
  </script>
@endpush