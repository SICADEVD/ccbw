@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>Avez-vous des forets ou jachère ?</td>
                            <td>
                                {{ @$infosproducteur->foretsjachere }}

                            </td>
                        </tr>

                        <tr>
                            <td>Superficie</td>
                            <td>{{ @$infosproducteur->superficie }}</td>
                        </tr>
                        <tr>
                            <td>Avez-vous D’autres Cultures En Dehors Du Cacao?</td>
                            <td>{{ @$infosproducteur->autresCultures }}</td>
                        </tr>
                        @if ($infosproducteur->typesculture)
                            <tr>
                                <td>Type de culture</td>
                                <td>
                                    {{ implode(', ', $infosproducteur->typesculture->pluck('typeculture')->toArray()) }}
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td>Avez-vous d’autres activités en dehors des cultures?</td>
                            <td>
                                {{ @$infosproducteur->autreActivite }}

                            </td>
                        </tr>
                        @if ($infosproducteur->autresactivites)
                            <tr>
                                <td>Activités</td>
                                <td>
                                    {{ implode(', ', $infosproducteur->autresactivites->pluck('typeactivite')->toArray()) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Avez-vous recours à une main d'œuvre familiale ?</td>
                            <td>
                                {{ @$infosproducteur->mainOeuvreFamilial }}
                            </td>
                        </tr>
                        @if ($infosproducteur->mainOeuvreFamilial == 'oui')
                            <tr>
                                <td>Combien De Personnes (de La Famille Travaillent)</td>
                                <td>
                                    {{ @$infosproducteur->travailleurFamilial }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Combien de travailleurs (rémunéré) avez-vous ?</td>
                            <td>
                                {{ @$infosproducteur->travailleurs }}
                            </td>
                        </tr>
                        <tr>
                            <td>Nombre de Travailleurs Permanents (plus de 12mois)</td>
                            <td>
                                {{ @$infosproducteur->travailleurspermanents }}
                            </td>
                        </tr>

                        <tr>
                            <td>Nombre de Travailleurs temporaires</td>
                            <td>
                                {{ @$infosproducteur->travailleurstemporaires }}
                            </td>
                        </tr>
                        <tr>
                            <td>Etes vous membre de société de travail ?</td>
                            <td>
                                {{ @$infosproducteur->societeTravail }}
                            </td>
                        </tr>
                        @if ($infosproducteur->societeTravail == 'oui')
                            <tr>
                                <td>Nombre De Personne</td>
                                <td>
                                    {{ @$infosproducteur->nombrePersonne }}
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td>As-tu un Compte Mobile Money ?</td>
                            <td>{{ @$infosproducteur->mobileMoney }}</td>
                        </tr>
                        @if ($infosproducteur->mobileMoney == 'oui')
                            @foreach($infosproducteur->mobiles as $mobile)
                                <tr>
                                    <td>Compte {{ @$mobile->operateur }}</td>
                                    <td>
                                        {{ @$mobile->numero }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td>As-tu un compte bancaire (dans une banque) ?</td>
                            <td>
                                {{ @$infosproducteur->compteBanque }}
                            </td>
                        </tr>
                        @if ($infosproducteur->compteBanque == 'oui')
                            <tr>
                                <td>Nom de la banque</td>
                                <td>
                                    {{ @$infosproducteur->nomBanque }}
                                </td>
                            </tr>
                            @if ($infosproducteur->nomBanque == 'Autre')
                                <tr>
                                    <td>Nom de la banque</td>
                                    <td>
                                        {{ @$infosproducteur->autreBanque }}
                                    </td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.traca.producteur.infos', encrypt($infosproducteur->producteur_id)) }}" />
@endpush

@push('script')
    <script type="text/javascript">
        $('#listecultures,#gardePapiersChamps,#numeroCompteMM,#listeactivites,#nomBanque,#autreBanque,#travailleurFamilial')
            .hide();
        $('.foretsjachere').change(function() {
            var foretsjachere = $('.foretsjachere').val();
            if (foretsjachere == 'oui') {
                $('#superficie').show('slow');
            } else {
                $('#superficie').hide('slow');
            }
        });
        if ($('.foretsjachere').val() == 'oui') {
            $('#superficie').show('slow');
        } else {
            $('#superficie').hide('slow');
        }
        $('.autresCultures').change(function() {
            var autresCultures = $('.autresCultures').val();
            if (autresCultures == 'oui') {
                $('#listecultures').show('slow');
            } else {
                $('#listecultures').hide('slow');
                $('.listecultures').val('');
            }
        });
        if ($('.autresCultures').val() == 'oui') {
            $('#listecultures').show('slow');
        } else {
            $('#listecultures').hide('slow');
            $('.listecultures').val('');
        }
        $('.mainOeuvreFamilial').change(function() {
            var mainOeuvreFamilial = $('.mainOeuvreFamilial').val();
            if (mainOeuvreFamilial == 'oui') {
                $('#travailleurFamilial').show('slow');
                $('.travailleurFamilial').show('slow');
            } else {
                $('#travailleurFamilial').hide('slow');
                $('.travailleurFamilial').val('');
            }
        });
        $('.mainOeuvreNonFamilial').change(function() {
            var mainOeuvreNonFamilial = $('.mainOeuvreNonFamilial').val();
            if (mainOeuvreNonFamilial == 'oui') {
                $('#travailleurNonFamilial').show('slow');
                $('.travailleurNonFamilial').show('slow');
            } else {
                $('#travailleurNonFamilial').hide('slow');
                $('.travailleurNonFamilial').val('');
            }
        });

        $('.societeTravail').change(function() {
            var societeTravail = $('.societeTravail').val();
            if (societeTravail == 'oui') {
                $('#societe').show('slow');
                $('#nombrePersonne').prop('required', true);
            } else {
                $('#societe').hide('slow');
                $('#nombrePersonne').prop('required', false);
                $('.nombrePersonne').val('');
            }
        });
        if ($('.societeTravail').val() == 'oui') {
            $('#societe').show('slow');
            $('#nombrePersonne').prop('required', true);
        } else {
            $('#societe').hide('slow');
            $('#nombrePersonne').prop('required', false);
            $('.nombrePersonne').val('');
        }

        $('.nomBanque').change(function() {
            var nomBanque = $('.nomBanque').val();
            if (nomBanque == 'Autre') {
                $('#autreBanque').show('slow');
                $('.autreBanque').show('slow');
            } else {
                $('#autreBanque').hide('slow');
                $('.autreBanque').val('');
            }
        });
        if ($('.nomBanque').val() == 'Autre') {
            $('#autreBanque').show('slow');
            $('.autreBanque').show('slow');
        } else {
            $('#autreBanque').hide('slow');
            $('.autreBanque').val('');
        }
        $('.compteBanque').change(function() {
            var compteBanque = $('.compteBanque').val();
            if (compteBanque == 'oui') {
                $('#nomBanque').show('slow');
                $('.nomBanque').show('slow');
            } else {
                $('#nomBanque').hide('slow');
                $('.nomBanque').val('');
            }
        });
        if ($('.compteBanque').val() == 'oui') {
            $('#nomBanque').show('slow');
            $('.nomBanque').show('slow');
        } else {
            $('#nomBanque').hide('slow');
            $('.nomBanque').val('');
        }
        $('.autreActivite').change(function() {
            var autreActivite = $('.autreActivite').val();
            if (autreActivite == 'oui') {
                $('#listeactivites').show('slow');
            } else {
                $('#listeactivites').hide('slow');
                $('.listeactivites').val('');
            }
        });
        if ($('.autreActivite').val() == 'oui') {
            $('#listeactivites').show('slow');
        } else {
            $('#listeactivites').hide('slow');
            $('.listeactivites').val('');
        }

        $('.mobileMoney').change(function() {
            var mobileMoney = $('.mobileMoney').val();
            if (mobileMoney == 'oui') {
                $('#numeroCompteMM').show('slow');
                $('.numeroCompteMM').css('display', 'block');
            } else {
                $('#numeroCompteMM').hide('slow');
                $('.numeroCompteMM').val('');
            }
        });
        if ($('.mobileMoney').val() == 'oui') {
            $('#numeroCompteMM').show('slow');
            $('.numeroCompteMM').css('display', 'block');
        } else {
            $('#numeroCompteMM').hide('slow');
            $('.numeroCompteMM').val('');
        }
    </script>
@endpush
