@extends('teacher.layouts.app')
@section('breadcrumbs')
<div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('teacher.studyMaterial.index')}}">StudyMaterial</a></li>
            <li class="breadcrumb-item active" aria-current="page">StudyMaterial Edit</li>
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
                            <h4>Edit StudyMaterial</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form method="post" action="{{ route('teacher.studyMaterial.update',$studyMaterial) }}" name="form" class="row g-3" id="studyMaterialEditForm" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" id="title" value="{{$studyMaterial->title}}">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="visibility" class="form-label">Visibility</label>
                            <select class="form-select" id="visibility" name="visibility">
                                <option value="public" @if($studyMaterial->visibility=='public') selected @endif>Public</option>
                                <option value="private" @if($studyMaterial->visibility=='private') selected @endif>Private</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            {{-- <input type="text" name="email" class="form-control" id="email"> --}}
                            <textarea name="description" class="form-control" id="description">{{$studyMaterial->description}}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="select-state" class="form-label">Select Batches</label>
                            <select class="form-select form-select" id="select-state" name="batch_ids[]" multiple placeholder="Select a cateogry..." autocomplete="off" class="form-select">
                                @php 
                                $selectedBatches = $studyMaterial->batches->pluck('batch_id')->toArray();
                                @endphp
                                @foreach($batches as $batch)
                                    <option value="{{$batch->id}}" @if(in_array($batch->id,$selectedBatches)) selected @endif>{{$batch->name}}</option>
                                @endforeach
                            </select>
                        </div>

                         <div class="col-md-4">
                            <label for="file" class="form-label">File</label>
                            <input type="file" name="files[]" class="form-control" id="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" multiple>
                            <div id="file-previews"></div>
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
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
@endpush
@push('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <script>
         new TomSelect("#select-state",{
            plugins: ['remove_button'],
            create: false,
            searchField: ['text'],
        });
        $(document).ready(function() {
            $('#studyMaterialEditForm').validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        minlength: 6
                    },
                    'batch_ids[]': {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter the studyMaterial's name"
                    },
                    email: {
                        required: "Please enter the studyMaterial's email",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        minlength: "Your password must be at least 6 characters long"
                    },
                    'batch_ids[]': {
                        required: "Please select at least one batch"
                    }
                },
                errorPlacement: function(error, element) {
                    error.css('color', 'red');
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
            $('#file').on('change', function(event) {
                var files = event.target.files;
                $('#file-previews').empty(); // Clear previous previews

                for (var i = 0; i < files.length; i++) {
                    (function(file) {
                        var reader = new FileReader();
                        
                        reader.onload = function(e) {
                            var previewHtml = '';
                            console.log(file.type);

                            if (file.type.match('image.*')) {
                                previewHtml = '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 100px; max-height: 100px; margin: 10px;">';
                            } else {
                                previewHtml = '<p>' + file.name + '</p>';
                            }

                            $('#file-previews').append(previewHtml);
                        };

                        if (file.type.match('image.*')) {
                            reader.readAsDataURL(file);
                        } else {
                            reader.onload(); // Trigger the onload manually for non-image files
                        }
                    })(files[i]);
                }
            });
        });
    </script>
@endpush
