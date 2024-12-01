@extends('teacher.layouts.app')
@section('breadcrumbs')
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('teacher.meeting.index') }}">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">Study Material Create</li>
            </ol>
        </nav>
    </div>
@endsection
@section('content')
    <div class="row layout-spacing mt-3">
        <div id="flLoginForm" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Create Study Material</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form method="post" action="{{ route('teacher.meeting.store') }}" name="form" class="row g-3" id="meetingCreateForm" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <label for="topic" class="form-label">Topic</label>
                            <input type="text" name="topic" class="form-control" id="topic">
                        </div>

                        <div class="col-md-6">
                            <label for="dateTimeFlatpickr" class="form-label">Start Date Time</label>
                            <div class="form-group mb-0">
                                <input id="dateTimeFlatpickr" name="start_time" value="" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="batch" class="form-label">Select Batch</label>
                            <select class="form-control" name="batch" id="batch">
                                <option value="">Select Batch</option>
                                @foreach(auth()->guard('teacher')->user()->batches as $batch)
                                    <option value="{{$batch->batch->id}}">{{$batch->batch->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>

    </style>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
    <link href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
@endpush
@push('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.js') }}"></script>

    {{-- <script src="../src/plugins/src/tomSelect/custom-tom-select.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <script>
        var f2 = flatpickr(document.getElementById('dateTimeFlatpickr'), {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        $(document).ready(function() {
            $('#meetingCreateForm').validate({
                rules: {
                    topic: {
                        required: true
                    },
                    start_time: {
                        required: true
                    },
                    batch:{
                        required:true
                    }
                },
                messages: {

                },
                errorPlacement: function(error, element) {
                    error.css('color', 'red');
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    // Add extra validation for flatpickr here, in case the input isn't valid
                    var flatpickrValue = $('#dateTimeFlatpickr').val(); // Get the Flatpickr value
                    if (flatpickrValue === "") {
                        // Show error manually if Flatpickr input is empty
                        $('#dateTimeFlatpickr').after('<label id="start_time-error" class="error" style="color:red;">This field is required.</label>');
                        return false; // Prevent form submission
                    }
                    form.submit();
                }
            });
        });
    </script>
@endpush
