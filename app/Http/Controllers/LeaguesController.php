<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaguesController extends Controller
{
  public function getLeagues()
  {
    return view('leagues.leagueHome');
  }

  public function getMyLeagues()
  {
    return view('leagues.leagueList');
  }

  public function getLeagueDetail($leagueID)
  {
    return view('leagues.leagueDetail', compact('leagueID'));
  }

  public function searchLeagues()
  {
    return view('leagues.leagueSearch');
  }

  public function newLeague()
  {
    return view('leagues.createLeague');
  }
}
