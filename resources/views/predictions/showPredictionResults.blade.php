@extends('layout')

@section('content')
<div class=pageTitle>
<h1>Predictions</h1>
</div>
<div class=pageBody>
  <?php
  $gameWeekIDPrev = $gameWeekID - 1;
  $gameWeekIDNext = $gameWeekID + 1;
  ?>
  @if($gameWeekIDPrev > 0)
    <a href="/predictions/{{ $gameWeekIDPrev }}">
      <button type="button" class="FFbutton" href="/predictions/{{ $gameWeekIDPrev }}">Previous</button>
    </a>
  @else
    <button type="button" class="disabled">Previous</button>
  @endif
  Game Week {{ $gameWeekID }}
  @if($gameWeekIDNext < 39)
    <a href="/predictions/{{ $gameWeekIDNext }}">
      <button type="button" class="FFbutton" href="/predictions/{{ $gameWeekIDNext }}">Next</button>
    </a>
  @else
    <button type="button" class="disabled">Next</button>
  @endif
  <hr>
  <ol>
    <li>
      <h3>Points: <strong class="points">{{ $points }}</strong></h3>
    </li>
  </ol>
  <form method="post" action="/predictions/{{ $gameWeekID }}/update">
    {{ csrf_field() }}
  <table class="table">
    <tr>
      <td width="5%"></td>
      <td width="30%">Home</td>
      <td width="17%"></td>
      <td width="17%"></td>
      <td width="30%">Away</td>
      <td width="5%"></td>
    </tr>
    @foreach ($results as $result)
      <tr>
        <td></td>
        <td colspan="4">{{ date('l d M Y, H:i', strtotime($result->fixDate)) }}</td>
        <input type="hidden" name="fixtureDate[]" value="{{ $result->fixDate }}"/>
        <input type="hidden" name="gameWeekID[]" value="{{ $gameWeekID }}"/>
        <input type="hidden" name="userID[]"  value="{{ $userID }}"/>
        <td></td>
      </tr>
      <tr>
        <td><img src="storage/app/public/arsenal.jpg"></td>
        <td align="right">{{ $result->homeTeamName }}</td>
        <td align="right"><input type="text" class="fixtureResults" name="homeScore[]" value="{{ $result->homeScore }}" required/></td>
        <td align="left"><input type="text" class="fixtureResults" name="awayScore[]" value="{{ $result->awayScore }}" required/></td>
        <td align="left">{{ $result->awayTeamName }}</td>
        <td><img src="storage/app/public/arsenal.jpg"></td>
      </tr>
    @endforeach
  </table>
</form>
</div>

@endsection
