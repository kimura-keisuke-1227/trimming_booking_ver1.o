{{ $nonMemberBooking->last_name .$nonMemberBooking ->first_name }} 様

以下の予約をキャンセルしました。

【予約内容】
ペットのお名前:{{ $nonMemberBooking -> name}}
犬種:{{ $booking -> course -> dogtype -> type}}
予約店舗: {{ $salon -> salon_name }}
コース: {{ $booking-> course -> courseMaster -> course}}
予約日: {{ $date}}
開始時刻: {{ $st_time }}
電話番号:{{ $nonMemberBooking->phone }} 
メールアドレス：{{ $nonMemberBooking->email }} 
メッセージ:
{{ $message_text }}

※このメールは配信専用のアドレスで配信されています。
このメールに返信されても返信内容の確認およびご返答ができませんので、ご了承ください。