<div class="col-lg-4"
     @isset($id)id="{{ $id }}"@endisset
>
    <h4 class="alert alert-info">
        @isset($icon)
            <i class="fa fa-{{ $icon }}"></i>
        @endisset
        <span
        @isset($i18n_title)data-i18n="{{ $i18n_title }}"@endisset
        @isset($class)class="{{ $class }}"@endisset
        ></span>
    </h4>
    <div class="comment" data-section="client"></div>
</div>


@push('scripts')
    <script src="{{ asset('assets/js/marked.min.js') }}"></script>
    <script src="{{ asset('assets/js/munkireport.comment.js') }}"></script>
@endpush
