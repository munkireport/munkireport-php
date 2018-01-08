/*!
 * Storageplot for MunkiReport
 * requires nv.d3.js (https://github.com/novus/nvd3)
 */
var drawStoragePlots = function(serialNumber, divid) {

	// Get storage data
    var url = appUrl + '/module/disk_report/get_data/'+serialNumber
    var chart;
    d3.json(url, function(err, data){

	    var height = 300;
	    var width = 250;
	    var siFormat = d3.format('0.2s');
	    var format = function(d){ return siFormat(d) + 'B'}

	    $.each(data, function(index, obj){

	    	var id = 'storage-plot-'+index
	    	var row = d3.select("#"+divid)
	    		.append('div')
	    		.attr('class', 'row')

	    	d3.select("#"+divid).append('hr')

	    	var info = row.append('div')
	    					.attr('class', 'col-sm-6')

	    	info.append('h4')
	    		.text(obj.volumename)

	    	var table = info.append('table')
	    					.attr('class', 'table table-striped')
	    					.append('tbody')

	    	// get encryption string
	    	var encrypted = obj.encrypted == 1 ? i18n.t('disk_report.encrypted') : i18n.t('disk_report.not_encrypted');
	    	if(obj.encrypted == -1)
	    	{
	    		encrypted = i18n.t('unknown')
	    	}

	    	var props = [
	    		{
	    			key: i18n.t('disk_report.mountpoint'),
	    			val: obj.mountpoint
	    		},
                {
	    			key: i18n.t('disk_report.media_type'),
	    			val: obj.media_type && obj.media_type.toUpperCase()
	    		},
                {
	    			key: i18n.t('disk_report.volume_type'),
	    			val: obj.volumetype.toUpperCase()
	    		},
	    		{
	    			key: i18n.t('disk_report.smartstatus'),
	    			val: obj.smartstatus
	    		},{
	    			key: i18n.t('disk_report.bus_protocol'),
	    			val: obj.busprotocol
	    		},{
	    			key: i18n.t('disk_report.type'),
	    			val: obj.internal == 1 ? i18n.t('disk_report.internal') : i18n.t('disk_report.external')
	    		},
	    		{
	    			key: i18n.t('disk_report.encryption_status'),
	    			val: encrypted
	    		}];

	    	var tr = table.selectAll("tr")
						    .data(props)
							.enter().append("tr")
							.html(function(d) { return '<th>'+d.key+'</th><td>'+d.val+'</td>'; })


	    	row.append('div')
	    			.attr('class', 'col-sm-6')
	    				.append('svg')
	    				.attr('id', id)
	    				.attr('class', 'pull-right')


		   	// Filter data
		   	var fill = [
		   		{
		   			key: i18n.t('disk_report.used'),
		   			cnt: obj.totalsize - obj.freespace
		   		},
			    {
			    	key: i18n.t('disk_report.free'),
			    	cnt:obj.freespace
			    }];

			nv.addGraph(function() {
				var chart = nv.models.pieChart()
				    .x(function(d) { return d.key })
				    .y(function(d) { return d.cnt })
				    .showLabels(true)
				    .valueFormat(format)
				    .donut(true)
				    .labelsOutside(true)
				    .labelType('value');

				chart.title(format(obj.totalsize));

				//chart.pie.donut(true);

				d3.select("#"+id)
					.style('height', height)
				    .style('width', width)
				    .datum(fill)
				    .transition().duration(1200)
				    .call(chart);

                // Remove fill from labels
                d3.selectAll('#'+id+' .nv-pieLabels text').style('fill', null);

				return chart;

		    });

	    });



	});
};
