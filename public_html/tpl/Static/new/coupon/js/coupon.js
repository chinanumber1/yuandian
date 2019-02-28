!function(e, t) {
    e.fn.placeholder = function(l) {
        var s = {
            labelMode: !1,
            labelStyle: {},
            labelAlpha: !1,
            labelAcross: !1
        },
        o = e.extend({},
        s, l || {}),
        n = function(e, t) {
            "" === e.val() ? t.css("opacity", .4).html(e.data("placeholder")) : t.html("")
        },
        a = function(l) {
            if (document.querySelector) return e(l).attr("placeholder");
            var s;
            return s = l.getAttributeNode("placeholder"),
            s && "" !== s.nodeValue ? s.nodeValue: t
        };
        return e(this).each(function() {
            var t = e(this),
            l = "placeholder" in document.createElement("input"),
            s = a(this);
            if (! (!s || !o.labelMode && l || o.labelMode && !o.labelAcross && l)) if (t.data("placeholder", s), o.labelMode) {
                var i = t.attr("id"),
                c = null;
                i || (i = "placeholder" + Math.random(), t.attr("id", i)),
                c = e('<label for="' + i + '"></label>').css(e.extend({
                    lineHeight: "1.3",
                    position: "absolute",
                    color: "graytext",
                    cursor: "text",
                    marginLeft: t.css("marginLeft"),
                    marginTop: t.css("marginTop"),
                    paddingLeft: t.css("paddingLeft"),
                    paddingTop: t.css("paddingTop")
                },
                o.labelStyle)).insertBefore(t),
                o.labelAlpha ? (t.bind({
                    focus: function() {
                        n(e(this), c)
                    },
                    input: function() {
                        n(e(this), c)
                    },
                    blur: function() {
                        "" === this.value && c.css("opacity", 1).html(s)
                    }
                }), window.screenX || (t.get(0).onpropertychange = function(e) {
                    e = e || window.event,
                    "value" == e.propertyName && n(t, c)
                }), c.get(0).oncontextmenu = function() {
                    return t.trigger("focus"),
                    !1
                }) : t.bind({
                    focus: function() {
                        c.html("")
                    },
                    blur: function() {
                        "" === e(this).val() && c.html(s)
                    }
                }),
                o.labelAcross && t.removeAttr("placeholder"),
                "" === t.val() && c.html(s)
            } else t.bind({
                focus: function() {
                    e(this).val() === s && e(this).val(""),
                    e(this).css("color", "")
                },
                blur: function() {
                    "" === e(this).val() && e(this).val(s).css("color", "graytext")
                }
            }),
            "" === t.val() && t.val(s).css("color", "graytext")
        }),
        e(this)
    }
} (jQuery),
function() {
    function e(e) {
        var t = /^[0-9]{11}$/;
        return t.test(e) ? !0 : !1
    }
    function t(e, t, l, s) {
        var o = document.createElement("script");
        o.src = tracker_url;
        var n = document.getElementsByTagName("script")[0];
        n.parentNode.insertBefore(o, n)
    }
    $("#phone").placeholder();
    var l = $("#doReceive").attr("data-href");
    $(".yhqDiv").hide(),
    $("#inputPhone").show(),
    $("#phoneInvaild").hide(),
    $("#doReceive").click(function(s) {
        var o = $("#phone").val();
        var coupon_id = $("#coupon_id").val();
        var verify = $("input[name='verify']").val();
        var verify_type = $("input[name='verify_type']").val();
        n = $(this);
        n.attr("class").indexOf("disable") > -1 || (e(o) ? $.ajax({
            url: l,
            dataType: "json",
            type: "POST",
            data: {
                phone: o,
				coupon_id:coupon_id,
				verify:verify,
				verify_type:verify_type
            },
            beforeSend: function(e) {
                $("#phoneInvaild").hide(),
                n.removeClass("btn").addClass("disable")
            },
            complete: function(e, t) {
                $("#doReceive").removeClass("disable").addClass("btn")
            },
            error: function(e, t, l) {
                $("#msgTitle").html("其他原因领取失败！"),
                $("#msg").html("系统原因领券失败!")
            },
            success: function(e) {
				if(e.dom_id=='verify'){
					alert(e.msg);
					$('#doReceive').removeClass('disable').addClass('btn');
					window.location.reload();
				}
				switch(e.error_code)
					{	
						case 0:
							$(".yhqDiv").hide();$("#toOrder").show();
							$("#msgTitle").html("恭喜您，领券成功！");
							$('#had_phone').html(o);
							break;
						case 1:
							$(".yhqDiv").show();$("#toOrder").hide();
							alert("领取失败！");
							break;
						case 2:
							$(".yhqDiv").show();$("#toOrder").hide();
							alert("该优惠券已过期！");
							break;
						case 3:
							$(".yhqDiv").show();$("#toOrder").hide();
							alert("该优惠券已被领完！");
							
							break;
						case 4:
							$(".yhqDiv").show();$("#toOrder").hide();
							alert("仅限新用户领取！");
							
							break;
						case 5:
							$(".yhqDiv").show();$("#toOrder").hide();
							alert("不能在领取了！");
							break;
						}
				}
				//if ($(".yhqDiv").hide(), $("#toOrder").show(), e && e.error_code || ($("#msgTitle").html("其他原因领取失败！"), $("#msg").html("领券失败!")), 0 == e.error_code || 1 == e.error_code) {
				//	4 == e.error_code ? ($("#msgTitle").html("仅限新用户领取！"), oldSuccessMsg = oldSuccessMsg.replace("#amount#", e.discount).replace("#phone#", o), $("#msg").html(oldSuccessMsg), t(o, e.statisticCode, e.statisticActivityId, "bindsuccess")) :($("#msgTitle").html("恭喜您，领券成功！"), newSuccessMsg = newSuccessMsg.replace("#amount#", e.coupon.discount).replace("#phone#", o), $("#msg").html(newSuccessMsg), t(o, e.statisticCode, e.statisticActivityId, "bindsuccess"))
				//} else 5 == e.error_code ? ($("#msgTitle").html("已抢过该券！"), $("#msg").html("账户<span>" + o + "</span>已抢过该券!")) : 3 == e.error_code ? ($("#msgTitle").html("已抢完了!"), $("#msg").html("已抢完了!")) :5 == e.error_code ? ($("#inputPhone").show(), $("#phoneInvaild").show()) : 2 == e.error_code && ($("#msgTitle").html("领取活动已结束！"), $("#msg").html("对不起，领取活动已结束！"))
			//}
        }) : $("#phoneInvaild").show())
    }),
    $("#phone").keyup(function(t) {
        var l = $("#phone").val();
        l.length < 11 || (e(l) ? ($("#phoneInvaild").hide(), $("#doReceive").removeClass("disable").addClass("btn")) : ($("#phoneInvaild").show(), $("#doReceive").removeClass("btn").addClass("disable")))
    }),
    $("#toOrderBtn").click(function(e) {
        window.open($(this).attr("data-href"))
    })
} ();