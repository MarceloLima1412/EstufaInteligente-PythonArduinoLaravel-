@extends('layouts.app')

@section('title', 'Lista de Medida de Luz')

@section('content')

@if(count($luzes))
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Quantidade de Luz</th>
            <th>Data</th>            
        </tr>
    </thead>
    <tbody>
    @foreach ($luzes as $luz)
        <tr>
            <td>{{$luz->qtdLuz}}</td>
            <td>{{$luz->data}}</td>
        </tr>
    @endforeach
    </table>
@else
    <h2>Não foram encontradas medidas de luz</h2>
@endif
@endsection
