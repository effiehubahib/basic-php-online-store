// The button to increment the product value
$(document).on('click', '.product_quantity_up', function(e){
	var allowBuyWhenOutOfStock = false;
	var quantityAvailable = $(this).data('maxval');
	
	e.preventDefault();
	fieldName = $(this).data('field-qty');
	var currentVal = parseInt($('input[name='+fieldName+']').val());
	if (!allowBuyWhenOutOfStock && quantityAvailable > 0)
		quantityAvailableT = quantityAvailable;
	else
		quantityAvailableT = 100000000;
	if (!isNaN(currentVal) && currentVal < quantityAvailableT)
		$('input[name='+fieldName+']').val(currentVal + 1).trigger('keyup');
	else
		$('input[name='+fieldName+']').val(quantityAvailableT);

	$('#quantity_wanted').change();
});
 // The button to decrement the product value
$(document).on('click', '.product_quantity_down', function(e){
	e.preventDefault();
	fieldName = $(this).data('field-qty');
	var currentVal = parseInt($('input[name='+fieldName+']').val());
	if (!isNaN(currentVal) && currentVal > 1)
		$('input[name='+fieldName+']').val(currentVal - 1).trigger('keyup');
	else
		$('input[name='+fieldName+']').val(1);

	$('#quantity_wanted').change();
});