<?php
class Form
{
  private $message = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST["titulo"]) && isset($_POST["release"]) && isset($_POST["local"])) {
      try {
        $conexao = Transaction::get();
        $noticia = new Crud("noticia");
        $titulo = $conexao->quote($_POST["titulo"]);
        $release = $conexao->quote($_POST["release"]);
        $local = $conexao->quote($_POST["local"]);
        $resultado = $noticia->insert("titulo, `release`, local", "$titulo, $release, $local");
      } catch (Exception $e) {
        echo $e->getMessage();
      }
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