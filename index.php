<!DOCTYPE html>
<html>
<head>
    <title>Gerador de QR Code PIX</title>
    <meta charset="UTF-8">
</head>
<body>
<h1>Gerador de QR Code PIX</h1>
<form action="gerar_qr.php" method="POST">
    <div>
        <label>Chave PIX:</label>
        <input type="text" name="chave_pix" required>
    </div>
    <div>
        <label>Valor (R$):</label>
        <input type="number" name="valor" step="0.01" required>
    </div>
    <div>
        <label>Nome do Recebedor:</label>
        <input type="text" name="recebedor" required>
    </div>
    <button type="submit">Gerar QR Code</button>
</form>
</body>
</html>