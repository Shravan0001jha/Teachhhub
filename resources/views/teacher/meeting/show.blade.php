@extends('teacher.layouts.app')
@section('breadcrumbs')
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('teacher.meeting.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Meeting View</li>
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
                            <h4>Meeting Detailes</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="col-md-12">
                        <label class="form-label">Topic: </label>
                        <input type="text" class="form-control" value="{{$meeting->topic}}">
                    </div>
                    <div class="col-md-12 mt-3 mt-3">
                        <label class="form-label">Start Time: </label>
                        <input type="text" class="form-control" value="{{$meeting->start_time}}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Duration: </label>
                        <input type="text" class="form-control" value="{{$meeting->duration}}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Start URL: </label>
                        <input type="text" class="form-control text-nowrap" value="{{$meeting->start_url}}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Join URL: </label>
                        <input type="text" class="form-control" value="{{$meeting->join_url}}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Password: </label>
                        <input type="text" class="form-control" value="{{$meeting->password}}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Batch: </label>
                        <input type="text" class="form-control" value="{{$meeting->batch->name}}">
                    </div>
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
    </script>
@endpush
