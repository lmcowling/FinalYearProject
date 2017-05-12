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
        return view('welcome');
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
Route::get('leagues/searchLeagues', 'LeaguesController@searchLeagues'); // search for leagues.
Route::post('leagues/searchLeagues', 'LeaguesController@leagueSearchResults'); // search for leagues.
Route::get('leagues/new', 'LeaguesController@newLeague'); // create a new league.
Route::post('leagues/add', 'LeaguesController@addLeague'); // add a new league.
Route::get('leagues/{leagueID}', 'LeaguesController@getLeagueDetail'); // the details of a specific league.
Route::get('leagues/{leagueID}/delete', 'LeaguesController@deleteLeague'); // delete a specific league.
Route::post('leagues/{leagueID}/verify', 'LeaguesController@verifyLeague'); // verify league joining.
Route::get('leagues/{leagueID}/join', 'LeaguesController@joinLeague'); // join a specific league.
Route::get('leagues/{leagueID}/leave', 'LeaguesController@leaveLeague'); // leave a specific league.


Route::get('predictions', 'PredictionsController@predictionsList'); // get predictions list
Route::get('predictions/{gameWeekID}', 'PredictionsController@showPredictions'); // show predictions
Route::get('predictions/{gameWeekID}/add', 'PredictionsController@addPredictions'); // input predictions
Route::post('predictions/{gameWeekID}/insert', 'PredictionsController@insertPredictions'); // adding predictions
Route::get('predictions/{gameWeekID}/edit', 'PredictionsController@editPredictions'); // edit predictions
Route::post('predictions/{gameWeekID}/update', 'PredictionsController@updatePredictions'); // update predictions

Auth::routes();

Route::get('/home', 'HomeController@index');
