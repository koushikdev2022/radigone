<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\InfoController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\User\SurveyController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\TicketController;

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

Route::group(['middleware' => ['cors', 'json.response'], 'as' => 'api::'], function () {
    Route::prefix('v1')->group(function () {
        Route::get('info', [InfoController::class, 'info']);
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('user/login', [AuthController::class, 'loginUser']);
        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('all-ad-preferences', [UserController::class, 'allAdPreferences']);
            Route::prefix('user')->group(function() {
                Route::get('profile-info', [AuthController::class, 'userProfileInfo']);
                Route::post('profile-update', [AuthController::class, 'userProfileUpdate']);
                Route::post('change-password', [AuthController::class, 'userChangePassword']);
                Route::get('surveys', [SurveyController::class, 'surveyAvailable']);
                Route::get('survey/ad-view/{id}', [SurveyController::class, 'surveyAdView']);
                Route::get('survey/ad/questions/{id}', [SurveyController::class, 'surveyQuestions']);
                Route::post('survey/ad/answer/{id}', [SurveyController::class, 'surveyQuestionsAnswers']);
                Route::get('withdraw/history', [UserController::class, 'withdrawLog']);
                Route::get('transaction/history', [UserController::class, 'transactionLog']);
                Route::get('ad-preference/list', [UserController::class, 'adPreferenceList']);
                Route::post('ad-preference/update', [UserController::class, 'adPreferenceUpdate']);
                Route::post('support-ticket/store', [TicketController::class, 'storeSupportTicket']);
                Route::get('support-tickets', [TicketController::class, 'supportTicket']);
            });
        });
    });
});


