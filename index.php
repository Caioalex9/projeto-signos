<?php
// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Carregar o arquivo XML
$arquivoXml = "signos.xml";
if (file_exists($arquivoXml)) {
    $signos = simplexml_load_file($arquivoXml);
} else {
    die("Erro: O arquivo XML não foi encontrado!");
}

// Função para determinar o signo com base na data
function determinarSigno($dia, $mes, $signos) {
    foreach ($signos->signo as $signo) {
        $dataInicio = explode("/", $signo->dataInicio);
        $dataFim = explode("/", $signo->dataFim);

        $diaInicio = intval($dataInicio[0]);
        $mesInicio = intval($dataInicio[1]);
        $diaFim = intval($dataFim[0]);
        $mesFim = intval($dataFim[1]);

        if (
            ($mes == $mesInicio && $dia >= $diaInicio) ||
            ($mes == $mesFim && $dia <= $diaFim) ||
            ($mes > $mesInicio && $mes < $mesFim) ||
            ($mesInicio > $mesFim && ($mes > $mesInicio || $mes < $mesFim))
        ) {
            return $signo;
        }
    }
    return null;
}

// Verificar se o formulário foi enviado
$resultado = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $dataNascimento = htmlspecialchars($_POST['dataNascimento']);
    $data = explode("-", $dataNascimento);

    if (count($data) == 3) {
        $ano = intval($data[0]);
        $mes = intval($data[1]);
        $dia = intval($data[2]);

        $signoEncontrado = determinarSigno($dia, $mes, $signos);
        if ($signoEncontrado) {
            $resultado = [
                'nome' => $nome,
                'signo' => (string) $signoEncontrado->nome,
                'descricao' => (string) $signoEncontrado->descricao
            ];
        } else {
            $resultado = "Não foi possível determinar seu signo. Verifique a data.";
        }
    } else {
        $resultado = "Data de nascimento inválida.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descubra seu Signo</title>
</head>
<body>
    <h1>Descubra seu Signo</h1>
    <form method="POST" action="">
        <label for="nome">Seu Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br><br>
        <label for="dataNascimento">Data de Nascimento:</label>
        <input type="date" id="dataNascimento" name="dataNascimento" required>
        <br><br>
        <button type="submit">Descobrir Signo</button>
    </form>

    <?php if ($resultado): ?>
        <h2>Resultado:</h2>
        <?php if (is_array($resultado)): ?>
            <p><strong>Nome:</strong> <?= $resultado['nome'] ?></p>
            <p><strong>Signo:</strong> <?= $resultado['signo'] ?></p>
            <p><strong>Descrição:</strong> <?= $resultado['descricao'] ?></p>
        <?php else: ?>
            <p><?= $resultado ?></p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
