<?php
session_start();
include('verifica_login.php');
?>

<h2 class="title has-text-grey"> Olá, <?php echo $_SESSION['nome'];?></h2>
<h2><a href="logout.php" type="submit" class="is-text-justified button is-link is-large is-bold">Sair</a></h2>
    
<!--
Cria um formulario que requisita dados da ordem de serviço
-->
        <?php
        
        $erro = null;
        $valido = false;
        
        if(isset($_REQUEST["validar"])&& $_REQUEST["validar"]==true)
        {
            if(strlen(utf8_decode($_POST["nome_cliente"]))<5)
            {
                $erro = "Preencha o campo Nome do Cliente corretamente (5 ou mais caracteres)";
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
                        $valido = true;
                        
                        try
                        {
                            $connection = new PDO("mysql:host=127.0.0.1:3306;dbname=login","root","");
                            $connection->exec("set names utf-8");
                        } 
                        catch (PDOException $e) 
                        {
                            echo "Falha: ".$e->getMessage();
                            exit();
                        }
                         
                        $sql = "INSERT INTO ordems
                                (nome_cliente, tipo_servico, equipamento, andamento, programada, encerrada, cancelada)
                                VALUES (?,?,?,?,?,?,?)";
                                              
                         
                        $stmt = $connection->prepare($sql);
                        
                        $stmt->bindParam(1, $_POST["nome_cliente"]);
                        $stmt->bindParam(2, $_POST["tipo_servico"]);
                        $stmt->bindParam(3, $_POST["equipamento"]);
                        
                        $checkHumanas = isset($_POST["andamento"]) ? 1 : 0;
                        $stmt->bindParam(4, $checkandamento);
                        
                        $checkExatas = isset($_POST["programada"]) ? 1 : 0;
                        $stmt->bindParam(5, $checkprogramada);
                        
                        $checkBiologicas = isset($_POST["encerrada"]) ? 1 : 0;
                        $stmt->bindParam(6, $checkencerrada);
                        
                        $checkBiologicas = isset($_POST["cancelada"]) ? 1 : 0;
                        $stmt->bindParam(7, $checkcancelada);
                        
                        $stmt->execute();
                        
                        if($stmt->errorCode() !="00000")
                        {
                            $valido = false;
                            $erro = "Erro código" . $stmt->errorCode().":";
                            $erro .=implode(",",$stmt->errorInfo());
                        }
                    }        
                }
        
        ?>
        
        <!-- 
        Banco de Dados: ordems
        -->
<html>
    <head>
        <title>Banco de dados: Ordens</title>
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
                
                    <h3 class="title has-text-grey">Cadastrar Ordem de Serviço</h3>
        <?php
        
        if($valido==true)
        {
            echo "Dados enviados com sucesso!";
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
        <BR>
        <FORM method=POST action="?validar=true" class="is-size-4-desktop">
            Nome do Cliente:
            <input type=TEXT name=nome_cliente
                   <?php if(isset($_POST["nome_cliente"])) {
                       echo "value='" .$_POST["nome_cliente"]."'";
                   }
                   ?>
                   ><BR>
            
            Tipo de Serviço:
            <input type=TEXT name=tipo_servico
                   <?php if(isset($_POST["tipo_servico"])) {
                       echo "value='" .$_POST["tipo_servico"]."'";
                   }
                   ?>
                   ><BR>
            
            Equipamento:
            <input type=TEXT name=equipamento
                   <?php if(isset($_POST["equipamento"])) {
                       echo "value='" .$_POST["equipamento"]."'";
                   }
                   ?>
                   ><BR><BR>
            
            <h3>Status:</h3>
            <input type=CHECKBOX name="andamento"
                   <?php if(isset($_POST["andamento"])) {
                        echo "checked";
                   }
                   ?>
                   >    Andamento
            <BR>
            
            <input type=CHECKBOX name="programada"
                   <?php if(isset($_POST["programada"])) {
                        echo "checked";
                   }
                   ?>
                   >    Programada
            <BR>
            
            <input type=CHECKBOX name="encerrada"
                   <?php if(isset($_POST["encerrada"])) {
                        echo "checked";
                   }
                   ?>
                   >    Encerrada
            <BR>
            
            <input type=CHECKBOX name="cancelada"
                   <?php if(isset($_POST["cancelada"])) {
                        echo "checked";
                   }
                   ?>
                   >    Cancelada
            <BR>
            <BR>
            
            <input type=SUBMIT class="button is-center is-link is-large is-bold" value="Salvar">
            

        </form>
        
        <a href="Lista.php" type="submit" class="button is-center is-link is-large is-bold">Visualizar Registros</a>
        
        <?php
            }
           ?>
    </body>
</html>
