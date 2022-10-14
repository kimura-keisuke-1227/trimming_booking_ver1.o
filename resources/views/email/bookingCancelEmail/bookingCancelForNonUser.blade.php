{{ $user->last_name .$user ->first_name }} 様

以下の予約をキャンセルしました。

【予約内容】
ペットのお名前:{{ $pet -> name}}
犬種:{{ $pet -> dogtype -> type}}
予約店舗: {{ $salon -> salon_name }}
コース: {{ $course -> courseMaster -> course}}
予約日: {{ $date}}
開始時刻: {{ $st_time }}
メッセージ:
{{ $message_text }}

※このメールは配信専用のアドレスで配信されています。
このメールに返信されても返信内容の確認およびご返答ができませんので、ご了承ください。