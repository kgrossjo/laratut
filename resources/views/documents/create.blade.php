@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add Document</div>

                <div class="card-body">
                	<form action="{{url('documents')}}" enctype="multipart/form-data" method="post">
                		@csrf
                		<div class="form-group">
                			<label for="title">Title:</label>
                			<input type="text" class="form-control" name="title">
                		</div>
                		<div class="form-group">
                			<label for="content">Content:</label>
                			<textarea class="form-control" rows="10" cols="80" name="content" placeholder="Document content goes here."></textarea>
                		</div>
                		<button type="submit" class="btn btn-primary">Add Document</button>
                	</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
