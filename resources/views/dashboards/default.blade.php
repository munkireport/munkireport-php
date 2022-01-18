@extends('layouts.mr')

@push('scripts')
    <script src="{{ asset('assets/js/munkireport.autoupdate.js') }}"></script>
@endpush

@section('content')
    <div class="container-fluid">

        @foreach($dashboard_layout as $row)
            <div class="row pt-4">
                @foreach($row as $name => $data)
                    @php
                        list($component, $mergedData) = app(\munkireport\lib\Widgets::class)->getComponent($name);
                    @endphp
                    <x-dynamic-component :component="$component" :name="$name" :data="$mergedData"></x-dynamic-component>
                @endforeach
            </div>
        @endforeach

    </div>
@endsection
