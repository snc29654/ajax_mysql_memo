<?php
include('param.php');

header('Content-Type: text/html; charset=UTF-8');
//if(!$_POST['kind'] || !$_POST['contents']){exit("未入力あり");}

file_put_contents("../from_html.txt", $_POST['kind'] ."\n",FILE_APPEND);
file_put_contents("../from_html.txt", $_POST['contents']."\n",FILE_APPEND);
file_put_contents("../from_html.txt", $_POST['action']."\n",FILE_APPEND);

try{
	

    $pdo = new PDO(
        $dsn,
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $pdo->exec("create table if not exists $dbtable(
        id int not null auto_increment primary key,
        kind varchar(40) unique,
        contents text
      )");


    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$pdo->query("SET NAMES UTF8;");


}catch(PDOException $Exception){
    die('接続できません：' .$Exception->getMessage());
}

try{

	$sql = "SET NAMES UTF8;";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $date=date('Y年m月d日 H時i分s秒');

    if(strcmp($_POST['action'],"delall")==0){
        $sql = "DELETE FROM $dbname.$dbtable";
    }
    if(strcmp($_POST['action'],"add")==0){
        $sql = "INSERT INTO `${dbtable}` SET kind = '${_POST['kind']}($date)', contents = '${_POST['contents']}';";
    }
    $stmh = $pdo->prepare($sql);
    $stmh->execute();


}catch(PDOException $Exception){
    die('接続エラー：' .$Exception->getMessage());
}

try{
    $pdo = new PDO(
        $dsn,
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}catch(PDOException $Exception){
    die('接続エラー：' .$Exception->getMessage());
}

try{
    $sql = "SELECT * FROM $dbname.$dbtable";
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
