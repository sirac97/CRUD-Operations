
<?php
require_once "./db.php";
 // echo "Connected";
 $sort = $_GET["sort"] ?? "created" ;

if ( !empty($_POST)){
    extract($_POST);
    //Validate form data
    //title 
    //price

    try {
        $sql = "insert into games (title, price, launch) values (?,?,?)" ;
        $rs = $db->prepare($sql);
        $rs->execute([$title,$price,$launch]) ;
   
   } catch(PDOException $ex) {
           $errMsg = "Insert fail" ;
   }


} else if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    try {
        $rs = $db->prepare("delete from games where id = :id") ;
        $rs->execute(["id" => $id]);

    }catch(PDOException $ex){
        $errMsg="delete fail" ;
    }
}
  


 try {
     $rs = $db->query("select count(*) from games ") ;
     $gamescount = $rs->fetchColumn();
     // var_dump($games) ;

} catch(PDOException $ex) {
        echo "<p>", $ex->getMessage(),"</p>";
}



//

  const PAGESIZE=3 ;
  $page = $_GET["page"] ?? 1 ; // current page, default page is 1.
  $totalPage = ceil($gamescount / PAGESIZE) ;
  $start = ($page - 1) * PAGESIZE ;
  
  try {

    $rs = $db->query("select * from games order by title limit ". PAGESIZE. " offset ". $start) ;
    $games = $rs->fetchAll(PDO::FETCH_ASSOC) ;
    

} catch(PDOException $ex) {
       echo "<p>", $ex->getMessage(),"</p>";
}

   //

   
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
    .mb50{
        margin-bottom: 50px;
    }
    .pagination{
      position: absolute;
      margin-left: 320px;
    }
    </style>
  </head>
  <body>

    <div class="card-panel teal lighten-2 white-text mb50">
    <h4 class="center">List of Games</h4>
    </div>


    <div class="fixed-action-btn">
  <a class="btn-floating btn-large red modal-trigger z-depth-2" href="#add_form">
    <i class="large material-icons">add</i>
  </a>
</div>
    
<div class="container">
    <form action="" method="post">
        <table>
        <tr>
            <td colspan="2">
            <div class="input-field">
              <input name="title" id="title" type="text" class="validate">
              <label for="title">Title</label>
            </div>
            </td>
            <td>
            <div class="input-field">
              <input name="price" id="price" type="text" class="validate">
              <label for="price">Price</label>
            </div>
            </td>
            <td>
            <input name="launch" type="text" class="datepicker">
            </td>
            <td>
            <button class="btn waves-effect waves-light" type="submit" name="action">Submit
              <i class="material-icons ">add</i>
            </button>
            </td>
        </tr>
        <tr>
        <th width="5%">ID</th>
        <th width="45%" >
        <a href="?sort=title">TITLE
             <?= $sort == "title" ? "<i class='material-icons'>arrow_drop_down</i>": "" ?>
             </a>
        </th>
        <th width="10%">PRICE</th>
        <th width="20%">LAUNCH</th>
        <th width="20%" class="center">OPS</th>
        </tr>

        <?php foreach( $games as $game) : ?>
        <tr>
        <td><?= $game["id"] ?></td>
        <td><?= $game["title"] ?></td>
        <td>₺<?= $game["price"] ?></td>
        <td><?= $game["launch"] ?></td>
        <td>
        <a href="?delete=<?= $game["id"] ?>" class="btn-small"><i class="material-icons">delete</i></a>
        <a href="edit.php?id=<?= $game["id"] ?>" class="btn-small"><i class="material-icons">edit</i></a>
        <a class="btn-small modal-trigger" href="#game<?= $game["id"] ?>" class="btn-small"><i class="material-icons">visibility</i></a>
        </td>
        </tr>

        <?php endforeach ?>

        </table>
    </form>

</div>

<?php  foreach($games as $game) : ?>
    <div id="game<?= $game['id']?>" class="modal">
    <div class="modal-content">
      <table>
      <tr>
              <td>ID:</td>
              <td><?= $game['id'] ?></td>
          </tr>     
          <tr>
              <td>Title:</td>
              <td><?= $game['title'] ?></td>
          </tr>
          <tr>
              <td>Price:</td>
              <td><?= $game['price'] ?></td>
          </tr>
          <tr>
              <td>Launch:</td>
              <td><?= $game['launch'] ?></td>
          </tr>
          
      </table>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>


<?php endforeach ?>

<div id="add_form" class="modal">
  <form action="" method="post" >
    
    <div class="modal-content">
        <h5 class="center">Add New Game</h5>
        

        <div class="input-field">
              <input name="title" id="title" type="text" class="validate">
              <label for="title">Title</label>
            </div>

            <div class="input-field">
              <input name="price" id="price" type="text" class="validate">
              <label for="price">Price</label>
            </div>

        <div class="input-field">
        <input name="launch" type="text" class="datepicker">
        
        </div>

      </div>
      
      <div class="modal-footer">
      <button class="btn waves-effect waves-light" type="submit" name="action">Add
              <i class="material-icons ">add</i>
            </button>
      </button>

    </div>
  </form>



</div>



<ul class="pagination">
<?php
     for ($i=1; $i<= $totalPage; $i++) {
        $qsPage = http_build_query(["page" => $i]) ;
        if ( $i == $page) {
          echo "<li class='active'><a href='?$qsPage'>$i</a></li>" ;
        } else {
          echo "<li class='waves-effect'><a href='?$qsPage'>$i</a></li>" ;
        }
     }
    ?>
  </ul>


<?php
 if(isset($errMsg)){
     echo "<script> M.toast({html: '$errMsg', classes: 'red-white-text'});  </script>"; 
 }
?>

  <script>
   
   document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
  }); 
   
   $(function(){
        $('.datepicker').datepicker({format: "yyyy-mm-dd"});

    })
  </script>


  </body>
</html>