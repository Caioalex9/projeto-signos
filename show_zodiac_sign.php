<?php include('header.php'); ?>

<?php
$data_nascimento = DateTime::createFromFormat('Y-m-d', $_POST['data_nascimento']);
$signos = simplexml_load_file("signos.xml");

foreach ($signos->signo as $signo) {
    $dataInicio = DateTime::createFromFormat('d/m', $signo->dataInicio);
    $dataFim = DateTime::createFromFormat('d/m', $signo->dataFim);

    $dataInicio->setDate($data_nascimento->format('Y'), $dataInicio->format('m'), $dataInicio->format('d'));
    $dataFim->setDate($data_nascimento->format('Y'), $dataFim->format('m'), $dataFim->format('d'));

    if ($data_nascimento >= $dataInicio && $data_nascimento <= $dataFim) {
        echo "<h1>Seu signo é {$signo->signoNome}</h1>";
        echo "<p>{$signo->descricao}</p>";
        break;
    }
}
?>
<a href="index.php" class="btn btn-secondary">Voltar</a>
</div>
</body>
</html>
