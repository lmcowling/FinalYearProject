<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Predictions;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use DateTime;

class PredictionsController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

  public function predictionsList()
  {
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    $resultGW = DB::table('fixtures')
                  ->select('gameWeekID')
                  ->whereRaw('fixtureDate >= CURDATE()')
                  ->orderby('fixtureDate', 'asc')
                  ->get()
                  ->first();

    $predictionCount = DB::table('predictions')
                          ->whereRaw('fixtureDate >= CURDATE() AND userID = '.$userID.' AND gameWeekID = '.$resultGW->gameWeekID.'')
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
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    $results = DB::table('resultsTable AS r')
                  ->join('teamsTable AS tth', 'r.homeTeamID', '=', 'tth.teamID')
                  ->join('teamsTable AS tta', 'r.awayTeamID', '=', 'tta.teamID')
                  ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'r.fixtureDate AS fixDate', 'r.homeTeamID AS homeTeamID', 'r.awayTeamID AS awayTeamID')
                  ->where('gameWeekID',$gameWeekID)
                  ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                  ->get();
    if(count($results)>0)
    {
      return $this->showPredictionResults($gameWeekID);
    }
    else
    {
      $predictionCount = DB::table('predictions')
                            ->whereRaw('fixtureDate >= CURDATE() AND userID = '.$userID.' AND gameWeekID = '.$gameWeekID.'')
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
  }

    public function addPredictions($gameWeekID)
    {
      // Get the currently authenticated user...
      $user = Auth::user();
      // Get the currently authenticated user's ID...
      $userID = Auth::id();

      $fixtures = DB::table('fixtures AS f')
                    ->join('teamsTable AS tth', 'f.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'f.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'f.fixtureDate AS fixDate', 'f.homeTeamID AS homeTeamID', 'f.awayTeamID AS awayTeamID', 'f.fixtureID AS fixtureID')
                    ->where('gameWeekID',$gameWeekID)
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      $date_now = date("Y-m-d H:i:s");

      $results = DB::table('resultsTable AS r')
                    ->join('teamsTable AS tth', 'r.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'r.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'r.fixtureDate AS fixDate', 'r.homeTeamID AS homeTeamID', 'r.awayTeamID AS awayTeamID', 'r.fixtureID AS fixtureID')
                    ->where('gameWeekID',$gameWeekID)
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      return view('predictions/addPredictions',compact('fixtures', 'gameWeekID', 'date_now', 'results', 'userID'));
    }

    public function insertPredictions(Request $request)
    {
      $homeTeamID = request('homeTeamID');
      $homeScore = request('homeScore');
      $awayScore = request('awayScore');
      $awayTeamID = request('awayTeamID');
      $userID = request('userID');
      $fixtureDate = request('fixtureDate');
      $fixtureID = request('fixtureID');
      $gameWeekID = request('gameWeekID');

      foreach($homeTeamID as $key => $gw )
      {
        $arrData[] = array("homeTeamID"=>$homeTeamID[$key], "homeScore"=>$homeScore[$key],
                          "awayScore"=>$awayScore[$key], "awayTeamID"=>$awayTeamID[$key],
                          "userID"=>$userID[$key], "gameWeekID"=>$gameWeekID[$key],
                          "fixtureDate"=>$fixtureDate[$key],"fixtureID"=>$fixtureID[$key]);
      }
      Predictions::insert($arrData);
      // DB::table('predictions')->insert($data); // Query Builder
      return redirect('/predictions');
    }

    public function editPredictions($gameWeekID)
    {
      // Get the currently authenticated user...
      $user = Auth::user();
      // Get the currently authenticated user's ID...
      $userID = Auth::id();

      $results = DB::table('resultsTable AS r')
                    ->join('teamsTable AS tth', 'r.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'r.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'r.fixtureDate AS fixDate', 'r.homeTeamID AS homeTeamID', 'r.awayTeamID AS awayTeamID', 'r.fixtureID AS fixtureID')
                    ->where('gameWeekID',$gameWeekID)
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      $predictions = DB::table('predictions AS p')
                    ->join('teamsTable AS tth', 'p.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'p.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'p.fixtureDate AS fixDate', 'p.homeTeamID AS homeTeamID',
                              'p.awayTeamID AS awayTeamID', 'p.homeScore AS homeScore', 'p.awayScore AS awayScore', 'p.fixtureID AS fixtureID')
                    ->where([['gameWeekID', '=', $gameWeekID],['userID', '=', $userID]])
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      $date_now = date("Y-m-d H:i:s");

      return view('predictions/editPredictions',compact('predictions', 'gameWeekID', 'date_now', 'results', 'userID'));
    }

    public function updatePredictions(Request $request)
    {
      $homeTeamID = request('homeTeamID');
      $homeScore = request('homeScore');
      $awayScore = request('awayScore');
      $awayTeamID = request('awayTeamID');
      $userID = request('userID');
      $fixtureDate = request('fixtureDate');
      $fixtureID = request('fixtureID');
      $gameWeekID = request('gameWeekID');

      $userIDF = array_first($userID);
      $gameWeekIDF = array_first($gameWeekID);

      DB::table('predictions')->where([['gameWeekID', '=', $gameWeekIDF],['userID', '=', $userIDF],])->delete();

      foreach($homeTeamID as $key => $gw )
      {
        $arrData[] = array("homeTeamID"=>$homeTeamID[$key], "homeScore"=>$homeScore[$key],
                          "awayScore"=>$awayScore[$key], "awayTeamID"=>$awayTeamID[$key],
                          "userID"=>$userID[$key], "gameWeekID"=>$gameWeekID[$key],
                          "fixtureDate"=>$fixtureDate[$key],"fixtureID"=>$fixtureID[$key]);
      }
      Predictions::insert($arrData);
      // DB::table('predictions')->insert($data); // Query Builder
      return redirect('/predictions');
    }

    public function showPredictionResults($gameWeekID)
    {
      // Get the currently authenticated user...
      $user = Auth::user();
      // Get the currently authenticated user's ID...
      $userID = Auth::id();

      $results = DB::table('resultsTable AS r')
                    ->join('teamsTable AS tth', 'r.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'r.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'r.fixtureDate AS fixDate', 'r.homeTeamID AS homeTeamID',
                    'r.awayTeamID AS awayTeamID', 'r.homeScore AS homeScore', 'r.awayScore AS awayScore')
                    ->where('gameWeekID',$gameWeekID)
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      $predictions = DB::table('predictions AS p')
                    ->join('teamsTable AS tth', 'p.homeTeamID', '=', 'tth.teamID')
                    ->join('teamsTable AS tta', 'p.awayTeamID', '=', 'tta.teamID')
                    ->select('tth.teamName AS homeTeamName', 'tta.teamName AS awayTeamName', 'p.fixtureDate AS fixDate', 'p.homeTeamID AS homeTeamID',
                              'p.awayTeamID AS awayTeamID', 'p.homeScore AS homeScore', 'p.awayScore AS awayScore')
                    ->where([['gameWeekID', '=', $gameWeekID],['userID', '=', $userID]])
                    ->orderby('fixtureDate', 'asc', 'homeTeamName', 'asc')
                    ->get();

      //correct score
      $pointsCS = DB::table('predictions AS p')
                    ->leftjoin('resultsTable AS r', 'p.fixtureID', '=', 'r.fixtureID')
                    ->select('p.userID', DB::raw('SUM(CASE WHEN r.homeScore = p.homeScore AND r.awayScore = p.awayScore THEN 3 ELSE 0 END) AS totalPoints'))
                    ->where([['p.gameWeekID', '=', $gameWeekID],['p.userID', '=', $userID]])
                    ->groupBy('p.userID')
                    ->first();


      //home win
      $pointsHW = DB::table('predictions AS p')
                    ->leftjoin('resultsTable AS r', 'p.fixtureID', '=', 'r.fixtureID')
                    ->select('p.userID', DB::raw('SUM(CASE WHEN (r.homeScore > r.awayScore AND p.homeScore > p.awayScore) AND
                    (r.homeScore != p.homeScore OR r.awayScore != p.awayScore) THEN 1 ELSE 0 END) AS totalPoints'))
                    ->where([['p.gameWeekID', '=', $gameWeekID],['p.userID', '=', $userID]])
                    ->groupBy('p.userID')
                    ->first();

      //away win
      $pointsAW = DB::table('predictions AS p')
                    ->leftjoin('resultsTable AS r', 'p.fixtureID', '=', 'r.fixtureID')
                    ->select('p.userID', DB::raw('SUM(CASE WHEN (r.homeScore < r.awayScore AND p.homeScore < p.awayScore) AND
                    (r.homeScore != p.homeScore OR r.awayScore != p.awayScore) THEN 1 ELSE 0 END) AS totalPoints'))
                    ->where([['p.gameWeekID', '=', $gameWeekID],['p.userID', '=', $userID]])
                    ->groupBy('p.userID')
                    ->first();

      //draw
      $pointsDD = DB::table('predictions AS p')
                    ->leftjoin('resultsTable AS r', 'p.fixtureID', '=', 'r.fixtureID')
                    ->select('p.userID', DB::raw('SUM(CASE WHEN (r.homeScore = r.awayScore AND p.homeScore = p.awayScore) AND
                    (r.homeScore != p.homeScore OR r.awayScore != p.awayScore) THEN 1 ELSE 0 END) AS totalPoints'))
                    ->where([['p.gameWeekID', '=', $gameWeekID],['p.userID', '=', $userID]])
                    ->groupBy('p.userID')
                    ->first();

      $pointCount = 0;

      if(count($pointsCS)<1)
      {
        // $pointsCS = 0;
        $pointCount = 1;
      }
      if(count($pointsHW)<1)
      {
        // $pointsHW = 0;
        $pointCount = 1;
      }
      if(count($pointsAW)<1)
      {
        // $pointsAW = 0;
        $pointCount = 1;
      }
      if(count($pointsDD)<1)
      {
        // $pointsDD = 0;
        $pointCount = 1;
      }

      if($pointCount>0)
      {
        $points = 0;
      }
      else
      {
        $points = $pointsCS->totalPoints+$pointsHW->totalPoints+$pointsAW->totalPoints+$pointsDD->totalPoints;
      }

      $date_now = date("Y-m-d H:i:s");

      return view('predictions/showPredictionResults',compact('predictions', 'gameWeekID', 'date_now', 'results', 'userID', 'points'));
    }

}
//
// '2017-12-31 23:59:59'
// INSERT INTO predictions(homeTeamID,awayTeamID,gameWeekID,homeScore,awayScore,fixtureDate,userID,fixtureID)
// VALUES(2,12,34,3,0,'2017-04-22 15:00:00',2,47),
// (7,18,34,3,1,'2017-04-22 15:00:00',2,48),
// (16,15,34,3,1,'2017-04-22 15:00:00',2,49),
// (20,6,34,0,1,'2017-04-22 15:00:00',2,50),
// (3,11,34,2,2,'2017-04-23 14:15:00',2,51),
// (9,5,34,1,0,'2017-04-23 16:30:00',2,52),
// (4,13,34,3,2,'2017-04-25 19:45:00',2,53),
// (1,8,34,1,1,'2017-04-26 19:45:00',2,54),
// (12,15,34,0,0,'2017-04-26 19:45:00',2,55),
// (5,17,34,0,2,'2017-04-26 20:00:00',2,56),
// (10,11,34,1,1,'2017-04-27 20:00:00',2,57);



// INSERT INTO resultsTable(homeTeamID,awayTeamID,gameWeekID,homeScore,awayScore,fixtureDate,fixtureID)
// VALUES(2,12,34,2,0,'2017-04-22 15:00:00',47),
// (7,18,34,2,1,'2017-04-22 15:00:00',48),
// (16,15,34,3,0,'2017-04-22 15:00:00',49),
// (20,6,34,0,1,'2017-04-22 15:00:00',50),
// (3,11,34,2,2,'2017-04-23 14:15:00',51),
// (9,5,34,1,0,'2017-04-23 16:30:00',52),
// (4,13,34,3,2,'2017-04-25 19:45:00',53),
// (1,8,34,1,1,'2017-04-26 19:45:00',54),
// (12,15,34,0,0,'2017-04-26 19:45:00',55),
// (5,17,34,0,2,'2017-04-26 20:00:00',56),
// (10,11,34,1,1,'2017-04-27 20:00:00',57);
