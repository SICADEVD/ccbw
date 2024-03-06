@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered">

                        <tr>
                            <td>Accord de consentement du producteur
                            </td>
                            <td>
                                {{ @$producteur->consentement }}

                            </td>
                        </tr>

                        <tr>
                            <td>Comment vous vous definissez ?
                            </td>
                            <td>
                                {{ @$producteur->proprietaires }}

                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                {{ @$producteur->plantePartage }}

                            </td>
                        </tr>

                        <tr>
                            <td>Année de démarrage
                            </td>
                            <td>
                                {{ @$producteur->anneeDemarrage }}

                            </td>
                        </tr>
                        <tr>
                            <td>Année de fin
                            </td>
                            <td>
                                {{ @$producteur->anneeFin }}


                            </td>
                        </tr>
                        <tr>
                            <td>Statut
                            </td>
                            <td>
                                {{ @$producteur->statut }}

                            </td>
                        </tr>

                        <tr>
                            <td>Année de certification
                            </td>
                            <td>
                                {{ @$producteur->certificat }}

                            </td>
                        </tr>
                        <tr>
                            <td>Code producteur
                            </td>
                            <td>
                                {{ @$producteur->codeProd }}

                            </td>
                        </tr>

                        <tr>
                            <td>Certificat

                            </td>
                            <td>
                                {{ @$producteur->certificats }}

                            </td>
                        </tr>

                        <tr>
                            <td>Autre Certificat
                            </td>
                            <td>
                                {{ @$producteur->autreCertificats }}

                            </td>
                        </tr>

                        <tr>
                            <td>
                                Section
                            </td>

                            <td>
                                {{ @$producteur->section }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Localite
                            </td>
                            <td>
                                {{ @$producteur->localite_id }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Programme
                            </td>
                            <td>
                                {{ @$producteur->programme_id }}
                            </td>
                        </tr>
                        <tr>
                            <td>Habitez-vous dans un campement ou village ?
                            </td>
                            <td>
                                {{ @$producteur->habitationProducteur }}
                            </td>
                        </tr>
                        <tr>
                            <td>Nom du producteur
                            </td>
                            <td>
                                {{ @$producteur->nom }}
                            </td>
                        </tr>

                        <tr>
                            <td>Prenoms du producteur
                            </td>
                            <td>
                                {{ @$producteur->prenoms }}
                            </td>
                        </tr>

                        <tr>
                            <td>Genre
                            </td>
                            <td>
                                {{ @$producteur->sexe }}
                            </td>
                        </tr>

                        <tr>
                            <td>Statut matrimonial
                            </td>
                            <td>
                                {{ @$producteur->statutMatrimonial }}
                            </td>
                        </tr>

                        <tr>
                            <td>Nationalité
                            </td>
                            <td>
                                {{ @$producteur->nationalite }}
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Date de naissance
                            </td>
                            <td>
                                {{ @$producteur->dateNaiss }}
                            </td>
                        </tr>
                        <tr>
                            <td>Numero de téléphone
                            </td>
                            <td>
                                {{ @$producteur->phone1 }}
                            </td>
                        </tr>

                        <tr>
                            <td>Avez-vous un proche à contacter pour vous joindre
                            </td>
                            <td>
                                {{ @$producteur->autreMembre }} </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                {{ @$producteur->autrePhone }}
                            </td>
                        </tr>
                        <tr>
                            <td>Numero de téléphone
                            </td>
                            <td>
                                {{ @$producteur->phone2 }}

                            </td>
                        </tr>
                        <tr>
                            <td> Niveau d'étude
                            </td>
                            <td>
                                {{ @$producteur->niveau_etude }}
                            </td>
                        </tr>
                        <tr>
                            <td>Type de pièces
                            </td>
                            <td>
                                {{ @$producteur->type_piece }}
                            </td>
                        </tr>

                        <tr>
                            <td>N° de la pièce
                            </td>
                            <td>
                                {{ @$producteur->numPiece }} </td>
                        </tr>


                        <tr>
                            <td>N° de carte CCC
                            </td>
                            <td>
                                {{ @$producteur->num_ccc }} </td>
                        </tr>

                        <tr>
                            <td>Avez-vous une carte CMU ?
                            </td>
                            <td>
                                {{ @$producteur->carteCMU }} </td>
                        </tr>

                        <tr>
                            <td>N° de la pièce CMU
                            </td>
                            <td>
                                {{ @$producteur->numCMU }} </td>
                        </tr>

                        <tr>
                            <td>Votre type de carte de sécurité social
                            </td>
                            <td>
                                {{ @$producteur->typeCarteSecuriteSociale }}
                            </td>
                        </tr>
                        <tr>
                            <td>N° de carte de sécurité sociale
                            </td>
                            <td>

                                {{ @$producteur->numSecuriteSociale }}

                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.traca.producteur.index') }}" />
@endpush

@push('script')
    <script type="text/javascript">
        //$("#section_id").chained("#localite");
    </script>
@endpush

@push('script')
    <script type="text/javascript">
        $('#listecultures,#gardePapiersChamps,#numeroCompteMM,#typeCarteSecuriteSociales,#garantie,#autrePhones,#autreCertificat,#plantePartager,#statutCertifie')
            .hide();
        $(document).ready(function() {
            function handleStatutChange() {
                var statut = $('.statut').val();
                if (statut == 'Certifie') {
                    $('#statutCertifie').show('slow');
                    $('.certificat').show('slow');
                    $('.codeProd').show('slow');
                    $('#certificat').prop('required', true);
                    $('#codeProd').prop('required', true);
                    $('.certificats').show('slow');
                    $('.select2-multi-select.certificats').prop('required', true);
                } else {
                    $('#statutCertifie').hide('slow');
                    $('#certificat').val('');
                    $('#codeProd').val('');
                    $('#certificat').prop('required', false);
                    $('#codeProd').prop('required', false);
                    $('.certificats').hide('slow');
                    var select2Element = $('.select2-multi-select.certificats');
                    select2Element.val(null).trigger('change');
                    select2Element.prop('required', false);
                }
            }

            $('.statut').change(handleStatutChange);
            handleStatutChange();
        });
        //afficher le champ de saisie du numero de la piece de sécurité sociale
        $('.typeCarteSecuriteSociale').change(function() {
            var typeCarteSecuriteSociale = $('.typeCarteSecuriteSociale').val();
            if (typeCarteSecuriteSociale == 'AUCUN') {

                $('#typeCarteSecuriteSociales').hide('slow');
                $('.numSecuriteSociale').val('');
                $("#numSecuriteSociale").prop("required", false);
            } else {
                $('#typeCarteSecuriteSociales').show('slow');
                $('.numSecuriteSociale').show('slow');
                $("#numSecuriteSociale").prop("required", true);
            }
        });
        if ($('.typeCarteSecuriteSociale').val() == 'AUCUN') {
            $('#typeCarteSecuriteSociales').hide('slow');
            $('.numSecuriteSociale').val('');
            $("#numSecuriteSociale").prop("required", false);
        } else {
            $('#typeCarteSecuriteSociales').show('slow');
            $('.numSecuriteSociale').show('slow');
            $("#numSecuriteSociale").prop("required", true);
        }

        //afficher le champ autre certificat

        $('.certificats').change(function() {
            var certificats = $('.certificats').find(":selected").map((key, item) => {
                return item.textContent.trim();
            }).get();
            console.log(certificats);
            if (certificats.includes("Autre")) {
                $('#autreCertificat').show('slow');
                $("#autreCertificats").prop("required", true);
            } else {
                $('#autreCertificat').hide('slow');
                $('.autreCertificats').val('');
                $("#autreCertificats").prop("required", false);
            }
        });
        if ($('.certificats').find(":selected").map((key, item) => {
                return item.textContent.trim();
            }).get().includes("Autre")) {
            $('#autreCertificat').show('slow');
            $("#autreCertificats").prop("required", true);
        } else {
            $('#autreCertificat').hide('slow');
            $('.autreCertificats').val('');
            $("#autreCertificats").prop("required", false);
        }

        //afficher le champ de saisie du numéro de téléphone d'une autre personne

        $('.autreMembre').change(function() {
            var autreMembre = $('.autreMembre').val();
            if (autreMembre == 'oui') {
                $('#autrePhones').show('slow');
                $('.autrePhone').show('slow');
                $("#autrePhone").prop("required", true);
                $('.phone2').show('slow');
                $("#phone2").prop("required", true);
            } else {
                $('#autrePhones').hide('slow');
                $('.autrePhone').val('');
                $('.phone2').val('');
                $("#autrePhone").prop("required", false);
                $("#phone2").prop("required", false);
            }
        });
        if ($('.autreMembre').val() == 'oui') {
            $('#autrePhones').show('slow');
            $('.autrePhone').show('slow');
            $("#autrePhone").prop("required", true);
            $('.phone2').show('slow');
            $("#phone2").prop("required", true);
        } else {
            $('#autrePhones').hide('slow');
            $('.autrePhone').val('');
            $('.phone2').val('');
            $("#autrePhone").prop("required", false);
            $("#phone2").prop("required", false);
        }

        //afficher le champ de saisie année de garantie

        $('.proprietaires').change(function() {
            var proprietaires = $('.proprietaires').val();
            if (proprietaires == 'Garantie') {
                $('#garantie').show('slow');
                $("#anneeDemarrage").prop("required", true);
                $("#anneeFin").prop("required", true);

            } else {
                $('#garantie').hide('slow');
                $('anneeDemarrage').val('');
                $('anneeFin').val('');
                $("#anneeDemarrage").prop("required", false);
                $("#anneeFin").prop("required", false);
            }
        });
        if ($('.proprietaires').val() == 'Garantie') {
            $('#garantie').show('slow');
            $("#anneeDemarrage").prop("required", true);
            $("#anneeFin").prop("required", true);

        } else {
            $('#garantie').hide('slow');
            $('anneeDemarrage').val('');
            $('anneeFin').val('');
            $("#anneeDemarrage").prop("required", false);
            $("#anneeFin").prop("required", false);
        }

        $('.proprietaires').change(function() {
            var proprietaires = $('.proprietaires').val();
            if (proprietaires == 'Planté-partager') {
                $('#plantePartager').show('slow');
                $('.plantePartage').show('slow');
                $("#plantePartage").prop("required", true);
            } else {
                $('#plantePartager').hide('slow');
                $('.plantePartage').val('');
                $("#plantePartage").prop("required", false);
            }
        });
        if ($('.proprietaires').val() == 'Planté-partager') {
            $('#plantePartager').show('slow');
            $('.plantePartage').show('slow');
            $("#plantePartage").prop("required", true);
        } else {
            $('#plantePartager').hide('slow');
            $('.plantePartage').val('');
            $("#plantePartage").prop("required", false);
        }

        //afficher le champ de saisie du numero de la piece CMU
        $('.carteCMU').change(function() {
            var cmu = $('.carteCMU').val();
            if (cmu == 'oui') {
                $('#pieceCMU').show('slow');
                $('.numCMU').show('slow');
                $("#numCMU").prop("required", true);

            } else {
                $('#pieceCMU').hide('slow');
                $('.numCMU').val('');
                $("#numCMU").prop("required", false);
            }
        });
        if ($('.carteCMU').val() == 'oui') {
            $('#pieceCMU').show('slow');
            $('.numCMU').show('slow');
            $("#numCMU").prop("required", true);

        } else {
            $('#pieceCMU').hide('slow');
            $('.numCMU').val('');
            $("#numCMU").prop("required", false);
        }

        $('#superficie').hide();
        $('.foretsjachere').change(function() {
            var foretsjachere = $('.foretsjachere').val();
            if (foretsjachere == 'oui') {
                $('#superficie').show('slow');
            } else {
                $('#superficie').hide('slow');
                $('.superficie').val('');
            }
        });
        if ($('.foretsjachere').val() == 'oui') {
            $('#superficie').show('slow');
        } else {
            $('#superficie').hide('slow');
            $('.superficie').val('');
        }
    </script>
    <script type="text/javascript">
        $("#localite_id").chained("#section");

        $(document).ready(function() {
            $(".select2-basic").select2();
        });
    </script>
@endpush
