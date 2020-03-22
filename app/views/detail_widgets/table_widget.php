<div class="col-lg-4">
    <h4 
        <?=$i18n_title ? "data-i18n=\"$i18n_title\"" : ''?>
        <?=$class ? "class=\"$class\"" : ''?>
    ></h4>
    <table>
        <?php foreach($table as $row):?>
        <tr>
            <th data-i18n="<?=$row['i18n_header']?>"></th>
            <td>
                <?php if(isset($row['prepend'])):?>
                <?=$row['prepend']?>
                <?php endif?>
                <span class="<?=$row['class']?>"></span>
                <?php if(isset($row['append'])):?>
                <?=$row['append']?>
                <?php endif?>
            </td>
        </tr>
        <?php endforeach?>
    </table>
</div>
