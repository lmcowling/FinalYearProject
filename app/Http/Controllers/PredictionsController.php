<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Predictions;
use Validator;
use DateTime;

class PredictionsController extends Controller
{
  public function predictionsList()
  {
    $resultGW = DB::table('fixtures')
                  ->select('gameWeekID')
                  ->whereRaw('fixtureDate >= CURDATE()')
                  ->orderby('fixtureDate', 'asc')
                  ->get()
                  ->first();

    $predictionCount = DB::table('predictions')
                          ->whereRaw('fixtureDate >= CURDATE() AND userID = 1 AND gameWeekID = '.$resultGW->gameWeekID.'')
                          ->get()
                          ->count();

    if($predictionCount > 0)
    {
      return $this->editPredictions($resultGW->gameWeekID);
    }
    else
    {
      return $this->addPredictions($resultGW->gameWeekID);
    }
  }

  public function showPredictions($gameWeekID)
  {
    $predictionCount = DB::table('predictions')
                          ->whereRaw('fixtureDate >= CURDATE() AND userID = 1 AND gameWeekID = '.$gameWeekID.'')
                          ->get()
                          ->count();

    if($predictionCount > 0)
    {
      return $this->editPredictions($gameWeekID);
    }
    else
    {
      return $this->addPredictions($gameWeekID);
    }
  }

    public function addPredictions($gameWeekID)
    {

      $fixtures = DB::table('fixtures AS f')
                    ->join('teamsTable AS tth', 'f.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'f.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'f.fixtureDate AS fixDate', 'f.homeTeamID AS homeTeamID', 'f.awayTeamID AS awayTeamID')
                    ->where('gameWeekID',$gameWeekID) // add userID
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      $date_now = date("Y-m-d H:i:s");

      $results = DB::table('resultsTable AS r')
                    ->join('teamsTable AS tth', 'r.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'r.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'r.fixtureDate AS fixDate', 'r.homeTeamID AS homeTeamID', 'r.awayTeamID AS awayTeamID')
                    ->where('gameWeekID',$gameWeekID) // add userID
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      return view('predictions/addPredictions',compact('fixtures', 'gameWeekID', 'date_now', 'results'));
    }

    public function insertPredictions(Request $request)
    {
      $homeTeamID = request('homeTeamID');
      $homeScore = request('homeScore');
      $awayScore = request('awayScore');
      $awayTeamID = request('awayTeamID');
      $userID = request('userID');
      $fixtureDate = request('fixtureDate');
      $gameWeekID = request('gameWeekID');

      foreach($homeTeamID as $key => $gw )
      {
        $arrData[] = array("homeTeamID"=>$homeTeamID[$key], "homeScore"=>$homeScore[$key],
                          "awayScore"=>$awayScore[$key], "awayTeamID"=>$awayTeamID[$key],
                          "userID"=>$userID[$key], "gameWeekID"=>$gameWeekID[$key],
                          "fixtureDate"=>$fixtureDate[$key]);
      }
      Predictions::insert($arrData);
      // DB::table('predictions')->insert($data); // Query Builder
      return redirect('/predictions');
    }

    public function editPredictions($gameWeekID)
    {
      $predictions = DB::table('predictions AS p')
                    ->join('teamsTable AS tth', 'p.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'p.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'p.fixtureDate AS fixDate', 'p.homeTeamID AS homeTeamID',
                              'p.awayTeamID AS awayTeamID', 'p.homeScore AS homeScore', 'p.awayScore AS awayScore')
                    ->where('gameWeekID',$gameWeekID)
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      return view('predictions/editPredictions',compact('predictions', 'gameWeekID'));
    }

    public function updatePredictions(Request $request) // test
    {
      $homeTeamID = request('homeTeamID');
      $homeScore = request('homeScore');
      $awayScore = request('awayScore');
      $awayTeamID = request('awayTeamID');
      $userID = request('userID');
      $fixtureDate = request('fixtureDate');
      $gameWeekID = request('gameWeekID');

      $users = DB::table('predictions')->where([['gameWeekID', '=', $gameWeekID],['userID', '=', $userID],])->delete();

      foreach($homeTeamID as $key => $gw )
      {
        $arrData[] = array("homeTeamID"=>$homeTeamID[$key], "homeScore"=>$homeScore[$key],
                          "awayScore"=>$awayScore[$key], "awayTeamID"=>$awayTeamID[$key],
                          "userID"=>$userID[$key], "gameWeekID"=>$gameWeekID[$key],
                          "fixtureDate"=>$fixtureDate[$key]);
      }
      Predictions::insert($arrData);
      // DB::table('predictions')->insert($data); // Query Builder
      return redirect('/predictions');
    }

}
//
// '2017-12-31 23:59:59'
// INSERT INTO resultsTable(homeTeamID,awayTeamID,gameWeekID,homeScore,awayScore,fixtureDate)
// VALUES(2,12,34,4,0,'2017-04-22 15:00:00'),
// (7,18,34,2,0,'2017-04-22 15:00:00'),
// (16,15,34,2,0,'2017-04-22 15:00:00'),
// (20,6,34,0,0,'2017-04-22 15:00:00'),
// (3,11,34,0,2,'2017-04-23 14:15:00'),
// (9,5,34,1,2,'2017-04-23 16:30:00'),
// (4,13,34,4,2,'2017-04-25 19:45:00'),
// (1,8,34,1,0,'2017-04-26 19:45:00'),
// (12,15,34,1,0,'2017-04-26 19:45:00'),
// (5,17,34,0,1,'2017-04-26 20:00:00'),
// (10,11,34,0,0,'2017-04-27 20:00:00');
