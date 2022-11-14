<body>
<div class="container">
    <table class="table table-bordered">
        <thead>
        <tr>
            <td>День</td>
            <td>Описание</td>
            <td>Повторение</td>
        </tr>
        </thead>
        <tbody id="holiday_table">
            <?php foreach($holidays as $row){ ?>
                <tr>
                    <td><?php echo date('Y-m-d',$row['date'])?></td>
                    <td><?php echo $row['name']?></td>
                    <td>
                        <input class="checkbox_holiday" type="checkbox" data-date="<?php echo $row['date'] ?>" <?php if($row['to_repeat']==1){echo 'checked';}?>>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <form id="new_holiday" action="javascript:void(0)">
        <div class="form-group">
            <label for="pwd">ДАТА:</label>
            <input type="date" name="date">
        </div>
        <div class="form-group">
            <label for="pwd" >НАЗВАНИЕ:</label>
            <input name="name">
        </div>
        <input type="submit">
    </form>
</div>
</body>
<script src="http://staff.kg/public/js/holiday.js"></script>
</html>