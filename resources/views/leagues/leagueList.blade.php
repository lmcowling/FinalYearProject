@extends('layout')
@section('content')
<div class="container">
  <div class="pageTitle"><h1>League Search Results</h1></div>
  <div class="pageBody">
    @foreach ($results as $result)
      <div class="form-group">
        <a href="/leagues/{{ $result->leagueID }}">{{ $result->leagueName }}</a>
      </div>
    @endforeach
  </div>
</div>
@endsection
