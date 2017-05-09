@extends('layout')

@section('content')
<div class=pageTitle>
<h1>Predictions</h1>
</div>

<div class=pageBody>
  <?php
  $gameWeekIDPrevious = $gameWeekID - 1;
  $gameWeekIDNext = $gameWeekID + 1;
  ?>
  @if($gameWeekIDPrevious > 0)
    <a href="/predictions/{{ $gameWeekIDPrevious }}">
      <button type="button" class="btn" href="/predictions/{{ $gameWeekIDPrevious }}">Previous</button>
    </a>
  @endif
  Game Week {{ $gameWeekID }}
  @if($gameWeekIDNext < 39)
    <a href="/predictions/{{ $gameWeekIDNext }}">
      <button type="button" class="btn" href="/predictions/{{ $gameWeekIDNext }}">Next</button>
    </a>
  @endif
  <form method="post" action="/predictions/{{ $gameWeekID }}/insert">
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
    @foreach ($fixtures as $fixture)
      <tr>
        <td></td>
        <td colspan="4">{{ date('l d M Y, H:i', strtotime($fixture->fixDate)) }}</td>
        <input type="hidden" name="fixtureDate[]" value="{{ $fixture->fixDate }}"/>
        <input type="hidden" name="gameWeekID[]" value="{{ $gameWeekID }}"/>
        <input type="hidden" name="userID[]"  value="1"/>
        <td></td>
      </tr>
      <tr>
        <td><img src="storage/app/public/arsenal.jpg"></td>
        <td align="right">{{ $fixture->homeTeamName }}</td>
        <input type="hidden" name="homeTeamID[]"  value="{{ $fixture->homeTeamID }}"/>
        @if($fixture->fixDate > $date_now)
          <td align="right"><input type="text" width="50px" name="homeScore[]" value="0" required style="text-align:right;"/></td>
          <td align="left"><input type="text" width="50px" name="awayScore[]" value="0" required style="text-align:left;"/></td>
        @elseif(count($results)>0)
          <td align="center" colspan="2">
            <input type="text" width="50px" name="gameStarted" value="TEST" readonly style="text-align:center;"/>
          </td>
        @else
          <td align="center" colspan="2">
            <input type="text" width="50px" name="gameStarted" value="Too Late!" readonly style="text-align:center;"/>
          </td>
        @endif
        <input type="hidden" name="awayTeamID[]" value="{{ $fixture->awayTeamID }}"/>
        <td align="left">{{ $fixture->awayTeamName }}</td>
        <td><img src="storage/app/public/arsenal.jpg"></td>
      </tr>
    @endforeach
    <tr>
      <td colspan="6" align="center">
        <button type="submit" class="btn">Add predictions</button>
      </td>
    </tr>
  </table>
</form>
</div>

@endsection
