function select_time() {
    var h = 0,
    m = 0;
    for (var i = 0; i < 48; i++) {
        var M;
        if (m == 0) {
            M = '00';
        } else {
            M = m;
        }
		var hsr= h<10 ? "0"+h : h;
		var time = hsr + ' : ' + M;
        var option = "<option value='" + hsr + ":" + M + "'>" + time + "</option>";
		$(option).appendTo("select[name='office_start_time']");
        $(option).appendTo("select[name='office_stop_time']");
		$(option).appendTo("select[name='office_start_time2']");
        $(option).appendTo("select[name='office_stop_time2']");
        $(option).appendTo("select[name='office_start_time3']");
        $(option).appendTo("select[name='office_stop_time3']");
        m += 30;
        if (m == 60) {
            m = 0;
            h++;
        }
    };
}
select_time();