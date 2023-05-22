@extends('layouts.mr')

@push('stylesheets')
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/me/profile.js') }}"></script>
@endpush

@section('content')
    <div class="container">
        <div class="row pt-4">
            <div class="col">
                <h3>User Profile</h3>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-12">
                <form id="profileForm">
                    <div class="form-group">
                        <label for="profileLocaleSelect">Language</label>
                        <select class="form-control" id="profileLocaleSelect">
                            @foreach(scandir(PUBLIC_ROOT.'assets/locales') AS $list_url)
                                @if( strpos($list_url, 'json'))
                                    @php $lang = strtok($list_url, '.'); @endphp
                                    <option @if ($lang === $locale)
                                                selected="selected"
                                            @endif
                                                value="{{ $lang }}"
                                                data-i18n="nav.lang.{{ $lang }}">{{ $lang }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="profileThemeSelect">Theme</label>
                        <select class="form-control" id="profileThemeSelect">
                            @foreach(scandir(PUBLIC_ROOT.'assets/themes') AS $theme)
                                @if( $theme != 'fonts' && strpos($theme, '.') === false)
                                    <option @if ($theme == $current_theme)
                                                selected="selected"
                                            @endif
                                                value="{{ $theme }}"
                                                data-switch="{{ $theme }}">{{ $theme }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>


@endsection

