<?php
class Form
{
  private $message = "";
  private $error = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("titulo", "");
    $form->set("release", "");
    $form->set("local", "");
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
        if (empty($_POST["id"])) {
          $noticia->insert(
            "titulo, `release`, local",
            "$titulo, $release, $local"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $noticia->update(
            "titulo = $titulo,`release` = $release, local = $local",
            "id = $id"
          );
        }
        $this->message = $noticia->getMessage();
        $this->error = $noticia->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }else {
      $this->message = "Campos não informados!";
      $this->error = true;
    }
  }
  public function editar()
  {
    if (isset($_GET["id"])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET["id"]);
        $noticia = new Crud("noticia");
        $resultado = $noticia->select("*", "id = $id");
        if (!$noticia->getError()) {
        $form = new Template("view/form.html");
        foreach ($resultado[0] as $cod => $valor) {
          $form->set($cod, $valor);
        }
        $this->message = $form->saida();
        }else {
          $this->message = $noticia->getMessage();
          $this->error = true;
        }
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function getMessage()
  {
    if (is_string($this->error)) {
      return $this->message;
    } else {
      $msg = new Template("view/msg.html");
      if ($this->error) {
        $msg->set("cor", "danger");
      } else {
        $msg->set("cor", "success");
      }
      $msg->set("msg", $this->message);
      $msg->set("uri", "?class=Tabela");
      return $msg->saida();
    }
  }
  public function __destruct()
  {
    Transaction::close();
  }
}