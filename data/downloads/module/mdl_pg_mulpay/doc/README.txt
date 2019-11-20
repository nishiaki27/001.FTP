EC-CUBE GMO-PG決済モジュール

Version 2.3.6

======================
EC-CUBE 対応バージョン
======================
  2.11.0
  2.11.1
  2.11.2
  2.11.3 + 修正パッチ eccube-2.11.3_update_file_20111014_01.zip
  2.11.4
  2.11.5

  ※ 本モジュールは、上記バージョンのEC-CUBEにのみ対応しております。

  ※ EC-CUBE 2.11.3では、公式に配布されていた修正パッチ
     eccube-2.11.3_update_file_20111014_01.zip に相当する修正が
     適用されていることを前提とします。

  ※ 2.11.0未満のEC-CUBE(2.4.x)ではご利用できません。そちらに対応したGMO-PG決済
     モジュールをご利用下さい。


========
変更履歴
========
20131206
  - version 2.3.6
  - 決済通知において、一部の無効な通知が有効なものとして扱われる場合がある問題を修正
  - 2クリック決済において問題のある処理を修正
  - 2クリック決済においてデザインテンプレートを一部修正。

20130920
  - version 2.3.5
  - 携帯ドメインと認識する対象ドメインの設定値を最新のEC-CUBEにあわせた。
  - 再オーソリ時のウェイトを調整。
  - ミニストップの支払い方法案内を変更。

20130329
  - version 2.3.4
  - 注文確認画面から決済情報入力画面へブラウザのバックボタンで移動した場
    合に決済完了出来るが受注情報が無効になる場合がある問題を修正。

20130117
  - version 2.3.3
  - 詳細デバッグモードの追加。
    inc/include.php の冒頭にある MDL_PG_MULPAY_DEBUG の値を true にする
    ことで、日付別のログファイルが生成され詳細な決済データが出力されます。
    このモードは試験時のみご利用下さい。

20121211
  - version 2.3.2
  - クレジットカード決済において、購入者が特別な遷移をした場合、または一
    部特定の環境の場合に、稀に決済処理状態が決済サーバーと一致しない事が
    ある問題を修正。
  - コンビニ決済時に環境によって文字コードエラーが生じる場合がある問題を
    修正

20121115
  - version 2.3.1
  - EC-CUBE管理画面からの金額変更時に決済ステータスが正常に反映されない
    場合がある問題を修正

20120810 
  - version 2.3.0
  - ドコモケータイ払い 対応追加
  - MULPAY側の会員情報の名前にEC-CUBE会員固有キーを保存するように修正
    (既存会員の場合は単に会員固有キーを入れた形で情報更新する)
  - EC-CUBE会員固有キーが異なる場合カード情報を自動削除するよう機能追加

20120330
  - auかんたん決済にて、KDDI社画面でキャンセルするとwarningが表示される問題を修正

20120326
  - auかんたん決済の結果通知からも、お支払方法(AuPayMethod)を取得するよう変更
  - auかんたん決済RetURLのStatusがREQSUCCESS,AUTHPROCESSの場合、SearchTradeMulti
    で問合せを実行し、改めてStatusを確認するよう変更

20120324
  - auかんたん決済WebMoney支払による即時売上のキャンセル操作を禁止

20120323
  - auかんたん決済状況管理の「返金」を「返品」に修正
  - auかんたん決済の利用条件最大金額を9,999,999円に修正

