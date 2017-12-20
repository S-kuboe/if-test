// コンストラクタ
var dspContact = function () {};

$(document).ready(function () {
	var objContactt = new dspContact();

	// 確認ボタンクリック時
	$("input[name='confirm']").click(function () {
		var ret;
		ret = getJsErrorAjax('fmContact');
		if (ret) {
			objContactt.Conf();
		} else {
			return false;
		}
	});

	// 送信ボタンクリック時
	$("input[name='send']").click(function () {
		objContactt.Send();
	});

	// 戻るボタンクリック時
	$("input[name='back']").click(function () {
		objContactt.Back();
	});

});

// 確認処理
dspContact.prototype.Conf = function () {
	$("#strAction").val("confirm");
	$("#fmContact").attr("action", "./index.php");
	$("#fmContact").submit();
};

// 送信処理
dspContact.prototype.Send = function () {
	$("#strAction").val("send");
	$("#fmContact").attr("action", "./index.php");
	$("#fmContact").submit();
};

// 戻る処理
dspContact.prototype.Back = function () {
	$("#strAction").val("");
	$("#fmContact").attr("action", "./index.php");
	$("#fmContact").submit();
};
