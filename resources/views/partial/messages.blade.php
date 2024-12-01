@if ($errors->any())
    @foreach ($errors->all() as $error)
    {{-- {{dd($error)}} --}}
        <script>
            danger_notification('{{ $error }}');
        </script>
    @endforeach
@endif

@if (session('success'))
    <script>
        success_notification('{{ session('success') }}');
    </script>
@endif

@if (session('error'))
    <script>
        danger_notification('{{ session('error') }}');
    </script>
@endif
