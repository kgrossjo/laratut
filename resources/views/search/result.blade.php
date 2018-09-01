@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Search Result</div>

                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <strong>Score</strong>
                            </div>
                            <div class="col-sm">
                                <strong>ID</strong>
                            </div>
                        </div>
                        @foreach ($result as $item)
                            <div class="row">
                                <div class="col-sm">
                                    {{ $item['score'] }}
                                </div>
                                <div class="col-sm">
                                    <a href="{{ route('documents.show', ['document' => $item['id'] ]) }}">
                                        {{ $item['id'] }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
