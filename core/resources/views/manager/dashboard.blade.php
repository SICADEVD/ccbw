@extends('manager.layouts.app')
@section('panel')
 
    <div class="grid gy-4"> 
    <div class="grid-item">  
                        <div class="card box--shadow2 bg--white"> 
                        <div class="card-body text-center">
                        <h5 class="card-title">Nombre de Coopérative</h5>
                        <h1 class="text--black" style="font-size: 56px;">{{$nbcoop}}</h1>
                        </div> 
                        </div>
        </div>

    <div class="grid-item"> 
                    <div class="card box--shadow2 bg--white">  
                    <div class="card-body text-center">
                        {!! $prodbysexe->container() !!} 
                    </div> 
                    </div>
        </div>

        <div class="grid-item">  
                        <div class="card box--shadow2 bg--white"> 
                        <div class="card-body text-center">
                        <h5 class="card-title">Nombre de Parcelles</h5>
                        <h1 class="text--black" style="font-size: 56px;">{{$nbparcelle}}</h1>
                        </div> 
                        </div>
        </div>

        <div class="grid-item"> 
        <div class="card box--shadow2 bg--white"> 
        <div class="card-body text-center"> 
                        {!! $mapping->container() !!}
                    </div> 
        </div>
        </div>
  
        <div class="grid-item"> 
                    <div class="card box--shadow2 bg--white">  
                    <div class="card-body text-center">
                        {!! $producteurbydays->container() !!}
                    </div> 
                    </div>
        </div>
         
        <div class="grid-item"> 
                    <div class="card box--shadow2 bg--white">  
                    <div class="card-body text-center">
                        {!! $formationbymodule->container() !!} 
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

 
@endpush