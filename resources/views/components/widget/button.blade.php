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
        <div class="card-body text-center"></div>
    </div>
</div>

@once
    @push('scripts')
    <script>
      $(document).on('appUpdate', function(e, lang) {

        var apiUrl = "{{ $api_url }}";
        var widgetId = "{{ $widget_id }}";
        var listingLink = "{{ $listing_link }}";
        var i18nEmptyResult = "{{ isset($i18n_empty_result) ? $i18n_empty_result : '' }}"

        var body = $('#' + widgetId + ' div.card-body');

        $.getJSON( appUrl + apiUrl, function( data ) {

          // Clear previous content
          body.empty();

          var buttons = {{ Illuminate\Support\Js::from($buttons) }};
          var buttons_rendered = 0;

          // Calculate entries
          if(data.length){

            // render
            $.each(buttons, function(i, o){
              var button = data.find(x => String(x.label) === String(o.label));
              var count = button ? button.count : 0;

              // Hide when count is zero
              if( o.hide_when_zero && count == 0){
                return;
              }

              buttons_rendered = buttons_rendered + 1;

              // Use localized label
              if( o.i18n_label){
                label = i18n.t(o.i18n_label, { count: +count });
              }else{
                label = o.label;
              }

              // Set default class to btn-info
              o.class = o.class ? o.class : 'btn-info';

              // Search component
              if( o.search_component ){
                searchComponent = '/#'+encodeURIComponent(o.search_component);
              }else{
                searchComponent = '';
              }

              body.append(
                $('<a>')
                  .attr('href', appUrl+listingLink+searchComponent)
                  .css('min-width', '70px')
                  .addClass("btn " + o.class)
                  .toggleClass("disabled", count == 0)
                  .addClass(function(){ return count ? '' : 'disabled'})
                  .append(
                    $('<span>')
                      .addClass('bigger-150')
                      .text(count)
                  )
                  .append('<br>')
                  .append(document.createTextNode(label))
              ).append(' ')
            });
          }
          if(buttons_rendered == 0){
            if (i18nEmptyResult){
              body.append(i18n.t(i18nEmptyResult));
            }else{
              body.append(i18n.t('no_clients'));
            }
          }
        });
      });
    </script>
    @endpush
@endonce
