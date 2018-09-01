@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Search Form</div>

                <div class="card-body">
                    <form action="{{ route('search.execute') }}"
                        enctype="multipart/form-data"
                        method="post">
                        @csrf
                        <div class="form-group">
                            <label for="query">Query:</label>
                            <input type="text" class="form-control" name="query">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection