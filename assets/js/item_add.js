function item_selected(value) {
    
    var isChecked = $('#item_'+value).is(':checked');
    if (isChecked) {
        $('#item_price_'+value).prop('disabled', false);
        
    } else {
        $('#item_price_'+value).val('');
        $('#item_price_'+value).prop('disabled', true);
    }
}