20120318
  - 受注管理において金額変更の制限を追加
    「金額変更可能な決済手段」 
      ・クレジットカード 
      ・iD（減額のみ） 
      ・auかんたん決済（減額のみ） 
    「金額変更できない決済手段」 
      ・コンビニ 
      ・モバイルSuica 
      ・Mobile Edy 
      ・Pay-easy（ATM決済、ネットバンキング決済） 
      ・PayPal
      ・WebMoney 

  - auかんたん決済に対応
    1. ショップ側
      1.1. お支払方法の指定に'auかんたん決済'を追加
           PC,各社スマートフォン,各社携帯に対応

      1.2. 2クリック決済に対応
           auかんたん決済での2クリック決済が可能


    2. 管理画面側
      2.1. モジュール設定画面に、auかんたん決済の項目を追加
           必須項目: 処理区分※、表示サービス名、表示電話番号
           任意項目: 支払い開始期限、加盟店自由項目1, 加盟店自由項目2

           ※ WebMoneyによる支払いは、処理区分にかかわらず即時売上(CAPTURE)に
              なります。

      2.2. 受注情報の編集画面に実売上処理を実装
           仮売上(AUTH)状態の受注を、実売上(SALES)に変更できる。

           実売上処理の前に金額変更することで、仮売上時と異なる金額で
           売上確定が可能である。仮売上時より大きい金額はエラーとなる。

      2.3. 受注情報の編集画面にキャンセル・返金処理を実装
           仮売上(AUTH)・実売上(SALES)・即時売上(CAPTURE)状態の受注を
           キャンセル・返金できる。

           現状態と処理実行日により、下表の通り処理する。

                       仮売上       それ以降
             現状態    〜90日以内
             -------------------------------------------------
             仮売上    キャンセル   エラー
   
                       売上確定     翌月           それ以降
             現状態    当月内       〜翌々月末日
             -------------------------------------------------
             実売上    キャンセル   返金※         エラー
             即時売上  キャンセル   返金※         エラー
   
             ※返金処理の前に金額変更することで、一部返金が可能である。
               売上確定時より大きい金額はエラーとなる。

           WebMoneyによる即時売上は、キャンセル・返金はできません。

      2.4. 受注管理のサイドメニューに'auかんたん決済状況'を追加
           auかんたん決済の下記現在状況ごとに、受注情報を表示する。
           - 仮売上(AUTH)
           - 実売上(SALES)
           - 即時売上(CAPTURE)
           - キャンセル(CANCEL)
           - 返品(RETURN)
           - 決済失敗(PAYFAIL)


    3. 結果通知処理

      3.1. 結果通知パラメータ'Status'を受注情報(dtb_order)の'memo04'に反映

      3.2. ダウンロード商材に対応
           結果通知パラメータ'Status'が'SALES'または'CAPTURE'の場合、
           入金日(dtb_order.payment_date)を設定

      3.3. 結果通知パラメータ'AuPayMethod'を受注情報(dtb_order)の'memo05'に反映


20120301
  - EC-CUBE 2.11.5対応

20111128
  - クレジット決済の受注編集画面にて、合計金額を変更して、かつ【クレジット決済状況変更】
    が選択されていない場合、GMOPG管理画面に変更金額が反映されない。
    この場合、エラーを表示するよう修正。

    また、再オーソリと同時に金額変更した場合、変更後の金額が反映されない問題を修正。
    (class/utils/LC_Mdl_PG_MULPAY_Export.php)
  - html/pg_mulpay/receive.phpと共に配置していた.htaccessは不要なので削除。

20111116
  - コンビニ決済の完了画面／メール中で、プリペイドカードの「ド」の欠落
    を修正 (inc/incluce.php)

20111104
  - EC-CUBE 2.11.4対応
  - 本体側の修正をマージ
    http://svn.ec-cube.net/open_trac/changeset/21295
    http://svn.ec-cube.net/open_trac/changeset/21297

20111101
  - Version 2.1.2 
  - EC-CUBEオーナーズストアからダウンロードしたモジュール中のファイル
    に欠損がある問題を修正

20111027
  - Version 2.1.1 (EC-CUBE 2.11.2に対応)リリース

20111024
  - doc/test_upgrade_index.phpを2.11系対応版に更新

20111016
  - EC-CUBE 2.11.3対応
  - 本体側の修正をマージ
    http://svn.ec-cube.net/open_trac/changeset/21182
    http://svn.ec-cube.net/open_trac/changeset/21228
    http://svn.ec-cube.net/open_trac/changeset/21234

20110814
  - Version 2.1.1 (EC-CUBE 2.11.2に対応)
  - 2.11.2スマートフォンページデザイン変更
  - 本体側の修正をマージ
    http://svn.ec-cube.net/open_trac/ticket/1322
    http://svn.ec-cube.net/open_trac/changeset/20815
  - multiple.tplのインストール先ファイル名がdeliv_addr.tplとなる問題を
    修正。下記ファイル名をdeliv_addr.tplからmultiple.tplへ変更する。
    
      data/Smarty/templates/default/twoClick/deliv_addr.tpl
      data/Smarty/templates/sphone/twoClick/deliv_addr.tpl
      data/Smarty/templates/mobile/twoClick/deliv_addr.tpl

