@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>My Quizzes</h1>
        <a href="{{ route('teacher.quiz.create') }}" class="btn btn-primary">Create Quiz</a>
        <ul>
            @foreach($quizzes as $quiz)
                <li>{{ $quiz->title }}</li>
            @endforeach
        </ul>
    </div>
@endsection

@push('styles')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
@endpush

@push('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#zero-config').DataTable({
                // DataTable options can be added here
            });
        });
    </script>
    <!-- END PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
@endpush