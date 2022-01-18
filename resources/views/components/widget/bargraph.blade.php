<div class="col-lg-6 col-md-6">
    <div class="card" id="{{ $widget_id }}">
        <div
                class="card-header"
                @isset($i18n_tooltip)
                data-i18n="[title]{{ $i18n_tooltip }}"
                @endisset
        >
            <i class="fa {{ $icon }}"></i>
            <span data-i18n="{{ $i18n_title }}"></span>
            <span class="counter badge"></span>
            <a href="{{ $listing_link }}" class="pull-right text-reset"><i class="fa fa-list"></i></a>
        </div>
        <div class="card-body">
            <svg style="width:100%"></svg>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script type="text/javascript">
      $(document).on('appReady', function() {

        var widgetId = "{{ $widget_id }}";
        var apiUrl = "{{ $api_url }}";
        var listingLink = "{{ $listing_link }}";
        var margin = {{ Illuminate\Support\Js::from($margin) }};
        var searchComponent = "{{ isset($search_component) }}";
        var labelModifier = "{{ $label_modifier ?? '' }}";

        var conf = {
          url: appUrl + apiUrl, // Url for json
          widget: widgetId, // Widget id
          margin: margin,
        };

        if (searchComponent) {
            conf.elementClickCallback = function (e) {
              var label = e.data.label;
              window.location.href = appUrl + listingLink + '#' + searchComponent;
            };
        }

        if (labelModifier) {
          conf.labelModifier = function (label) {
            return labelModifier;
          }
        }

        mr.addGraph(conf);

      });
    </script>
    @endpush
@endonce

