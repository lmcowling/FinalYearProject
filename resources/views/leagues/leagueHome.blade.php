@extends('layout')

@section('content')

<h1 class="">League home</h1>
<div class="container">
  <ol><a href="leagues">League Home</a></ol>
  <ol><a href="leagues/myLeagues">My Leagues</a></ol>
  <ol><a href="leagues/myLeagues/{leagueID}'">League Detail</a></ol>
  <ol><a href="leagues/search">Search Leagues</a></ol>
  <ol><a href="leagues/new">Create Leagues</a></a></ol>
</div>

@endsection
