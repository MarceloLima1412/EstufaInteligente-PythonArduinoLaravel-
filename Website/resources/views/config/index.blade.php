@extends('layouts.app')

@section('title', 'Lista de Medida de Agua')

@section('content')

@if(count($configs))
    <table class="table table-striped">
    <tbody>
    @foreach ($configs as $config)
    <tr>
            <td>{{$config->descricao}}</td>
            @if($config->id==1)
             @if($config->variavel==1)
                <td>30 segundos</td>
                <td><form  method="POST"  action="{{route('config.variavel', $config)}}"role="form" class="inline">
                        @csrf
                        @method('patch')
                        <input type = "hidden" name="variavel" value="">
                            <button type="submit" class="btn btn-primary">Mudar para 5 segundos</button>
                    </form>
                    </td>
                @else
                    <td>5 segundos</td>
                    <td><form  method="POST"  action="{{route('config.variavel', $config)}}"role="form" class="inline">
                            @csrf
                            @method('patch')
                            <input type = "hidden" name="variavel" value="">
                                <button type="submit" class="btn btn-primary">Mudar para 30 segundos</button>
                        </form>
                        </td>
                @endif
                @elseif($config->id==2)
                @if($config->variavel==1)
                <td>Ligado</td>
                <td><form  method="POST"  action="{{route('config.variavel', $config)}}"role="form" class="inline">
                        @csrf
                        @method('patch')
                        <input type = "hidden" name="variavel" value="">
                            <button type="submit" class="btn btn-primary">Desligar</button>
                    </form>
                    </td>
                @else
                    <td>Desligado</td>
                    <td><form  method="POST"  action="{{route('config.variavel', $config)}}"role="form" class="inline">
                            @csrf
                            @method('patch')
                            <input type = "hidden" name="variavel" value="">
                                <button type="submit" class="btn btn-primary">Ligar</button>
                        </form>
                        </td>
                @endif
                    @else
                    @if($config->variavel==1)
                    <td>Ligado</td>
                    <td><form  method="POST"  action="{{route('config.variavel', $config)}}"role="form" class="inline">
                            @csrf
                            @method('patch')
                            <input type = "hidden" name="variavel" value="">
                                <button type="submit" class="btn btn-primary">Desligar</button>
                        </form>
                        </td>
                    @else
                        <td>Desligado</td>
                        <td><form  method="POST"  action="{{route('config.variavel', $config)}}"role="form" class="inline">
                                @csrf
                                @method('patch')
                                <input type = "hidden" name="variavel" value="">
                                    <button type="submit" class="btn btn-primary">Ligar</button>
                            </form>
                            </td>
                    @endif
            @endif
        </tr>
    @endforeach
    </table>
@else
    <h2>Não foram encontradas configs</h2>
@endif
@endsection
