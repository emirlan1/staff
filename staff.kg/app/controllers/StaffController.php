<?php
namespace Staff\Controllers;

use http\Params;
use Staff\Models\Users;
use Staff\Models\Times;
use Staff\Models\Nonworkdays;
use DateTime;


class StaffController extends ControllerBase
{

    public function indexAction()
    {
        //объявим модель
        $users = new Users();
        $times = new Times();
        //Месяц и год, по дефолту текущий или то что юзер передал через get
        $month = $this->request->get('month', 'int', date('m'));
        $year = $this->request->get('year', 'int', date('Y'));
        //Первая датаБ для определения с какого года вести отсчет
        $first_date = Times::findFirst([
            "order" => "date ASC",
        ])->toArray()['date'];
        $first_date = date('Y',strtotime($first_date));
        //Получим всех юзеров и переведем в массив
        //Только вот мы должны быть первыми
        if ($this->session->get('auth-identity')) {
            $all_users = Users::find([
                "order" => "id=".$this->session->get('auth-identity')['id']." DESC",
            ])->toArray();
        } else {
            $all_users = Users::find([
                "order" => "id DESC",
            ])->toArray();
        }
        //Получим все праздники и переведем в массив
        $all_holidays = Nonworkdays::find()->toArray();
        $days = array();
        //Кол-во дней в текущем месяце
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        //Создадим цикл который будет хранит все дни текущего месяца, в каждый месяц мы вставим всех юзеров, а юзерам дадим даты их прихода
        for($i=0; $i<$days_in_month;$i++){
            $days[$i]['day'] = $i+1;
            $days[$i]['WeekDay'] = date('l',mktime(12, 0, 0, $month, $i+1, $year));
            $days[$i]['holiday'] = 0;
            $days[$i]['view_class'] = 'success';
            $days[$i]['date'] = date("Y-m-d",mktime(12, 0, 0, $month, $i+1, $year));
            $days[$i]['mktime'] = mktime(12, 0, 0, $month, $i+1, $year);
                //Сначала проверим является ли день субботой или воскресеньем, тк они в любом случае праздничные
                if($days[$i]['WeekDay'] == 'Saturday' || $days[$i]['WeekDay'] == 'Sunday'){
                    $days[$i]['holiday'] = true;
                    $days[$i]['view_class'] = 'danger';
                }
                //Цикл для проверки, является ли этот день праздничным
                foreach($all_holidays as $holiday_row) {
                    //Если этот праздник повторяется каждый год, проверяем только месяц и день
                    if ($holiday_row['to_repeat'] == 1) {
                        if (date("m.d", mktime(12, 0, 0, $month, $i + 1)) == date('m.d', $holiday_row['date'])) {
                            $days[$i]['holiday'] = true;
                            $days[$i]['view_class'] = 'danger';

                        }
                    }
                    //Иначе чекаем полную дату
                    else {
                        if (date("m.d.y", mktime(12, 0, 0, $month, $i + 1, $year)) == date('m.d.y', $holiday_row['date'])) {
                            $days[$i]['holiday'] = true;
                            $days[$i]['view_class'] = 'danger';

                        }
                    }
                }
            if($days[$i]['date'] ==  date('Y-m-d')){
                $days[$i]['view_class'] = 'info';
            }
            //Циклом будем отправлять в функцию get_staff id каждого юзера
            //Оттуда мы получим, все даты прихода юзера, засунем их в этот же массив под ключом times
            $y=0;
            foreach($all_users as $row){
                $all_users[$y]['times'] = $users->get_staff($row['id'],$month,$year);
                $y++;

            }

            //Вставим юзеров
            $days[$i]['users'] = $all_users;
        }
        $personal_info = $users->get_personal_info($this->session->get('auth-identity')['id'],$month,$year,$all_holidays);
        $this->view->setVars(array(
            'all_users' => $all_users,
            'days' => $days,
            'first_date' => $first_date,
            'selected_month' => $month,
            'selected_year' => $year,
            'personal_info' => $personal_info
        ));
    }
    public function startTimeAction(){
        $id = $this->filter->sanitize($this->request->getPost('id'), 'int');
        $action = $this->request->getPost('action');
        $times = new Times();

        if($action == 'start'){
            $current_time = $times->start($id);
            $data = array(
                'current_time' => $current_time,
            );
            return json_encode($data);
        }
        if($action == 'stop'){
            $current_time = $times->stop($id);
            $data = array(
                'current_time' => $current_time,
            );
            return json_encode($data);
        }
        $data = array(
            'current_time' => 0
        );
        return json_encode($data);
    }
    public function stopTimeAction(){
        $id = $this->filter->sanitize($this->request->getPost('id'), 'int');
        $key = $this->filter->sanitize($this->request->getPost('key'), 'int');
        $date = $this->filter->sanitize($this->request->getPost('date'), 'int');
        $action = $this->request->getPost('action');
        $input_value = $this->filter->sanitize($this->request->getPost('input_value'), 'int');
        $times = new Times();
        $times->change($id,$action,$date,$input_value,$key);
        $data = array(
            'id' => $id,
            'key' => $key,
            'date' => $date,
            'action' => $action,
            'input_value' => $input_value,
        );
        return json_encode($data);
    }
    public function dinnerAndLateAction(){
        $id = $this->filter->sanitize($this->request->getPost('id'), 'int');
        $check_info = $this->filter->sanitize($this->request->getPost('check_info'), 'int');
        $date = $this->filter->sanitize($this->request->getPost('date'), 'int');
        $action = $this->request->getPost('action');
        $user_time = Times::findFirst(array(
            "conditions" => "user_id = :id: and date = :date:",
            "bind" => array(
                'id' => "$id",
                'date' => "$date"
            )
        ));
        if($action == 'dinner'){
            $user_time->dinner = $check_info;
            $user_time->update();
        }
        else{
            $user_time->late = $check_info;
            $user_time->update();
        }

        $data = array(
            'id' => $id,
            'check_info' => $check_info
        );
        return json_encode($data);
    }
}