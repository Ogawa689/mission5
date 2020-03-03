<!DOCTYPE html>
<html>

 <head>
 
  <meta http-equiv="content-type" content="text/html";charset="utf-8">

 </head>


 <body>

  好きな楽曲・よく聞く楽曲はなんですか？(昔聞いていたものでも結構です。)<br>
  例）ボレロ（ラヴェル）、おら東京さ行ぐだ（吉幾三）など<br><br>

  <?php
     
   //データベースへの接続
   $pdo=new PDO(mysql:host=localhost;dbname="データベース名";charset=utf8,"ユーザー名","パスワード",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
      //new PDO("mysql:host=ホスト名;dbname=データベース名;charset=utf8","ユーザー名","パスワード")


   //テーブルの作成
   $sql="CREATE TABLE IF NOT EXISTS mission5db_5"
       ."("
       ."id INT AUTO_INCREMENT PRIMARY KEY,"
       ."name VARCHAR(32),"
       ."comment VARCHAR(200),"
       ."time CHAR(32),"
       ."pass CHAR(4)"
       .");";
   $stmt=$pdo->query($sql);
  

    //テーブル表示
    //$sql="SHOW TABLES";
    //$result=$pdo->query($sql);
    //foreach($result as $line){
       //echo $line[0];
       //echo "<br>";
    //}


    //データ表示
    //$sql="SHOW CREATE TABLE mission5db_4";
    //$result=$pdo->query($sql);
    //foreach($result as $row){
       //echo $row[1];
       //echo "<br>";
    //}
    //echo "<hr>";



    //押されたボタンによる分岐

    //①「送信」ボタンの場合（新規投稿機能・編集投稿機能）
    if(isset($_POST["sendBot"])){

       if((isset($_POST["name"])) && (isset($_POST["comment"]))){

          //名前が空の場合
          if($_POST["name"]==""){
             $isName=false;
             echo "名前が未入力のため投稿に失敗しました(´･ω･`；)"."<br>";
          }
          else{
             $isName=true;
          }

          //コメントが空の場合
          if($_POST["comment"]==""){
             $isComment=false;
             echo "コメントが未入力のため投稿に失敗しました(´･ω･`；)"."<br>";
          }
          else{
             $isComment=true;
          }

          //パスワードが空の場合
          if($_POST["pass"]==""){
             $isPass=false;
             echo "パスワードが未入力のため投稿に失敗しました(´･ω･`；)"."<br>";
          }
          else{
             $isPass=true;
          }
       }


       //名前、コメント、パスワード全てが入力された場合に以下の機能が実行
       if(isset($isName,$isComment,$isPass)){
          
          //編集投稿でない場合、INSERTによる新規投稿
          if($_POST["editMark"]==""){
             $sql=$pdo->prepare("INSERT INTO mission5db_5(name,comment,time,pass) VALUES(:name,:comment,:time,:pass)"); //:name、:commentはパラメータ

             $name=$_POST["name"];
             $comment=$_POST["comment"];
             $time=date("Y/m/d H:i:s");
             $pass=$_POST["pass"];

             $sql->bindParam(":name",$name,PDO::PARAM_STR);
             $sql->bindParam(":comment",$comment,PDO::PARAM_STR);
             $sql->bindParam(":time",$time,PDO::PARAM_STR);
             $sql->bindParam(":pass",$pass,PDO::PARAM_INT);

             $sql->execute();
          }


          //editMarkがあった場合、編集投稿
          else{
             $sql="UPDATE mission5db_5 SET name=:name,comment=:comment,time=:time,pass=:pass where id=:id";

             $stmt=$pdo->prepare($sql);

             $editName=$_POST["name"];
             $editComment=$_POST["comment"];
             $editTime=date("Y/m/d H:i:s");
             $editPass=$_POST["pass"];

             $stmt->bindParam(":name",$editName,PDO::PARAM_STR);
             $stmt->bindParam(":comment",$editComment,PDO::PARAM_STR);
             $stmt->bindParam(":time",$editTime,PDO::PARAM_STR);
             $stmt->bindParam(":pass",$editPass,PDO::PARAM_STR);
             $stmt->bindParam(":id",$editNumber,PDO::PARAM_INT);
 
             $stmt->execute();
             echo "編集が完了しました"."<br>";

             $editMark=="";
          }

       } //名前コメントパスワード全て揃った時の分岐終了

    } //「送信」ボタンの分岐終了


    //②「削除」ボタンが押された場合（削除機能）
    elseif(isset($_POST["deleteBot"])){
       
       //削除対象番号が空の場合
       if($_POST["deleteNumber"]==""){
          $isDeleteNumber=false;
          echo "削除対象番号が未入力です(´･ω･`；)";
       }
       else{
          $isDeleteNumber=true;
       }
       
       //削除パスワードが空の場合
       if($_POST["deletePass"]==""){
          $isDeletePass=false;
          echo "パスワードが未入力のため削除に失敗しました(´･ω･`；)"."<br>";
       }
       else{
          $isDeletePass=true;
       }
       
       if(isset($isDeleteNumber,$isDeletePass)){
          
          $deleteNumber=(int)$_POST["deleteNumber"];
          $deletePass=$_POST["deletePass"];

          $sql="SELECT*FROM mission5db_5 where id=:delete_id";
          $stmt=$pdo->prepare($sql);
          $stmt->bindParam(":delete_id",$deleteNumber,PDO::PARAM_INT);
          $stmt->execute();
          $result=$stmt->fetch();

          if($deletePass!=$result["pass"]){
             echo "パスワードが違います"."<br>";
          }

          else{
             $sql="DELETE FROM mission5db_5 where id=:delete_id";
             $stmt=$pdo->prepare($sql);
             $stmt->bindParam("delete_id",$deleteNumber,PDO::PARAM_INT);
             $stmt->execute();
             echo "投稿を削除しました"."<br>";
          }
       }

    }


    //③「編集」ボタンが押された場合（編集番号選択機能）
    elseif(isset($_POST["editBot"])){

       if($_POST["editNumber"]==""){
          $isEditNumber=false;
          echo "編集対象番号が未入力です(´･ω･`；)";
       }
       else{
          $isEditNumber=true;
       }
       
       if($_POST["editPass"]==""){
          $isEditPass=false;
          echo "パスワードが未入力です(´･ω･`；)";
       }
       else{
          $isEditPass=true;
       }

       if(isset($isEditNumber,$isEditPass)){
          $editNumber=$_POST["editNumber"];
          $editPass=$_POST["editPass"];

          $sql="SELECT*FROM mission5db_5 where id=:edit_id";
          $stmt=$pdo->prepare($sql);
          $stmt->bindParam(":edit_id",$editNumber,PDO::PARAM_INT);
          $stmt->execute();
          $result=$stmt->fetch();

          if($editPass!=$result["pass"]){
             echo "パスワードが違います"."<br>";
          }

          else{
             $selectName=$result["name"];
             $selectComment=$result["comment"];
             $selectPass=$result["pass"];
             $editMark=$editNumber;
             echo "投稿内容が編集できます"."<br>";
          }
       }
   }
  ?>


  <!-- 投稿フォーム -->

  〇新規投稿<br>
  <form action="mission_5-1.php"method="post">

     名前：<br>
     <input type="text"name="name"value="<?php if(isset($selectName)){echo $selectName;} ?>"><br>

     コメント：<br>
     <input type="text"name="comment"value="<?php if(isset($selectComment)){echo $selectComment;} ?>"><br>

     パスワード（4ケタの数字を組み合わせてください）：<br>
     <input type="text"name="pass"value="<?php if(isset($selectPass)){echo $selectPass;} ?>"><br>

     <!-- 編集投稿かどうかを判別するためのフォーム（ブラウザ上では見えない） -->
     <input type="hidden"name="editMark"value="<?php if(isset($editSend)){echo $editMark;} ?>">

     <input type="submit"value="送信"name="sendBot"><br>

  </form>
  <br>


  〇コメント削除
  <form action="mission_5-1.php"method="post">

     削除対象の投稿番号：<br>
     <input type="text"name="deleteNumber"><br>

     削除対象のパスワード：<br>
     <input type="text"name="deletePass"><br>

     <input type="submit"value="削除"name="deleteBot"><br>

  </form>
  <br>
  

  〇コメント編集<br>
  <form action="mission_5-1.php"method="post">

     編集対象の投稿番号：<br>
     <input type="text"name="editNumber"><br>

     編集対象のパスワード：<br>
     <input type="text"name="editPass"><br>

     <input type="submit"value="編集"name="editBot"><br>

  </form>
  <br>

  <!-- 投稿フォーム終わり -->


  <?php

   //書き込みの表示機能
   $sql="SELECT*FROM mission5db_5";
   $stmt=$pdo->query($sql);

   $count=$stmt->rowCount();//書き込み件数を取得
   if($count==0){
      echo "まだ投稿はありません<br>";
   }
   else{
      echo "現在".$count."件の投稿があります<br><br><br>";
   }

   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

      echo "No.".$row["id"]."<br>";
      echo "名前:".$row["name"]."<br>";
      echo $row["comment"]."<br>";
      echo $row["time"]."<br>";
      echo "<br><br>";
   }

  ?>

 </body>

</html>
