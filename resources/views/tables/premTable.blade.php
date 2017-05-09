@extends('layout')

@section('content')

<h1 class="">Premier League Table</h1>
<div class="container">
    <table class="table">
      <th>
        <td>Team</td>
        <td>Played</td>
        <td>Won</td>
        <td>Draw</td>
        <td>Lost</td>
        <td>Goals For</td>
        <td>Goals Against</td>
        <td>Goal Differece</td>
        <td>Points</td>
      </th>
      @foreach ($tableResults as $tableResult)
        <tr>
          <td>1sgsg</td>
        </tr>
      @endforeach
    </table>
</div>

@endsection


<?php
$path = storage_path() . "http://api.football-data.org/v1/competitions/426/leagueTable";
$json = json_decode(file_get_contents($path), true);
?>

{{-- SELECT
  team,
  COUNT(*) AS played,
  COUNT(CASE WHEN goalsfor > goalsagainst THEN 1 END) AS wins,
  COUNT(CASE WHEN goalsagainst> goalsfor THEN 1 END) AS lost,
  COUNT(CASE WHEN goalsfor = goalsagainst THEN 1 END) AS draws,
  SUM(goalsfor) AS goalsfor,
  SUM(goalsagainst) AS goalsagainst,
  SUM(goalsfor) - SUM(goalsagainst) AS goal_diff,
  SUM(
        CASE WHEN goalsfor > goalsagainst THEN 3 ELSE 0 END
      + CASE WHEN goalsfor = goalsagainst THEN 1 ELSE 0 END
  ) score
FROM (
  SELECT homeTeamID AS team, homeScore AS goalsfor, awayScore AS goalsagainst
  FROM resultsTable
  UNION
  SELECT awayTeamID AS team, awayScore AS goalsfor, homeScore AS goalsagainst
  FROM resultsTable
) a
GROUP BY team
ORDER BY score DESC, goal_diff DESC; --}}
