<div id="MODULE-tab"></div>
<h2 data-i18n="MODULE.title"></h2>

<table>
    <tr>
        <th>Item 1</th>
        <td id="MODULE_item1"></td>
    </tr>
    <tr>
        <th>Item 2</th>
        <td id="MODULE_item2"></td>
    </tr>
</table>

<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/MODULE/get_data/' + serialNumber, function(data){
        $('#MODULE_item1').text(data['item1'])
        $('#MODULE_item2').text(data['item2'])
    });
});
</script>
