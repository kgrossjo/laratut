@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Document</div>

                <div class="card-body">
                	<form action="{{route('documents.update', ['document' => $document->id])}}" enctype="multipart/form-data" method="post">
                		@csrf
                		<input name="_method" type="hidden" value="PATCH">
                		<div class="form-group">
                			<label for="id">ID:</label>
                			<input type="text" disabled="disabled" class="form-control" name="id" value="{{ $document->id }}">
                		</div>
                		<div class="form-group">
                			<label for="title">Title:</label>
                			<input type="text" class="form-control" name="title" value="{{ $document->title }}">
                		</div>
                		<div class="form-group">
                			<label for="content">Content:</label>
                			<textarea class="form-control" rows="10" cols="80" name="content" placeholder="Document content goes here.">{{ $document->content }}</textarea>
                		</div>
                		<button type="submit" class="btn btn-primary">Update Document</button>
                	</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection