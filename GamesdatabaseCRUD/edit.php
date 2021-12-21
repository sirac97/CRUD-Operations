
<?php

if (preg_match('/^\d{1,6}$/', $_GET["id"])=== 0 ){
    echo "ID is invalid." ;
    exit;
}

require_once "./db.php" ;

if(!empty($_POST)){
    extract($_POST);

    try{
            $sql = "update games set title=:title, price=:price, launch=:launch where id =:id" ;
            $rs = $db->prepare($sql) ;
            $rs->execute(["id" => $id, "price" => $price, "launch" => $launch, "title" => $title]);
    }catch (PDOException $ex){

    }
        // update yaptıktan sonra anasayfaya dönmek için
        header("Location: index.php") ;
        exit;

}




$id = $_GET["id"];
try{
    $rs = $db->prepare("select * from games where id = :id") ;
    $rs->execute(["id" => $id]) ; 
    
    $game = $rs->fetch(PDO::FETCH_ASSOC) ;
   //  var_dump($game) ;
}catch(PDOException $ex){
    echo "Query Error :", $ex->getMessage() ;
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Title of the document</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <style>
    </style>
  </head>
  <body>
    <div class="card-panel teal lighten-2 white-text">
    <h5 class="center">Edit the Game</h5>
    </div>
    <div class="container">
        <form action="" method="post">
            <div class="input-field">
              <input name="title" id="title" type="text" class="validate" value="<?= $game["title"] ?>">
              <label for="title">Title</label>
            </div>
        
        <div class="input-field">
          <input name="price" id="price" type="text" class="validate" value="<?= $game["price"] ?>">
          <label for="price">Price</label>
        </div>
        
        <div class="input-field">
          <input name="launch" id="launch" type="text" class="datepicker" value="<?= $game["launch"] ?>">
          <label for="launch">Launch Date</label>
        </div>
        <input name="id" type="hidden" value="<?= $game["id"] ?>">
        <div class="center">
        <button class="btn waves-effect waves-light" type="submit" name="action">Update
          <i class="material-icons right">edit</i>
        </button>
        
        </div>

        
        </form>
    </div>

  <script>
    $(function(){
            $('.datepicker').datepicker({
                format:"yyyy-mm-dd",   // yıl (2021 gibi) sonra ay sonra da gün şeklinde
                onOpen: function(){ // edit yaparken seçili tarih belli olsun diye 
                    this.setDate(new Date(this.el.value)) ;
                }
            });
    })
  </script>
  </body>
</html>