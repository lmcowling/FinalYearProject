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
    @foreach ($predictions as $prediction)
      <tr>
        <td></td>
        <td colspan="4">{{ date('l d M Y, H:i', strtotime($prediction->fixDate)) }}</td>
        <input type="hidden" name="fixtureDate[]" value="{{ $prediction->fixDate }}"/>
        <input type="hidden" name="fixtureID[]" value="{{ $prediction->fixtureID }}"/>
        <input type="hidden" name="gameWeekID[]" value="{{ $gameWeekID }}"/>
        <input type="hidden" name="userID[]"  value="{{ $userID }}"/>
        <td></td>
      </tr>
      <tr>
        <td><img src="storage/app/public/arsenal.jpg"></td>
        <td align="right">{{ $prediction->homeTeamName }}</td>
        <input type="hidden" name="homeTeamID[]" value="{{ $prediction->homeTeamID }}"/>
        @if($prediction->fixDate > $date_now)
          <td align="right"><input type="text" class="prediction" name="homeScore[]" value="{{ $prediction->homeScore }}" required/></td>
          <td align="left"><input type="text" class="prediction" name="awayScore[]" value="{{ $prediction->awayScore }}" required/></td>
        @elseif(count($results)>0) {{-- fnish --}}
          <td align="center" colspan="2">
            <input type="hidden" name="homeScore[]" value=""/>
            <input type="text" class="fixtureResults" name="results" value="Show Results" readonly style="text-align:center;"/>
            <input type="hidden" name="awayScore[]" value=""/>
          </td>
        @else
          <td align="center" colspan="2">
            <input type="hidden" name="homeScore[]" value=""/>
            <input type="text" class="predictionMissed" width="50px" name="gameStarted" value="Game Started" readonly style="text-align:center;"/>
            <input type="hidden" name="awayScore[]" value=""/>
          </td>
        @endif
        <input type="hidden" name="awayTeamID[]" value="{{ $prediction->awayTeamID }}"/>
        <td align="left">{{ $prediction->awayTeamName }}</td>
        <td><img src="storage/app/public/arsenal.jpg"></td>
      </tr>
    @endforeach
    <tr>
      <td colspan="6" align="center">
        <button type="submit" class="FFbutton">Update predictions</button>
      </td>
    </tr>
  </table>
</form>
</div>

@endsection
