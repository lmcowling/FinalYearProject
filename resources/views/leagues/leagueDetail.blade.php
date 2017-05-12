@extends('layout')
@section('content')
<div class="container">
  <div class="pageTitle">
    <h1>{{ $leagueDetail->leagueName }}</h1>
    <h3>Admin: {{ $leagueDetail->userName }}</h3>
    @if($userID == $leagueDetail->leagueAdmin)
      <h3>League Code: {{ $leagueDetail->leagueCode }}</h3>
      <a href="/leagues/{{ $leagueDetail->leagueID }}/delete"><button type="button" class="FFbutton">Delete League</button></a>
    @endif
    @if($leagueCheck==0)
      @if($leagueDetail->status == 0) {{--Private--}}
        <form method="post" action="/leagues/{{ $leagueDetail->leagueID }}/verify">
          {{ csrf_field() }}
          <input type="hidden" class="leagueName" name="leagueID" value="{{ $leagueDetail->leagueID }}"/>
          <input type="hidden" class="leagueName" name="realLeagueCode" value="{{ $leagueDetail->leagueCode }}"/>
          <input type="text" class="leagueName" name="inputLeagueCode" value=""/>
          <button type="submit" class="FFbutton">Join League</button>
        </form>
      @elseif($leagueDetail->status == 1) {{--Public--}}
        <a href="/leagues/{{ $leagueDetail->leagueID }}/join"><button type="button" class="FFbutton">Join League</button></a>
      @endif
    @else
      <a href="/leagues/{{ $leagueDetail->leagueID }}/leave"><button type="button" class="FFbutton">Leave League</button></a>
    @endif
  </div>
  <div class="pageBody">
    @if($leagueCheck>0)
      <table class="table">
        <th>
          <td>Position</td>
          <td>Username</td>
          <td>Points</td>
        </th>
        @foreach ($leagueTable as $league)
          <tr>
            <td></td>
            <td></td>
            <td>{{ $league->userName }}</td>
            <td></td>
          </tr>
        @endforeach
      </table>
    @endif
  </div>
</div>
@endsection
