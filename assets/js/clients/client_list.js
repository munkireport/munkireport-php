$(function(){
	var clientInfoPopovers = [],
		popoverOptions = {
			title: 'Machine Data',
			content: loadPopoverContent
		};

	$("span[data-serialnumber]").popover({
		title: function(){
			var id = $.now();
			return "<div id='pop-title-" + id + "'>Title</div>";
		},
		html: true,
		content: function() {
			var id = $.now();
			return loadPopoverContent(id, $(this).data("serialnumber"));
		}
	});




	function loadPopoverContent(id, serialNumber)
	{
		var div = $("<div></div>")
			.attr("id", id)
			.text("Loading...")
			.css({
				"height": 225,
				"width": 250
			});

		$.ajax({
			url: window.location.pathname + "/detail/" + serialNumber + ".json",
			success: function(response) {
				formatResponse(response, id);
			},
			error: function(response) {
				$("#" + id).html("<span class='text-error'>Error fetching data</span>");
			}
		})
		return div;
	}




	function formatResponse(response, divId)
	{
		var $div = $("#" + divId),
			$title = $("#pop-title-" + divId);
		$div.empty();
		$title.empty();

		if (Object.prototype.toString.call(response) != '[object Object]')
		{
			$div.text("Error parsing response from server");
			return;
		}

		var img = $("<center>").append($("<img>")
						.attr('src', response.meta.iconURL)
						.css("height", "100px"),
					$("<hr>")
				),
			cpu = generateReportItem(
					"Processor",
					response.machine.current_processor_speed
						+ " " + response.machine.cpu_arch
				),
			ram = generateReportItem("Memory",
				response.machine.physical_memory),
			ser = generateReportItem("Serial Number",
				response.machine.serial_number),
			osx = generateReportItem("Software",
				"OS X " + response.machine.os_version);

		$title.text(response.machine.machine_desc);
		$div.append(img, cpu, ram, ser, osx);
	}




	function generateReportItem(title, value)
	{
		return $("<div></div>").append(
			$("<b></b>").text(title + " "),
			$("<span></span>").text(value)
		);
	}
});