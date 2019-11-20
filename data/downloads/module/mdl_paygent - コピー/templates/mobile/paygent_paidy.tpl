<!--{strip}-->
    Paidy翌月払い（コンビニ/銀行）はご利用の端末からはご利用いただけません。<br>
    他の決済方法を選択してください。

    <form action="<!--{$smarty.const.ROOT_URLPATH}-->shopping/load_payment_module.php" method="post">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->">
        <input type="hidden" name="mode" value="return">
        <center><input type="submit" value="戻る"></center>
    </form>
<!--{/strip}-->