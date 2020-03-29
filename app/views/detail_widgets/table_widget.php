<div 
    class="col-lg-4" 
    <?=isset($id) ? "id=\"$id\"" : ''?>
>
    <h4>
        <?=isset($icon) ? "<i class=\"fa fa-$icon\"></i>" : ''?>
        <span
            <?=isset($i18n_title) ? "data-i18n=\"$i18n_title\"" : ''?>
            <?=isset($class) ? "class=\"$class\"" : ''?>
        ></span>
    </h4>
    <table
        <?=isset($table_id) ? "id=\"$table_id\"" : ''?>
    >
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

<?php if(isset($js_link)):?>
  <?php foreach(is_array($js_link) ? $js_link : [$js_link] as $link):?>
  <script src="<?=url($link)?>"></script>
  <?php endforeach?>
<?php endif?>
