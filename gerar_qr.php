<?php
require_once 'vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chavePix = $_POST['chave_pix'];
    $valor = $_POST['valor'];
    $recebedor = $_POST['recebedor'];

    // Função para criar o payload PIX
    function gerarPayloadPix($chave, $valor, $nome) {
        $payload = "00020126";  // Payload Format Indicator e Merchant Account Information
        $payload .= "26";       // Merchant Account Information
        $payload .= sprintf("%02d", strlen($chave) + 4) . "0014" . $chave;
        $payload .= "52040000"; // Merchant Category Code
        $payload .= "5303986";  // Transaction Currency (BRL)
        $payload .= sprintf("%02d", strlen($valor)) . $valor;
        $payload .= "5802BR";   // Country Code
        $payload .= sprintf("%02d", strlen($nome)) . $nome;
        $payload .= "6304";     // CRC16

        // Calcula o CRC16
        $crc = crc16($payload);
        return $payload . $crc;
    }

    // Função para calcular CRC16
    function crc16($str) {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($str); $i++) {
            $crc ^= ord($str[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }
            }
        }
        return sprintf("%04X", $crc & 0xFFFF);
    }

    // Gera o payload PIX
    $payload = gerarPayloadPix($chavePix, $valor, $recebedor);

    // Configurações do QR Code
    $options = new QROptions([
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel' => QRCode::ECC_L,
        'scale' => 5,
    ]);

    // Gera o QR Code
    $qrcode = new QRCode($options);
    $qrImage = $qrcode->render($payload);
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>QR Code PIX Gerado</title>
    </head>
    <body>
    <h1>QR Code PIX</h1>
    <img src="<?php echo $qrImage; ?>" alt="QR Code PIX">
    <p>Payload: <?php echo $payload; ?></p>
    <a href="index.html">Voltar</a>
    </body>
    </html>

    <?php
}
?>