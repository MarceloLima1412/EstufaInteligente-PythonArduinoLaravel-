@extends('layouts.app')

@section('title', 'Lista de Medida de Agua')

@section('content')

@if(count($aguas))
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Quantidade de Agua</th>
            <th>Data</th>            
        </tr>
    </thead>
    <tbody>
    @foreach ($aguas as $agua)
        <tr>
            <td>{{$agua->qtdAgua}}</td>
            <td>{{$agua->data}}</td>
        </tr>
    @endforeach
    </table>
@else
    <h2>Não foram encontradas medidas de agua</h2>
@endif
@endsection
