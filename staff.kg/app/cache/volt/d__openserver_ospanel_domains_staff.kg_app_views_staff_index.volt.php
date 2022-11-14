<?php \Phalcon\Tag::setDoctype(\Phalcon\Tag::HTML5); ?>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script></head>
<script></script>
<body>
<div class="container">
    <ul class="list-group">
        <li class="list-group-item list-group-item-success">First item</li>
        <li class="list-group-item list-group-item-info">Second item</li>
        <li class="list-group-item list-group-item-warning">Third item</li>
        <li class="list-group-item list-group-item-danger">Fourth item</li>
    </ul>
    <table class="table table-bordered">
        <thead>
        <tr>
            <?php foreach($globalBlaa as $row){  ?>
                        <td><?php echo $row['login']; ?></td>
            <?php } ?>

        </tr>
        </thead>
        <tbody>
        <tr class="success">
            <td>Come:<span>8-30</span><br><br></br>Leave:<span>9-30</span> <span><?php echo $time;?>zzz</span></td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>