<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
// use Illuminate\;

class TablesController extends Controller
{
  public function getPremTable()
  {

    // $tableResults =
    //                   DB::raw('SELECT
    //                    team,
    //                    COUNT(*) AS played,
    //                    COUNT(CASE WHEN goalsfor > goalsagainst THEN 1 END) AS wins,
    //                    COUNT(CASE WHEN goalsagainst> goalsfor THEN 1 END) AS lost,
    //                    COUNT(CASE WHEN goalsfor = goalsagainst THEN 1 END) AS draws,
    //                    SUM(goalsfor) AS goalsfor,
    //                    SUM(goalsagainst) AS goalsagainst,
    //                    SUM(goalsfor) - SUM(goalsagainst) AS goal_diff,
    //                    SUM(
    //                          CASE WHEN goalsfor > goalsagainst THEN 3 ELSE 0 END
    //                        + CASE WHEN goalsfor = goalsagainst THEN 1 ELSE 0 END
    //                    ) score
    //                  FROM (
    //                    SELECT homeTeamID AS team, homeScore AS goalsfor, awayScore AS goalsagainst
    //                    FROM resultsTable
    //                    UNION
    //                    SELECT awayTeamID AS team, awayScore AS goalsfor, homeScore AS goalsagainst
    //                    FROM resultsTable
    //                  ) a
    //                  GROUP BY team
    //                  ORDER BY score DESC, goal_diff DESC;');
                    // Football::LeagueTable('426');
                     return view('tables.premTable', compact('tableResults'));

  }

  public function getTables()
  {
    $results = DB::table('teamsTable')->get();

    return view('tables.tableList', compact('results'));
  }

  public function getUserTable()
  {
    return view('tables.userTable');
  }
}





// for the predictions class

// $gameResults = DB::table('resultsTable AS rt')
//                   ->join('teamsTable AS tth', 'rt.homeTeamID', '=', 'tth.teamID')
//                   ->join('teamsTable AS tta', 'rt.awayTeamID', '=', 'tta.teamID')
//                   ->select('tth.teamName AS team1', 'tta.teamName AS team2', 'rt.homeScore AS team1Score', 'rt.awayScore AS team2Score')
//                   ->where('rt.gameWeekID', '=', 34)
//                   ->get();
//
// return view('tables.premTable', compact('gameResults'));
//
// <table class="table">
//   <th>
//     {{-- <td>Team</td>
//     <td>Played</td>
//     <td>Won</td>
//     <td>Draw</td>
//     <td>Lost</td>
//     <td>Goals For</td>
//     <td>Goals Against</td>
//     <td>Goal Differece</td>
//     <td>Points</td>
//     <td>Last 5 Games</td> --}}
//     <td>Home Team</td>
//     <td>Home Score</td>
//     <td>Away Score</td>
//     <td>Away Team</td>
//   </th>
//   @foreach ($gameResults as $gameResult)
//     <tr>
//       <td></td>
//       <td>{{ $gameResult->team1 }}</td>
//       <td>{{ $gameResult->team1Score }}</td>
//       <td>{{ $gameResult->team2Score }}</td>
//       <td>{{ $gameResult->team2 }}</td>
//     </tr>
//   @endforeach
// </table>
