<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {

    //Staff Login
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login');
        Route::get('logout', 'logout')->name('logout');
    });
    //Staff Password Forgot
    Route::controller('ForgotPasswordController')->name('password.')->prefix('password')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });
    //Manager Password Rest
    Route::controller('ResetPasswordController')->name('password.')->prefix('password')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('reset.form');
        Route::post('password/reset/change', 'reset')->name('change');
    });
});


            //Home Controller
            Route::group(['middleware' => ['auth', 'permission']], function() {
    
            
            Route::controller('StaffController')->group(function () {
                Route::get('dashboard', 'dashboard')->name('dashboard');
                Route::get('password', 'password')->name('password');
                Route::get('profile', 'profile')->name('profile');
                Route::post('profile/update', 'profileUpdate')->name('profile.update.data');
                Route::post('password/update', 'passwordUpdate')->name('password.update.data');
                Route::post('ticket/delete/{id}', 'ticketDelete')->name('ticket.delete');

                //Manage Cooperative
                Route::name('cooperative.')->prefix('cooperative')->group(function () {
                    Route::get('list', 'cooperativeList')->name('index');
                    Route::get('income', 'cooperativeIncome')->name('income');
                });
            }); 


            Route::controller('LivraisonController')->name('livraison.')->prefix('livraison')->group(function () {
                Route::get('send', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::post('update/{id}', 'update')->name('update');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::get('invoice/{id}', 'invoice')->name('invoice');
                Route::get('delivery/list', 'delivery')->name('delivery.list');
                Route::get('details/{id}', 'details')->name('details');
                Route::post('payment', 'payment')->name('payment');
                Route::post('delivery/store', 'deliveryStore')->name('delivery');
                Route::get('list', 'livraisonList')->name('manage.list');
                Route::get('date/search', 'livraisonDateSearch')->name('date.search');
                Route::get('search', 'livraisonSearch')->name('search');
                Route::get('send/list', 'sentLivraisonList')->name('manage.sent.list');
                Route::get('received/list', 'receivedLivraisonList')->name('received.list');
                //New Route
                Route::get('sent/queue', 'sentQueue')->name('sent.queue');
                Route::post('dispatch-all/', 'livraisonAllDispatch')->name('dispatch.all'); 
                Route::post('status/{id}', 'dispatched')->name('dispatched');
                Route::get('upcoming', 'upcoming')->name('upcoming');
                Route::post('receive/{id}', 'receive')->name('receive');
                Route::get('delivery/queue', 'deliveryQueue')->name('delivery.queue');
                Route::get('delivery/list/total', 'delivered')->name('manage.delivered');
                Route::get('parcelle', 'getParcelle')->name('get.parcelle');

                Route::get('list', 'livraisonInfo')->name('index');
                            Route::get('dispatch/list', 'dispatchLivraison')->name('dispatch');
                            Route::get('sent-queue/list', 'sentInQueue')->name('sentQueue');
                            Route::get('delivered', 'delivered')->name('delivered'); 
                            Route::get('sent', 'sentLivraison')->name('sent');
                            Route::get('/exportLivraisonsExcel', 'exportExcel')->name('exportExcel.livraisonAll');
            });

            Route::controller('LivraisonController')->prefix('cashs')->group(function () {
                Route::get('collection', 'cash')->name('cash.livraison.income');
            });

            Route::controller('StaffTicketController')->prefix('ticket')->name('ticket.')->group(function () {
                Route::get('/', 'supportTicket')->name('index');
                Route::get('new', 'openSupportTicket')->name('open');
                Route::post('create', 'storeSupportTicket')->name('store');
                Route::get('view/{ticket}', 'viewTicket')->name('view');
                Route::post('reply/{ticket}', 'replyTicket')->name('reply');
                Route::post('close/{ticket}', 'closeTicket')->name('close');
                Route::get('download/{ticket}', 'ticketDownload')->name('download');
            });

                        //Manage Producteur
                        Route::controller('ProducteurController')->name('traca.producteur.')->prefix('producteur')->group(function () {
                            Route::get('list', 'index')->name('index');
                            Route::get('infos/{id}', 'infos')->name('infos');
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store');
                            Route::post('info/store', 'storeinfo')->name('storeinfo');
                            Route::get('infos/edit/{id}', 'editinfo')->name('editinfo');
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportProducteursExcel', 'exportExcel')->name('exportExcel.producteurAll');
                            Route::post('/uploadcontent', 'uploadContent')->name('uploadcontent');
                        });
            
                        //Manage Parcelle
                        Route::controller('ParcelleController')->name('traca.parcelle.')->prefix('parcelle')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportParcellesExcel', 'exportExcel')->name('exportExcel.parcelleAll');
                            Route::post('/uploadcontent', 'uploadContent')->name('uploadcontent');
                        });
            
                        //Manage Estimation
                        Route::controller('EstimationController')->name('traca.estimation.')->prefix('estimation')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportEstimationsExcel', 'exportExcel')->name('exportExcel.estimationAll');
                            Route::post('/uploadcontent', 'uploadContent')->name('uploadcontent');
                        });
            
                        //Manage Suivi Menage
                        Route::controller('MenageController')->name('suivi.menage.')->prefix('menage')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportMenagesExcel', 'exportExcel')->name('exportExcel.menageAll');
                        });
            
            
                         //Manage Suivi Parcelle
                        Route::controller('SuiviParcelleController')->name('suivi.parcelles.')->prefix('suivi/parcelles')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportSuiviParcellesExcel', 'exportExcel')->name('exportExcel.suiviParcelleAll');
                        });
            
                        //Manage Suivi Formation
                        Route::controller('FormationController')->name('suivi.formation.')->prefix('formation')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportFormationsExcel', 'exportExcel')->name('exportExcel.formationAll');
                        });
            
                        //Manage Suivi Inspection
                        Route::controller('InspectionController')->name('suivi.inspection.')->prefix('inspection')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportInspectionsExcel', 'exportExcel')->name('exportExcel.inspectionAll');
                        });
                        //Manage Suivi Application
                        Route::controller('ApplicationController')->name('suivi.application.')->prefix('application')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportApplicationsExcel', 'exportExcel')->name('exportExcel.applicationAll');
                        });
            
                        //Manage Suivi Ssrteclmrs
                        Route::controller('SsrteclmrsController')->name('suivi.ssrteclmrs.')->prefix('ssrteclmrs')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportSsrteclmrsExcel', 'exportExcel')->name('exportExcel.ssrteclmrsAll');
                        });
            
                        //Manage Agroapprovisionnements
                        Route::controller('AgroapprovisionnementController')->name('agro.approvisionnement.')->prefix('agro/approvisionnement')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportApprovisionnementExcel', 'exportExcel')->name('exportExcel.approvisionnementAll');
                        });
            
                        //Manage Agrodistributions
                        Route::controller('AgrodistributionController')->name('agro.distribution.')->prefix('agro/distribution')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::post('update', 'update')->name('update'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportDistributionsExcel', 'exportExcel')->name('exportExcel.distributionAll');
                            Route::post('/get/agroparcelles/arbres', 'getAgroParcellesArbres')->name('getAgroParcellesArbres');
                        });
            
                        //Manage Agroevaluations
                        Route::controller('AgroevaluationController')->name('agro.evaluation.')->prefix('agro/evaluation')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('destroy/{id}', 'destroy')->name('destroy'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportEvaluationsExcel', 'exportExcel')->name('exportExcel.evaluationsAll');
                        });
            
                        //Manage Agrodeforestations
                        Route::controller('AgrodeforestationController')->name('agro.deforestation.')->prefix('agro/deforestation')->group(function () {
                            Route::get('list', 'index')->name('index'); 
                            Route::get('create', 'create')->name('create');
                            Route::post('store', 'store')->name('store'); 
                            Route::get('edit/{id}', 'edit')->name('edit');
                            Route::post('status/{id}', 'status')->name('status');
                            Route::get('/exportDeforestationsExcel', 'exportExcel')->name('exportExcel.deforestationsAll');
                        });
            
                        //Manage Livraison
                      


        });


