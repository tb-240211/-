<!DOCTYPE html>
<html lung = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>m5-1</title>
    </head>
    
    <span style="font-size: 30px;">掲示板</span>
    
    <body>
        <?php
            
            // データベース接続設定
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn , $user , $password , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            // テーブル作成（テーブル名；keijiban）
            $sql = "CREATE TABLE IF NOT EXISTS keijiban"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name TEXT,"//名前
            . "comment TEXT,"//コメント
            . "date DATETIME,"//日時
            . "pass char(12)"//パスワード
            .");";
            $stmt = $pdo->query($sql);
            
            
            //新規投稿機能
            if(empty($_POST["editNo"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) ){
                
                //insert文；データレコードの挿入
                $sql = $pdo -> prepare("INSERT INTO keijiban (name, comment, date, pass) VALUES (:name, :comment, now(), :pass)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $date = date("Y/m/d H:i:s");
                $pass = $_POST["pass"];
                
                $sql -> execute();
            }
            //編集実行機能
            if(!empty($_POST["editNo"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
                
                $editNo = $_POST["editNo"];
                
                $id = $editNo; //変更する投稿番号
                $name = $_POST["name"];
                $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
                $date = date("Y/m/d H:i:s");
                $sql = 'UPDATE keijiban SET name=:name, comment=:comment, date=:date, pass=:pass WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                $stmt->execute();
                
            }
            
            //編集選択機能
            if(!empty($_POST["edit"]) && !empty($_POST["editpass"]) ){
                
                $edit = $_POST["edit"];
                $editpass = $_POST["editpass"];
                
                $sql = "SELECT * FROM keijiban WHERE id=$edit";
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    
                    $editname = $row["name"];
                    $editcom = $row["comment"];
                    
                }
            }
            
            //削除機能
            if( !empty($_POST["delete"]) && !empty($_POST["delpass"]) ){
                
                $delete = $_POST["delete"];
                $delpass = $_POST["delpass"];
                
                $id = $delete;
                $sql = 'delete from keijiban where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                $stmt->execute();
                
            }
            
            
        ?>
        
        <!--新規投稿フォーム　※-編集モードのときは値を取得-->
        <form action="" method="post">
            <input type="text" name="name" placeholder="名前" value= "<?php if(!empty($editname)) {echo $editname;} ?>" ><br>
            <input type="text" name="comment" placeholder="コメント" value= "<?php if(!empty($editcom)) {echo $editcom;} ?>" >
            <input type="hidden" name="editNo" value="<?php if(!empty($edit)) {echo $edit;} ?>"><br>
            <input type="password" name="pass" placeholder="パスワードを設定">
            <input type="submit" name="submit"><br>
        </form>
        <br>
        
        <!--削除フォーム-->
        <form action="" method="post">
            <input type="number" name="delete" placeholder="削除番号"><br>
            <input type="password" name="delpass" placeholder="パスワードを入力">
            <input type="submit" value="削除"><br>
        </form>
        
        <!--編集フォーム-->
        <form action="" method="post">
            <input type="number" name="edit" placeholder="編集番号"><br>
            <input type="password" name="editpass" placeholder="パスワードを入力">
            <input type="submit" value="編集"><br>
        </form>
        
        <span style="font-size: 20px;">【投稿一覧】</span><br>
        
        <?php
            
            //表示機能
            $sql = 'SELECT * FROM keijiban';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();//すべて取ってくるという意味
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
                echo "<hr>";
            }
            
        ?>        
        
    </body>
</html>
