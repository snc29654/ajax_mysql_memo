<?php
include('param.php');

header('Content-Type: text/html; charset=UTF-8');

try{
	

    $pdo = new PDO(
        $dsn,
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$pdo->query("SET NAMES UTF8;");


}catch(PDOException $Exception){
    die('接続できません：' .$Exception->getMessage());
}

$srch_word = $_POST['srch_word'];
$kind = $_POST['kind'];
try{

    if(strcmp($_POST['actionread'],"srch")==0){

        $sql = "SELECT * FROM $dbname.$dbtable WHERE Contents LIKE '%" . $srch_word . "%'";

    }else if(strcmp($_POST['actionread'],"kindselect")==0){

        $sql = "SELECT * FROM $dbname.$dbtable WHERE kind LIKE '%" . $kind . "%'";

    }else if(strcmp($_POST['actionread'],"readall")==0){

        $sql = "SELECT * FROM $dbname.$dbtable";
    }else{
 
    }
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
}catch(PDOException $Exception){
    die('接続エラー：' .$Exception->getMessage());
}
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
?>

<?php
	file_put_contents("../db_log.txt", $row['id']."\n",FILE_APPEND);
	file_put_contents("../db_log.txt", $row['kind']."\n",FILE_APPEND);
	file_put_contents("../db_log.txt", $row['contents']."\n",FILE_APPEND);

    echo "id=";echo strip_tags($row['id']);      echo "\n";
    echo "*************************************\n";
    
    echo strip_tags($row['kind']);      echo "\n";
    echo "*************************************\n";
    echo strip_tags($row['contents']);   echo "\n";   
    echo "*************************************\n";
    }
?>
