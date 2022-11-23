<?php 

    require "./Bibliotecas/PHPMailer/Exception.php";
    require "./Bibliotecas/PHPMailer/OAuth.php";
    require "./Bibliotecas/PHPMailer/PHPMailer.php";
    require "./Bibliotecas/PHPMailer/POP3.php";
    require "./Bibliotecas/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

class Messagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = array( 'codigo_status'=>null, 'descricao_status'=>'');

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function mensagemValida(){
      if(empty($this->para)||empty($this->assunto)||empty($this->mensagem)){
        return false;
      } 
        return true;
    }
}

$mensagem = new Messagem();
$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto',$_POST['assunto']);
$mensagem->__set('mensagem',$_POST['mensagem']);

if(!$mensagem->mensagemValida()){
    die('Mensagem não é valida');
} 

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'testelimarj@gmail.com';                     //SMTP username
        $mail->Password   = 'ydvkqwsfpbdvyzwb';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('testelimarj@gmail.com', 'Teste Remetente');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto'); //Assunto
        $mail->Body    = $mensagem->__get('mensagem'); //Mensagem
        $mail->AltBody = 'É necessário utilizar um client que suporte HMTL';

        $mail->send();
        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'Mensagem enviada com Sucesso';
    } catch (Exception $e) {
        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = "Não foi possível enviar esse email, por favor tente novamente. Detalhes do erro : {$mail->ErrorInfo}";
    }
?>

<html>
    <head>
    <meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>

        <div class="container">  

			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">

                <div class="col-md-12">
                        <?php if($mensagem->status['codigo_status']==1){?>
                        
                            <div class="container">
                                <h1 class="display-4 text-success">Sucesso!</h1>
                                <p><?= $mensagem->status['descricao_status']; ?></p>
                                <a  href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                            </div>

                        <?php } ?>

                        <?php if($mensagem->status['codigo_status']==2){?>

                            <div class="container">
                                <h1 class="display-4 text-danger">Ops!</h1>
                                <p><?= $mensagem->status['descricao_status']; ?></p>
                                <a  href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                            </div>

                        <?php } ?>
                </div>

            </div>
        </div>

    </body>
</html>