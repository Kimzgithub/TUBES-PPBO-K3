<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Baru</title>
</head>
<body>
    <h2>Halo, {{ $recipient->name }}!</h2>

    <p>{{ $message->body() }}</p>

    <p>
        Kamu menerima notifikasi baru dari
        {{ $message->actor()->name }}.
    </p>
</body>
</html>