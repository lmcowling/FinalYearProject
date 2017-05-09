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
        <input type="hidden" name="fixtureDate[]"  value="{{ $prediction->fixDate }}"/>
        <input type="hidden" name="gameWeekID[]"  value="{{ $gameWeekID }}"/>
        <input type="hidden" name="userID[]"  value="1"/>
        <td></td>
      </tr>
      <tr>
        <td><img src="storage/arsenal.jpg">{{-- <img src="uploads/photos/{{ $photo->filename }}"> --}}</td>
        <td align="right">{{ $prediction->homeTeamName }}</td>
        <input type="hidden" name="homeTeamID[]"  value="{{ $prediction->homeTeamID }}"/>
        <td align="right"><input type="text" width="50px" name="homeScore[]" value="{{ $prediction->homeScore }}" required/></td>
        <td align="left"><input type="text" width="50px" name="awayScore[]" value="{{ $prediction->awayScore }}" required/></td>
        <input type="hidden" name="awayTeamID[]" value="{{ $prediction->awayTeamID }}"/>
        <td align="left">{{ $prediction->awayTeamName }}</td>
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
