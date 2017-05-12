<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Leagues;
use App\UsersLeagues;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use DateTime;

class LeaguesController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

  public function getLeagues()
  {
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    // status=0=Private;
    // status=1=Public;

    $leaguesPrivate = DB::table('leagues AS l')
    ->join('usersLeagues AS ul', 'l.leagueID', '=', 'ul.leagueID')
    ->select('l.leagueID AS leagueID', 'l.leagueName AS leagueName')
    ->where([['l.status', '=', 0],['ul.userID', '=', $userID]])
    ->get();

    if(count($leaguesPrivate))
    {

    }
    else
    {
      $leaguesPrivate = "NOTHING";
    }

    $leaguesPublic = DB::table('leagues AS l')
    ->join('usersLeagues AS ul', 'l.leagueID', '=', 'ul.leagueID')
    ->select('l.leagueID AS leagueID', 'l.leagueName AS leagueName')
    ->where([['l.status', '=', 1],['ul.userID', '=', $userID]])
    ->get();
    if(count($leaguesPublic))
    {

    }
    else
    {
      $leaguesPublic = "NOTHING";
    }
    return view('leagues.leagueHome', compact('leaguesPrivate', 'leaguesPublic'));
  }

  public function getLeagueDetail($leagueID)
  {
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    $leagueDetail = DB::table('leagues AS l')
    ->join('users AS u', 'l.leagueAdmin', '=', 'u.id')
    ->select('u.name AS userName', 'l.leagueName AS leagueName', 'l.leagueAdmin AS leagueAdmin',
              'l.leagueID AS leagueID', 'l.status AS status', 'l.leagueCode AS leagueCode')
    ->where('l.leagueID', '=', $leagueID)
    ->get()
    ->first();

    $leagueCheck = DB::table('usersLeagues')
    ->where([['leagueID', '=', $leagueID], ['userID', '=', $userID]])
    ->get()
    ->count();

    $leagueTable = DB::table('leagues AS l')
    ->join('usersLeagues AS ul', 'l.leagueID', '=', 'ul.leagueID')
    ->join('users AS u', 'ul.userID', '=', 'u.id')
    ->select('u.name AS userName', 'l.leagueID')
    ->where('l.leagueID', '=', $leagueID)
    ->get();

    return view('leagues.leagueDetail', compact('leagueDetail', 'leagueTable', 'userID', 'leagueCheck'));
  }

  public function leagueSearchResults(Request $request)
  {
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    $leagueName = request('leagueName');

    $results = DB::table('leagues AS l')
    ->join('usersLeagues AS ul', 'l.leagueID', '=', 'ul.leagueID')
    ->select('l.leagueID', 'l.leagueName', 'l.leagueCode')
    ->where([['l.leagueName', 'LIKE', '%'.$leagueName.'%'],['ul.userID', '!=', $userID]])
    ->get();

    return view('leagues.leagueList', compact('results'));
  }

  public function newLeague()
  {
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    $leagueCodeUnique="";
    $continue = true;
    while($continue)
    {
      $leagueCode = "";
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < 6; $i++)
      {
        $leagueCode .= $characters[rand(0, $charactersLength - 1)];
      }
      $leagueCodeCheck = Leagues::where('leagueCode','=',$leagueCode)->count();
      if($leagueCodeCheck == 0)
      {
        $leagueCodeUnique = $leagueCode;
        $continue = false;
      }
    }
    return view('leagues.createLeague', compact('userID', 'leagueCodeUnique'));
  }

  public function addLeague(Request $request)
  {
    $leagueName = request('leagueName');
    $leagueCode = request('leagueCode');
    $status = request('status');
    $leagueAdmin = request('leagueAdmin');

    $arrData[] = array("leagueName"=>$leagueName, "leagueCode"=>$leagueCode,"status"=>$status, "leagueAdmin"=>$leagueAdmin);

    Leagues::insert($arrData);

    $league = DB::table('leagues')->select('leagueID')
    ->where([['status', '=', $status],['leagueAdmin', '=', $leagueAdmin],['leagueName', '=', $leagueName],['leagueCode', '=', $leagueCode]])
    ->get()->first();
    DB::table('usersLeagues')->insert(['leagueID' => $league->leagueID, 'userID' => $leagueAdmin]);

    return redirect('leagues');

  }

  public function deleteLeague($leagueID)
  {
    DB::table('leagues')->where('leagueID', '=', $leagueID)->delete();
    DB::table('usersLeagues')->where('leagueID', '=', $leagueID)->delete();

    return redirect('leagues');
  }

  public function verifyLeague(Request $request)
  {
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    $leagueID = request('leagueID');
    $realLeagueCode = request('realLeagueCode');
    $inputLeagueCode = request('inputLeagueCode');

    if($realLeagueCode == $inputLeagueCode)
    {
      DB::table('usersLeagues')->insert(['leagueID' => $leagueID, 'userID' => $userID]);
    }

    return redirect('leagues');
  }

  public function joinLeague($leagueID)
  {
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    DB::table('usersLeagues')->insert(['leagueID' => $leagueID, 'userID' => $userID]);

    return redirect('leagues');
  }

  public function leaveLeague($leagueID)
  {
    // Get the currently authenticated user...
    $user = Auth::user();
    // Get the currently authenticated user's ID...
    $userID = Auth::id();

    DB::table('usersLeagues')->where(['leagueID' => $leagueID, 'userID' => $userID])->delete();

    return redirect('leagues');
  }
}
