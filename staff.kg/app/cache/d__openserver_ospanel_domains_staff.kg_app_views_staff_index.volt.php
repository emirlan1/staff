<body>
<div class="container">
    <ul class="list-group">
        <li class="list-group-item list-group-item-success">Общее кол-во часов за месяц: <?php echo $personal_info['total']['h'].':'.$personal_info['total']['m'] ?></li>
        <li class="list-group-item list-group-item-info">Процент посещения: <?php echo $personal_info['percent'] ?>%</li>
        <li class="list-group-item list-group-item-warning">Assigned: <?php echo $personal_info['assigned'] ?></li>
    </ul>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td><button id="hide_show_button" type="button" class="btn btn-primary">Hide/Show</button>
            </td>
            <?php foreach($all_users as $row):  ?>
              <td><?php echo $row['login']; ?></td>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>

        <center>
            <form action method="get">
                <select name="month" onchange="this.form.submit();">
                    <?php for($i=1;$i<=12;$i++){ ?>
                    <?php $monthName = date("F", mktime(0, 0, 0, $i)) ?>

                        <option value="<?php echo $i ?>" <?php if($i == $selected_month){echo 'selected="selected"';} ?> ><?php echo $monthName; ?></option>
                    <?php } ?>
                </select>

                <select name="year" onchange="this.form.submit();">
                    <?php for($i=$first_date;$i<=date('Y');$i++){ ?>
                    <option value="<?php echo $i ?>" <?php if($i == $selected_year){echo 'selected="selected"';} ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </form>
        </center>
            <?php
                $this->partial('partials/user_staff',['role' => $this->session->get('auth-identity')['profile']]);
            ?>
        </tbody>
    </table>
</div>
</body>
<script src="http://staff.kg/public/js/staff.js"></script>
</html>