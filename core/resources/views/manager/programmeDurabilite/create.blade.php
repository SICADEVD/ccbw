@extends('manager.layouts.app')
@section('panel')
  <div class="row mb-none-30">

    <div class="col-lg-12 mb-30">
      <div class="card">
        <div class="card-body"> 
          {!! Form::open(array('route' => ['manager.durabilite.store'],'method'=>'POST','class'=>'form-horizontal', 'id'=>'flocal', 'enctype'=>'multipart/form-data')) !!}   
            <div class="form-group row">
              <?php echo Form::label(__('Nom du programme'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                <div class="col-xs-12 col-sm-8">
                  <?php echo Form::text('libelle', null, array('placeholder' => __('Nom du programme'),'class' => 'form-control', 'required')); ?>
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
    <x-back route="{{ route('manager.durabilite.index') }}" />
@endpush

@push('script')
  <script type="text/javascript">
    $(document).ready(function () {
    });
  </script>
@endpush