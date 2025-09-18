@extends('layouts.public')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom-0 text-center pt-4">
                    <h4 class="card-title mb-1">{{ $survey->title }}</h4>
                    <p class="text-muted small">{{ $survey->description }}</p>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('public.surveys.take', $survey) }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <fieldset class="mb-4">
                            <legend class="h6 mb-3 fw-bold">Personal Information</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Your Name" required>
                                        <label for="name">Your Name</label>
                                        <div class="invalid-feedback">Please enter your name.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control form-control-sm" placeholder="Mobile No" required maxlength="10" pattern="[0-9]{10}" title="Please enter a 10-digit mobile number">
                                        <label for="mobile_no">Mobile No</label>
                                        <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="vidhansabha_id" id="vidhansabha_id" class="form-select form-select-sm" required>
                                            <option value="" disabled selected>Select Vidhansabha</option>
                                            @foreach ($vidhansabhas as $vidhansabha)
                                                <option value="{{ $vidhansabha->id }}">{{ $vidhansabha->constituency_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="vidhansabha_id">Vidhansabha</label>
                                        <div class="invalid-feedback">Please select your Vidhansabha.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="caste" id="caste" class="form-control form-control-sm" placeholder="Caste" required>
                                        <label for="caste">Caste</label>
                                        <div class="invalid-feedback">Please enter your caste.</div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <hr class="my-4">

                        @foreach ($survey->questions as $key => $question)
                            <fieldset class="mb-4">
                                <legend class="h6 mb-3 fw-bold">Question {{ $key + 1 }}: {{ $question->text }}</legend>
                                <div class="row row-cols-2 row-cols-sm-3 g-2">
                                    @foreach ($question->options as $option)
                                        <div class="col">
                                            <input class="btn-check" type="radio" name="answers[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" required autocomplete="off">
                                            <label class="btn btn-outline-primary w-100 h-100" for="option-{{ $option->id }}">
                                                @if ($option->image_path)
                                                    <img src="{{ asset('storage/' . $option->image_path) }}" alt="Option Image" class="img-fluid rounded mb-2" style="max-height: 60px;">
                                                @endif
                                                <span class="d-block small">{{ $option->text }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Submit Survey</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})()
</script>
@endsection