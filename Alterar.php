<?php

$erro = null;
$valido = false;

try{
    $connection = new PDO("mysql:host=127.0.0.1:3306;dbname=login","root","");
    $connection->exec("set names utf-8");
} 
catch (PDOException $e)
{
    echo "Falha: ".$e->getMessage();
    exit();
}

if(isset($_REQUEST["validar"]) && $_REQUEST["validar"]==true)
{
    if(strlen(utf8_decode($_POST["nome_cliente"]))<5)
    {
        $erro="Preencha o campo Nome do Cliente corretamente (5 ou mais caracteres)";
    }
        else if(strlen(utf8_decode($_POST["tipo_servico"]))<6)
            {
                $erro = "Preencha corretamente o tipo de serviço.";
            }
            else if(strlen(utf8_decode($_POST["equipamento"]))<3)
                { 
                $erro = "Preencha o campo Equipamento";
                }
         else
         {
             $valido=TRUE;
             
             $sql="UPDATE ordems SET
                     nome_cliente=?,
                     tipo_servico=?,
                     equipamento=?,
                     andamento=?,
                     programada=?,
                     encerrada=?,
                     cancelada=?
                     WHERE os=?";
             
             $stmt=$connection->prepare($sql);
             
             $stmt->bindParam(1, $_POST["nome_cliente"]);
             $stmt->bindParam(2, $_POST["tipo_servico"]);
             $stmt->bindParam(3, $_POST["equipamento"]);
             
             $checkHumanas= isset($_POST["andamento"])? 1:0;
             $stmt->bindParam(4, $checkandamento);
             
             $checkHumanas= isset($_POST["programada"])? 1:0;
             $stmt->bindParam(5, $checkprogramada);
             
             $checkHumanas= isset($_POST["encerrada"])? 1:0;
             $stmt->bindParam(6, $checkencerrada);
             
             $checkHumanas= isset($_POST["cancelada"])? 1:0;
             $stmt->bindParam(7, $checkcancelada);
             
             $stmt->bindParam(8, $_POST["os"]);
             
             $stmt->execute();
             
             if($stmt->errorCode() != "00000")
             {
                 $valido=FALSE;
                 $erro="Erro código: ".$stmt->errorCode().":";
                 $erro .=implode(",", $stmt->errorInfo());
             }
         }
}
else
{
    $rs = $connection->prepare("SELECT * FROM ordems WHERE os=?"); 
    $rs->bindParam(1, $_REQUEST["os"]);
    
    if ($rs->execute())
    {
        if($registro=$rs->fetch(PDO::FETCH_OBJ))
        {
            $_POST["nome_cliente"]=$registro->nome_cliente;
            $_POST["tipo_servico"]=$registro->tipo_servico;
            $_POST["equipamento"]=$registro->equipamento;
            
            $_POST["andamento"]=$registro->andamento==1 ? true:NULL;
            $_POST["programada"]=$registro->programada==1 ? true:NULL;            
            $_POST["encerrada"]=$registro->encerrada==1 ? true:NULL;
            $_POST["cancelada"]=$registro->cancelada==1 ? true:NULL;
        }
        else
        {
            $erro="Registro não encontrado";
        }
    }
        else
        {
            $erro="Falha na captura do registro";
        }
    }
?>
<html>
    <head>
        <title>Banco de Dados: Alteração</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="css/bulma.min.css" />
        <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>
    <body>
        
        <div class="hero-body">
            <div class="container has-text-justified">
                
                    <h3 class="title has-text-grey">Alterar ordens cadastradas</h3>
    
        <?php
        
        if($valido==TRUE)
        {
            echo "Dados alterados com sucesso!";
            echo "<BR><BR>";
            echo "<A href='Lista.php' class='button is-center is-link is-large is-bold'>Visualizar registros</A>";
        }
 else
    {
     if(isset($erro))
     {
         echo $erro."<BR><BR>";
     }
     
     ?>
                    <form method=POST action="?validar=true" class="is-size-4-desktop">
            <ins>Nome do Cliente:</ins> 
            <input type="TEXT" name="nome_cliente"
                   <?php if(isset($_POST["nome_cliente"])){ echo "value='" .$_POST["nome_cliente"]."'";}?>
                   ><BR><BR>
                   
            <ins>Tipo de Serviço:</ins>
            <input type="TEXT" name="tipo_servico"
                   <?php if(isset($_POST["tipo_servico"])){ echo "value='" .$_POST["tipo_servico"]."'";}?>
                   ><BR><BR>
            
            <ins>Equipamento:</ins>
            <input type="TEXT" name="equipamento"
                   <?php if(isset($_POST["equipamento"])){ echo "value='" .$_POST["equipamento"]."'";}?>
                   ><BR><BR>
            
            <ins>Status:</ins>
                <BR>
            <input type="CHECKBOX" name="andamento"
                   <?php if(isset($_POST["andamento"])){ echo "checked";}?>
                   >    Andamento
            <BR>
            
            <input type="CHECKBOX" name="programada"
                   <?php if(isset($_POST["programada"])){ echo "checked";}?>
                   >    Programada
            <BR>
            
            <input type="CHECKBOX" name="encerrada"
                   <?php if(isset($_POST["encerrada"])){ echo "checked";}?>
                   >    Encerrada
            <BR>
            
            <input type="CHECKBOX" name="cancelada"
                   <?php if(isset($_POST["cancelada"])){ echo "checked";}?>
                   >    Cancelada
            <BR><BR>
            
            <input type="HIDDEN" name="os"
                   value="<?php echo $_REQUEST["os"];?>"
            >
            
            <input type="SUBMIT" class="button is-center is-link is-large is-bold" value="Alterar">
            
        </form>
                  <a href="Lista.php" type="submit" class="button is-center is-link is-large is-bold">Voltar</a>
        <?php
    }
        ?>
               
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>


                 