@extends('layout')

@section('content')

<h1 class="">Predicted League Table</h1>
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
        <td>Last 5 Games</td>
      </th>
      <tr>
        <td>1</td>
        <td>Leeds</td>
        <td>33</td>
        <td>33</td>
        <td>0</td>
        <td>0</td>
        <td>75</td>
        <td>10</td>
        <td>65</td>
        <td>99</td>
        <td>W W W W W</td>
      </tr>
    </table>
</div>

@endsection
