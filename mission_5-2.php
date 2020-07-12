<!DOCTYPE html>
<html lang ="ja">
<head>
    <meta charset="UTF-08">
    <title>mission_5-2</title>
</head>    
<body>
<?php    
	// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //変数指定
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass = $_POST["pass"];
    $date = date('Y/m/d H:i:s');
    $del = $_POST["del"];
    $delpass = $_POST["delpass"];
    $edit = $_POST["edit"];
    $edpass = $_POST["edpass"];
    $hidden = $_POST["hidden"]; //編集投稿か確かめるフォーム
    
    //書き込みをテーブル内に記録
if($name && $comment && $pass &&empty($hidden)){
        //INSERTでテーブルに記録
        $sql = $pdo -> prepare("INSERT INTO BBS (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> execute();
        //投稿をすべて表示
        $sql = 'SELECT * FROM BBS';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
        }
    }
    
    //削除機能
    if($del){ //削除フォームに入力がある場合
        $sql = 'SELECT * FROM BBS';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            if($row['id']==$del){ //該当投稿ならば
                if($row['pass']==$delpass){ //パスワードを照会
                    $sql = 'delete from BBS where id=:id';       
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $del, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
        //投稿をすべて表示
        $sql = 'SELECT * FROM BBS';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
        }
    }
    
    //編集機能
    //編集したい投稿を投稿フォームに表示する
    if($edit){ //編集申請フォームに入力がある場合
        $sql = 'SELECT * FROM BBS';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            if($row['id']==$edit){ //該当投稿ならば
                if($row['pass']==$edpass){ //パスワードを照会
                    $name_e = $row['name'];
                    $comment_e = $row['comment'];
                    $num_e = $row['id'];
                }
            }
        }
    }
    
    //投稿を編集する
    if($name && $comment && $pass && $hidden){
        
        $ediname = $name."(編集済)";
        $sql = 'UPDATE BBS SET name=:name, comment=:comment, pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $hidden, PDO::PARAM_INT);
        $stmt->bindParam(':name', $ediname, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
        $stmt->execute();
        
        //投稿をすべて表示
        $sql = 'SELECT * FROM BBS';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
        }
    }
    
    
   
    
?>

<!--投稿フォーム-->
    <form action="" method="post">
    <input type="text" name="name" placeholder="名前" value="<?php echo htmlspecialchars($name_e);?>"
><br>
    <input type="text" name="comment" placeholder="コメント" value="<?php echo htmlspecialchars($comment_e); ?>"><br>
    <input type="text" name="pass" placeholder="パスワード">
    <input type="hidden" name="hidden" value="<?php echo htmlspecialchars($num_e); ?>"><br>
    <input type="submit" name="submit">
    </form>
    
<!--削除フォーム-->
    <form action="" method="post">
    <input type="number" name="del" placeholder="削除対象番号"><br>
    <input type="text" name="delpass" placeholder="パスワード"><br>
    <input type="submit" name="submit" value="削除">
    </form>
    
<!--編集申請フォーム-->
    <form action="" method="post">
    <input type="number" name="edit" placeholder="編集対象番号"><br>
    <input type="text" name="edpass" placeholder="パスワード"><br>
    <input type="submit" name="submit" value="編集">
    </form>

    
</body>
</html>