@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Survey') }}</div>

                <div class="card-body">
                    <form action="{{ route('surveys.update', $survey) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $survey->title }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ $survey->description }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control">{{ $survey->meta_description }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="meta_image">Meta Image</label>
                            <input type="file" name="meta_image" id="meta_image" class="form-control">
                            @if ($survey->meta_image)
                                <div class="mt-2">
                                    Current Image:
                                    <img src="{{ asset('storage/' . $survey->meta_image) }}" alt="Meta Image" style="max-width: 200px;">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
