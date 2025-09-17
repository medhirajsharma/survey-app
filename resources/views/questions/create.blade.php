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

                        <div class="form-group">
                            <label>Options</label>
                            <div class="input-group mb-2">
                                <input type="text" name="options[0][text]" class="form-control" placeholder="Option 1" required>
                                <div class="custom-file">
                                    <input type="file" name="options[0][image]" class="custom-file-input" id="option1Image">
                                    <label class="custom-file-label" for="option1Image">Choose file</label>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" name="options[1][text]" class="form-control" placeholder="Option 2" required>
                                <div class="custom-file">
                                    <input type="file" name="options[1][image]" class="custom-file-input" id="option2Image">
                                    <label class="custom-file-label" for="option2Image">Choose file</label>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" name="options[2][text]" class="form-control" placeholder="Option 3" required>
                                <div class="custom-file">
                                    <input type="file" name="options[2][image]" class="custom-file-input" id="option3Image">
                                    <label class="custom-file-label" for="option3Image">Choose file</label>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" name="options[3][text]" class="form-control" placeholder="Option 4" required>
                                <div class="custom-file">
                                    <input type="file" name="options[3][image]" class="custom-file-input" id="option4Image">
                                    <label class="custom-file-label" for="option4Image">Choose file</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.custom-file-input').forEach(function (input) {
        input.addEventListener('change', function (e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
</script>
@endsection
