@extends('layouts.master')

@section('heading')
    {{ __('Importation') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <strong>{{ __('Importation  ') }}</strong>
                </div>
                <div class="panel-body">
                    <form action="{{ route('import.csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="importation_1">{{ __('Choisir un fichier CSV 1') }}</label>
                            <input type="file" name="importation_1" accept=".csv" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="importation_2">{{ __('Choisir un fichier CSV 2') }}</label>
                            <input type="file" name="importation_2" class="form-control" id="csv_file" >
                        </div>
                        <div class="form-group">
                            <label for="importation_3">{{ __('Choisir un fichier CSV 3') }}</label>
                            <input type="file" name="importation_3" class="form-control" id="csv_file" >
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Importer') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <h3>Erreurs détectées :</h3>
        <ul   class="alert alert-danger text-center" >
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

@stop
