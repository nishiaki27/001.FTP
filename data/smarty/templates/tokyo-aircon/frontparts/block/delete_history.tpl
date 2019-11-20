<SCRIPT language="JavaScript">
<!--
// クッキーの削除
function deleteCookie() {
	for(i=1; i<6; i++){
		cName = "product["+i+"]="; // 削除するクッキー名
		dTime = new Date();
		dTime.setYear(dTime.getYear() - 1);
		document.cookie = cName + ";expires=" + dTime.toGMTString();
	}
	location.reload();
}
//-->
</SCRIPT>