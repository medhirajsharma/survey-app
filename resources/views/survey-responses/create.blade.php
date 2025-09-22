@extends('layouts.public')

@push('meta')
    @php
        $metaDescription = $survey->meta_description ?? $survey->description ?? "Take this survey: '" . $survey->title . "'";
        $metaImage = null;

        if ($survey->meta_image) {
            $metaImage = asset('storage/' . $survey->meta_image);
        } else {
            foreach ($survey->questions as $question) {
                foreach ($question->options as $option) {
                    if ($option->image_path) {
                        $metaImage = asset('storage/' . $option->image_path);
                        break 2; // Breaks out of both loops
                    }
                }
            }
        }
    @endphp
    <meta property="og:title" content="{{ $survey->title }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('public.surveys.take', $survey) }}">
    @if($metaImage)
    <meta property="og:image" content="{{ $metaImage }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $survey->title }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:url" content="{{ route('public.surveys.take', $survey) }}">
@endpush

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

            <div class="text-center mt-4">
                <h5 class="fw-bold mb-3">Share this survey:</h5>
                <div class="d-flex justify-content-center gap-2 flex-wrap share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('public.surveys.take', $survey)) }}" target="_blank" class="btn btn-primary mb-2 share-btn"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('public.surveys.take', $survey)) }}&text={{ urlencode($survey->title) }}" target="_blank" class="btn btn-info text-white mb-2 share-btn"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="https://wa.me/?text={{ urlencode($survey->title . ' - ' . route('public.surveys.take', $survey)) }}" target="_blank" class="btn btn-success mb-2 share-btn"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <button onclick="copyToClipboard('{{ route('public.surveys.take', $survey) }}')" class="btn btn-outline-secondary mb-2 share-btn"><i class="fas fa-link"></i> Copy Link</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.share-buttons .share-btn {
    transition: all 0.3s ease;
    border-radius: 50px;
    padding: 10px 20px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.share-buttons .share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.share-buttons .share-btn:active {
    transform: translateY(-1px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
}

.share-buttons .fa-facebook { color: #fff; }
.share-buttons .fa-twitter { color: #fff; }
.share-buttons .fa-whatsapp { color: #fff; }
.share-buttons .fa-link { color: #6c757d; }

.share-buttons .btn-primary:hover .fa-facebook { color: white; }
.share-buttons .btn-info:hover .fa-twitter { color: white; }
.share-buttons .btn-success:hover .fa-whatsapp { color: white; }
.share-buttons .btn-outline-secondary:hover .fa-link { color: white; }

.form-floating .select2-container--default .select2-selection--single {
    height: calc(3.5rem + 2px);
    padding: 1rem 0.75rem;
}

.form-floating .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 2.25rem;
    padding-left: 0;
}

.form-floating .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 3.5rem;
}

.form-floating .select2-container--default.select2-container--open .select2-selection--single {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-floating > label {
    z-index: 2;
}

</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#vidhansabha_id').select2({
        placeholder: 'Select Vidhansabha',
        width: '100%'
    });
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Survey link copied to clipboard!');
    }, function(err) {
        alert('Could not copy text: ', err);
    });
}

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
@endpush

@endsection