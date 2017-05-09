@extends('layout')

@section('content')

<h1 class="">List of Tables available</h1>
<div class="container">
  <table>
    <tr>
      <td><a href="tables/prem">Premier League Table</a></td>
    </tr>
    <tr>
      <td><a href="tables/user">User prediction Table</a></td>
    </tr>
  </table>
  @foreach ($results as $result)
    <ol>
      {{ $result->teamName }}
    </ol>
  @endforeach


  <table>
    <th>

    </th>
    <tr>

    </tr>
  </table>
</div>

@endsection
