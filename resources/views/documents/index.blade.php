@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Documents</div>
				<div class="card-body">
					<div class="container">
						<div class="row">
							<div class="col-1"><strong>ID</strong></div>
							<div class="col-3"><strong>Updated</strong></div>
							<div class="col"><strong>Title</strong></div>
						</div>
						@foreach ($documents as $d)
                            <div class="row">
                                <div class="col-1">
                                    <a href="{{ route('documents.show', ['document' => $d->id]) }}">
                                        {{ $d->id }}
                                    </a>
                                </div>
                                <div class="col-3">
                                    {{ $d->updated_at }}
                                </div>
                                <div class="col">
                                    {{ $d->title }}
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
