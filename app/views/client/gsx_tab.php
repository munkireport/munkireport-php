<?php //Initialize models needed for the table
$gsx_obj = new gsx_model($serial_number);
?>

<script>
   // $(document).on('appReady', function(e, lang) {
        //$.getJSON(appUrl + '/module/gsx/get_gsx_data/' + serialNumber, function(data){

          //  gsxData = data[0];

            // Set properties based on id
            //$.each(gsxData, function(prop, val){
              //  $('.mr-'+prop).html(val);
        //    });
            
        //});
    //}
</script>

<h2>GSX <a data-i18n="gsx.recheck" class="btn btn-default btn-xs" href="<?php echo url('module/gsx/recheck_gsx/' . $serial_number);?>"></a></h2>

        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width:220px" data-i18n="warranty.coverage"></td>
                    <td><?=$gsx_obj->warrantystatus?></td>
                    <td style="width:220px" data-i18n="gsx.warrantydays"></td>
                    <td><?=$gsx_obj->daysremaining?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.coverage.start"></td>
                    <td><?=$gsx_obj->coveragestartdate?></td>
                    <td data-i18n="gsx.modeldescription"></td>
                    <td><?=$gsx_obj->productdescription?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.coverage.end">Coverage End Date</td>
                    <td><?=$gsx_obj->coverageenddate?></td>
                    <td data-i18n="gsx.configuration"></td>
                    <td><?=$gsx_obj->configdescription?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.estpurchasedate"></td>
                    <td><?=$gsx_obj->estimatedpurchasedate?></td>
                    <td data-i18n="gsx.laborcovered"></td>
                    <td><?=$gsx_obj->laborcovered?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.coverage.constart"></td>
                    <td><?=$gsx_obj->contractcoveragestartdate?></td>
                    <td data-i18n="gsx.partscovered"></td>
                    <td><?=$gsx_obj->partcovered?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.coverage.conend"></td>
                    <td><?=$gsx_obj->contractcoverageenddate?></td>
                    <td data-i18n="gsx.contracttype"></td>
                    <td><?=$gsx_obj->contracttype?></td>
                </tr>
                    <tr>
                    <td data-i18n="reg_date"></td>
                    <td><?=$gsx_obj->registrationdate?></td>
                    <td data-i18n="gsx.country"></td>
                    <td><?=$gsx_obj->purchasecountry?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.sla"></td>
                    <td><?=$gsx_obj->warrantyreferenceno?></td>
                    <td data-i18n="gsx.loaner"></td>
                    <td><?=$gsx_obj->isloaner?></td>
                </tr>   
                    <tr>
                    <td data-i18n="gsx.vintage"></td>
                    <td><?=$gsx_obj->isvintage?></td>
                    <td data-i18n="gsx.obsolete"></td>
                    <td><?=$gsx_obj->isobsolete?></td>
                </tr>                   
             </tbody>
        </table>
