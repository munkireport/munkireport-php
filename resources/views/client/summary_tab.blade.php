<div class="row">
    <!-- widget 3xN -->
    @foreach($widget_list as $widget_id => $data)
        <!-- {{ $widget_id }} -->
        @php
            list($component, $mergedData) = app(\munkireport\lib\Widgets::class)->getDetailComponent($data);
        @endphp
        <x-dynamic-component :component="$component" :name="$name" :data="$mergedData"></x-dynamic-component>
    @endforeach
</div>
