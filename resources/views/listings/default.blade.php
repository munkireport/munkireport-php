@extends('layouts.mr')

@push('scripts')
    @isset($js)
        <script type="text/javascript">
          {{ $js }}
        </script>
    @endisset
    @isset($js_link)
        @if(is_array($js_link))
            @foreach($js_link as $src)
                <script src="{{ mr_url($src) }}"></script>
            @endforeach
        @else
            <script src="{{ mr_url($js_link) }}"></script>
        @endif
    @endisset
    <script type="text/javascript">

      $(document).on('appUpdate', function(e){
        var oTable = $('.table').DataTable();
        oTable.ajax.reload();
        return;
      });

      $(document).on('appReady', function(e, lang) {
        var columnDefs = [],
          mySort = [],
          columnFormatters = [],
          columnFilters = [],
          col = 0; // Column counter

          @isset($js_init)
          {{ $js_init }}
          @endisset

          $('.table th').map(function(){
            columnDefs.push({
              name: $(this).data('colname'),
              targets: col,
              visible: ! $(this).data('hide'),
              render: $.fn.dataTable.render.text()
            });
            if($(this).data('formatter')){
              columnFormatters.push({
                column: col,
                formatter: $(this).data('formatter')
              })
            }
            if($(this).data('filter')){
              columnFilters.push({
                column: col,
                filter: $(this).data('filter')
              })
            }
            if($(this).data('sort')){
              mySort.push([col, $(this).data('sort')])
            }
            col++;
          });
        mr.listingFormatter.setFormatters(columnFormatters);
        oTable = $('.table').dataTable( {
          columnDefs: columnDefs,
          ajax: {
            url: appUrl + '/datatables/data',
            type: "POST",
            data: function(d){
                @isset($not_null_column)
                  d.mrColNotEmpty = "{{ $not_null_column }}";
                @endisset
                mr.listingFilter.filter(d, columnFilters);
            }
          },
          order: mySort,
          dom: mr.dt.buttonDom,
          buttons: mr.dt.buttons,
          createdRow: function( nRow, aData, iDataIndex ) {
            mr.listingFormatter.format(nRow);
          }
        });
      });
    </script>
@endpush


@section('content')
<div class="container-fluid">
  <div class="row pt-4">
      <div class="col">
          <h3><span data-i18n="{{ $i18n_title }}"></span> <span id="total-count" class='badge badge-primary'>…</span></h3>

          <table class="table table-striped table-sm table-bordered">
            <thead>
              <tr>
                @foreach($table as $header)
                  <th
                      @isset($header['column'])
                          data-colname="{{ $header['column'] }}"
                      @endisset

                      @isset($header['sort']))
                        data-sort="{{ $header['sort'] }}"
                      @endisset
                      @isset($header['i18n_header']))
                        data-i18n="{{ $header['i18n_header'] }}"
                      @endisset
                      @isset($header['i18n-options']))
                        data-i18n-options='{{ $header['i18n-options'] }}'
                      @endisset
                      @isset($header['formatter']))
                        data-formatter="{{ $header['formatter'] }}"
                      @endisset
                      @isset($header['tab_link']))
                        data-tab-link="{{ $header['tab_link'] }}"
                      @endisset
                      @isset($header['filter']))
                        data-filter="{{ $header['filter'] }}"
                      @endisset
                      @isset($header['hide']))
                        class="hidden"
                      @endisset
                  ></th>
                @endforeach
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-i18n="listing.loading" colspan="{{ count($table) }}" class="dataTables_empty"></td>
                </tr>
            </tbody>
          </table>
    </div>
  </div>
</div>
@endsection
