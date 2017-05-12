@extends('layout')

@section('content')

<div class="container">
  <div class="pageTitle"><h1>Create a new league</h1></div>
  <div class="pageBody">
    <form method="post" action="/leagues/add">
      {{ csrf_field() }}
      <div class="form-group">
        <input type="text" class="leagueName" name="leagueName" value="" required/>
      </div>
      <div class="form-group">
        Private <input name="status" type="radio" value="0" checked="checked">
        Public <input name="status" type="radio" value="1">
      </div>
      <div class="form-group">
        <input name="leagueCode" type="hidden" value="{{ $leagueCodeUnique }}"/>
        <input name="leagueAdmin" type="hidden" value="{{ $userID }}"/>
        <button type="submit" class="FFbutton">Create League</button>
      </div>
  </form>
  </div>
</div>

@endsection
