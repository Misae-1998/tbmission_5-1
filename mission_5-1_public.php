<?php

//エラー「Notice」を無視する。
error_reporting(E_ALL & ~E_NOTICE);

//データベースへの接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$PWword = 'パスワード';
$pdo = new PDO($dsn, $user, $PWword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//「PDO::xx」で属性を設定する。
//PDO::ATTR_ERRMODEはエラーモードの設定。
//PDO::ERRMODE_WARNINGではE_WARNINGを発生させる。これだとアプリの動作を妨げずに問題点を確認できる

//tbtest6はあらかじめ作った

	
//データに入れたいものを変数に入れていく
$name = $_POST["name"];
$comment = $_POST["comment"];
$date = date("Y年m月d日 H:i:s");
$PW = $_POST["PW"];
$edit = $_POST["editnum"];
$edit2 = $_POST["editnum2"];
$delnum = $_POST["delnum"];
$delPW = $_POST["delPW"];
$editPW = $_POST["editPW"];
$buttonsub = $_POST["buttonsub"];
$buttondel = $_POST["buttondel"];
$buttoned = $_POST["buttoned"];

//新規送信ボタンをクリックされたとき
if(isset($buttonsub)){

	//名前、パスワードが入力されているとき
	if(!empty($name) && !empty($PW) && empty($edit2)){
				//投稿データを入力
				$sql = $pdo -> prepare("INSERT INTO tbtest6 (name, comment, date, PW) VALUES (:name, :comment, :date, :PW)");
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindParam(':date', $date, PDO::PARAM_STR);
				$sql -> bindParam(':PW', $PW, PDO::PARAM_STR);
				$sql -> execute();
				
			//更に編集番号が入力されているとき
		}else if(!empty($name) && !empty($PW) && !empty($edit2)){
				$sql = 'SELECT * FROM tbtest6';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach($results as $row){						

				//投稿番号が一致した投稿を編集
					if($row['id']==$edit2){
						$sql = 'update tbtest6 set name=:name,comment=:comment,date=:date,PW=:PW where id=:id';
						$stmt = $pdo->prepare($sql);
						$stmt -> bindParam(':id', $edit2, PDO::PARAM_INT);
						$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
						$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
						$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
						$stmt -> bindParam(':PW', $PW, PDO::PARAM_STR);
						$stmt -> execute();
						}	
				}		

		}			
}
		//編集ボタンをクリックされたとき
		if(isset($buttoned)){

			//編集申請番号とパスワードが入力されているとき
			if(!empty($edit) && !empty($editPW)){
				$sql='SELECT * FROM tbtest6';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach($results as $row){

					//編集元の投稿番号とパスワードが一致した投稿をフォームに表示
					if($row['id']==$edit && $row['PW']==$editPW){
						$editname = $row['name'];
						$editcomment = $row['comment'];
						$editnum = $row['id'];
			}
		}
	}
}

//削除ボタンをクリックされたとき
if(isset($buttondel)){
	//削除番号とパスワードが入力されているとき
	if(!empty($delnum) && !empty($delPW)){
		$sql = 'SELECT * FROM tbtest6';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach($results as $row){

			//削除元の投稿番号とパスワードが一致したときに削除
			if($row['id']==$delnum && $row['PW']==$delPW){
					$sql = 'delete from tbtest6 where id=:delnum';
					$stmt = $pdo->prepare($sql);
					$stmt -> bindParam(':delnum', $delnum, PDO::PARAM_INT);
					$stmt -> execute();
					}
		}

	}
}

//投稿内容を表示
$sql = 'SELECT * FROM tbtest6';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach($results as $row){
echo $row['id'].' / ';
echo $row['name'].' / ';
echo $row['comment'].' / ';
echo $row['date'].'<br>';
echo "<hr>";
}

?>

<!DOCTYPE html>
<html lang = "en">

<!-フォームに名前、コメント、日時を入力する-->

	<head>
		<meta charset = "UFT-8">
		<title>掲示板5-1</title>
	</head>
	<body>
	
		<form action = "mission_5-1_pass.php" method = "POST">
	
			<!-名前-->
            <input type="text" name="name" size="20" placeholder="名前" value="<?php echo $editname; ?>"><br>
            <!-コメント-->
             <input type="text" name="comment" size="50" placeholder="コメント" value="<?php echo $editcomment; ?>"><br>
             <!-パスワード-->
             <input type="text" name="PW" size="50" placeholder="パスワード"><br>
             <!-編集申請番号後で隠すやつ-->
			<input type="hidden" name="editnum2" size="" value="<?php echo $editnum; ?>" />
             <!--送信ボタン-->
             <input type="submit" name = "buttonsub" value="送信"><br>
             <!-削除番号-->
             <input type="text" name="delnum" size="20" placeholder="削除番号"><br>
             <!-削除PW-->
             <input type="text" name="delPW" size="50" placeholder="削除パスワード"><br>
             <!--削除ボタン-->
             <input type="submit" name = "buttondel" value="削除"><br>
             <!--編集番号-->
             <input type="text" name="editnum" size="20" placeholder="編集番号"><br>
             <!-編集PW-->
             <input type="text" name="editPW" size="50" placeholder="編集パスワード"><br>
             <!-編集ボタン-->
             <input type="submit" name = "buttoned" value="編集"><br>
    </form>



	</body>

</html>
