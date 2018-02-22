/*!
 * Comments for MunkiReport
 * requires bootstrap-markdown.js (http://github.com/toopay/bootstrap-markdown)
 */
$(document).on('appReady', function(e, lang) {

	try{
		if(serialNumber){}
	}
	catch(e){
		alert('Error: comment.js - No serialNumber');
		return;
	}

	//Initialize marked library
	var markdown_parser = marked;
	markdown_parser.setOptions({
	  renderer: new marked.Renderer(),
	  gfm: true,
	  tables: true,
	  breaks: true,
	  pedantic: false,
	  sanitize: true,
	  smartLists: true,
	  smartypants: false
	});

	var addComment = function(){

			var section = $(this).data('section'),
				commentdiv = $(this).prev(),
				editor = '',
				saveComment = function(){

					// add parsed text to hidden field
					var html = editor.parseContent();
					$('#myModal input[name="html"]').val(html);

					// Get formdata
					var formData = $('#myModal form').serializeArray();

					// Save comment
					var jqxhr = $.post( appUrl + "/module/comment/save", formData);

					jqxhr.done(function(data){

						// Update comment in page
						commentdiv.html(html);

						// Dismiss modal
						$('#myModal').modal('hide');
					})

				}

			$('#myModal .modal-body')
				.empty()
				.append($('<form>')
					.submit(saveComment)
					.append($('<input>')
						.attr('type', 'submit')
						.addClass('invisible'))
					.append($('<input>')
						.attr('type', 'hidden')
						.attr('name', 'serial_number')
						.val(serialNumber))
					.append($('<input>')
						.attr('type', 'hidden')
						.attr('name', 'section')
						.val(section))
					.append($('<input>')
						.attr('type', 'hidden')
						.attr('name', 'html'))
					.append($('<div>')
						.addClass('form-group')
						.append($('<label>')
							.text(i18n.t("dialog.comment.label")))
						.append($('<textarea>')
							.attr('name', 'text')
							.attr('rows', 10)
							.addClass('form-control'))));

			$.getJSON( appUrl + '/module/comment/retrieve/' + serialNumber + '/' + section, function( data ) {
				data.text = data.text || ''
				$('textarea').text(data.text)
				$('textarea').markdown({
					parser: markdown_parser,
					autofocus:false,
					savable:false,
					iconlibrary: 'fa',
					fullscreen:{enable:false},
					onShow: function(e){
						// Store a reference to the editor
						editor = e;
					}
				});
			});


			$('#myModal button.ok')
				.text(i18n.t("dialog.save"))
				.off()
				.click(saveComment);
			$('#myModal .modal-title').text(i18n.t("dialog.comment.add"));
			$('#myModal').modal('show');
		}

	// Comment toggle
	$('.comment-toggle')
		.empty()
		.off()
		.addClass('btn btn-default btn-sm pull-right')
		.click(function(){
			$($(this).data('target')).toggleClass('hide');
		})
		.append($('<i>')
			.addClass('fa fa-comment'))

	// If comments on this page, get comment data
	$('div.comment')
		.empty()
		.append(function(){
			var me = $(this),
				section = $(this).data('section');
			$.getJSON( appUrl + '/module/comment/retrieve/' + serialNumber + '/' + section, function( data ) {
				data.html = data.html || 'No comments'
				me.html(data.html)
					.after($('<button>')
						.addClass('btn btn-default hidden-print')
						.data('section', section)
						.click(addComment)
						.text('Edit'))
			});

		});

});
