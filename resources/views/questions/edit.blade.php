@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Question') }}</div>

                <div class="card-body">
                    <form action="{{ route('questions.update', $question) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="text">Question</label>
                            <input type="text" name="text" id="text" class="form-control" value="{{ $question->text }}" required>
                        </div>

                        <div class="form-group" id="options-container">
                            <label>Options</label>
                            @foreach ($question->options as $index => $option)
                                <div class="input-group mb-2">
                                    <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">
                                    <input type="text" name="options[{{ $index }}][text]" class="form-control" placeholder="Option {{ $index + 1 }}" value="{{ $option->text }}" required>
                                    <div class="custom-file">
                                        <input type="file" name="options[{{ $index }}][image]" class="custom-file-input" id="option{{ $index }}Image">
                                        <label class="custom-file-label" for="option{{ $index }}Image">{{ $option->image_path ? basename($option->image_path) : 'Choose file' }}</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger remove-option">Remove</button>
                                    </div>
                                </div>
                                @if ($option->image_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $option->image_path) }}" alt="Option Image" width="100">
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-secondary" id="add-option">Add More Option</button>
                        <button type="submit" class="btn btn-primary">Update Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('add-option').addEventListener('click', function () {
            const optionsContainer = document.getElementById('options-container');
            const newIndex = optionsContainer.querySelectorAll('.input-group').length;

            const newOption = document.createElement('div');
            newOption.classList.add('input-group', 'mb-2');
            newOption.innerHTML = `
                <input type="hidden" name="options[${newIndex}][id]" value="">
                <input type="text" name="options[${newIndex}][text]" class="form-control" placeholder="Option ${newIndex + 1}" required>
                <div class="custom-file">
                    <input type="file" name="options[${newIndex}][image]" class="custom-file-input" id="option${newIndex}Image">
                    <label class="custom-file-label" for="option${newIndex}Image">Choose file</label>
                </div>
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-option">Remove</button>
                </div>
            `;
            optionsContainer.appendChild(newOption);

            const newFileInput = newOption.querySelector('.custom-file-input');
            newFileInput.addEventListener('change', function (e) {
                var fileName = e.target.files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        });

        document.getElementById('options-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-option')) {
                if (document.getElementById('options-container').querySelectorAll('.input-group').length > 2) {
                    e.target.closest('.input-group').remove();
                }
            }
        });

        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function (e) {
                var fileName = e.target.files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        });
    });
</script>
@endsection