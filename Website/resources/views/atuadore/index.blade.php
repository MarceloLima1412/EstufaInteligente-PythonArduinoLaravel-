@extends('layouts.app')

@section('title', 'Atuadores')

@section('content')

@if(count($atuadores))
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Nome do Atuador</th>
            <th>Ativo</th>        
        </tr>
    </thead>
    <tbody>
    @foreach ($atuadores as $atuadore)
        <tr>
            <td>{{$atuadore->tipo}}</td>
            <td>{{$atuadore->ativo}}</td>
            <td><form  method="POST"  action="{{route('atuadores.ativar', $atuadore)}}"role="form" class="inline">
                @csrf
                @method('patch')
                <input type = "hidden" name="ativar" value="">
                    <button type="submit" class="btn btn-primary">Ativar/Desativar</button>
            </form>
            </td>
        </tr>
    @endforeach
    </table>
@else
    <h2>Não foram encontrados atuadores</h2>
@endif
@endsection
