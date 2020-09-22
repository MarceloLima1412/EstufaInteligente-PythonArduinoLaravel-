@extends('layouts.app')

@section('title', 'Lista de Utilizadores')

@section('content')

@if(count($users))
    <table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>     
            <th>Tipo de Utilizador</th>       
        </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{{$user->id}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->tipo_user}}</td>
            @can('view', App\User::class) 
            <td><form  method="POST"  action="{{route('users.ativar', $user)}}"role="form" class="inline">
                @csrf
                @method('patch')
                <input type = "hidden" name="ativar" value="">
                    <button type="submit" class="btn btn-primary">Anonimo/Funcionario</button>
            </form>
            </td>
            @endcan
        </tr>
    @endforeach
    </table>
@else
    <h2>Não foram encontrados utilizadores
    </h2>
@endif
@endsection
