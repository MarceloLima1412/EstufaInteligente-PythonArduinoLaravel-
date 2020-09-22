@extends('layouts.app')

@section('title', 'Lista de Medida de Temperatura e Humidade')

@section('content')

@if(count($tempehumids))
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Quantidade de Temperatura</th>
            <th>Quantidade de Humidade</th>
            <th>Data</th>            
        </tr>
    </thead>
    <tbody>
    @foreach ($tempehumids as $tempehumid)
        <tr>
            <td>{{$tempehumid->temperatura}}</td>
            <td>{{$tempehumid->humidade}}</td>
            <td>{{$tempehumid->data}}</td>
        </tr>
    @endforeach
    </table>
@else
    <h2>Não foram encontradas medidas de temperatura e humidade</h2>
@endif
@endsection
