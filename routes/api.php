<?php

use Illuminate\Support\Facades\Route;
use Infinety\TemplyPages\Http\Controllers\PageAdvancedController;
use Infinety\TemplyPages\Http\Controllers\PageConfigurationsController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
 */

Route::get('pages', PageAdvancedController::class.'@allPages');
Route::get('fields', PageAdvancedController::class.'@fields');
Route::post('actions/save-fields', PageAdvancedController::class.'@saveFields');
Route::get('page/template-fields', PageAdvancedController::class.'@pageTemplateFields');
Route::get('page/template-fields-values', PageAdvancedController::class.'@pageTemplateFieldsValues');
Route::get('page-info/{id}', PageAdvancedController::class.'@pageInfo');
Route::get('go-to/{id}', PageAdvancedController::class.'@gotoPage');
Route::delete('page/{id}/destroy', PageAdvancedController::class.'@destroy');
Route::get('configurations', PageConfigurationsController::class.'@getConfigurations');
Route::post('page/set-design', PageAdvancedController::class.'@setDesign');
Route::get('templates-type/{template}', PageAdvancedController::class.'@getPageTemplatesTypes');
