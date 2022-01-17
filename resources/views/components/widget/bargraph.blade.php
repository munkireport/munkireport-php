<div class="col-lg-6 col-md-6">
    <div class="card" id="{{ $id }}">
        <div
            class="card-header"
            @isset($tooltip)
            data-i18n="[title]{{ $tooltip }}"
            @endisset
        >
            <i class="fa {{ $icon }}"></i>
            <span data-i18n="{{ $title }}"></span>
            <span class="counter badge"></span>
            <a href="{{ $listingLink }}" class="pull-right text-reset"><i class="fa fa-list"></i></a>
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

        var widgetId = "{{ $id }}";
        var apiUrl = "{{ $apiUrl }}";
        var listingLink = "{{ $listingLink }}";
        var margin = {{ Illuminate\Support\Js::from($margin) }};
        var searchComponent = "{{ $searchComponent }}";
        var labelModifier = "{{ $labelModifier }}";

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

