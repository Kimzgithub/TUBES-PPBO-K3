<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUBES PPBO - KELOMPOK 3</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 16px;
            max-width: 540px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .badge {
            display: inline-block;
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #ffffff;
        }
        p {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .status-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px dashed rgba(255, 255, 255, 0.15);
            padding: 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #38bdf8;
        }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">Laravel {{ app()->version() }} • Siap Digunakan</span>
        <h1>Projek TUBES PPBO - K3</h1>
        <p>Halaman utama ini sudah berhasil diperbarui dan siap diisi dengan fitur atau tampilan web tugas kelompok Anda.</p>
        
        <div class="status-box">
            Status Server: <strong>Online</strong> (localhost)
        </div>
    </div>
</body>
</html>
