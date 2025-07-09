<?php
$host = 'localhost';
$banco = 'igreja';
$password = '';
$usuario = 'root';

$conexao = new mysqli($host , $usuario, $password, $banco);
if ($conexao -> connect_error){
    echo"Erro  na conexão";
}else{
   
}