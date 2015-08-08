<?php 

require_once('config.php');
require_once('function.php');

//GETでuser_idを取得
$user_id = $_GET['id'];

// 登録処理
if($_SERVER['REQUEST_METHOD'] != 'POST'){
// CSRF対策
  //setToken();

}else{
  //checkToken();
  
  $user_id = $_POST['user_id'];
  $title = $_POST['title'];
  $body = $_POST['body'];
  $image = $_FILES['image'];

    //$userの配列を取得
  $user = getuser($user_id);

  //データベースに接続
  $dbh = connectDb();

  if($image['error'] != 0 and $image['error'] != 4) {
    echo 'エラーが発生しました。投稿は完了していません！　エラーコード'.$image['error'];
    exit;
  }

  if($image['error'] != 4){
    //拡張子の抽出
    $imagesize = getimagesize($image['tmp_name']);
    switch ($imagesize['mime']) {
      case "image/png":
        $ext = 'png';
        break;

      case 'image/jpeg':
        $ext = 'jpg';
        break;

      case 'image/gif':
        $ext = 'gif';
        break;

      default:
        echo 'このファイルはアップロードできません!';
        exit;
    }

    //保存場所・保存名を作成
    $imageFileName = sha1(time().mt_rand()).$ext;
    $imageFIlePath = IMAGES_DIR."/".$imageFileName;

    //写真の保存
    $rs = move_uploaded_file($image['tmp_name'], $imageFIlePath);
    if(!$rs){
      echo 'アップロードできませんでした…';
      exit;
    }

    //imagesテーブルに保存
    $sql = "insert into images 
      (filename, filepath, uploaded)
      values
      (:filename, :filepath, now())";
    $stmt = $dbh->prepare($sql);
    $params = array(
      ":filename" => $imageFileName,
      ":filepath" => $imageFIlePath
      );
    $stmt->execute($params);

    if(!$image_id = $dbh->lastInsertId()){
      echo '写真の投稿に失敗しました!';
      exit;
    }
  }
  
  //postsテーブルに保存
  $sql = 'Insert into posts
    (user_id, university_id, title, body, image_id, created, modified)
    values
    (:user_id, :university_id, :title, :body, :image_id, now(), now())';
  $stmt = $dbh->prepare($sql);
  $params = array(
    ':user_id' => $user_id,
    ':university_id' => $user['university_id'],
    ':image_id' => $image_id,    
    ':title' => $title,
    ':body' => $body
  );
  $stmt->execute($params);

  if(!$id = $dbh->lastInsertId()){
    echo '投稿に失敗しました!';
    exit;
  }

  echo "<script>location.href='index.php'</script>";
  exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Connect</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">

    <!-- Custom Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" type="text/css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="css/animate.min.css" type="text/css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/creative.css" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container" style="padding:20px 0">
    
          <form enctype="multipart/form-data" class="form-horizontal" style="margin-bottom:15px;" action="" method="post">
            
            <div class="form-group">
              <label class="control-label col-sm-2" for="title">タイトル</label>
              <div class="col-sm-6">
                <input type="text" name="title" class="form-control" placeholder="title" required>
              </div>
              <div class="col-sm-1">
                <h4><span class="label label-danger">必須</span></h4>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-sm-2" for="body" required>本文</label>
              <div class="col-sm-6">
                <textarea name="body" class="form-control" placeholder="body" rows="5"></textarea>
              </div>
              <div class="col-sm-1">
                <h4><span class="label label-danger">必須</span></h4>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-sm-2" for="file">画像ファイル</label>
              <div class="col-sm-6">
                <input type="file" name="image" class="form-control">
              </div>
            </div>

            <div class="form-group">
              <input type="hidden" value="<? echo $user_id; ?>" name="user_id">
            </div>

            <!-- <div class="form-group">
              <input type="hidden" value="<? echo $token; ?>" name="token">
            </div> -->

            <div class="form-group">
              <div class="col-sm-offset-7 col-sm-2">
                <input type="submit" value="submit" class="btn btn-primary">
              </div>
            </div>
          </form>
     
    </div>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/jquery.fittext.js"></script>
    <script src="js/wow.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/creative.js"></script>
    <script src="connect.js"></script>
   
</body>

</html>
