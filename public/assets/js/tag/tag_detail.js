$(document).on('appReady', function(e, lang) {

	var currentTags = {}
		tagsRetrieved = false;
	$('.machine-refresh-desc')
		.after($('<div>').append($('<select>')
			.addClass('tags')
			.attr("data-role", "tagsinput")
			.attr("multiple", "multiple"))
			);

	// instantiate the bloodhound suggestion engine
	var hotDog = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		prefetch: {
			url: appUrl + '/module/tag/all_tags',
			cache: false
		}
	});

	// initialize the bloodhound suggestion engine
	hotDog.initialize();

	// Activate tags input
	$('select.tags').tagsinput({
		typeaheadjs: {
		  source: hotDog.ttAdapter()
		}
	});

	// Fix bug in tagsinput/typeahead that shows the last tag on blur
	$('input.tt-input').on('blur', function(){$(this).val('')})

	// Get current tags
	$.getJSON( appUrl + '/module/tag/retrieve/' + serialNumber, function( data ) {
		// Set item value
		if(data.length == 0){
			// Show 'Add tags button'
		}
		else{
			// Show 'Edit tags button'
		}
		$.each(data, function(index, item){
			$('select.tags').tagsinput("add", item.tag)
			// Store tag id
			currentTags[item.tag] = item.id;
		});
		// Signal tags retrieved
		tagsRetrieved = true;
	});

	// Now add event handlers
	$('select.tags')
		.on('itemAdded', function(event) {
			// Check if tags are retrieved
			if(!tagsRetrieved){
				return;
			}

			// Save tag
			formData = {serial_number: serialNumber, tag: event.item};
			var jqxhr = $.post( appUrl + "/module/tag/save", formData);

			jqxhr.done(function(data){
				if(data.error){
					alert(data.error)
				}
				else {
					// Store id in currentTags
					currentTags[data.tag] = data.id;
				}

			})
		}).on('itemRemoved', function(event) {
			var id = currentTags[event.item]
			var jqxhr = $.post( appUrl + "/module/tag/delete/"+serialNumber+"/"+id);

			jqxhr.done(function(data){
				if(data.error){
					alert(data.error)
				}
				else {
					// remove tag from currentTags
					delete currentTags[event.item];
				}
			})
		});
});