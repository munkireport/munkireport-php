// Global munkireport object
var mr = {
        debug: false,
        dt:{},

        statusFormat: {
            install_failed: {type: 'danger'},
            install_succeeded: {type: 'success'},
            installed: {type: 'info'},
            pending_install: {type: 'warning'},
            pending_removal: {type: 'warning'},
            removed: {type: 'info'},
            uninstall_failed: {type: 'danger'},
            uninstalled: {type: 'success'}
        },

        // Graphing defaults
        graph: {
            barColor: ['steelBlue']
        },

        // Localstorage handler
        state: function(id, data){

            if( data == undefined)
            {
              // Get data
              return JSON.parse( localStorage.getItem(id) );

            }
            else
            {
              // Set data
              localStorage.setItem( id, JSON.stringify(data) );
            }
        },

        // Set Preference handler (uses localstorage)
        setPref: function(key, val){
            var globalPrefs = mr.state('global') || {};
            globalPrefs[key] = val;
            mr.state('global', globalPrefs);
        },

        // Get Preference handler (uses localstorage)
        getPref: function(key){
            var globalPrefs = mr.state('global') || {};
            return globalPrefs[key];
        },
        
        setHotKey: function(key, url){
          $(document).bind('keydown', key, function(){
              window.location = url;
              return true;
          });
        },

        // Integer or integer string OS Version to semantic OS version
        integerToVersion: function(osvers)
        {
        	osvers = "" + osvers
        	// If osvers contains a dot, don't convert
        	if( osvers.indexOf(".") == -1)
            {
        		// Remove non-numerical string
        		osvers = isNaN(osvers) ? "" : osvers;

        		// Left pad with zeroes if necessary
        		osvers = ("000000" + osvers).substr(-6)
        		osvers = osvers.match(/.{2}/g).map(function(x){return +x}).join('.')
            }
            return osvers
        },

        listingFilter: {
            filter: function(d, filters){
                for (var i = 0; i < filters.length; i++) {
                    var filterObj = filters[i];
                    if (typeof window[filterObj.filter] === "function"){
                        // Use filter from Global Space
                        window[filterObj.filter](filterObj.column, d);
                    }else if(this[filterObj.filter]){
                        this[filterObj.filter](filterObj.column, d);
                    }
                }
            },
            osFilter: function(col, d){
                // OS version
                if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                    var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                    d.search.value = search;
                }
            },
            columnNameFilter: function(col, d){
                var colData = d.columns[col];
                if(colData.name ==  d.search.value){
                    colData.search.value = '> 0';
                    d.search.value = '';
                }
            }
        },

        listingFormatter: {
            formatters: [],
            numFormatters: 0,
            tabLinks: [],
            setFormatters: function(formatters){
                this.formatters = formatters
                this.numFormatters = formatters.length
                this.tablink = this.initTabLinks()
            },
            initTabLinks: function(){
                var headers = $('th[data-colname]');
                for (var i = 0; i < headers.length; i++) {
                    var head = $(headers[i])
                    this.tabLinks.push(
                        head.data('tab-link') ? '#tab_'+head.data('tab-link') : ''
                    )
                }
            },
            getTabLink: function(col){
                return this.tabLinks[col]
            },
            format: function(row){
                if (this.numFormatters) {
                    for (var i = 0; i < this.numFormatters; i++) {
                        colFormatter = this.formatters[i]
                        if(this[colFormatter.formatter]){
                            this[colFormatter.formatter](colFormatter.column, row);
                        }else if (typeof window[colFormatter.formatter] === "function"){
                            // Use formatter from Global Space
                            window[colFormatter.formatter](colFormatter.column, row);
                        }
                    }
                }
            },
            clientDetail: function(col, row){
                // Update name in first column to link
                var cell=$('td:eq('+col+')', row);
                var name=cell.text() || "No Name";
                var sn=$('td:eq('+(col+1)+')', row).text();
                cell.html(
                    mr.getClientDetailLink(name, sn, this.getTabLink(col))
                );
            },
            timestampToMoment: function(col, row){
                var cell = $('td:eq('+col+')', row)
                var checkin = parseInt(cell.text());
                var date = new Date(checkin * 1000);
                cell.html('<span title="'+date+'">'+moment(date).fromNow()+'</span>');
            },
            binaryYesNo: function(col, row){
                var cell = $('td:eq('+col+')', row),
                    value = cell.text()
                value = value == '1' ? i18n.t('yes') :
                    (value === '0' ? i18n.t('no') : '')
	        	cell.text(value)
            },
            binaryEnabledDisabled: function(col, row){
                var cell = $('td:eq('+col+')', row),
                    value = cell.text()
                value = value == '1' ? '<span class="label label-success">'+i18n.t('enabled')+'</span>' :
                    (value === '0' ? '<span class="label label-danger">'+i18n.t('disabled')+'</span>' : '')
	        	cell.html(value)
            },
            fileSize: function(col, row){
                var cell = $('td:eq('+col+')', row)
                var fs=cell.text();
                cell.addClass('text-right').text(fileSize(fs, 0));
            },
            upperCase: function(col, row){
                var cell = $('td:eq('+col+')', row)
                cell.addClass('text-uppercase')
            },
            osVersion: function(col, row){
                // Format OS Version
                var cell = $('td:eq('+col+')', row),
	        	    osvers = cell.text();
	        	if( osvers > 0 && osvers.indexOf(".") == -1){
	        	     osvers = osvers.match(/.{2}/g).map(function(x){return +x}).join('.')
	        	}
	        	cell.text(osvers)
            }
        },

        // Get client detail link
        getClientDetailLink: function(name, sn, hash)
        {
        	hash = (typeof hash === "undefined") ? "" : hash;
        	return '<div class="machine">\
            		<a class="btn btn-default btn-xs" href="'+appUrl+'/clients/detail/'+sn+hash+'">'+name+'</a>\
            		<a href="'+appUrl+'/manager/delete_machine/'+sn+'" class="btn btn-xs btn-danger">\
            		<i class="fa fa-times"></i></a></div>';
        },

        /*
         * Natural Sort algorithm for Javascript - Version 0.8.1 - Released under MIT license
         * Author: Jim Palmer (based on chunking idea from Dave Koelle)
         */
        naturalSort: function(a, b) {
            var re = /(^([+\-]?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?(?=\D|\s|$))|^0x[\da-fA-F]+$|\d+)/g,
                sre = /^\s+|\s+$/g,   // trim pre-post whitespace
                snre = /\s+/g,        // normalize all whitespace to single ' ' character
                dre = /(^([\w ]+,?[\w ]+)?[\w ]+,?[\w ]+\d+:\d+(:\d+)?[\w ]?|^\d{1,4}[\/\-]\d{1,4}[\/\-]\d{1,4}|^\w+, \w+ \d+, \d{4})/,
                hre = /^0x[0-9a-f]+$/i,
                ore = /^0/,
                insensitive = true,
                i = function(s) {
                    return (insensitive && ('' + s).toLowerCase() || '' + s).replace(sre, '');
                },
                // convert all to strings strip whitespace
                x = i(a),
                y = i(b),
                // chunk/tokenize
                xN = x.replace(re, '\0$1\0').replace(/\0$/,'').replace(/^\0/,'').split('\0'),
                yN = y.replace(re, '\0$1\0').replace(/\0$/,'').replace(/^\0/,'').split('\0'),
                // numeric, hex or date detection
                xD = parseInt(x.match(hre), 16) || (xN.length !== 1 && Date.parse(x)),
                yD = parseInt(y.match(hre), 16) || xD && y.match(dre) && Date.parse(y) || null,
                normChunk = function(s, l) {
                    // normalize spaces; find floats not starting with '0', string or 0 if not defined (Clint Priest)
                    return (!s.match(ore) || l == 1) && parseFloat(s) || s.replace(snre, ' ').replace(sre, '') || 0;
                },
                oFxNcL, oFyNcL;
            // first try and sort Hex codes or Dates
            if (yD) {
                if (xD < yD) { return -1; }
                else if (xD > yD) { return 1; }
            }
            // natural sorting through split numeric strings and default strings
            for(var cLoc = 0, xNl = xN.length, yNl = yN.length, numS = Math.max(xNl, yNl); cLoc < numS; cLoc++) {
                oFxNcL = normChunk(xN[cLoc] || '', xNl);
                oFyNcL = normChunk(yN[cLoc] || '', yNl);
                // handle numeric vs string comparison - number < string - (Kyle Adams)
                if (isNaN(oFxNcL) !== isNaN(oFyNcL)) {
                    return isNaN(oFxNcL) ? 1 : -1;
                }
                // if unicode use locale comparison
                if (/[^\x00-\x80]/.test(oFxNcL + oFyNcL) && oFxNcL.localeCompare) {
                    var comp = oFxNcL.localeCompare(oFyNcL);
                    return comp / Math.abs(comp);
                }
                if (oFxNcL < oFyNcL) { return -1; }
                else if (oFxNcL > oFyNcL) { return 1; }
            }
        }, // End naturalSort

        // Draw graph for nvd3 and update graph
        drawGraph: function(conf){
            var graphData = [{"key": " ", "values": []}];
            d3.json(conf.url, function(data) {
                graphData[0].values = data;
                height = data.length * 26 + 40;
                conf.chart.height(height);

                d3.select(conf.svg)
                    .attr('height', height)
                    .datum(graphData)
                    .transition()
                    .duration(500)
                    .call(conf.chart);

                conf.chart.update();

            });

        },

        // Get preference for graph in this order: conf, default for graph, default
        getGraphPref: function(setting, graphName, conf){
            if(conf[setting]) return conf[setting];
            if(mr.graph[graphName] && mr.graph[graphName][setting]) return mr.graph[graphName][setting];
            return mr.graph[setting];
        },

        // Add nvd3 graph
        addGraph: function(conf){
            // Sanity check
            if( ! conf.widget){
                alert('no widget provided for addGraph');
                return;
            };

            // Assemble svg identifier
            conf.svg = '#' + conf.widget + ' svg';

            nv.addGraph(function() {
              conf.chart = nv.models.multiBarHorizontalChart()
                  .x(function(d) { return conf.labelModifier ? conf.labelModifier(d.label) : d.label })
                  .y(function(d) { return d.count })
                  .margin(conf.margin ? conf.margin : {top: 20, right: 10, bottom: 20, left: 70})
                  .showValues(true)
                  .valueFormat(d3.format(''))
                  .showControls(false)
                  .showLegend(false)
                  .barColor(mr.getGraphPref('barColor', conf.widget, conf))//conf.barColor ? conf.barColor : (graphSettings.barColor ? mr.graph.barColor))
                  .height(0);

              // Hide tooltips
              conf.chart.tooltip.enabled(false);

              conf.chart.yAxis
                  .tickFormat(d3.format(''));

              d3.select(conf.svg)
                  .attr('height', 0)
                  .datum([{"key": " ","values": []}])
                  .call(conf.chart);

            // Callback for click events
              if(conf.elementClickCallback){
                  // visit page on click
                  conf.chart.multibar.dispatch.on("elementClick", function(e) {
                      conf.elementClickCallback(e)
                  });

                  d3.select(conf.svg).attr("class", "clickLabels");
              }

                // Call the munkireport drawGraph routine
                mr.drawGraph(conf);

                // update chart data on appUpdate
                $(document).on('appUpdate', function(){mr.drawGraph(conf)});

                // update chart data on Resize
                nv.utils.windowResize(conf.chart.update);

            });

        },

        loadTheme: function() {
            // Get global state
            var theme = mr.getPref('theme') || default_theme;
            var theme_dir = baseUrl + 'assets/themes/' + theme + '/';
            var theme_file = theme_dir + 'bootstrap.min.css';
            $('#bootstrap-stylesheet').attr('href', theme_dir + 'bootstrap.min.css');
            $('#nvd3-override-stylesheet').attr('href', theme_dir + 'nvd3.override.css');

            // Add active to menu item
            $('[data-switch]').parent().removeClass('active');
            $('[data-switch="'+theme+'"]').parent().addClass('active');

            // Store theme in session
            $.post( appUrl + "/settings/theme", { set: theme });
        },

        sortMenu: function(menuIdentifier) {
            var $menu = $(menuIdentifier),
            	$itemsli = $menu.children('li');

            $itemsli.sort(function(a,b){
            	var an = $(a).find('a').text(),
            		bn = $(b).find('a').text();

            	if(an.toLowerCase() > bn.toLowerCase()) {
            		return 1;
            	}
            	if(an.toLowerCase() < bn.toLowerCase()) {
            		return -1;
            	}
            	return 0;
            });

            $itemsli.detach().appendTo($menu);
        }
    };
