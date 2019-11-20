<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright (c) 2006 PAYGENT Co.,Ltd. All rights reserved.
 *
 * https://www.paygent.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*}-->
マーチャントID：<!--{$merchant_id}-->
ご担当者様

ペイジェントをご利用いただき誠にありがとうございます。

Paidyへ送信された購入金額と受注情報の購入金額が一致していません。
ペイジェントオンラインで売上処理をしている場合は、返金処理をお願い致します。

■対象決済
決済ID：<!--{$payment_id}-->
取引ID：<!--{$trading_id}-->
Paidyへ送信された購入金額：<!--{$paidy_amount|default:0|number_format}-->円
受注情報の購入金額：<!--{$payment_amount|default:0|number_format}-->円

■ペイジェントオンライン：https://online.paygent.co.jp/
※ログインできない場合は下記URLをご参照下さい。
  http://www.paygent.co.jp/notes/pgol_login.pdf
※クライアント証明書シリアル番号の確認方法は下記URLをご参照下さい。
  http://www.paygent.co.jp/notes/check_serial.html
※パスワードは定期的にご変更下さい。
　 変更後のパスワードの有効期限は１００日間となります。
※ペイジェントオンラインの利用方法は、『PAYGENT online 利用　マニュアル』をご参照下さい。
　下記加盟店サポートページからもダウンロードできます。
ご不明点等ございましたらお問合せ下さいませ。
どうぞ宜しくお願い致します。
-----------------------------------------------------
■ペイジェントサービスカウンター（加盟店様専用）
　TEL：050-3066-0300
　MAIL：pg-support@paygent.co.jp
　営業時間　9:00～17:30（平日のみ）

■ペイジェント加盟店サポートページ
　http://www.paygent.co.jp/merchant/
　ID： paygent   PASSWORD： payment
