@extends('layouts.app')

@section('title', 'Lista de Medida de Humidade')

@section('content')

@if(count($moistures))
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Percentagem de Humidade</th>
            <th>Data</th>            
        </tr>
    </thead>
    <tbody>
    @foreach ($moistures as $moisture)
        <tr>
            <td>{{$moisture->moisture}}</td>
            <td>{{$moisture->data}}</td>
        </tr>
    @endforeach
    </table>
@else
    <h2>Não foram encontradas medidas de Humidade</h2>
@endif
@endsection
