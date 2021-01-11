<html>
    <head>
        <title>Banco de dados: Lista</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="css/bulma.min.css" />
        <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>
    <body>
         <section>
        <div class="hero-body">
            <div class="has-text-justified">
                <div class="column is-offset-4">
                    <h3 class="title has-text-grey">Lista de Ordens cadastradas</h3>
                   
   
                    <table class="table is-left" border="2">
            <tr>
                <th>Nome do Cliente</th>
                <th>Tipo de Serviço</th>
                <th>Equipamento</th>
                <th>Em andamento</th>
                <th>Programada</th>
                <th>Encerrada</th>
                <th>Cancelada</th>
            </tr>
<?php

    try
    {
        $connection = new PDO("mysql:host=127.0.0.1:3306;dbname=login","root","");
        $connection->exec("set names utf8");
    }
        catch (PDOException $ex)
    {
            echo "Falha: ".$e->getMessage();
            exit();
    }
            
    if(isset($_REQUEST["excluir"])&&$_REQUEST["excluir"]==true)
    {
        $stmt=$connection->prepare("DELETE FROM ordems WHERE os=?");
        $stmt->bindParam(1,$_REQUEST["os"]);
        $stmt->execute();
        
        if($stmt->errorCode() !="00000")
        {
            echo "Erro código".$stmt->errorCode().":";
            echo implode(",",$stmt->errorInfo());
        }
        else
        {
            echo "Sucesso: OS removida com sucesso<BR><BR>";
        }
    }
    $rs = $connection->prepare("SELECT * FROM ordems");
    
    if($rs->execute())
    {
    while ($registro = $rs->fetch(PDO::FETCH_OBJ))
    {
        echo "<TR>";
        
        echo "<TD>".$registro->nome_cliente."</TD>";
        echo "<TD>".$registro->tipo_servico."</TD>";
        echo "<TD>".$registro->equipamento."</TD>";
        echo "<TD>".$registro->andamento."</TD>";
        echo "<TD>".$registro->programada."</TD>";
        echo "<TD>".$registro->encerrada."</TD>";
        echo "<TD>".$registro->cancelada."</TD>";
        
        echo "<TD>";
        echo "<A class='button is-center is-link is-bold' href='?excluir=true&os=" .$registro->os. "'>Excluir</A>";
        echo "<A class='button is-center is-link is-bold' href='Alterar.php?os=" .$registro->os. "'>    Alterar</A>";
        echo "<TD>";
    }
    }
    else
    {
        echo "Falha na seleção das ordens<BR>";
    }
    
    ?>
        </table>
        
        <BR>
        <a href="painel.php" type="submit" class="is-text-justified button is-link is-large is-bold">Criar novo registro</a>
                </div>
            </div>
        </div>
         </section>
    </body>
</html>