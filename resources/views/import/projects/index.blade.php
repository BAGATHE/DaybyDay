@extends('layouts.master')

@section('heading')
    {{ __('Importation') }}
@stop

@section('content')



    <div class="row">
        <div class="col-md-4 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <strong>{{ __('Importer Project') }}</strong>
                </div>
                <div class="panel-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csv_file">{{ __('Choisir un fichier CSV') }}</label>
                            <input type="file" name="csv_file" class="form-control" id="csv_file" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Importer') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <strong>{{ __('Importer Task') }}</strong>
                </div>
                <div class="panel-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csv_file">{{ __('Choisir un fichier CSV') }}</label>
                            <input type="file" name="csv_file" class="form-control" id="csv_file" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Importer') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>



    <div class="row">
        <div class="col-md-4 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <strong>{{ __('Importer invoices') }}</strong>
                </div>
                <div class="panel-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csv_file">{{ __('Choisir un fichier CSV') }}</label>
                            <input type="file" name="csv_file" class="form-control" id="csv_file" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Importer') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-4 ">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <strong>{{ __('Importer payments') }}</strong>
                </div>
                <div class="panel-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csv_file">{{ __('Choisir un fichier CSV') }}</label>
                            <input type="file" name="csv_file" class="form-control" id="csv_file" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Importer') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <strong>{{ __('Importer Appointement') }}</strong>
                </div>
                <div class="panel-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csv_file">{{ __('Choisir un fichier CSV') }}</label>
                            <input type="file" name="csv_file" class="form-control" id="csv_file" required>
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

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

@stop
