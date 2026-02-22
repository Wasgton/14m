<!DOCTYPE html>
<html>
<head>
    <title>Novo Contato pelo Site</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; color: #333;">
    <h2>Novo Contato - KTORZE M</h2>
    <p><strong>Nome:</strong> {{ $name }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Mensagem:</strong></p>
    <div style="background: #f4f4f5; padding: 15px; border-radius: 8px;">
        {!! nl2br(e($messageStr)) !!}
    </div>
</body>
</html>
