// JavaScript Document
// echarts
// create for AgnesXu at 20161115

//折线图
var line = echarts.init(document.getElementById('line'));
line.setOption({
    color:["#879355"],
    title: {
        x: 'left',
        text: '',
        textStyle: {
            fontSize: '18',
            color: '#4c4c4c',
            fontWeight: 'bolder'
        }
    },
    tooltip: {
        trigger: 'axis',
        formatter: function (params) {
            params = params[0];
            var date = new Date(params.name);
            return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' : ' + params.value[1];
			//return timeZero(date.getMinutes()) + ":" + timeZero(date.getSeconds())
        },
        axisPointer: {
            animation: false
        }
    },
	toolbox: {
        show: true,
        feature: {
            dataZoom: {
                yAxisIndex: 'none'
            },
            dataView: {readOnly: false},
            magicType: {type: ['line', 'bar']}
        }
    },
	grid: {
		top: "40",
		left: "46",
		right: '60',
		bottom: "20"
	},
	backgroundColor: "rgb(24,25,30)",
    xAxis:  {
        /*type: 'category',
        boundaryGap: false,*/
		type: 'time',
		splitNumber: 5,
        //data: ['20:00','21:00','22:00','23:00','24:00','25:00','26:00','27:00','28:00','29:00','30:00','31:00','32:00'],
        axisLabel: {
            //interval:0,
			show: true,
			textStyle: {
				color: "rgb(155,155,155)"
			},
			/*formatter: function(value, index) {
				var d = new Date();
				d.setTime(value);
				return timeZero(d.getMinutes()) + ":" + timeZero(d.getSeconds())
			},
			interval: function(index, value) {
				return true
			}*/
        },
		axisLine: {
			lineStyle: {
				color: "rgb(62,72,85)",
				type: "solid",
				width: 1
			}
		},
		axisTick: {
			show: true,
			lineStyle: {
				color: "rgb(62,72,85)",
				type: "solid",
				width: 1
			}
		},
		splitLine: {
			show: true,
			interval: 100,
			lineStyle: {
				color: "rgb(62,72,85)",
				type: "dotted",
				width: 1
			}
		}
		
		
    },
    yAxis: {
        type: 'value',
		scale: true,
		show: true,
		position: "right",
		splitNumber: 5,
		boundaryGap: [0.01, 0.01],
		axisLine: {
			show: false
		},
		splitLine: {
			show: true,
			lineStyle: {
				color: "rgb(62,72,85)",
				type: "dotted",
				width: 1
			}
		},
		axisLabel: {
			textStyle: {
				color: "rgb(155,155,155)"
			}
		}
    },
   /* series: [
        {
            name:'值',
            type:'line',
            data:[23, 42, 18, 45, 48, 49,60,70,81,40,50,60],
            markLine: {data: [{type: 'average', name: '平均值'}]}
        }
    ]*/
	series: [{
        name: '模拟数据',
        type: 'line',
        showSymbol: false,
        hoverAnimation: false,
        data: data,
		markLine: {
		data: [{type: 'average', name: '平均值'}],
		symbol: ["none", "none"],
		precision: 5,
		//data: (function() {
//			var axisy = [{
//				yAxis: ymax,
//				xAxis: 0,
//				value: ymax,
//				label: {
//					normal: {
//						formatter: function(data) {
//							if ($scope.trade_type == "1") {
//								return JSUtils.number.fiveFloat(ymax).join(".")
//							} else {
//								return JSUtils.number.moneySplitIntAndFloat(ymax).join(".")
//							}
//						}
//					}
//				},
//				lineStyle: {
//					normal: {
//						color: "#007aff"
//					}
//				}
//			}];
//			/*if (markL.length <= 0) {
//				return axisy
//			} else {
//				return axisy.concat(markL)
//			}*/
//		})(),
		animation: false,
		lineStyle: {
			normal: {
				color: "#007aff"
			}
		}
		
		},
    }]
}) ;
function timeZero(p) {
	if (p < 10) {
		return "0" + p
	}
	return p
}

//============================================================================================================================================================
function randomData() {
    now = new Date(+now + oneDay);
    value = value + Math.random() * 21 - 10;
    return {
        name: now.toString(),
        value: [
            [now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'),
            Math.round(value)
        ]
    }
}
//return timeZero(d.getMinutes()) + ":" + timeZero(d.getSeconds())
var data = [];

var now = +new Date(2016, 9, 3);
var oneDay = 24 * 3600 * 1000;
var value = Math.random() * 1000;
for (var i = 0; i < 1000; i++) {
    data.push(randomData());
}

setInterval(function () {

    for (var i = 0; i < 5; i++) {
        data.shift();
        data.push(randomData());
    }

    line.setOption({
        series: [{
            data: data
        }]
    });
}, 1000);
var xmax = data[data.length - 1]["value"][0];
var ymax = data[data.length - 1]["value"][1];

