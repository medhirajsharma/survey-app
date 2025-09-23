@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Survey') }}</div>

                <div class="card-body">
                    <form action="{{ route('surveys.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="vidhansabha_id">Vidhansabha (Optional)</label>
                            <select name="vidhansabha_id" id="vidhansabha_id" class="form-control">
                                <option value="">Select Vidhansabha</option>
                                @foreach($vidhansabhas as $vidhansabha)
                                    <option value="{{ $vidhansabha->id }}">{{ $vidhansabha->constituency_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="meta_image">Meta Image</label>
                            <input type="file" name="meta_image" id="meta_image" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Create</button>
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
