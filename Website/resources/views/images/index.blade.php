@extends('layouts.app')

@section('title', 'Imagem 24h da estufa')

@section('content')
@foreach ($images as $image)
        <tr>
            <td>{{$image->image}}</td>
        </tr>
@endforeach
@endsection
