<?php
// Exibe o hash gerado para a senha padrão 123456
$senha = '123456';
$hash = password_hash($senha, PASSWORD_DEFAULT);

echo "<h3>Senha original:</h3>";
echo "<pre>$senha</pre>";

echo "<h3>Hash gerado:</h3>";
echo "<pre>$hash</pre>";
?>
