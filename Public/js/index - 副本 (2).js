// JavaScript Document
// echarts
// create for AgnesXu at 20161115

//折线图
var data_trade = [];
var markP = [];
var markL = [];
var xmax;
var ymax;
var xmaxadd;
var echart_right = 50;
var myChart = echarts.init(document.getElementById('line'));
myChart.setOption({
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
    /*tooltip: {
        trigger: 'axis',
        formatter: function (params) {
            params = params[0];
            var date = new Date(params.name);
            //return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' : ' + params.value[1];
			return timeZero(date.getMinutes()) + ":" + timeZero(date.getSeconds());
        },
        axisPointer: {
            animation: false
        },
		interval: function(params) {
				return true
			}
    },*/
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
				name: "模拟数据",
				type: "line",
				symbol: true,
				showSymbol: false,
				symbolSize: 0,
				hoverAnimation: false,
				data: data,
				itemStyle: {
					normal: {
						color: "rgb(145,166,35)",
						type: "solid",
						borderWidth: 1,
						lineStyle: {
							type: "solid",
							width: 1
						}
					}
				},
				markPoint: {
					show: true,
					data: (function() {
						var aar = [{
							name: "固定 x 像素位置",
							symbol: "emptyCircle",
							symbolSize: 5,
							coord: [xmax, ymax],
							label: {
								normal: {
									show: false
								}
							}
						}];
						if (markP.length <= 0) {
							return aar
						} else {
							return aar.concat(markP)
						}
					})()
				},
				 markLine: {
					data: [{yAxis: ymax,xAxis:0, name: '平均值'}],
					lineStyle: {
						normal: {
							color: "#007aff"
						}
					},
					symbol: ["none", "circle"],
					precision: 5,
					animation: false,
				}
				/*markLine: {
					symbol: ["none", "none"],
					precision: 5,
					data: (function() {
						var axisy = [{
							yAxis: ymax,
							xAxis: 0,
							value: ymax,
							label: {
								normal: {
									formatter: function(data) {
										if ($scope.trade_type == "1") {
											return JSUtils.number.fiveFloat(ymax).join(".")
										} else {
											return JSUtils.number.moneySplitIntAndFloat(ymax).join(".")
										}
									}
								}
							},
							lineStyle: {
								normal: {
									color: "#007aff"
								}
							}
						}];
						if (markL.length <= 0) {
							return axisy
						} else {
							return axisy.concat(markL)
						}
					})(),
					animation: false,
				}*/
			}]
		
}) ;
		
	
function timeZero(p) {
	if (p < 10) {
		return "0" + p
	}
	return p
}
function marketList(datas) {
	var tmpObj = "";
	var tmpDate = "";
	if (data.length <= 0) {
		timess = datas[0]["timess"];
		for (var i = datas.length - 1; i >= 0; i--) {
			tmpObj = datas[i];
			tmpDate = JSUtils.date.timeStrTODate(tmpObj.timess);
			data.push(getChartValue(tmpDate, tmpObj.pricetrade))
		}
	} else {
		if (datas.length <= 0) {} else {
			var oldtimess = timess;
			timess = datas[0]["timess"];
			xmaxadd = JSUtils.date.timeStrTODate(xaxismax(timess) + "");
			for (var i = datas.length - 1; i >= 0; i--) {
				if (Number(oldtimess) < Number(datas[i]["timess"])) {
					tmpObj = datas[i];
					tmpDate = JSUtils.date.timeStrTODate(tmpObj.timess);
					data.shift();
					data.push(getChartValue(tmpDate, tmpObj.pricetrade))
				}
			}
		}
	}
	ymax = data[data.length - 1]["value"][1];
	if ($scope.trade_type == "1") {
		$scope.nowplice = JSUtils.number.fiveFloat(ymax).join(".")
	} else {
		$scope.nowplice = JSUtils.number.moneySplitIntAndFloat(ymax).join(".")
	}
	xmax = data[data.length - 1]["value"][0];
	var option = {
		grid: {
			top: "40",
			left: "17",
			right: echart_right,
			bottom: "20"
		},
		backgroundColor: "rgb(24,25,30)",
		xAxis: {
			type: "time",
			splitNumber: 5,
			axisLabel: {
				show: true,
				textStyle: {
					color: "rgb(155,155,155)"
				},
				formatter: function(value, index) {
					var d = new Date();
					d.setTime(value);
					return timeZero(d.getMinutes()) + ":" + timeZero(d.getSeconds())
				},
				interval: function(index, value) {
					return true
				}
			},
			axisLine: {
				lineStyle: {
					color: "rgb(62,72,85)",
					type: "dotted",
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
		yAxis: [{
			type: "value",
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
				show: true,
				textStyle: {
					color: "rgb(155,155,155)"
				},
				formatter: function(value, index) {
					if ($scope.trade_type == "1") {
						return JSUtils.number.fiveFloat(value).join(".")
					} else {
						return JSUtils.number.moneySplitIntAndFloat(value).join(".")
					}
				},
				interval: function(index, value) {
					return true
				}
			}
		}],
		series: [{
			name: "模拟数据",
			type: "line",
			symbol: true,
			showSymbol: false,
			symbolSize: 0,
			hoverAnimation: false,
			data: data,
			itemStyle: {
				normal: {
					color: "rgb(145,166,35)",
					type: "solid",
					borderWidth: 1,
					lineStyle: {
						type: "solid",
						width: 1
					}
				}
			},
			markPoint: {
				show: true,
				data: (function() {
					var aar = [{
						name: "固定 x 像素位置",
						symbol: "emptyCircle",
						symbolSize: 5,
						coord: [xmax, ymax],
						label: {
							normal: {
								show: false
							}
						}
					}];
					if (markP.length <= 0) {
						return aar
					} else {
						return aar.concat(markP)
					}
				})()
			},
			markLine: {
				symbol: ["none", "none"],
				precision: 5,
				data: (function() {
					var axisy = [{
						yAxis: ymax,
						xAxis: 0,
						value: ymax,
						label: {
							normal: {
								formatter: function(data) {
									if ($scope.trade_type == "1") {
										return JSUtils.number.fiveFloat(ymax).join(".")
									} else {
										return JSUtils.number.moneySplitIntAndFloat(ymax).join(".")
									}
								}
							}
						},
						lineStyle: {
							normal: {
								color: "#007aff"
							}
						}
					}];
					if (markL.length <= 0) {
						return axisy
					} else {
						return axisy.concat(markL)
					}
				})(),
				animation: false,
			}
		}]
	};
	if (option && typeof option === "object") {
		myChart.setOption(option, true)
	}
	$timeout(function() {
		if (hornNumtimes == hornNum) {
			horn = "1";
			hornNumtimes = 0
		} else {
			horn = "0";
			hornNumtimes++
		}
		var countCownTimeList = $scope.cLList;
		for (var i = 0; i < countCownTimeList.length; i++) {
			var countCownTime = Number(countCownTimeList[i]["countCownTime"]);
			var countDownFlag = countCownTimeList[i]["countDownFlag"];
			var billid = $scope.cLList[i].billid;
			countCownTime = countCownTime - 1;
			if (countCownTime < 0) {
				if (countCownTime <= -2) {
					$scope.cLList[i].countCownTime = "等待交割"
				}
			} else {
				if (!countDownFlag) {
					$scope.cLList[i].countCownTime = countCownTime
				} else {
					$scope.cLList[i].countCownTime = "等待交割"
				}
			}
			if (countCownTime <= 0) {
				TargetMarketTrade(billid, i);
				$scope.cLList[i].clearResultTime = Number(10)
			}
		}
		singleT()
	}, 1000)
}

var singleNum = 0;
var hornNumtimes = 1;

function singleT() {
	if ($scope.sycleCtrl) {
		JSUtils.system.addHandler(window, "online", function() {
			$scope.sycleCtrl = true;
			window.location.reload()
		});
		JSUtils.system.addHandler(window, "offline", function() {
			$scope.sycleCtrl = false
		});
		var tradeTop = document.getElementById("tradeTop");
		trade.singleTargetQuery(targetid, timess, horn, function(data, state) {
			var markets = data.data["markets"];
			if (markets.length > 0 && markets[0]["isclose"] == "1") {
				document.getElementById("btn_1").style.visibility = "hidden";
				document.getElementById("btn_2").style.visibility = "hidden";
				trade.alert("该交易品处于休市状态", "", function() {
					$location.path("tabs/trade")
				});
				return
			}
			var usertrades = data.data["usertrades"];
			if (usertrades) {
				horn = "0";
				broadcast(usertrades)
			}
			if (markets) {
				marketList(markets)
			}
		}, null)
	}
}
var JSUtils = {
	system: {
		addHandler: function(element, type, handler) {
			if (element.addEventListener) {
				element.addEventListener(type, handler, false)
			} else {
				if (element.attachEvent) {
					element.attachEvent("on" + type, handler)
				} else {
					element["on" + type] = handler
				}
			}
		},
		setTimeOut: function(callback, timeout, param) {
			var args = Array.prototype.slice.call(arguments, 2);
			var _cb = function() {
					callback.apply(null, args)
				};
			return window.setTimeout(_cb, timeout)
		},
		setInterval: function(callback, timeout, param) {
			var args = Array.prototype.slice.call(arguments, 2);
			var _cb = function() {
					callback.apply(null, args)
				};
			return window.setInterval(_cb, timeout)
		},
		getPageParamVal: function(name) {
			var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
			var r = window.location.search.substr(1).match(reg);
			if (r != null) {
				return r[2]
			} else {
				return null
			}
		}
	},
	number: {
		getSepNum: function(inputValue, minValue, maxValue) {
			if (inputValue < minValue) {
				inputValue = minValue
			} else {
				if (inputValue > maxValue) {
					inputValue = maxValue
				}
			}
			return inputValue
		},
		getMin: function(val1, val2) {
			if (val1 > val2) {
				return val2
			} else {
				return val1
			}
		},
		getMax: function(val1, val2) {
			if (val1 > val2) {
				return val1
			} else {
				return val2
			}
		},
		getRandomInt: function(min, max) {
			if (min > max || max < 0) {
				throw "getRandomInt  指定参数错误"
			}
			var tmp = 0;
			if (min < 0) {
				tmp = min;
				max = max - min;
				min = 0
			}
			return JSUtils.number.chnInt(Math.random() * (max - min + 1) + min) + tmp
		},
		getFormatNum: function(num) {
			if (isNaN(JSUtils.number.chnInt(num))) {
				return num
			}
			if (JSUtils.number.chnInt(num) == parseFloat(num)) {
				return JSUtils.number.chnInt(num)
			} else {
				return parseFloat(num)
			}
		},
		formatNumeric: function(num, numericLen) {
			var val = Math.pow(10, numericLen);
			num = JSUtils.number.chnInt(Math.round(num * val)) / val;
			var str = num.toString();
			var str1, num1, num2 = str.indexOf(".");
			if (num2 == -1) {
				num1 = numericLen;
				if (numericLen != 0) {
					str += "."
				}
			} else {
				str1 = str.substr(num2 + 1, str.length);
				num1 = numericLen - str1.length
			}
			var str2 = "";
			for (var i = 0; i < num1; i++) {
				str2 += "0"
			}
			return str + str2
		},
		IN: function(val, array) {
			for (var i = 0; i < array.length; i++) {
				if (val == array[i]) {
					return true
				}
			}
			return false
		},
		chnInt: function(req) {
			return parseInt(req, 10)
		},
		oneFloat: function(param) {
			var param = param + "";
			var strarr = param.split(".");
			if (strarr.length <= 1) {
				strarr.push("0")
			} else {
				if (strarr[1].length > 1) {
					strarr[1] = (strarr[1] + "").substr(0, 1)
				}
			}
			return strarr
		},
		fiveFloat: function(param) {
			var param = param + "";
			var strarr = param.split(".");
			if (strarr.length <= 1) {
				strarr.push("00000")
			} else {
				if (strarr[1].length >= 5) {
					strarr[1] = (strarr[1] + "").substr(0, 5)
				} else {
					if (strarr[1].length < 5 || strarr[1].length > 0) {
						if (strarr[1].length == 1) {
							strarr[1] = strarr[1] + "0000"
						} else {
							if (strarr[1].length == 2) {
								strarr[1] = strarr[1] + "000"
							} else {
								if (strarr[1].length == 3) {
									strarr[1] = strarr[1] + "00"
								} else {
									if (strarr[1].length == 4) {
										strarr[1] = strarr[1] + "0"
									}
								}
							}
						}
					}
				}
			}
			return strarr
		},
		moneySplitIntAndFloat: function(money) {
			if (!money) {
				return ["0", "00"]
			} else {
				var str = money + "";
				var strarr = str.split(".");
				if (strarr.length <= 1) {
					strarr.push("00")
				} else {
					if (strarr[1].length <= 1) {
						strarr[1] += "0"
					} else {
						if (strarr[1].length > 1) {
							strarr[1] = strarr[1].substr(0, 2)
						}
					}
				}
				return strarr
			}
		}
	},
	object: {
		hasProperty: function(obj) {
			for (var i in obj) {
				return true
			}
			return false
		},
		hasSepProperty: function(obj, propName) {
			return obj.hasOwnProperty(propName)
		},
		deleteSepProperty: function(obj, propName) {
			delete obj[propName]
		},
		exist: function(obj) {
			try {
				if (obj == null) {
					return false
				}
				if (obj == undefined) {
					return false
				}
				return typeof(obj) != "undefined"
			} catch (e) {
				return false
			}
		},
		strToJsonObj: function(jsonStr) {
			if (JSUtils.string.isAvailably(jsonStr)) {
				return eval("(" + jsonStr + ")")
			}
			return eval("(null)")
		},
		toJsonStr: function(obj) {
			return JSON.stringify(obj)
		},
		jsonClone: function(jsonObj) {
			var buf;
			if (jsonObj instanceof Array) {
				buf = [];
				var i = jsonObj.length;
				while (i--) {
					buf[i] = JSUtils.object.jsonClone(jsonObj[i])
				}
				return buf
			} else {
				if (jsonObj instanceof Object) {
					buf = {};
					for (var k in jsonObj) {
						buf[k] = JSUtils.object.jsonClone(jsonObj[k])
					}
					return buf
				} else {
					return jsonObj
				}
			}
		},
		arraySort: function(array, order, columnName) {
			array.sort(function(obj1, obj2) {
				var val1, val2, tmpVal;
				var ret;
				val1 = obj1[columnName];
				val2 = obj2[columnName];
				if (order == 0) {
					tmpVal = val2;
					val2 = val1;
					val1 = tmpVal
				}
				if (val1 < val2) {
					ret = -1
				} else {
					if (val1 > val2) {
						ret = 1
					} else {
						ret = 0
					}
				}
				return ret
			})
		}
	},
	string: {
		isAvailably: function(str) {
			if (str == null) {
				return false
			}
			if (typeof(str) == "undefined") {
				return false
			}
			if (str == "") {
				return false
			}
			if (str == "null" || str == "undefined") {
				return false
			}
			return true
		},
		getAvailablyString: function(str1, str2) {
			return JSUtils.string.isAvailably(str1) ? str1 : str2
		},
		getFormatString: function(str, replaceWords) {
			var ret = str;
			if (replaceWords == null) {
				return str
			}
			for (var i = 0; i < replaceWords.length; i++) {
				var re = eval("/\\{" + i + "\\}/ig");
				ret = ret.replace(re, replaceWords[i])
			}
			return ret
		},
		replaceWord: function(source, rep1, rep2) {
			return source.replace(new RegExp(rep1, "gm"), rep2)
		},
		getStressHtml: function(str, replaceWords, stressWordCss) {
			if (replaceWords == null) {
				return str
			}
			for (var i = 0; i < replaceWords.length; i++) {
				var tmpRepObj = replaceWords[i];
				if (typeof(tmpRepObj) == "object") {
					replaceWords[i] = JSUtils.string.getReplaceSpan(tmpRepObj.css, tmpRepObj.word)
				} else {
					replaceWords[i] = JSUtils.string.getReplaceSpan(stressWordCss, tmpRepObj)
				}
			}
			return JSUtils.string.getFormatString(str, replaceWords)
		},
		getNormalHtml: function(str, replaceWords, stressWordCss) {
			if (replaceWords == null) {
				return str
			}
			for (var i = 0; i < replaceWords.length; i++) {
				var tmpRepObj = replaceWords[i];
				if (typeof(tmpRepObj) == "object") {
					replaceWords[i] = "<span class='noempty " + tmpRepObj.css + "'>" + tmpRepObj.word + "</span>"
				} else {
					replaceWords[i] = "<span class='noempty " + stressWordCss + "'>" + tmpRepObj + "</span>"
				}
			}
			return JSUtils.string.getFormatString(str, replaceWords)
		},
		getReplaceSpan: function(css, word) {
			return "<span class='noempty " + css + "'> " + word + " </span>"
		},
		getRandomUrlParams: function() {
			return "random=" + (new Date().getTime() + Math.random())
		},
		startWith: function(sourceStr, startStr) {
			var d = startStr.length;
			return (d >= 0 && sourceStr.indexOf(startStr) == 0)
		},
		endWith: function(sourceStr, endStr) {
			var d = sourceStr.length - endStr.length;
			return (d >= 0 && sourceStr.lastIndexOf(endStr) == d)
		},
		contains: function(sourceStr, containStr) {
			return (sourceStr.indexOf(containStr) > 0)
		},
		phoneToShow: function(phoneNum) {
			if (!JSUtils.form.check.isPhone(phoneNum)) {
				return phoneNum
			}
			return phoneNum.substring(0, 3) + "******" + phoneNum.substring(phoneNum.length - 2, phoneNum.length)
		}
	},
	form: {
		check: {
			isEmpty: function(objVal) {
				return !JSUtils.string.isAvailably(objVal)
			},
			isMail: function(objVal) {
				var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
				return pattern.test(objVal)
			},
			isDigit: function(objVal) {
				var reg = new RegExp("^[0-9]*$");
				return reg.test(objVal)
			},
			isPhone: function(str) {
				var re = /^(13[0-9]{9})|(14[0-9]{9})|(15[0-9]{9})|(18[0-9]{9})$/;
				return re.test(str)
			}
		}
	},
	data: {},
	localSave: {
		get: function(key) {
			var localStr = window.localStorage.getItem(key);
			return JSUtils.object.strToJsonObj(localStr)
		},
		set: function(key, val) {
			var localStr = JSUtils.object.toJsonStr(val);
			window.localStorage.setItem(key, localStr)
		},
		remove: function(key) {
			window.localStorage.removeItem(key)
		}
	},
	date: {
		timeStrTODate: function(timeStr) {
			if (!JSUtils.string.isAvailably(timeStr)) {
				return null
			}
			if (timeStr.length != 14) {
				return null
			}
			var str = timeStr.substr(0, 4) + "/" + timeStr.substr(4, 2) + "/" + timeStr.substr(6, 2) + " " + timeStr.substr(8, 2) + ":" + timeStr.substr(10, 2) + ":" + timeStr.substr(12, 2);
			return new Date(str)
		},
		timeStrToTime: function(timeStr) {
			if (!JSUtils.string.isAvailably(timeStr)) {
				return null
			}
			if (timeStr.length != 14) {
				return null
			}
			return timeStr.substr(4, 2) + "." + timeStr.substr(6, 2) + " - " + timeStr.substr(8, 2) + ":" + timeStr.substr(10, 2) + ":" + timeStr.substr(12, 2)
		}
	},
	curApp: {
		constants: {
			tradeInfoFormat: {
				btc_cny: 2,
				ltc_cny: 2,
				"USDJPY.FX": 5,
				"EURJPY.FX": 5,
				"IF00C1.CFE": 2,
				"HSI.HI": 2,
				"IXIC.GI": 2,
				"GDAXI.GI": 2,
				"GC16Z.CMX": 2,
				"USDCAD.FX": 5,
				"GC00Y.CMX": 2
			}
		},
		sessionObj: {
			put: function(key, value) {
				if (!JSUtils.string.isAvailably(value)) {
					return
				}
				var sessionObj = JSUtils.curApp.sessionObj.__getObj();
				if (!sessionObj) {
					sessionObj = {}
				}
				sessionObj[key] = value;
				JSUtils.curApp.sessionObj.__saveObj(sessionObj)
			},
			get: function(key) {
				var sessionObj = JSUtils.curApp.sessionObj.__getObj();
				if (sessionObj) {
					return sessionObj[key]
				}
				return ""
			},
			__getObj: function() {
				var sessionObj = JSUtils.localSave.get("sessionObj");
				if (!sessionObj) {
					sessionObj = {}
				}
				return sessionObj
			},
			__saveObj: function(sobj) {
				JSUtils.localSave.set("sessionObj", sobj)
			}
		}
	}
};
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

var now = +new Date(2012, 9, 3);
var oneDay = 24 * 3600 * 1000;
var mm = 60 * 1000;
var ss = 1000;
var value = Math.random() * 1000;
for (var i = 0; i < 1000; i++) {
    data.push(randomData());
}

setInterval(function () {
	var xmax = data[data.length - 1]["value"][0];
	var ymax = data[data.length - 1]["value"][1];
	console.log(ymax);
	console.log(xmax);
    for (var i = 0; i < 1; i++) {
        data.shift();
        data.push(randomData());
    }
	
    myChart.setOption({
        series: [{
            data: data,
        }],
		batch: [{
			// 第一个 dataZoom 组件
			xmax: xmax,
			ymax: ymax
		}]
    });
}, 1000);

