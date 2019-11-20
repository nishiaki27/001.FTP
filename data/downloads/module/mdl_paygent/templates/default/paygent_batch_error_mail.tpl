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
ペイジェント決済の入金検知バッチにてエラーが発生しましたので通知いたします。

エラー内容:
通知情報の取得が一定期間実行されていないため、
ペイジェント側で当該の通知データが削除されています。

計 <!--{$id_total}-->件の取得できなかった通知データがありましたので、
ペイジェントオンラインで当該の決済を確認し、
EC-CUBE側のデータを手動で更新してください。

※削除されたデータがどの決済の通知情報であったかを検索することはできません。
  正常にデータが反映されていない期間を確認し、期間内にステータスが変わった
  データを検索してください。

<決済通知ID>
・<!--{$id_from}-->～<!--{$id_to}-->(計 <!--{$id_total}-->件)