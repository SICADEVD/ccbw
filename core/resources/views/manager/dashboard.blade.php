@extends('manager.layouts.app')
@section('panel')
    <div class="row gy-4">
    <div class="col-md-4 col-sm-6">
            <div class="card bg--white has-link box--shadow2">
                 
                <div class="card-body">
                    <div class="row align-items-center"> 
                        <div class="col-12 text-end"> 
                        {!! $chart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

  
         

    </div><!-- row end-->

   
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end">
        <h3>{{ __(auth()->user()->cooperative->name) }}</h3>
    </div>
@endpush
@push('script') 
<script src="{{ $chart->cdn() }}"></script>

{{ $chart->script() }}
@endpush