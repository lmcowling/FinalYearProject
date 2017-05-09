<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function (){
    return "Error 404. Page doesn't exist";
    //     return view('welcome');
});

// Route::get('home', function (){
//     return view('welcome');
// });

Route::get('about', function () {
    return view('about');
});

Route::get('tables', 'TablesController@getTables'); // list of tables.
Route::get('tables/prem', 'TablesController@getPremTable'); // the actual table.
Route::get('tables/user', 'TablesController@getUserTable'); // the table made from the users predictions.

Route::get('leagues', 'LeaguesController@getLeagues'); // list of leagues.
Route::get('leagues/myLeagues', 'LeaguesController@getMyLeagues'); // the users leagues there are in.
Route::get('leagues/myLeagues/{leagueID}', 'LeaguesController@getLeagueDetail'); // the details of a specific league.
Route::get('leagues/search', 'LeaguesController@searchLeagues'); // search for leagues.
Route::get('leagues/new', 'LeaguesController@newLeague'); // create a new league.

Route::get('predictions', 'PredictionsController@predictionsList'); // input predictions
Route::get('predictions/{gameWeekID}', 'PredictionsController@showPredictions'); // input predictions
Route::get('predictions/{gameWeekID}/add', 'PredictionsController@addPredictions'); // input predictions
Route::post('predictions/{gameWeekID}/insert', 'PredictionsController@insertPredictions'); // adding predictions
Route::get('predictions/{gameWeekID}/edit', 'PredictionsController@editPredictions'); // edit predictions
Route::post('predictions/{gameWeekID}/update', 'PredictionsController@updatePredictions'); // update predictions

Auth::routes();

Route::get('/home', 'HomeController@index');
