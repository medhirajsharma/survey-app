@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Question to ') }} {{ $survey->title }}</div>

                <div class="card-body">
                    <form action="{{ route('surveys.questions.store', $survey) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="text">Question</label>
                            <input type="text" name="text" id="text" class="form-control" required>
                        </div>

                        <div class="form-group" id="options-container">
                            <label>Options</label>
                            <div class="input-group mb-2">
                                <input type="text" name="options[0][text]" class="form-control" placeholder="Option 1" required>
                                <div class="custom-file">
                                    <input type="file" name="options[0][image]" class="custom-file-input" id="option1Image">
                                    <label class="custom-file-label" for="option1Image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-option">Remove</button>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" name="options[1][text]" class="form-control" placeholder="Option 2" required>
                                <div class="custom-file">
                                    <input type="file" name="options[1][image]" class="custom-file-input" id="option2Image">
                                    <label class="custom-file-label" for="option2Image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-option">Remove</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" id="add-option">Add More Option</button>
                        <button type="submit" class="btn btn-primary">Add Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-option').addEventListener('click', function () {
        const optionsContainer = document.getElementById('options-container');
        const lastOption = optionsContainer.querySelector('.input-group:last-of-type');
        const newOption = lastOption.cloneNode(true);

        const newIndex = optionsContainer.querySelectorAll('.input-group').length;
        const newText = newOption.querySelector('input[type="text"]');
        const newFile = newOption.querySelector('input[type="file"]');
        const newLabel = newOption.querySelector('label');

        newText.name = `options[${newIndex}][text]`;
        newText.placeholder = `Option ${newIndex + 1}`;
        newText.value = ''; // Clear the value
        newText.required = true;

        newFile.name = `options[${newIndex}][image]`;
        newFile.id = `option${newIndex + 1}Image`;
        newFile.value = ''; // Clear the value

        newLabel.setAttribute('for', `option${newIndex + 1}Image`);
        newLabel.innerText = 'Choose file';

        // Clear the file input display name
        const bsCustomFileInput = newOption.querySelector('.custom-file-label');
        if (bsCustomFileInput) {
            bsCustomFileInput.innerText = 'Choose file';
        }

        optionsContainer.appendChild(newOption);

        newFile.addEventListener('change', function (e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });

        newOption.querySelector('.remove-option').addEventListener('click', function (e) {
            if (document.getElementById('options-container').querySelectorAll('.input-group').length > 2) {
                e.target.closest('.input-group').remove();
            }
        });
    });

    document.querySelectorAll('.custom-file-input').forEach(function (input) {
        input.addEventListener('change', function (e) {
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
</script>

@endsection
