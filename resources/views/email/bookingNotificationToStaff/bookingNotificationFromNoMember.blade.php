{{ $nonMemberBooking->last_name .$nonMemberBooking ->first_name }} 様

以下の通り、予約を受付けました。

【予約内容】
ペットのお名前:{{ $nonMemberBooking -> name}}
犬種:{{ $booking -> course -> dogtype -> type}}
予約店舗: {{ $salon -> salon_name }}
コース: {{ $booking-> course -> courseMaster -> course}}
予約日：{{ $date }}
開始時刻：{{ $st_time }}
終了時刻：{{ $ed_time }}
お客様向け終了予定時刻：{{ $ed_time_for_show }}
メッセージ:
{{ $booking -> message }}

(ご注意)
予約の状況に応じて店舗よりお客様にお電話をさせていただくことがあります。
また、来店時に狂犬病と混合ワクチン接種証明書をご持参ください。

※このメールは配信専用のアドレスで配信されています。
このメールに返信されても返信内容の確認およびご返答ができませんので、ご了承ください。