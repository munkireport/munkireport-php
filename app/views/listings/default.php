<?php $this->view('partials/head'); ?>

<div class="container">

  <div class="row">

      <div class="col-lg-12">
        
          <h3><span data-i18n="<?=$i18n_title?>"></span> <span id="total-count" class='label label-primary'>…</span></h3>

          <table class="table table-striped table-condensed table-bordered">
            <thead>
              <tr>
                <?php $colCounter = 0?>
                <?php foreach($table as $header):?>
                  <?php $colCounter++?>
                  <th 
                  <?php if(isset($header['column'])):?>
                      data-colname="<?=$header['column']?>" 
                    <?php endif?>
                    <?php if(isset($header['sort'])):?>
                      data-sort="<?=$header['sort']?>" 
                    <?php endif?>
                    <?php if(isset($header['i18n_header'])):?>
                      data-i18n="<?=$header['i18n_header']?>" 
                    <?php endif?>
                    <?php if(isset($header['i18n-options'])):?>
                      data-i18n-options='<?=$header['i18n-options']?>' 
                    <?php endif?>
                    <?php if(isset($header['formatter'])):?>
                      data-formatter="<?=$header['formatter']?>" 
                    <?php endif?>
                    <?php if(isset($header['tab_link'])):?>
                      data-tab-link="<?=$header['tab_link']?>" 
                    <?php endif?>
                    <?php if(isset($header['filter'])):?>
                      data-filter="<?=$header['filter']?>" 
                    <?php endif?>
                    <?php if(isset($header['hide'])):?>
                      class="hidden" 
                    <?php endif?>
                  ></th>
                <?php endforeach?>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-i18n="listing.loading" colspan="<?$colCounter?>" class="dataTables_empty"></td>
                </tr>
            </tbody>
          </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?php if(isset($js)):?>
<script type="text/javascript">
<?=$js?>
</script>
<?php endif?>

<?php if(isset($js_link)):?>
  <script src="<?=url($js_link)?>"></script>
<?php endif?>


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
        
        <?php if(isset($js_init)):?>
        <?=$js_init?>;
        <?php endif?>

        $('.table th').map(function(){
            columnDefs.push({
              name: $(this).data('colname'), 
              targets: col,
              visible: ! $(this).data('hide')
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
                  <?php if(isset($not_null_column)):?>
                     d.mrColNotEmpty = "<?=$not_null_column?>";
                  <?php endif?>
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

<?php $this->view('partials/foot'); ?>
