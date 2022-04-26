<?php
class Tabela
{
  private $message = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    Transaction::get();
    $noticia = new Crud("noticia");
    $resultado = $noticia->select();
    $tabela = new Template("view/tabela.html");
    if(is_array($resultado) > 0){ 
    $tabela->set("linha", $resultado);
    $this->message = $tabela->saida();
    }
  }
  public function remover()
  {
    try {
      $conexao = Transaction::get();
      $id = $conexao->quote($_GET["id"]);
      $noticia = new Crud("noticia");
      $resultado = $noticia->delete("id = $id");
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function getMessage()
  {
    return $this->message;
  }
  public function __destruct()
  {
    Transaction::close();
  }
}
