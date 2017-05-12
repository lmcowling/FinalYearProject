@extends('layout')
@section('content')
<div class="container">
  <div class="pageTitle"><h1 class="">My Leagues</h1></div>
  <div class="pageBody">
    <form method="post" action="/leagues/searchLeagues">
      {{ csrf_field() }}
      <div class="form-group">
        League Name <input type="text" class="leagueName" name="leagueName" value=""/>
        <button type="submit" class="FFbutton">Search League</button>
      </div>
    </form>
    <a href="leagues/new"><button type="button" class="FFbutton">Create League</button></a>
    <hr/>
    <div class="pageHeader">Public Leagues</div>
    @if($leaguesPublic != "NOTHING")
      @foreach ($leaguesPublic as $leaguePub)
        <ol><a href="leagues/{{ $leaguePub->leagueID }}">{{ $leaguePub->leagueName }}</a></ol>
      @endforeach
    @else
      <ol>Join or Create a Public League now!</ol>
    @endif
    <hr/>
    <div class="pageHeader">Private Leagues</div>
    @if($leaguesPrivate != "NOTHING")
      @foreach ($leaguesPrivate as $leaguePri)
        <ol><a href="leagues/{{ $leaguePri->leagueID }}">{{ $leaguePri->leagueName }}</a></ol>
      @endforeach
    @else
      <ol>Join or Create a Private League now!</ol>
    @endif
  </div>
</div>
@endsection
