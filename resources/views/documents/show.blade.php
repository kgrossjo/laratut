@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Show Document</div>

                <div class="card-body">
					<p>
						I think it makes no sense to show the document like this.
						When folks click on the id of the document in the list
						documents view, they should land on the edit view immediately.
						But at least I can show that I know how to show a document...
					</p>
                	<div class="row">
                		<div class="col-2">
                			ID
                		</div>
                		<div class="col">
                			{{ $document->id }}
                		</div>
                	</div>
                	<div class="row">
                		<div class="col-2">
                			Title
                		</div>
                		<div class="col">
                			{{ $document->title }}
                		</div>
                	</div>
                	<div class="row">
                		<div class="col-2">
                			Content
                		</div>
                		<div class="col">
                			{{ $document->content }}
                		</div>
                	</div>
                	<div class="btn-toolbar" role="toolbar">
                		<div class="btn-group">
                            <a href="{{ route('documents.edit', ['document' => $document->id]) }}" class="btn btn-primary">
                                Update
                            </a>
                		</div>
                		<div class="btn-group">
                            <button type="button" class="btn btn-secondary">
                                Delete
                            </button>
                		</div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