20110804
  - Windows環境ではファイル名に':'(コロン)が使用できないため、バックアップ
    ディレクトリ名にコロンを使用しないよう変更。

20110713
  - Version 2.1.0 (EC-CUBE 2.11.1に対応)リリース

20110707
  - Version 2.1.0 (EC-CUBE 2.11.1に対応)
  - 通常購入フローでのクレジット決済で、2クリック用支払情報の保存に問題
    があり、次の2クリック決済フローで支払方法が設定されない問題を修正

20110704
  - コンビニ・ATM・ネットバンク・PayPalの各決済で、お客樣情報の「フリガナ」
    に半角カナが使われている場合に決済エラー(M01011013)が発生する為、
    全角カナに変換して決済APIを実行するよう変更。

20110630
  - 2クリック決済にてポイント変更画面に遷移せず決済を完了した場合、
    受注情報(dtb_order)の使用ポイント(use_point)がNULLに設定される
    問題を修正。
  - 決済情報入力画面より確認画面に戻った際、ポイントが復元されない
    問題を修正。使用ポイントの減算タイミングを決済成功時に変更した。
  - インストール時に管理ディレクトリ名を「admin」から変更した場合、
    ファイルのコピー先に反映されない問題を修正

20110629
  - ダウンロード商材・通常商材を跨いだ2クリック決済で、お支払い方法に
    対して複数の配送方法がマッチする場合、配送業者・お支払い方法を
    選択し直してもらうよう変更。

20110628
  - クレジット決済状況変更(AlterTran)リクエストのAmount,Taxパラメータは、
    SearchTradeで取得した値を使用するよう変更。
    GMOPG側と金額の不整合が発生した状態で、状況変更・キャンセルが実行可能
    になる。

20110623
  - クレジット決済の金額変更で、お支払い合計の値が変更されているかどうか
    のチェックは不要なので削除。
  - ダウンロード商材、通常商材の購入間で2クリック決済情報を利用するよう
    変更。
  - 本体側修正 http://svn.ec-cube.net/open_trac/changeset/20978 をマージ。
    (copy/2click/data/Smarty/templates/sphone/twoClick/cart_index.tpl)
  - クレジット決済の処理区分が有効性チェックに設定されている場合、
    クレジット情報入力画面でお支払い方法の選択肢を表示しないよう変更。

20110622
  - 結果通知による金額更新で、Amount+Taxを設定するよう変更
    (receive.php)
  - クレジット決済の結果通知は、TranIDの大小比較により処理対象を
    決定するよう変更。処理済みのTranIDより小さいTranIDを持つ結果通知は
    処理しない。(receive.php)

20110617
  - 決済情報入力画面のボタン画像「次へ」「ご注文完了ページへ」のロール
    オーバ切り替えが動作していない問題を修正。
    (templates/default_credit.tpl, templates/default_common_btn.tpl)

20110614
  - クレジット決済状況変更機能に、簡易オーソリ(SAUTH)を追加
    キャンセル操作は、下表に従って、取消/返品のいずれかを実行

                    当日  翌日以降  翌月          180日より後
          現状態          〜当月内  〜180日以内
          -------------------------------------------------
          仮売上    取消   返品     返品          エラー
          実売上    取消   返品     月跨返品      エラー
          即時売上  取消   返品     月跨返品      エラー
      簡易オーソリ  取消   返品     返品          エラー

20110608
  - iD決済でiアプリ起動後に「戻る」を選択した時、注文フローに戻れない不具合を修正
    結果通知なしでも、SearchTradeMultiを利用し決済状況を正しく判定するよう改良
    
20110525
  - iD決済の決済状況に「決済失敗」(PAYFAIL)を追加
  - クレジット決済の結果受信において、合計(dtb_order.total)とお支払い合計
    (dtb_order.payment_total)が同じ値に設定せず、お支払い合計のみ設定する
    よう変更。(receive.php)

    受注情報の一貫性は、単価・送料等を手動で調整して、お支払い合計と合致
    させる必要がある。

20110524
  - iD決済でiアプリ起動後に「戻る」を選択した時、注文フローに戻れない不具合を修正

