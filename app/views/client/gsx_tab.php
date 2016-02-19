<?php //Initialize models needed for the table
$gsx_obj = new gsx_model($serial_number);
?>

    <h2>GSX <a data-i18n="gsx.recheck" class="btn btn-default btn-xs" href="<?php echo url('module/gsx/recheck_gsx/' . $serial_number);?>"></a></h2>

        <table class="table table-striped table-bordered">
            <tbody>
                <tr">
                    <td style="width:220px" data-i18n="warranty.coverage"></td>
                    <td><?=$gsx_obj->warrantyStatus?></td>
                    <td style="width:220px" data-i18n="gsx.warrantydays"></td>
                    <td><?=$gsx_obj->daysRemaining?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.coverage.start"></td>
                    <td><?=$gsx_obj->coverageStartDate?></td>
                    <td data-i18n="gsx.modeldescription"></td>
                    <td><?=$gsx_obj->productDescription?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.coverage.end">Coverage End Date</td>
                    <td><?=$gsx_obj->coverageEndDate?></td>
                    <td data-i18n="gsx.configdesc"></td>
                    <td><?=$gsx_obj->configDescription?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.estpurchasedate"></td>
                    <td><?=$gsx_obj->estimatedPurchaseDate?></td>
                    <td data-i18n="gsx.laborcovered"></td>
                    <td><?=$gsx_obj->laborCovered?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.coverage.constart"></td>
                    <td><?=$gsx_obj->contractCoverageStartDate?></td>
                    <td data-i18n="gsx.partscovered"></td>
                    <td><?=$gsx_obj->partCovered?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.coverage.conend"></td>
                    <td><?=$gsx_obj->contractCoverageEndDate?></td>
                    <td data-i18n="gsx.contracttype"></td>
                    <td><?=$gsx_obj->contractType?></td>
                </tr>
                    <tr>
                    <td data-i18n="reg_date"></td>
                    <td><?=$gsx_obj->registrationDate?></td>
                    <td data-i18n="gsx.country"></td>
                    <td><?=$gsx_obj->purchaseCountry?></td>
                </tr>
                    <tr>
                    <td data-i18n="gsx.sla"></td>
                    <td><?=$gsx_obj->warrantyReferenceNo?></td>
                    <td data-i18n="gsx.loaner"></td>
                    <td><?=$gsx_obj->isLoaner?></td>
                </tr>   
                    <tr>
                    <td data-i18n="gsx.vintage"></td>
                    <td><?=$gsx_obj->isVintage?></td>
                    <td data-i18n="gsx.obsolete"></td>
                    <td><?=$gsx_obj->isObsolete?></td>
                </tr>                   
             </tbody>
        </table>
