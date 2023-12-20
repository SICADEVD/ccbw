@extends('manager.layouts.app')
@section('panel')
    <div class="row gy-4">
 
        <div class="col-md-4 col-sm-6">
            
        <div class="card bg--white has-link box--shadow2">
                <div class="card-body">
                    <div class="row align-items-center"> 
                        <div class="col-12 text-end"> 
                        {!! $chart1->renderHtml() !!}
                        </div>
                       
                    </div>
                </div>
            </div>
            <div class="card bg--purple has-link box--shadow2">
                <div class="card-body">
                    <div class="row align-items-center">  
                        <div class="col-12 text-center "> 
                         
                        <span class="text-white text--big">@lang('Total producteur')</span>
                            <h2 class="text-white">{{ $totalproducteur }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card bg--white has-link box--shadow2">
                 
                <div class="card-body">
                    <div class="row align-items-center"> 
                        <div class="col-12 text-end"> 
                        {!! $chart2->renderHtml() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card bg--white has-link box--shadow2">
                 
                <div class="card-body">
                    <div class="row align-items-center"> 
                        <div class="col-12 text-end"> 
                        {!! $chart3->renderHtml() !!}
                        </div>
                        <div class="col-12 text-end"> 
                        {!! $chart4->renderHtml() !!}
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
{!! $chart1->renderChartJsLibrary() !!} 
{!! $chart1->renderJs() !!} 
{!! $chart2->renderJs() !!} 
{!! $chart3->renderJs() !!} 
{!! $chart4->renderJs() !!} 
@endpush