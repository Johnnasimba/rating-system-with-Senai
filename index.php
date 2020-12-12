<?php
$conn = new mysqli('localHost',  'root',  '', 'senairatingsystem');
    if(isset($_POST['save'])) {

        $uID = $conn -> real_escape_string($_POST['uID']);
        $rateIndex =$conn -> real_escape_string($_POST['rateIndex']);
        $rateIndex++; 

        if(!$uID) {
            $conn->query("INSERT INTO stars (rateIndex) VALUES ('$rateIndex')");
            $sql = $conn->query("SELECT id FROM stars ORDER BY id DESC LIMIT 1");
            $uData = $sql->fetch_assoc();
            $uID = $uData['id'];
        } else {
            $conn->query("UPDATE stars SET rateIndex WHERE id = $uID");
            exit(json_encode(array('id' => $uID)));
        }
    }
    $sql = $conn->query("SELECT id FROM stars");
    $numR = $sql->num_rows;

    $sql = $conn->query("SELECT SUM(rateIndex) as total FROM stars");
    $rData = $sql->fetch_array();
    $total = $rData['total'];
    $avg = $total / $numR
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/1936634b09.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating system</title>
</head>
<body>
    <div  align="center" style="background-color: #000; padding: 50px; color:white">
        <i class="fa fa-star fa-2x" data-index="0" ></i>
        <i class="fa fa-star fa-2x" data-index="1" ></i>
        <i class="fa fa-star fa-2x" data-index="2" ></i>
        <i class="fa fa-star fa-2x" data-index="3" ></i>
        <i class="fa fa-star fa-2x" data-index="4" ></i>
        <br><br>
        <?php echo round($avg, 1);  ?>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.js" integrity="sha256-DrT5NfxfbHvMHux31Lkhxg42LY6of8TaYyK50jnxRnM="crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <script>
     let rateIndex = -1, uID = 0;
     const star = $('.fa-star');


     $(document).ready(function ( ) {
            resetStarColors();

            if(localStorage.getItem('rateIndex') != null){

            setStars(parseInt(localStorage.getItem('rateIndex')));
            uID = localStorage.getItem('uID');
            }

        star.on('click', function() {
            rateIndex = parseInt($(this).data('index'));
            localStorage.setItem('rateIndex', rateIndex )
            saveToTheDB();
        });
        
         star.mouseover(function() {
            resetStarColors();
            let currentIndex = parseInt($(this).data('index'));
            setStars(currentIndex)
         })
         star.mouseleave(function() {
            resetStarColors();
            if(rateIndex != -1)
            setStars(rateIndex);
         });
     });
         function saveToTheDB() {
             $.ajax({
                 url: "index.php",
                 method: "POST",
                 dataType: "json",
                 data: {
                     save: 1,
                     uID: uID,
                     rateIndex: rateIndex
                 }, success : function(res) {
                    uID = res.id;
                    localStorage.setItem('uID', uID )
                 }

             });
         }


         function setStars(max) {
            for (var i=0; i<= max; i++)
                $('.fa-star:eq('+i+')').css('color', 'green')
               
         }
         
         function resetStarColors () {
             star.css('color', 'white')
         }
        
   
 </script>
</body>
</html>
