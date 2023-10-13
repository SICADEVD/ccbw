@extends('manager.layouts.app')
@section('panel')
  
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Employee</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Employee</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_employee"><i class="fa fa-plus"></i> Add Employee</a>
                        <div class="view-icons">
                            <a href="{{ route('manager.hr.all.employee.card') }}" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                            <a href="{{ route('manager.hr.all.employee.list') }}" class="list-view btn btn-link"><i class="fa fa-bars"></i></a>
                        </div>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->{!! Toastr::message() !!}

            <!-- Search Filter -->
            <form action="{{ route('manager.hr.all.employee.list.search') }}" method="POST">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="employee_id">
                            <label class="focus-label">Employee ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating">
                            <label class="focus-label">Employee Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating">
                            <label class="focus-label">Position</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <button type="sumit" class="btn btn-success btn-block"> Search </button>  
                    </div>
                </div>
            </form>
            <!-- Search Filter -->
            {{-- message --}}
            

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Employee ID</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th class="text-nowrap">Join Date</th>
                                    <th>Role</th>
                                    <th class="text-right no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $items )
                                <tr>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="{{ url('employee/profile/'.$items->user_id) }}" class="avatar"><img alt="" src="{{ URL::to('/assets/images/'. $items->avatar) }}"></a>
                                            <a href="{{ url('employee/profile/'.$items->user_id) }}">{{ $items->name }}<span>{{ $items->position }}</span></a>
                                        </h2>
                                    </td>
                                    <td>{{ $items->user_id }}</td>
                                    <td>{{ $items->email }}</td>
                                    <td>{{ $items->phone_number }}</td>
                                    <td>{{ $items->join_date }}</td>
                                    <td>{{ $items->role_name }}</td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{ url('all/employee/view/edit/'.$items->user_id) }}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="{{url('all/employee/delete/'.$items->user_id)}}"onclick="return confirm('Are you sure to want to delete it?')"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
      
        <!-- Add Employee Modal -->
        <div id="add_employee" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <form action="{{ route('manager.hr.all.employee.save') }}" method="POST">
                           // @csrf
                     
                        </form> -->
                        <x-form id="save-data-form">
        <div class="add-client bg-white rounded">
            <div class="row p-20">
                <div class="col-lg-9">
                    <div class="row">
                    <div class="col-lg-6 col-md-6">
                                <x-forms.text fieldId="name" :fieldLabel="__('Nom Employe')"
                                    fieldName="name" fieldRequired="true" :fieldPlaceholder="__('e.g John Doe')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <x-forms.text fieldId="email" :fieldLabel="__('Email Employe')"
                                    fieldName="email" fieldRequired="true" :fieldPlaceholder="__('e.g johndoe@domain.com')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.datepicker fieldId="date_of_birth" :fieldLabel="__('Date de Naissance')"
                                    fieldName="date_of_birth" :fieldPlaceholder="__('Select Date')" />
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.label class="my-3" fieldId="category_id"
                                    :fieldLabel="__('Désignation')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="designation"
                                        id="employee_designation" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </x-forms.input-group>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.label class="my-3" fieldId="category_id"
                                    :fieldLabel="__('Département')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="department"
                                        id="employee_department" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->department }}</option>
                                        @endforeach
                                    </select>
                                </x-forms.input-group>
                            </div>
                            
                    </div>
                </div>

                <div class="col-lg-3">
                        <x-forms.file allowedFileExtensions="png jpg jpeg svg bmp" class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('modules.profile.profilePicture')" fieldName="image" fieldId="image"
                            fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="country" :fieldLabel="__('Pays')" fieldName="country"
                            search="true">
                            @foreach ($countries as $item)
                                <option data-tokens="{{ $item->iso3 }}" data-phonecode = "{{$item->phonecode}}"
                                    data-content="<span class='flag-icon flag-icon-{{ strtolower($item->iso) }} flag-icon-squared'></span> {{ $item->nicename }}"
                                    value="{{ $item->id }}">{{ $item->nicename }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>

                    <div class="col-lg-5 col-md-6">
                        <x-forms.label class="my-3" fieldId="mobile"
                            :fieldLabel="__('Mobile')"></x-forms.label>
                        <x-forms.input-group style="margin-top:-4px">


                            <x-forms.select fieldId="country_phonecode" fieldName="country_phonecode"
                                search="true" style="min-width: 100px;">

                                @foreach ($countries as $item)
                                    <option data-tokens="{{ $item->name }}"
                                            data-content="{{$item->flagSpanCountryCode()}}"
                                            value="{{ $item->phonecode }}">{{ $item->phonecode }}
                                    </option>
                                @endforeach
                            </x-forms.select>

                            <input type="tel" class="form-control height-35 f-14" placeholder="@lang('Contact')"
                                name="mobile" id="mobile">
                        </x-forms.input-group>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="gender" :fieldLabel="__('Genre')"
                            fieldName="gender">
                            <option value="homme">Homme</option>
                            <option value="femme">Femme</option> 
                        </x-forms.select>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <x-forms.datepicker fieldId="joining_date" :fieldLabel="__('Date Entree')"
                            fieldName="joining_date" :fieldPlaceholder="__('Select Date')" fieldRequired="true" />
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <x-forms.select fieldId="reporting_to" :fieldLabel="__('Superieur(e) Hierachique')"
                            fieldName="reporting_to" :fieldPlaceholder="__('placeholders.date')" search="true">
                            <option value="">--</option>
                            @foreach ($users as $item)
                                <x-user-option :user="$item" />
                            @endforeach
                        </x-forms.select>
                    </div>     
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('Adresse')"
                                fieldName="address" fieldId="address" :fieldPlaceholder="__('e.g. 132, My Street, Kingston, New York 12401')">
                            </x-forms.textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('A propos')"
                                fieldName="about_me" fieldId="about_me" fieldPlaceholder="">
                            </x-forms.textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="employment_type" :fieldLabel="__('Type de Contrat')"
                            fieldName="employment_type" :fieldPlaceholder="__('Selectionner')">
                            <option value="">--</option>
                            <option value="plein_temps">Plein Temps</option>
                            <option value="temps_partiel">Temps Partiel</option>
                            <option value="contractuel">Contractuel</option>
                            <option value="interimaire">Interimaire</option>
                            <option value="stagiaire">Stagiaire</option>
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6 d-none internship-date"> 
                    <x-forms.datepicker fieldId="internship_end_date" :fieldLabel="__('Date fin Interim')"
                            fieldName="internship_end_date" :fieldPlaceholder="__('Select Date')"/>
                    </div>
                    <div class="col-lg-3 col-md-6 d-none contract-date">
                        <x-forms.datepicker fieldId="contract_end_date" :fieldLabel="__('Date fin de contrat')"
                            fieldName="contract_end_date" :fieldPlaceholder="__('Select Date')"/>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="marital_status" :fieldLabel="__('Statut matrimonial')"
                            fieldName="marital_status" :fieldPlaceholder="__('Selectionner')">
                            <option value="">--</option>
                            <option value="celibataire">Celibataire</option>
                            <option value="marie">Marié</option>
                        </x-forms.select>
                    </div>

                    <div class="col-lg-3 col-md-6 d-none marriage_date">
                        <x-forms.datepicker fieldId="marriage_anniversary_date" :fieldLabel="__('Date de mariage')"
                            fieldName="marriage_anniversary_date" :fieldPlaceholder="__('Selectionner')"/>
                    </div>
 
            </div>
            <x-form-actions>
            <x-forms.button-primary id="save-employee-form" class="mr-3" icon="check">
                        @lang('Enregistrer')
                    </x-forms.button-primary>
                     
                    <x-forms.button-cancel class="border-0 " data-dismiss="modal">@lang('Annuler')
                    </x-forms.button-cancel>

                </x-form-actions>
        </div>
        </x-form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Employee Modal -->
    </div>
    <!-- /Page Wrapper -->
    
@endsection 
@push('script')
    <script>
        $("input:checkbox").on('click', function()
        {
            var $box = $(this);
            if ($box.is(":checked"))
            {
                var group = "input:checkbox[class='" + $box.attr("class") + "']";
                $(group).prop("checked", false);
                $box.prop("checked", true);
            }
            else
            {
                $box.prop("checked", false);
            }
        });
        $('#country').change(function(){
            var phonecode = $(this).find(':selected').data('phonecode');
            console.log(phonecode);
            $('#country_phonecode').val(phonecode);
            $('.select-picker').selectpicker('refresh'); 
        }); 
        // select auto id and email
        $('#name').on('change',function()
        {
            $('#employee_id').val($(this).find(':selected').data('employee_id'));
            $('#email').val($(this).find(':selected').data('email'));
        });
        $('#marital_status').change(function(){
            var value = $(this).val();
            if(value == 'marie') {
                $('.marriage_date').removeClass('d-none');
            }
            else {
                $('.marriage_date').addClass('d-none');
            }
        });
        $('#employment_type').change(function(){
            var value = $(this).val();
            if(value == 'contractuel') {
                $('.contract-date').removeClass('d-none');
            }
            else {
                $('.contract-date').addClass('d-none');
            }

            if(value == 'interimaire') {
                $('.internship-date').removeClass('d-none');
            }
            else {
                $('.internship-date').addClass('d-none');
            }
        });
    //     $('#date_of_birth,#marriage_anniversary_date,#contract_end_date,#internship_end_date').datepicker({    
    //  format: 'dd-mm-yyyy'});  
    datepicker('#date_of_birth', {
            position: 'bl',
            dateFormat: 'dd-mm-yyyy',
            maxDate: new Date(), 
        });
        datepicker('#marriage_anniversary_date', {
            position: 'bl',
            maxDate: new Date(), 
        });
        datepicker('#contract_end_date', {
            position: 'bl',
            maxDate: new Date(), 
        });
        datepicker('#internship_end_date', {
            position: 'bl',
            maxDate: new Date(), 
        });
        datepicker('#joining_date', {
            position: 'bl',
            maxDate: new Date(), 
        });
    </script>
    @endpush