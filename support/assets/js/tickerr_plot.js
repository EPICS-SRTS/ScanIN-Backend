function TickerrPlot() {
	this.labels = null;
	this.blabels = null;
	this.plot = null;
	this.data = null;
	this.container = null;
	this.responsiveLabels = null;
	var parent = this;
	
	this.setLabels = function(labels) { this.labels = labels; }
	this.setResponsiveLabels = function(labels) { this.responsiveLabels = labels; }
	this.setBLabels = function(blabels) { this.blabels = blabels; }
	this.setData = function(data) { this.data = data; }
	this.createPlot = function(container) {
		this.container = container;
		
		if(this.responsiveLabels == null) {
			this.plot = $.plot($(container), this.data, {
				series: {
					points: {
						show:true,
						radius:4
					},
					lines: {
						show:true
					},
					shadowSize: 4
				},
				grid: {
					color:'#333',
					borderColor:'transparent',
					borderWidth:3,
					hoverable:true,
					labelMargin:20
				},
				xaxis: {
					tickColor:'transparent',
					tickDecimals:0,
					tickFormatter: function(val,axis) {
						return parent.labels[val];
					}
				},
				yaxis: {
					tickDecimals:0
				}
			});
		}else{
			this.plot = $.plot($(container), this.data, {
				series: {
					points: {
						show:true,
						radius:4
					},
					lines: {
						show:true
					},
					shadowSize: 4
				},
				grid: {
					color:'#333',
					borderColor:'transparent',
					borderWidth:3,
					hoverable:true,
					labelMargin:20
				},
				xaxis: {
					tickColor:'transparent',
					tickDecimals:0,
					tickFormatter: function(val,axis) {
						return "<span class=\"plot_normal_label\">"+parent.labels[val]+"</span><span class=\"plot_responsive_label\">"+parent.responsiveLabels[val]+"</span>";
					}
				},
				yaxis: {
					tickDecimals:0
				}
			});
		}
	}
	
	this.bindHover = function() {
		$(this.container).bind('plothover', function(evt,position,item) {
			if(item) {
				var alldata = parent.plot.getData();
				var content = '<strong style="font-size:13px">'+parent.labels[item.datapoint[0]]+'</strong>';
				
				for(var i = 0; i < alldata.length; i++) {
					for(var j = 0; j < alldata[i].data.length; j++) {
						if(alldata[i].data[j][0] == item.datapoint[0] && alldata[i].data[j][1] == item.datapoint[1])
								content += '<br /><strong>'+parent.blabels[i]+'</strong>: '+item.datapoint[1];
					}
				}
				
				// Put content and shot
				$('#tooltip').css('display','block').html(content);
				
				// Measure
				if((item.pageX + $('#tooltip').width()) >= $(window).width())
					var left = item.pageX - $('#tooltip').width() - 30;
				else
					var left = item.pageX+5;
				
				$('#tooltip').css({
					'display':'block',
					'left': left,
					'top': item.pageY+5
				}).html(content);
			}else{
				$('#tooltip').css('display','none');
			}
		});
	}
}