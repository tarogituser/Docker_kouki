<?php
    $host = 'mysql';
    // mysql接続用のユーザー
    $username = 'data_user';
    $password = 'data';
    $database = 'data_master';

    $mysql = new mysqli($host, $username, $password, $database);
    if ($mysql->connect_error) {
        die("データベース接続エラー:" .$mysql->connect_error);
    }

    $id = 0;
    if (isset($_GET['class_id'])){
        $id = $_GET['class_id'];
    }

    //クエリの実行
    $result = $mysql->query("SELECT * FROM classes cl left outer join students on cl.class_id = " .$id);
    if ($result){
        if ($result->num_rows > 0){
            $row = $result->fetch_row();
            echo "class_id class_name"."<br>";
            echo $row[0], $row[1];
        }
        else{
            echo "該当データなし";
        }
        // 結果セットを解放
         $result->free();
    }
    else{
        echo "クエリの実行に失敗しました:" .$mysql->error;
    }
    
    $mysql->close();
?>