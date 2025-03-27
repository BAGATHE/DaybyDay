
@extends('layouts.master')

@section('heading')
    {{ __('Importation') }}
@stop

@section('content')

    <div class="row">
        <div class="table-responsive">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th scope="col">name</th>
                    <th scope="col">action</th>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->company_name }}</td>
                        <td><a href="{{route('clients.duplicate',['id'=>$client->id])}}" class="btn btn-info">copy</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>




@stop
