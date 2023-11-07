@extends('manager.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--purple has-link box--shadow2">
                <a href="{{ route('manager.livraison.sentQueue') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-hourglass-start f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Livraison en attente")</span>
                            <h2 class="text-white">{{ $livraisonQueueCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--cyan has-link box--shadow2">
                <a href="{{ route('manager.livraison.upcoming') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-history f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Livraison encours')</span>
                            <h2 class="text-white">{{ $upcomingCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--pink has-link box--shadow2">
                <a href="{{ route('manager.livraison.deliveryInQueue') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="lab la-accessible-icon f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('En attente de reception')</span>
                            <h2 class="text-white">{{ $deliveryQueueCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--green has-link box--shadow2">
                <a href="{{ route('manager.livraison.sent') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-check-double f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total envoyé")</span>
                            <h2 class="text-white">{{ $totalSentCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--deep-purple has-link box--shadow2">
                <a href="{{ route('manager.livraison.delivered') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-list-alt f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total Livré")</span>
                            <h2 class="text-white">{{ $livraisonDelivered }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--teal has-link box--shadow2">
                <a href="{{ route('manager.livraison.index') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-shipping-fast f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Toutes les livraisons')</span>
                            <h2 class="text-white">{{ $livraisonInfoCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--primary has-link overflow-hidden box--shadow2">
                <a href="{{ route('manager.staff.index') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-user-friends f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Total Staff')</span>
                            <h2 class="text-white">{{ $totalStaffCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--lime has-link box--shadow2">
                <a href="{{ route('manager.cooperative.index') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-university f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang(' Total Coopérative')</span>
                            <h2 class="text-white">{{ $cooperativeCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--orange has-link box--shadow2">
                <a href="{{ route('manager.cooperative.income') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-money-bill-wave f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total Recette")</span>
                            <h2 class="text-white">{{ showAmount($cooperativeIncome) }} {{ $general->cur_sym }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

    </div><!-- row end-->

   
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end">
        <h3>{{ __(auth()->user()->cooperative->name) }}</h3>
    </div>
@endpush
