<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $notificationMessage->title() }}</title>
</head>
<body>
    <h2>Halo, {{ $recipient->name }}!</h2>

    <p>{{ $notificationMessage->body() }}</p>

    <p>
        Kamu menerima notifikasi baru dari
        {{ $notificationMessage->actor()->name }}.
    </p>
</body>
</html>
