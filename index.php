<!--
This script runs the home page.


-->


<?php
    require 'connect.php';


    $query = "SELECT * FROM dioramas ORDER BY diorama_id DESC";
    $statement = $db->prepare($query);
    $statement->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Best Blog Ever</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

<div id="wrapper">
    <div id="header">
        <h1><a href="index.php">Best Blog Ever - Index</a></h1>
    </div> <!-- END div id="header" -->

<ul id="menu">
    <li><a href="index.php" class='active'>Home</a></li>
    <li><a href="create.php" >New Post</a></li>
</ul> <!-- END div id="menu" -->





<div id="all_blogs">

<?php while($row = $statement->fetch() ) : ?>

    <div class="blog_post">
      <h2><a href="show.php?id=<?= $row['Diorama_ID'] ?>"><?= $row['Title'] ?></a></h2>
    </div> <!-- END div class="blog_post" -->
<?php endwhile ?>

</div> <!-- END div id="all_blogs" -->



    <div id="footer">
        Copywrong 2020 - No Rights Reserved
    </div> <!-- END div id="footer" -->

</div> <!-- END div id="wrapper" -->
    


</body>
</html>
