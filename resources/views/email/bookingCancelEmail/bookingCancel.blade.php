{{ $user->last_name .$user ->first_name }} 様

以下の予約をキャンセルしました。

【お問い合わせ内容】
ペットのお名前:{{ $pet -> name}}
犬種:{{ $pet -> dogtype -> type}}
予約店舗: con affetto　{{ $salon -> salon_name }}
コース: {{ $course -> courseMaster -> course}}
予約日：{{ $date }}
開始時刻:{{ $date }}
予約日：{{ $date }}
開始時刻：{{ $st_time }}
終了予定時刻：{{ $ed_time_for_show }}
メッセージ:
{{ $message_text }}

(ご注意)
初めてご予約のお客様や、予約の状況に応じて店舗よりお客様にお電話をさせていただくことがあります。
また、初めてご利用のお客様は来店時にワクチン接種証明書をご持参ください。

※このメールは配信専用のアドレスで配信されています。
このメールに返信されても返信内容の確認およびご返答ができませんので、ご了承ください。