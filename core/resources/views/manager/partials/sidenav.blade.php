<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('manager.dashboard') }}" class="sidebar__main-logo"><img
                    src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('manager.dashboard') }}">
                    <a href="{{ route('manager.dashboard') }}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Tableau de bord')</span>
                    </a>
                </li>


                <li class="sidebar-menu-item {{ menuActive('manager.staff.index') }}">
                    <a href="{{ route('manager.staff.index') }}" class="nav-link ">
                        <i class="menu-icon las la-user-friends"></i>
                        <span class="menu-title">@lang('Gestion des Staffs')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)"
                        class="{{ menuActive(['manager.hr.*', 'manager.employees.index', 'manager.holidays.*', 'manager.departments.*', 'manager.designations.*', 'manager.holidays.*', 'manager.leaves.*', 'manager.archivages.*', 'manager.formation-staff.*', 'presentation-coop.*'], 3) }}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Gouvernance Ameliorée') </span>
                    </a>
                    <div
                        class="sidebar-submenu {{ menuActive(['manager.hr.*', 'manager.employees.*', 'manager.holidays.*', 'manager.departments.*', 'manager.designations.*', 'manager.holidays.*', 'manager.leaves.*', 'manager.archivages.*', 'manager.formation-staff.*', 'presentation-coop.*'], 2) }} ">
                        <ul>

                            <li class="sidebar-menu-item {{ menuActive('presentation-coop.index') }}">
                                <a href="{{ route('presentation-coop.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Présentation de la coopérative')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.employees.index') }}">
                                <a href="{{ route('manager.employees.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Tous les employés')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.hr.attendances.index') }}">
                                <a href="{{ route('manager.hr.attendances.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Présences')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.leaves.index') }}">
                                <a href="{{ route('manager.leaves.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Congés')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.holidays.index') }}">
                                <a href="{{ route('manager.holidays.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Jours Fériés')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.formation-staff.*') }}">
                                <a href="{{ route('manager.formation-staff.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Formations Staff')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.archivages.*') }}">
                                <a href="{{ route('manager.archivages.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Archivages')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['manager.suivi.menage.*','manager.suivi.ssrteclmrs.*'], 3) }}">
                        <i class=" menu-icon las la-universal-access"></i>
                        <span class="menu-title">@lang('SSRTE') </span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive(['manager.suivi.menage.*'], 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('manager.suivi.menage.index') }}">
                                <a href="{{ route('manager.suivi.menage.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Menages')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.suivi.ssrteclmrs.index') }}">
                                <a href="{{ route('manager.suivi.ssrteclmrs.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('SSRTE-CLMRS')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive([], 3) }}">
                        <i class=" menu-icon las la-universal-access"></i>
                        <span class="menu-title">@lang('Communauté résiliente') </span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive([], 2) }} ">
                        <ul>
                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('manager.traca.*', 3) }}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Gestion de la Traçabilites') </span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('manager.traca.*', 2) }} ">
                        <ul>

                            <li class="sidebar-menu-item {{ menuActive('manager.traca.producteur.index') }}">
                                <a href="{{ route('manager.traca.producteur.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Producteurs')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.traca.parcelle.index') }}">
                                <a href="{{ route('manager.traca.parcelle.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Identifications Parcelles')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.traca.estimation.index') }}">
                                <a href="{{ route('manager.traca.estimation.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Estimations')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('manager.suivi*', 3) }}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Gestion Suivi
                                                                        Productivité') </span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('manager.suivi*', 2) }} ">
                        <ul>

                            <li class="sidebar-menu-item {{ menuActive('manager.suivi.parcelles.index') }}">
                                <a href="{{ route('manager.suivi.parcelles.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Suivis Parcelles')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.suivi.formation.index') }}">
                                <a href="{{ route('manager.suivi.formation.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Formations')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.suivi.inspection.index') }}">
                                <a href="{{ route('manager.suivi.inspection.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Inspections')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.suivi.application.index') }}">
                                <a href="{{ route('manager.suivi.application.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Applications Phytos')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)"
                        class="{{ menuActive(['manager.livraison.*', 'manager.livraison.magcentral.*'], 3) }}">
                        <i class="menu-icon las la-university"></i>
                        <span class="menu-title">@lang('Gestion des livraisons') </span>
                    </a>
                    <div
                        class="sidebar-submenu {{ menuActive(['manager.livraison.*', 'manager.livraison.magcentral.*'], 2) }} ">
                        <ul>
                            <!-- <li
                                class="sidebar-menu-item {{ menuActive(['manager.livraison.index']) }}">
                                <a href="{{ route('manager.livraison.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Livraison Magasins de Section')</span>
                                </a>

                            </li> -->
                            <li
                                class="sidebar-menu-item {{ menuActive(['manager.livraison.stock.*']) }}">
                                <a href="{{ route('manager.livraison.stock.section') }}" class="nav-link">
                                    <i class="menu-icon las la-server"></i>
                                    <span class="menu-title">@lang('Stock Magasins de Section')</span>
                                </a>
                            </li> 
                            <!-- <li class="sidebar-menu-item {{ menuActive(['manager.livraison.magcentral.*']) }}">
                                <a href="{{ route('manager.livraison.magcentral.index') }}" class="nav-link">
                                    <i class="menu-icon las la-server"></i>
                                    <span class="menu-title">@lang('Livraison Magasins Centraux')</span>
                                </a>
                            </li> -->
                            <li class="sidebar-menu-item {{ menuActive(['manager.livraison.magcentral*']) }}">
                                <a href="{{ route('manager.livraison.magcentral.stock') }}" class="nav-link">
                                    <i class="menu-icon las la-server"></i>
                                    <span class="menu-title">@lang('Stock Magasins Centraux')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['manager.livraison.usine.connaissement']) }}">
                                <a href="{{ route('manager.livraison.usine.connaissement') }}" class="nav-link">
                                    <i class="menu-icon las la-server"></i>
                                    <span class="menu-title">@lang('Connaissement Usine')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['manager.livraison.prime.producteur']) }}">
                                <a href="{{ route('manager.livraison.prime.producteur') }}" class="nav-link">
                                    <i class="menu-icon las la-server"></i>
                                    <span class="menu-title">@lang('Prime aux Producteurs')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('manager.agro*', 3) }}">
                        <i class="menu-icon las la-tree"></i>
                        <span class="menu-title">@lang('Agroforesterie') </span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('manager.agro*', 2) }} ">
                        <ul>

                            <li class="sidebar-menu-item {{ menuActive('manager.agro.evaluation.index') }}">
                                <a href="{{ route('manager.agro.evaluation.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Evaluation des besoins')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.agro.approvisionnement.index') }}">
                                <a href="{{ route('manager.agro.approvisionnement.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approvisionnement')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.agro.distribution.index') }}">
                                <a href="{{ route('manager.agro.distribution.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Suivi distribution')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.agro.deforestation.index') }}">
                                <a href="{{ route('manager.agro.deforestation.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Risques Déforestation')</span>
                                </a>
                            </li>


                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item  {{ menuActive('ticket*') }}">
                    <a href="{{ route('manager.ticket.index') }}" class="nav-link">
                        <i class="menu-icon las la-ticket-alt"></i>
                        <span class="menu-title">@lang('Support Ticket')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('manager.settings.*') }}">
                    <a href="{{ route('manager.settings.cooperative-settings.index') }}" class="nav-link">
                        <i class="menu-icon las la-cogs"></i>
                        <span class="menu-title">@lang('Paramètres')</span>
                    </a>
                </li>
            </ul>

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
