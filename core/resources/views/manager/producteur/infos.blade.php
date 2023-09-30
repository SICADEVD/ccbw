@extends('manager.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">

                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Producteur')</th>
                                    <th>@lang('Forets & jachere')</th>
                                    <th>@lang('Autres Cultures')</th>
                                    <th>@lang('Papiers Champs')</th>
                                    <th>@lang('Numéro Mobile Money')</th>
                                    <th>@lang('Paiement Mobile')</th>
                                    <th>@lang('Compte Banque')</th>
                                    <th>@lang('Ajouté le')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($infosproducteurs as $info)
                                    <tr>

                                        <td>
                                            <span class="fw-bold">{{ $info->producteur->nom }}
                                                {{ $info->producteur->prenoms }}</span>
                                        </td>

                                        <td>

                                            <span class="fw-bold">{{ $info->foretsjachere }}</span>
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ $info->autresCultures }}</span>
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ $info->typeDocuments }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $info->mobileMoney }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $info->numeroCompteMM }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $info->compteBanque }}</span>
                                        </td>
                                        <td>
                                            {{ showDateTime($info->created_at) }} <br>
                                            {{ diffForHumans($info->created_at) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('manager.traca.producteur.editinfo', encrypt($info->id)) }}"
                                                class="icon-btn btn--primary ml-1">@lang('Edit')</a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <div id="cooperativeModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Créer une info producteur')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('manager.traca.producteur.storeinfo') }}" method="POST">
                    @csrf

                    <input type="hidden" name="producteur_id" value="{{ decrypt($id) }}" />
                    <div class="modal-body">
                        <div class="form-group row">
                            <?php echo Form::label(__('Avez-vous des forets ou jachère ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('foretsjachere', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control foretsjachere', 'required']); ?>

                            </div>
                        </div>


                        <div class="form-group row" id="superficie">
                            <?php echo Form::label(__('Superficie'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('superficie', null, ['placeholder' => 'Nombre', 'class' => 'form-control superficie', 'min' => '0']); ?>
                            </div>
                        </div>


                        <div class="form-group row">
                            <?php echo Form::label(__('Culture'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('autresCultures', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control autresCultures', 'required']); ?>

                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label('', null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8" id="listecultures">
                                <table class="table table-striped table-bordered">
                                    <tbody id="product_area">

                                        <tr>
                                            <td class="row">
                                                <div class="col-xs-12 col-sm-12 bg-success">
                                                    <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Information Culture 1')
                                                    </badge>
                                                </div>
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group row">
                                                        {{ Form::label(__('Type de culture'), null, ['class' => 'control-label']) }}
                                                        <input type="text" name="typeculture[]"
                                                            placeholder="Riz, Maïs, Igname, Banane, ..." id="typeculture-1"
                                                            class="form-control" value="{{ old('typeculture') }}">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group row">
                                                        {{ Form::label(__('Superficie de culture'), null, ['class' => 'control-label']) }}
                                                        <input type="text" name="superficieculture[]"
                                                            placeholder="Superficie de culture" id="superficieculture-1"
                                                            class="form-control " value="{{ old('superficieculture') }}">
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>

                                    </tbody>
                                    <tfoot style="background: #e3e3e3;">
                                        <tr>

                                            <td colspan="3">
                                                <button id="addRow" type="button" class="btn btn-success btn-sm"><i
                                                        class="fa fa-plus"></i></button>
                                            </td>
                                        <tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <hr class="panel-wide">

                        <div class="form-group row">
                            <?php echo Form::label(__("Nombre d'enfants de 5 à 17 ans présents dans le ménage ?"), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('age18', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                            </div>
                        </div>


                        <div class="form-group row">
                            <?php echo Form::label(__('Parmi ces personnes, combien sont scolarisés ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('persEcole', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__("Parmi les scolarisés, combien n'ont pas d'extrait de naissance ?"), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('scolarisesExtrait', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(__('Citez les maladies plus fréquentes chez vos enfants'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <table class="table table-striped table-bordered">
                                    <tbody id="maladies">

                                        <tr>
                                            <td class="row">
                                                <div class="col-xs-12 col-sm-12 bg-success">
                                                    <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Maladie 1')
                                                    </badge>
                                                </div>
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group row">
                                                        <input type="text" name="maladiesenfants[]"
                                                            placeholder="Rhume, Toux, ..." id="maladiesenfants-1"
                                                            class="form-control" value="{{ old('maladiesenfants') }}">
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>

                                    </tbody>
                                    <tfoot style="background: #e3e3e3;">
                                        <tr>

                                            <td colspan="3">
                                                <button id="addRowMal" type="button" class="btn btn-success btn-sm"><i
                                                        class="fa fa-plus"></i></button>
                                            </td>
                                        <tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <hr class="panel-wide">
                        <div class="form-group row">
                            <?php echo Form::label(__('Combien de travailleurs avez-vous ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('travailleurs', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Nombre de Travailleurs Permanents'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('travailleurspermanents', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Nombre de Travailleurs Non Permanents'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('travailleurstemporaires', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                            </div>
                        </div>
                        <hr class="panel-wide">
                        <div class="form-group row">
                            <?php echo Form::label(__('Quand une personne est blessée à la maison, que fais-tu ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('personneBlessee', ['Je me débrouille' => 'Je me débrouille', "Je vais rapidement à l'hôpital" => "Je vais rapidement à l'hôpital", "J'appelle quelqu'un" => "J'appelle quelqu'un", "J'ai des médicaments chez moi" => "J'ai des médicaments chez moi"], null, ['placeholder' => 'Selectionner une reponse...', 'class' => 'form-control personneBlessee', 'required']); ?>

                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Quel type de documents de tes champs possèdes-tu ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('typeDocuments', ['Titre foncier' => 'Titre foncier', 'Cadastre' => 'Cadastre', 'Attestation coutumières' => 'Attestation coutumières', 'Aucun' => 'Aucun'], null, ['class' => 'form-control typeDocuments', 'placeholder' => 'Selectionner une reponse...', 'required']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__("Comment gères-tu tes reçus d'achat ?"), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('recuAchat', ['Je les jette' => 'Je les jette', 'Je les gardes dans ma maison' => 'Je les gardes dans ma maison', 'Je ne prends pas' => 'Je ne prends pas'], null, ['placeholder' => 'Selectionner une reponse...', 'class' => 'form-control recuAchat', 'required']); ?>
                            </div>
                        </div>
                        <hr class="panel-wide">
                        <div class="form-group row">
                            <?php echo Form::label(__('As-tu un Compte Mobile Money ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('mobileMoney', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control mobileMoney', 'required']); ?>

                            </div>
                        </div>
                        <div id="numeroCompteMM">
                            <div class="form-group row">
                                <?php echo Form::label(__('Quel operateur ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                                <div class="col-xs-12 col-sm-8">
                                    <?php echo Form::select('operateurMM', ['Orange' => 'Orange', 'MTN' => 'MTN', 'Moov' => 'Moov', 'Wave' => 'Wave'], null, ['class' => 'form-control operateurMM']); ?>

                                </div>
                            </div>
                            <div class="form-group row">
                                <?php echo Form::label(__('Numéro du Compte Mobile Money'), null, ['class' => 'col-sm-4 control-label']); ?>
                                <div class="col-xs-12 col-sm-8">
                                    <?php echo Form::text('numeroCompteMM', null, ['placeholder' => __('Numéro du Compte Mobile Money'), 'class' => 'form-control phone numeroCompteMM']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(__('Accepterais tu qu’on paye ton cacao par ces moyens ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('paiementMM', ['Aucun' => 'Aucun', 'Virement bancaire' => 'Virement bancaire', 'Mobile Money' => 'Mobile Money'], null, ['class' => 'form-control paiementMM', 'required']); ?>

                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('As-tu un compte dans une banque ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('compteBanque', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control compteBanque', 'required']); ?>

                            </div>
                        </div>
                        <hr class="panel-wide">
                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark btnFermer" data-dismiss="modal">@lang('Fermer')</button>
                            <button type="submit" class="btn btn--primary"><i
                                    class="fa fa-fw fa-paper-plane"></i>@lang('Enregistrer une info')</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here ..." />
    @if (!$infosproducteurs->count())
        <a href="javascript:void(0)" class="btn  btn-outline--primary box--shadow1  h-45 addNewBrach"><i
                class="las la-plus"></i>@lang('Ajouter une info prod')</a>
    @endif
    <x-back route="{{ route('manager.traca.producteur.index') }}" />
@endpush

@push('script')
    <script>
        "use strict";
        $('.addNewBrach').on('click', function() {
            $('#cooperativeModel').modal('show');
        });


        $(document).ready(function() {
            $('#cooperativeModel .btnFermer').click(function() {
                $('#cooperativeModel').modal('hide');
            });
        });

        $(document).ready(function() {
            $('#cooperativeModel').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            var productCount = $("#product_area tr").length + 1;
            $(document).on('click', '#addRow', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Information Culture ' +
                    productCount +
                    '</badge></div><div class="col-xs-12 col-sm-12"><div class="form-group row"><label for="Type de culture" class="control-label">Type de culture</label><input placeholder="Riz, Maïs, Igname, Banane, ..." class="form-control" id="typeculture-' +
                    productCount +
                    '" name="typeculture[]" type="text"></div></div><div class="col-xs-12 col-sm-12"><div class="form-group row"><label for="superficieculture" class="control-label">Superficie de culture</label><input type="text" name="superficieculture[]" placeholder="Superficie de culture" id="superficieculture-' +
                    productCount +
                    '" class="form-control " value=""></div></div><div class="col-xs-12 col-sm-12"><button type="button" id="' +
                    productCount +
                    '" class="removeRow btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                productCount = parseInt(productCount) + 1;
                $('#product_area').append(html_table);

            });

            $(document).on('click', '.removeRow', function() {

                var row_id = $(this).attr('id');

                // delete only last row id
                if (row_id == $("#product_area tr").length) {

                    $(this).parents('tr').remove();

                    productCount = parseInt(productCount) - 1;

                    //    console.log($("#product_area tr").length);

                    //  productCount--;

                }
            });

        });
        $(document).ready(function() {

            var maladiesCount = $("#maladies tr").length + 1;
            $(document).on('click', '#addRowMal', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Maladie ' +
                    maladiesCount +
                    '</badge></div><div class="col-xs-12 col-sm-12"><div class="form-group row"><input placeholder="Rhume, Toux, ..." class="form-control" id="maladiesenfants-' +
                    maladiesCount +
                    '" name="maladiesenfants[]" type="text"></div></div><div class="col-xs-12 col-sm-12"><button type="button" id="' +
                    maladiesCount +
                    '" class="removeRowMal btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                maladiesCount = parseInt(maladiesCount) + 1;
                $('#maladies').append(html_table);

            });

            $(document).on('click', '.removeRowMal', function() {

                var row_id = $(this).attr('id');

                // delete only last row id
                if (row_id == $("#maladies tr").length) {

                    $(this).parents('tr').remove();

                    maladiesCount = parseInt(maladiesCount) - 1;

                }
            });

        });
        $('#listecultures,#gardePapiersChamps,#numeroCompteMM').hide();

        $('.autresCultures').change(function() {
            var autresCultures = $('.autresCultures').val();
            if (autresCultures == 'oui') {
                $('#listecultures').show('slow');
            } else {
                $('#listecultures').hide('slow');
                $('.listecultures').val('');
            }
        });

        $('.papiersChamps').change(function() {
            var papiersChamps = $('.papiersChamps').val();
            if (papiersChamps == 'oui') {
                $('#gardePapiersChamps').show('slow');
            } else {
                $('#gardePapiersChamps').hide('slow');
                $('.gardePapiersChamps').val('');
            }
        });

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
    </script>
    <script type="text/javascript">
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
    </script>
@endpush
