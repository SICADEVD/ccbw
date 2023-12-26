@extends('manager.layouts.app')
@section('panel')
 
    <div class="row gy-4"> 
    <div class="col-xxl-4 col-sm-4">  
                        <div class="card box--shadow2 bg--white" style="min-height:230px;"> 
                        <div class="card-body text-center">
                        <h5 class="card-title">Nombre de Coopérative</h5>
                        <h1 class="text--black" style="font-size: 56px;">{{$nbcoop}}</h1>
                        </div> 
                        </div>
        </div>

    <div class="col-xxl-4 col-sm-4"> 
                    <div class="card box--shadow2 bg--white" style="min-height:230px;">  
                    <div class="card-body text-center">
                        {!! $prodbysexe->container() !!} 
                    </div> 
                    </div>
        </div>

        <div class="col-xxl-4 col-sm-4">  
                        <div class="card box--shadow2 bg--white" style="min-height:230px;"> 
                        <div class="card-body text-center">
                        <h5 class="card-title">Nombre de Parcelles</h5>
                        <h1 class="text--black" style="font-size: 56px;">{{$nbparcelle}}</h1>
                        </div> 
                        </div>
        </div>

        <div class="col-xxl-4 col-sm-4"> 
        <div class="card box--shadow2 bg--white" style="min-height:230px;"> 
        <div class="card-body text-center"> 
                        {!! $mapping->container() !!}
                    </div> 
        </div>
        </div>
  
        <div class="col-xxl-4 col-sm-4"> 
                    <div class="card box--shadow2 bg--white" style="min-height:230px;">  
                    <div class="card-body text-center">
                        {!! $producteurbydays->container() !!}
                    </div> 
                    </div>
        </div>
         
        <div class="col-xxl-4 col-sm-4"> 
                    <div class="card box--shadow2 bg--white" style="min-height:230px;">  
                    <div class="card-body text-center">
                        {!! $formationbymodule->container() !!} 
                    </div> 
                    </div>
        </div>
        <div class="col-xxl-4 col-sm-4"> 
                    <div class="card box--shadow2 bg--white" style="min-height:230px;">  
                    <div class="card-body text-center">
                        {!! $producteurbymodule->container() !!} 
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

<script src="{{ $prodbysexe->cdn() }}"></script>

{{ $prodbysexe->script() }}
{{ $mapping->script() }}
{{ $producteurbydays->script() }}
{{ $formationbymodule->script() }}
{{ $producteurbymodule->script() }}
 
@endpush