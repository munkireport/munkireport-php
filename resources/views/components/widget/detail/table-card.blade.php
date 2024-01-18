<div class="col-lg-4"
     @isset($id)id="{{ $id }}"@endisset
>
    <div class="card shadow-sm">
        <div class="card-header">

                @isset($icon)
                    <i class="fa fa-{{ $icon }}"></i>
                @endisset
                <span @isset($i18n_title)data-i18n="{{ $i18n_title }}"@endisset
                @isset($class)class="{{ $class }}"@endisset
        ></span>
        </div>

        <div class="card-body" style="padding: 0">
    <table @isset($table_id) id="{{ $table_id }}"@endisset class="table">
        @isset($table)
            @foreach($table as $row)
            <tr>
                <th data-i18n="{{ $row['i18n_header'] }}"></th>
                <td>
                    @isset($row['prepend'])
                        {!! $row['prepend'] !!}
                    @endisset
                    <span class="{{ $row['class'] }}"></span>
                    @isset($row['append'])
                        {!! $row['append'] !!}
                    @endisset
                </td>
            </tr>
            @endforeach
        @endisset
    </table>
        </div>
    </div>
</div>

@push('scripts')
@isset($js)
    <script>
      {!! $js !!}
    </script>
@endisset
@isset($js_link)
    @foreach(is_array($js_link) ? $js_link : [$js_link] as $link)
    <script src="{{ url($link) }}"></script>
    @endforeach
@endisset
@endpush
