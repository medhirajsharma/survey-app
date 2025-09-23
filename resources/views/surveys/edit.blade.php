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
                            <label for="vidhansabha_id">Vidhansabha (Optional)</label>
                            <select name="vidhansabha_id" id="vidhansabha_id" class="form-control">
                                <option value="">Select Vidhansabha</option>
                                @foreach($vidhansabhas as $vidhansabha)
                                    <option value="{{ $vidhansabha->id }}" {{ $survey->vidhansabha_id == $vidhansabha->id ? 'selected' : '' }}>
                                        {{ $vidhansabha->constituency_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Results Visibility</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="results_visibility" id="results_visibility_show" value="show" {{ $survey->results_visibility == 'show' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="results_visibility_show">Show</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="results_visibility" id="results_visibility_hide" value="hide" {{ $survey->results_visibility == 'hide' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="results_visibility_hide">Hide</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="results_visibility" id="results_visibility_datetime" value="datetime" {{ $survey->results_visibility == 'datetime' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="results_visibility_datetime">Schedule</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3" id="results_visible_from_group" style="display: {{ $survey->results_visibility == 'datetime' ? 'block' : 'none' }};">
                            <label for="results_visible_from">Results Visible From</label>
                            <input type="datetime-local" name="results_visible_from" id="results_visible_from" class="form-control" value="{{ $survey->results_visible_from ? \Carbon\Carbon::parse($survey->results_visible_from)->format('Y-m-d\TH:i') : '' }}">
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#vidhansabha_id').select2({
            placeholder: "Select Vidhansabha",
            allowClear: true
        });
    });
</script>
@endpush
