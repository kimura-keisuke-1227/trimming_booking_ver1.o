{{ $user->last_name .$user ->first_name }} 様

以下の通り、予約を受付けました。

【お問い合わせ内容】
ペットのお名前:{{ $pet -> name}}
犬種:{{ $pet -> name}}
予約店舗: {{ $salon -> salon_name }}
コース: {{ $course -> courseMaster -> course}}
予約日：{{ $date }}
開始時刻:{{ $st_time }}
終了予定時刻：{{ $ed_time_for_show }}
メッセージ:
{{ $message_text }}

※このメールは配信専用のアドレスで配信されています。
このメールに返信されても返信内容の確認およびご返答ができませんので、ご了承ください。