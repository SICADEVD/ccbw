<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ApiproducteurController; 
use App\Http\Controllers\ApimenageController; 
use App\Http\Controllers\ApilivraisonController; 
use App\Http\Controllers\ApiparcelleController; 
use App\Http\Controllers\ApisuiviparcelleController; 
use App\Http\Controllers\ApisuiviformationController; 
use App\Http\Controllers\ApilocaliteController; 
use App\Http\Controllers\ApigetlistedatasController; 
use App\Http\Controllers\ApiestimationController; 
use App\Http\Controllers\ApissrteclrmsController; 
use App\Http\Controllers\ApiapplicationController;
use App\Http\Controllers\ApievaluationController;
use App\Http\Controllers\ApiProgrammeController;
use App\Http\Controllers\ApisectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::match(['POST'],'getupdateapp', [AuthController::class, 'getUpdateapp']);
Route::match(['POST'],'getdomain', [AuthController::class, 'getdomain']);
Route::match(['POST'],'getdelegues', [AuthController::class, 'getdelegues']);
Route::match(['POST'],'getapplicateurs', [AuthController::class, 'getapplicateurs']);
Route::match(['POST'],'connexion', [AuthController::class, 'connexion']);

Route::match(['POST'],'apiproducteur', [ApiproducteurController::class, 'store']);
Route::match(['POST'],'getproducteurs', [ApiproducteurController::class, 'getproducteurs']);
Route::match(['POST'],'apiinfosproducteur', [ApiproducteurController::class, 'apiinfosproducteur']);
Route::match(['POST'],'getproducteurupdate', [ApiproducteurController::class, 'getproducteurUpdate']);
Route::match(['POST'],'getstaff', [ApiproducteurController::class, 'getstaff']);

Route::match(['POST'],'apimenage', [ApimenageController::class, 'store']);
Route::match(['POST'],'getmagasinsection', [ApilivraisonController::class, 'getMagasinsection']);
Route::match(['POST'],'apilivraison', [ApilivraisonController::class, 'store']);

Route::match(['POST'],'apiparcelle', [ApiparcelleController::class, 'store']);
Route::match(['POST'],'getparcelles', [ApiparcelleController::class, 'index']);
Route::match(['POST'],'getparcelleupdate', [ApiparcelleController::class, 'getparcelleUpdate']);

Route::match(['POST'],'apisuiviparcelle', [ApisuiviparcelleController::class, 'store']);
Route::match(['POST'],'apisuiviformation', [ApisuiviformationController::class, 'store']); 
Route::match(['POST'],'apivisiteur', [ApisuiviformationController::class, 'storeVisiteur']);
Route::match(['POST'],'apitypethemeformation', [ApisuiviformationController::class, 'getTypethemeformation']); 
Route::match(['POST'],'gettypeformation', [ApisuiviformationController::class, 'getTypeformation']); 
Route::match(['POST'],'getthemes', [ApisuiviformationController::class, 'getThemes']); 
Route::match(['POST'],'getlocalite', [ApilocaliteController::class, 'index']); 
Route::match(['POST'],'apilocalite', [ApilocaliteController::class, 'store']); 
Route::match(['POST'],'getlistedatas', [ApigetlistedatasController::class, 'index']);
Route::match(['POST'],'apiestimation', [ApiestimationController::class, 'store']); 
Route::match(['POST'],'apissrteclrms', [ApissrteclrmsController::class, 'store']); 
Route::match(['POST'],'apiniveauxclasse', [ApissrteclrmsController::class, 'getNiveauxclasse']); 
Route::match(['POST'],'apiapplication', [ApiapplicationController::class, 'store']); 
Route::match(['POST'],'apievaluation', [ApievaluationController::class, 'store']); 
Route::match(['POST'],'getquestionnaire', [ApievaluationController::class, 'getQuestionnaire']); 
Route::match(['POST'],'getnotation', [ApievaluationController::class, 'getNotation']); 
Route::match(['POST'],'getcampagne', [AuthController::class, 'getCampagne']);

//route pour la gestion des sections

Route::match(['POST'],'getsections',[ApisectionController::class, 'getsections']);

//route pour la gestion des programmes

Route::match(['POST'],'getprogrammes',[ApiProgrammeController::class, 'index']);


