@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('manager.staff.store') }}" method="POST">
                        @csrf
                          
                        <input type="hidden" name="id" value="{{ $staff->id }}">
                        <div class="row">
                        <div class="form-group col-lg-4">
                                <label>@lang('Selectionner une Localite')</label>
                                <select class="form-control select2-multi-select" name="localite[]" multiple required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($localites as $localite)
                                        <option value="{{ $localite->id }}" @selected(in_array($localite->id,$userLocalite))>
                                            {{ __($localite->nom) }}</option>
                                    @endforeach
                                </select>
                            </div>  
                            <div class="form-group col-lg-4">
                                <label>@lang('Role')</label>
                                <select class="form-control" name="role" required> 
                                        <option value="adg" @selected('adg'==$staff->user_type)>ADG</option> 
                                        <option value="applicateur" @selected('applicateur'==$staff->user_type)>Applicateur</option> 
                                        <option value="coach" @selected('coach'==$staff->user_type)>Coach</option> 
                                        <option value="delegue" @selected('delegue'==$staff->user_type)>Delegue</option> 
                                        <option value="magasinier" @selected('magasinier'==$staff->user_type)>Magasinier</option> 
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang('Type de compte')</label>
                                <select class="form-control" name="type_compte" required> 
                                        <option value="web" @selected('web'==$staff->type_compte)>Web</option> 
                                        <option value="mobile" @selected('mobile'==$staff->type_compte)>Mobile</option> 
                                        <option value="mobile-web" @selected('mobile-web'==$staff->type_compte)>Mobile & Web</option>  
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>@lang('Prenom(s)')</label>
                                <input type="text" class="form-control" name="firstname"
                                    value="{{ __($staff->firstname) }}" required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang('Nom de famille')</label>
                                <input type="text" class="form-control" value="{{ __($staff->lastname) }}"
                                    name="lastname" required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang("Nom d'utilisateur")</label>
                                <input type="text" class="form-control" name="username"
                                    value="{{ __($staff->username) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>@lang('Email Adresse')</label>
                                <input type="email" class="form-control" name="email" value="{{ $staff->email }}"
                                    required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang('Contact')</label>
                                <input type="text" class="form-control" name="mobile" value="{{ $staff->mobile }}"
                                    required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang('Adresse')</label>
                                <input type="text" class="form-control" name="adresse" value="{{ $staff->adresse }}"
                                    >
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label for="rolePermission" class="form-label">Role</label>
                                <select class="form-control" 
                                    name="role" required>
                                    <option value="">Selectionner un rôle</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ in_array($role->name, $userRole) 
                                                ? 'selected'
                                                : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang("Mot de passe")</label>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Confirm Password')</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45"> @lang('Envoyer')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.staff.index') }}"/>
@endpush