20110519
  - EC-CUBE 2.11.1に対応
  - 2クリック決済購入フローを機能追加
  - クレジット決済状況変更機能にて、取引/返品/月跨返品を自動判別するキャンセル操作に一本化
    キャンセル操作は、下表に従って、取引/返品/月跨返品のいずれかを実行

                当日  翌日以降  翌月          180日より後
      現状態          〜当月内  〜180日以内
      -------------------------------------------------
      仮売上    取消   返品     返品          エラー
      実売上    取消   返品     月跨返品      エラー
      即時売上  取消   返品     月跨返品      エラー
    
  - モジュール設定画面にてクレジット支払方法／回数を選択できるよう機能追加
  - モジュール設定の登録時、EC-CUBE本体のファイルを上書きする際、バックアップファイルを作成
    バックアップ場所は、data/downloads/module/mdl_pg_mulpay_backup/yyyy-MM-dd_HH:mm:ss
  - ダウンロード商材に対応。結果通知receive.phpで、支払日(dtb_order.payment_date)を適切に設定する
  - iD決済状況変更機能を追加。変更操作は、実売上/キャンセル/金額変更の三つ
  - モジュール決済情報入力画面から「戻る」場合、在庫数が調整されない不具合を修正
  - ATM,iD,PayPal,Suica,Webmoneyの各決済で、商品名が正しく設定されない不具合を修正
  - EC-CUBEのソースツリー上のモジュールから上書きするphpファイルについて、
    マージの物理的な作業量を減らすための修正
    (対象ファイル data/downloads/module/mdl_pg_mulpay/copy/*.php)

20110414
  - Version 2.0.1 リリース

20110413
  - Version 2.0.1
  - 「カード決済状況変更機能」インストール時に、data/Smarty/templates/admin/order/subnavi.tpl
     が上書きされない問題を修正

20110404
  - Version 2.0.0 (EC-CUBE 2.11.0に対応)リリース

20110401
  - Version 2.0.0 (EC-CUBE 2.11.0に対応)


=========================================================================
モジュールバージョン 2.0.0, 2.0.1からアップデートする際に注意するファイル
=========================================================================

  2.0.0, 2.0.1では、下記のファイルをモジュール側で上書きしていました。

  - data/class_extends/page_extends/LC_Page_Ex.php
  - data/class_extends/page_extends/shopping/LC_Page_Shopping_Confirm_Ex.php
  - data/class_extends/page_extends/shopping/LC_Page_Shopping_LoadPaymentModule_Ex.php

  このカスタマイズは、本バージョンから不要になりましたので、ファイルを
  元に戻す必要があります。

  モジュール設定画面で自動上書きを行った場合は、それで元に戻ります。

  自動上書きを利用しない場合は、手動で戻す必要があります。
  data/downloads/module/mdl_pg_mulpay/copyディレクトリの同名ファイルか、
  EC-CUBEのソースコードから復元して下さい。

  加盟店樣独自のカスタマイズがある場合は、独自部分を再度マージいただけ
  ますようおねがい致します。

===================================================
EC-CUBEのソースツリー上にインストールされるファイル
===================================================

  モジュール設定時に本体側へコピーされるファイルは、
  data/downloads/module/mdl_pg_mulpay/copyディレクトリにあります。
  2クリック関連の一部ファイルは、copy/2clickディレクトリの下にあります。

  ■ 必ずインストールする必要があるファイル

    上書きするファイル                     コピー先ディレクトリ
    LC_Page_Shopping_Complete_Ex.php       data/class_extends/page_extends/shopping/
    SC_Helper_Purchase_Ex.php              data/class_extends/helper_extends/
    SC_Utils_Ex.php                        data/class_extends/
 
    新規作成するファイル                   作成先
    receive.php                            html/pg_mulpay/receive.php
    code_visa.gif.php                      html/user_data/code_visa.gif (名前変更)
    gmo_id.gif.php                         html/user_data/gmo_id.gif (名前変更)
    code_amex.gif.php                      html/user_data/code_amex.gif (名前変更)
    gmo_id_on.gif.php                      html/user_data/gmo_id_on.gif (名前変更)


  ■ 決済状況変更機能(クレジット,PayPal,iD)に必要なファイル

    上書きするファイル                     コピー先ディレクトリ
    LC_Page_Admin_Ex.php                   data/class_extends/page_extends/admin/
    LC_Page_Admin_Order_Edit_Ex.php        data/class_extends/page_extends/admin/order/
    subnavi.tpl                            data/Smarty/templates/admin/order/

    新規作成するファイル                   作成先
    gmopg_credit_status.php                html/admin/order/gmopg_credit_status.php
    gmopg_paypal_status.php                html/admin/order/gmopg_paypal_status.php
    gmopg_netid_status.php                 html/admin/order/gmopg_netid_status.php
    gmopg_au_status.php                    html/admin/order/gmopg_au_status.php
                                           ※ インストール時にadminの部分を変更した場合は、
                                              その変更場所にコピーされます

  ■ 2クリック決済機能に必要なファイル

    上書きするファイル                     コピー先ディレクトリ
    SC_CartSession_Ex.php                  data/class_extends/
    LC_Page_Cart_Ex.php                    data/class_extends/page_extends/cart/
    LC_Page_Mypage_DeliveryAddr_Ex.php     data/class_extends/page_extends/mypage/
    LC_Page_Shopping_Payment_Ex.php        data/class_extends/page_extends/shopping/

    新規作成するファイル                   コピー先ディレクトリ
    btn_determine.jpg                      user_data/packages/default/img/button/btn_determine.jpg
    btn_determine_on.jpg                   user_data/packages/default/img/button/btn_determine_on.jpg
    btn_2click.jpg                         user_data/packages/default/img/button/btn_2click.jpg
    btn_2click_on.jpg                      user_data/packages/default/img/button/btn_2click_on.jpg

    ※copy/2click/html/twoClick/にあります
    新規作成するファイル                   コピー先ディレクトリ
    index.php                              html/twoClick/index.php
    deliv.php                              html/twoClick/deliv.php
    payment.php                            html/twoClick/payment.php
    point.php                              html/twoClick/point.php
    confirm.php                            html/twoClick/confirm.php
    load_payment_module.php                html/twoClick/load_payment_module.php
    multiple.php                           html/twoClick/multiple.php

    ※2click/data/Smarty/templates/default/twoClick/にあります
    新規作成するファイル                   コピー先ディレクトリ
    cart_index.tpl                         data/Smarty/templates/default/cart/index.tpl
    deliv.tpl                              data/Smarty/templates/default/twoClick/deliv.tpl
    payment.tpl                            data/Smarty/templates/default/twoClick/payment.tpl
    point.tpl                              data/Smarty/templates/default/twoClick/point.tpl
    confirm.tpl                            data/Smarty/templates/default/twoClick/confirm.tpl
    multiple.tpl                           data/Smarty/templates/default/twoClick/multiple.tpl

    ※2click/data/Smarty/templates/sphone/twoClick/にあります
    新規作成するファイル                   コピー先ディレクトリ
    cart_index.tpl                         data/Smarty/templates/sphone/cart/index.tpl
    deliv.tpl                              data/Smarty/templates/sphone/twoClick/deliv.tpl
    payment.tpl                            data/Smarty/templates/sphone/twoClick/payment.tpl
    point.tpl                              data/Smarty/templates/sphone/twoClick/point.tpl
    confirm.tpl                            data/Smarty/templates/sphone/twoClick/confirm.tpl
    multiple.tpl                           data/Smarty/templates/sphone/twoClick/multiple.tpl

    ※2click/data/Smarty/templates/mobile/twoClick/にあります
    新規作成するファイル                   コピー先ディレクトリ
    cart_index.tpl                         data/Smarty/templates/mobile/cart/index.tpl
    deliv.tpl                              data/Smarty/templates/mobile/twoClick/deliv.tpl
    payment.tpl                            data/Smarty/templates/mobile/twoClick/payment.tpl
    point.tpl                              data/Smarty/templates/mobile/twoClick/point.tpl
    confirm.tpl                            data/Smarty/templates/mobile/twoClick/confirm.tpl
    multiple.tpl                           data/Smarty/templates/mobile/twoClick/multiple.tpl
    select_deliv.tpl                       data/Smarty/templates/mobile/twoClick/select_deliv.tpl


********************************************************************************


  上記ファイルのインストールを実行させないようにするには、
  モジュール設定の確認画面の項目:

     ▼変更を手動で反映
     [ ] 自動で上書きせずに、手動で変更を反映したい場合はチェックを入れてください

  に、チェックを入れて下さい。以後、モジュール設定を行っても、ファイルの上書きは
  しません。


********************************************************************************

  意図せず上書きしてしまった場合も、下記フォルダ

     data/downloads/module/mdl_pg_mulpay_backup/yyyy-MM-dd_HH:mm:ss

  にバックアップファイルが作成されますので、リカバリに利用できます。 
  フォルダ名yyyy-MM-dd_HH:mm:ssの部分は、モジュール設定を行った日時です。

********************************************************************************


========================
画面テンプレートファイル
========================

  ■ 2クリック決済 PC用テンプレート
    1. data/Smarty/templates/default/twoClick/cart_index.tpl
    2. data/Smarty/templates/default/twoClick/deliv.tpl
    3. data/Smarty/templates/default/twoClick/payment.tpl
    4. data/Smarty/templates/default/twoClick/point.tpl
    5. data/Smarty/templates/default/twoClick/confirm.tpl
    6. data/Smarty/templates/default/twoClick/multiple.tpl

    通常購入フローのテンプレート data/Smarty/templates/default/shopping/
    と同名のtplに対応します。

    cart_index.tplは、通常フローの data/Smarty/templates/default/cart/index.tpl
    に対応します。


  ■ 2クリック決済 スマートフォン用テンプレート
    1. data/Smarty/templates/sphone/twoClick/cart_index.tpl
    2. data/Smarty/templates/sphone/twoClick/deliv.tpl
    3. data/Smarty/templates/sphone/twoClick/payment.tpl
    4. data/Smarty/templates/sphone/twoClick/point.tpl
    5. data/Smarty/templates/sphone/twoClick/confirm.tpl
    6. data/Smarty/templates/sphone/twoClick/multiple.tpl

    通常購入フローのテンプレート data/Smarty/templates/sphone/shopping/
    と同名のtplに対応します。

    cart_index.tplは、通常フローの data/Smarty/templates/sphone/cart/index.tpl
    に対応します。


  ■ 2クリック決済 モバイル用テンプレート
    1. data/Smarty/templates/mobile/twoClick/cart_index.tpl
    2. data/Smarty/templates/mobile/twoClick/deliv.tpl
    3. data/Smarty/templates/mobile/twoClick/payment.tpl
    4. data/Smarty/templates/mobile/twoClick/point.tpl
    5. data/Smarty/templates/mobile/twoClick/confirm.tpl
    6. data/Smarty/templates/mobile/twoClick/multiple.tpl
    7. data/Smarty/templates/mobile/twoClick/select_deliv.tpl

    通常購入フローのテンプレート data/Smarty/templates/mobile/shopping/
    と同名のtplに対応します。

    cart_index.tplは、通常フローの data/Smarty/templates/mobile/cart/index.tpl
    に対応します。


  ■ モジュール決済情報入力画面 PC用テンプレート
    1. data/downloads/module/mdl_pg_mulpay/templates/default_credit.tpl
       クレジット決済
    2. data/downloads/module/mdl_pg_mulpay/templates/default_conveni.tpl
       コンビニ決済
    3. data/downloads/module/mdl_pg_mulpay/templates/default_suica.tpl
       モバイルSuica
    4. data/downloads/module/mdl_pg_mulpay/templates/default_edy.tpl
       Edy


  ■ モジュール決済情報入力画面 スマートフォン用テンプレート
    1. data/downloads/module/mdl_pg_mulpay/templates/sphone_credit.tpl
       クレジット決済
    2. data/downloads/module/mdl_pg_mulpay/templates/sphone_conveni.tpl
       コンビニ決済
    3. data/downloads/module/mdl_pg_mulpay/templates/sphone_suica.tpl
       モバイルSuica
    4. data/downloads/module/mdl_pg_mulpay/templates/sphone_edy.tpl
       Edy


  ■ モジュール決済情報入力画面 モバイル用テンプレート
    1. data/downloads/module/mdl_pg_mulpay/templates/mobile_credit.tpl
       クレジット決済
    2. data/downloads/module/mdl_pg_mulpay/templates/mobile_conveni.tpl
       コンビニ決済
    3. data/downloads/module/mdl_pg_mulpay/templates/mobile_suica.tpl
       モバイルSuica
    4. data/downloads/module/mdl_pg_mulpay/templates/mobile_edy.tpl
       Edy


======================================
conf/mdl_pg_mulpay_config.php.defaults
======================================

  決済方法、コンビニごとに、GMOPGからメールを送信するかどうかを制御する。
  mdl_pg_mulpay_config.phpにコピーして利用する。
  詳しくはファイル内のコメントを参照


以上

$Date: 2012-04-03 16:56:39 +0900 (Tue, 03 Apr 2012) $
