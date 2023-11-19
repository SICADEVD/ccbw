
<div class="sidebar bg--dark">
    @php
        $upcomingCount = \App\Models\LivraisonInfo::where('receiver_cooperative_id', auth()->user()->id)
            ->where('status', 1)
            ->count();
        // $deliveryCount = \App\Models\LivraisonInfo::where('receiver_cooperative_id',)
        use Illuminate\Support\Facades\Auth;
    @endphp
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('staff.dashboard') }}" class="sidebar__main-logo"><img
                    src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('staff.dashboard') }}">
                    <a href="{{ route('staff.dashboard') }}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang("Tableau de bord")</span>
                    </a>
                </li>
               {{--livraison --}}
               @if(Auth::user()->can('staff.livraison.index'))
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)" class="{{ menuActive('staff.livraison*', 3) }}">
                            <i class="menu-icon las la-sliders-h"></i>
                            <span class="menu-title">@lang('Livraison') </span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive('staff.livraison*', 2) }} ">
                            <ul>
                                @can('staff.livraison.create')
                                    <li class="sidebar-menu-item {{ menuActive('staff.livraison.create') }}">
                                
                                    <a href="{{ route('staff.livraison.create') }}" class="nav-link ">
                                        <i class="menu-icon las la-shipping-fast"></i>
                                        <span class="menu-title">@lang("Enregistrement")</span>
                                    </a>
                                    </li>
                                @endcan
                    
                                @can('staff.livraison.sent.queue')
                                    <li class="sidebar-menu-item {{ menuActive('staff.livraison.sent.queue') }}">
                                        <a href="{{ route('staff.livraison.sent.queue') }}" class="nav-link ">
                                            <i class="menu-icon las la-hourglass-start"></i>
                                            <span class="menu-title">@lang("En attente d'expédition")</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.livraison.dispatch')
                                    <li class="sidebar-menu-item {{ menuActive('staff.livraison.dispatch') }}">
                                        <a href="{{ route('staff.livraison.dispatch') }}" class="nav-link ">
                                            <i class="menu-icon las la-sync"></i>
                                            <span class="menu-title">@lang("Expédiée") </span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.livraison.upcoming')
                                    <li class="sidebar-menu-item {{ menuActive('staff.livraison.upcoming') }}">
                                        <a href="{{ route('staff.livraison.upcoming') }}" class="nav-link ">
                                            <i class="menu-icon las la-history"></i>
                                            <span class="menu-title">@lang("Encours") @if ($upcomingCount > 0)
                                                    <span class="menu-badge pill bg--danger ms-auto">{{ $upcomingCount }}</span>
                                                @endif
                                            </span>

                                        </a>
                                    </li>
                                @endcan
                                @can('staff.livraison.delivery.queue')
                                    <li class="sidebar-menu-item {{ menuActive('staff.livraison.delivery.queue') }}">
                                        <a href="{{ route('staff.livraison.delivery.queue') }}" class="nav-link ">
                                            <i class="menu-icon lab la-accessible-icon"></i>
                                            <span class="menu-title">@lang("En attente de reception")</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endif
                {{--fin livraison --}}

                {{--Début gestion des livraisons --}}
                @if(Auth::user()->can('staff.livraison.manage.sent.list'))
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)" class="{{ menuActive('staff.livraison.manage*', 3) }}">
                            <i class="menu-icon las la-sliders-h"></i>
                            <span class="menu-title">@lang('Gestion des livraisons') </span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive('staff.livraison.manage*', 2) }} ">
                            <ul>
                                @can('staff.livraison.manage.sent.list')
                                    <li class="sidebar-menu-item {{ menuActive('staff.livraison.manage.sent.list') }}">
                                        <a href="{{ route('staff.livraison.manage.sent.list') }}" class="nav-link ">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Total envoyé')</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.livraison.manage.delivered')
                                    <li class="sidebar-menu-item {{ menuActive('staff.livraison.manage.delivered') }}">
                                        <a href="{{ route('staff.livraison.manage.delivered') }}" class="nav-link ">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Total Livré')</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('staff.livraison.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.livraison.index') }}">
                                        <a href="{{ route('staff.livraison.index') }}" class="nav-link ">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Toutes les livraisons')</span>
                                        </a>
                                    </li>       
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endif
                {{--Fin gestion des livraisons --}}

                {{-- debut gestion de suivis --}}
                @if(Auth::user()->can('staff.suivi.menage.index') || Auth::user()->can('staff.suivi.formation.index') || Auth::user()->can('staff.suivi.inspection.index') || Auth::user()->can('staff.suivi.application.index') || Auth::user()->can('staff.suivi.ssrteclmrs.index') || Auth::user()->can('staff.suivi.parcelles.index'))
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)" class="{{ menuActive('staff.suivi*', 3) }}">
                            <i class="menu-icon las la-users"></i>
                            <span class="menu-title">@lang('Gestion de suivis') </span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive('staff.suivi*', 2) }} ">
                            <ul>
                                @can('staff.suivi.menage.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.suivi.menage.index') }}">
                                        <a href="{{ route('staff.suivi.menage.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Menages')</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('staff.suivi.parcelles.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.suivi.parcelles.index') }}">
                                        <a href="{{ route('staff.suivi.parcelles.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Parcelles')</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.suivi.formation.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.suivi.formation.index') }}">
                                        <a href="{{ route('staff.suivi.formation.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Formations')</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.suivi.inspection.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.suivi.inspection.index') }}">
                                        <a href="{{ route('staff.suivi.inspection.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Inspections')</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.suivi.application.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.suivi.application.index') }}">
                                        <a href="{{ route('staff.suivi.application.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Applications')</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.suivi.ssrteclmrs.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.suivi.ssrteclmrs.index') }}">
                                        <a href="{{ route('staff.suivi.ssrteclmrs.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('SSRTE-CLMRS')</span>
                                        </a>
                                    </li> 
                                @endcan
                                
                            </ul>
                        </div>
                    </li>
                @endif
                {{--fin gestion de suivis --}}
                {{-- Début Gestion de la Traçabilites --}}
                 @if(Auth::user()->can('staff.traca.producteur.index') || Auth::user()->can('staff.traca.parcelle.index') || Auth::user()->can('staff.traca.estimation.index'))
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)" class="{{ menuActive('staff.traca.*', 3) }}">
                            <i class="menu-icon las la-users"></i>
                            <span class="menu-title">@lang('Gestion de la Traçabilites') </span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive('staff.traca.*', 2) }} ">
                            <ul>
                              @can('staff.traca.producteur.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.traca.producteur.index') }}">
                                        <a href="{{ route('staff.traca.producteur.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Producteurs')</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.traca.parcelle.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.traca.parcelle.index') }}">
                                        <a href="{{ route('staff.traca.parcelle.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Parcelles')</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('staff.traca.estimation.index')
                                    <li class="sidebar-menu-item {{ menuActive('staff.traca.estimation.index') }}">
                                        <a href="{{ route('staff.traca.estimation.index') }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">@lang('Estimations')</span>
                                        </a>
                                    </li>
                                @endcan
                                {{-- <li class="sidebar-menu-item {{ menuActive('staff.livraison.deliveryInQueue') }}">
                                    <a href="{{ route('staff.livraison.deliveryInQueue') }}" class="nav-link">
                                        <i class="menu-icon las la-dot-circle"></i>
                                        <span class="menu-title">@lang('Livraison')</span>
                                    </a>
                                </li>  --}}

                            </ul>
                        </div>
                    </li>
                @endif
                {{-- Fin Gestion de la Traçabilites --}}

                {{-- debut liste des coopératives --}}
                @if(Auth::user()->can('staff.cooperative.index'))
                    <li class="sidebar-menu-item {{ menuActive('staff.cooperative.index') }}">
                        <a href="{{ route('staff.cooperative.index') }}" class="nav-link ">
                            <i class="menu-icon las la-university"></i>
                            <span class="menu-title">@lang('Liste des coopératives')</span>
                        </a>
                    </li>
                @endif
                {{-- fin liste des coopératives --}}
                {{-- debut recette  --}}
                @if(Auth::user()->can('staff.cash.livraison.income'))
                    <li class="sidebar-menu-item  {{ menuActive('staff.cash.livraison.income') }}">
                        <a href="{{ route('staff.cash.livraison.income') }}" class="nav-link">
                            <i class="menu-icon las la-wallet"></i>
                            <span class="menu-title">@lang('Recette')</span>
                        </a>
                    </li>
                @endif
                {{-- fin recette --}}
                {{-- debut ticket --}}
                @if(Auth::user()->can('staff.ticket.index'))
                    <li class="sidebar-menu-item  {{ menuActive('ticket*') }}">
                        <a href="{{ route('staff.ticket.index') }}" class="nav-link">
                            <i class="menu-icon las la-ticket-alt"></i>
                            <span class="menu-title">@lang('Support Ticket')</span>
                        </a>
                    </li> 
                @endif
                {{-- fin ticket --}}
            </ul>
            <div class="text-center mb-3 text-uppercase">
                <span class="text--primary">{{ __(systemDetails()['name']) }}</span>
                <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span>
            </div>
        </div>

        
        
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <div class="text-center mb-3 text-uppercase">
                <span class="text--primary">{{ __(systemDetails()['name']) }}</span>
                <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
