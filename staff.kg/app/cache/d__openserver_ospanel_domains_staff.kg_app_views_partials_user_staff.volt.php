<h1><?php echo $role; ?></h1>
<?php foreach($days as $day_row): ?>
    <tr class="<?php echo $day_row['view_class']; ?>">
        <td>
            DAY:<?php echo $day_row['day'];?><br>
            <?php echo $day_row['WeekDay'];?>
        </td>
        <span>
                <!-- Циклируем всех юзеров, Отнисительно строки в таблице,
                мы должны их проциклировать аналогично циклу в теге thead -->
            <?php foreach($day_row['users'] as $user_row): ?>
            <td>
                <?php if($this->session->get('auth-identity')['id'] == $user_row['id'] && $day_row['date'] ==  date('Y-m-d')){ ?>
                    <button data-id="<?php echo $user_row['id']; ?>" data-action="start" class="change_time_button">Start</button>
                    <button data-id="<?php echo $user_row['id']; ?>" data-action="stop" class="change_time_button">Stop</button><br>
                <?php } ?>
                <br>



                <!--Циклируем время прихода каждого юзера
                    Для того что бы получить  время его прихода-->
                <?php foreach($user_row['times'] as $user_time_row): ?>


                        <?php if($role == 'Administrators'){ ?>
                               <!-- Каждую дату его прихода, сравниваем с текущим днем-->
                                <?php if($user_time_row['date'] == $day_row['date']){   ?>
                                      <br>
                                         Обед:<input class="checkbox_change_user_params" type="checkbox" data-action="dinner" data-id="<?php echo $user_row['id']; ?>" data-date="<?php echo $day_row['date']; ?>" <?php if($user_time_row['dinner'] == 1){echo 'checked';} ?>>
                                      <br>
                                         Задержка:<input class="checkbox_change_user_params" type="checkbox" data-action="late" data-id="<?php echo $user_row['id']; ?>" data-date="<?php echo $day_row['date']; ?>" <?php if($user_time_row['late'] == 1){echo 'checked';} ?>>
                                      <br>

                                      <br>
                                      <br>
                                      <div class="<?php if($this->session->get('auth-identity')['id'] == $user_row['id'] && $day_row['date'] ==  date('Y-m-d')){echo 'user_time';}; if($user_time_row['late']==1){ echo ' list-group-item-danger';}  ?>">
                                            <?php foreach($user_time_row['input_array'] as $key => $input_row ): ?>

                                                <input class="time_input" type="text" data-date="<?php echo $day_row['date']; ?>" data-id="<?php echo $user_row['id']; ?>" data-action="start" data-key="<?php echo $key;?>" value="<?php echo $input_row['start'] ?>">-
                                                <input class="time_input" type="text" data-date="<?php echo $day_row['date']; ?>" data-id="<?php echo $user_row['id']; ?>" data-action="end" data-key="<?php echo $key;?>" value="<?php echo $input_row['stop'] ?>"><br>
                                            <?php endforeach; ?>
                                      </div>
                                      <br>
                                      total:<?php echo $user_time_row['total']['h'].':'.$user_time_row['total']['m']?><br>
                                      <?php if($user_time_row['less'] !== false){ echo 'less:'.$user_time_row['less']['h'].':'.$user_time_row['less']['m'];} ?>
                                <?php }?>
                        <?php }else{ ?>
                                <!-- Каждую дату его прихода, сравниваем с текущим днем-->
                                <?php if($user_time_row['date'] == $day_row['date']){   ?>
                                <input type="checkbox" disabled="disabled" <?php if($user_time_row['dinner'] == 1){echo 'checked';} ?>>
                                      <div class="<?php if($this->session->get('auth-identity')['id'] == $user_row['id'] && $day_row['date'] ==  date('Y-m-d')){echo 'user_time';}; if($user_time_row['late']==1){ echo ' list-group-item-danger';}  ?>">
                                          <?php echo $user_time_row['html'] ?>
                                      </div>
                                      <br>
                                      total:<?php echo $user_time_row['total']['h'].':'.$user_time_row['total']['m']?><br>
                                <?php if($user_time_row['less'] !== false){ echo 'less:'.$user_time_row['less']['h'].':'.$user_time_row['less']['m'];} ?>
                                <?php }?>
                        <?php } ?>



                <?php endforeach;?>
            </td>
            <?php endforeach;?>
        </span>
    </tr>
<?php endforeach;?>