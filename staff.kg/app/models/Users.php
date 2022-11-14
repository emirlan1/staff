<?php
namespace Staff\Models;

use MongoDB\BSON\Timestamp;
use Phalcon\Mvc\Model;

/**
 * FailedLogins
 * This model registers unsuccessfull logins registered and non-registered users have made
 */

class Users extends Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $login;

    /**
     *
     * @var integer
     */
    protected $access;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field login
     *
     * @param string $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Method to set the value of field access
     *
     * @param integer $access
     * @return $this
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Returns the value of field access
     *
     * @return integer
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('Users');
        $this->belongsTo('profilesId', __NAMESPACE__ . '\Profiles', 'id', [
            'alias' => 'profile',
            'reusable' => true
        ]);

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'login' => 'login', 
            'password' => 'password',
            'profilesId' => 'profilesId',
            'active' => 'active'
        );
    }
    public function get_staff($id,$month,$year){

        $min_year = date('Y-m-d',strtotime("$year-$month-01"));
        $max_year = date('Y-m-d',strtotime("$year-$month-31"));
        $user_time = Times::find(array(
            "conditions" => "user_id = :id: and date >= :min_year: and date <= :max_year:",
            "bind"       => array(
                'id' => $id,
                'min_year' => $min_year,
                'max_year' => $max_year
            )
        ));
        //print_die($min_year);
        //Проверяем на существо,
        if(!$user_time){
            return $user_time;
        }
        //У нас сохранен json массив в элементе time и прокуртив все время в нем нужно соединить все это в одно целое для юзера
        $user_time_array = $user_time->toArray();

        //i нужен что бы добавить во внешний массив всю информацию
        $i=0;
        //Циклируем все его время
        foreach($user_time_array as $row){
            //оздадим в каждом элменте пустой стринг, что бы добавлять к нему значения времени
            $user_time_array[$i]['html'] = '';
            $user_time_array[$i]['input_array'] = array();
            $user_time_array[$i]['seconds'] = '';
            //Переведем в объект и циклом вытащим время прихода и ухода
            $json_string_to_object = json_decode($row['time']);
            $y=0;
            foreach($json_string_to_object as $json_row){
                if($json_row->end != 0){
                    $user_time_array[$i]['html'] .= "<span>".date("h.i",$json_row->start).'-</span>';
                    $user_time_array[$i]['html'] .= "<span>".date("h.i",$json_row->end).'</span><br>';


                    $user_time_array[$i]['input_array'][$y]['start'] = date("h-i",$json_row->start);
                    $user_time_array[$i]['input_array'][$y]['stop'] = date("h-i",$json_row->end);



                    $user_time_array[$i]['seconds'] = intval($user_time_array[$i]['seconds']) + $json_row->end - $json_row->start;
                }
                else{
                    if(date('Y-m-d',$json_row->start) != date('Y-m-d')){
                        // UPDATE TIMES AND HTML NULL
                        $forgot_user_date = Times::findFirst(array(
                            "conditions" => "user_id = :id: and date = :date:",
                            "bind" => array(
                                'id' => "$id",
                                'date' => date('Y-m-d',$json_row->start)
                            )
                        ));

                        $time_array = json_decode($forgot_user_date->toArray()['time']);
                        end($time_array);
                        $last_time_array_key = key($time_array);
                        $time_array[$last_time_array_key]->end = null;

                        $json_time_array = json_encode($time_array);
                        $forgot_user_date->date = date('Y-m-d',$json_row->start);
                        $forgot_user_date->time = $json_time_array;
                        $forgot_user_date->user_id = $id;
                        $forgot_user_date->update();

                        $user_time_array[$i]['html'] .= "<span>".date("h.i",$json_row->start).'-</span>';
                        $user_time_array[$i]['html'] .= "<span>FORGOT</span><br>";

                        $user_time_array[$i]['input_array'][$y]['start'] = date("h-i",$json_row->start);
                        $user_time_array[$i]['input_array'][$y]['stop'] = null;


                        $user_time_array[$i]['seconds'] = intval($user_time_array[$i]['seconds']);


                    }
                    else{
                        $user_time_array[$i]['html'] .= "<span>".date("h.i",$json_row->start).'-</span>';


                        $user_time_array[$i]['input_array'][$y]['start'] = date("h-i",$json_row->start);
                        $user_time_array[$i]['input_array'][$y]['stop'] = null;
                        //$user_time_array[$i]['input_array'][$i]['start'] = null;

                        $user_time_array[$i]['seconds'] = intval($user_time_array[$i]['seconds']) + time() - $json_row->start;
                    }
                }
                $y++;
            }
            $user_time_array[$i]['total'] = $this->secondsToTime($user_time_array[$i]['seconds']);
            $user_time_array[$i]['less'] = 0;
            if($user_time_array[$i]['total']['h'] < 9){
                $user_time_array[$i]['less'] = $this->secondsToTime(32400 - intval($user_time_array[$i]['seconds']));
            }
            $i++;
        }
        return $user_time_array;

    }
    public function secondsToTime($inputSeconds) {

        $secondsInAMinute = 60;
        $secondsInAnHour  = 60 * $secondsInAMinute;
        $secondsInADay    = 24 * $secondsInAnHour;

        // extract days
        $days = floor($inputSeconds / $secondsInADay);

        // extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        // extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        // return the final array
        if($seconds > 0){
            $minutes += 1;
            $seconds = 0;
        }
        if($minutes == 60){
            $minutes -= 1;
        }
        $obj = array(
            'h' => (int) $hours,
            'm' => (int) $minutes,
            's' => (int) $seconds,
        );
        return $obj;
    }
    public function get_assigned($month,$year,$all_holidays){
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $assigned = '';
        $count = 0;
        for($i=0;$i<$days_in_month;$i++){
            $day = $i+1;
            $week_day = date('l',mktime(12, 0, 0, $month, $i+1, $year));
            if($week_day == 'Saturday' || $week_day == 'Sunday'){
                continue;
            }else{
                $count = 0;
                foreach($all_holidays as $holiday_row) {

                    //Если этот праздник повторяется каждый год, проверяем только месяц и день
                    if ($holiday_row['to_repeat'] == 1) {
                        if (date("m.d", mktime(12, 0, 0, $month, $i + 1)) == date('m.d', $holiday_row['date'])) {
                            $count = 1;
                        }
                    }
                    //Иначе чекаем полную дату
                    else {
                        if (date("m.d.y", mktime(12, 0, 0, $month, $i + 1, $year)) == date("m.d.y",$holiday_row['date'])) {
                            $count = 1;
                        }

                    }

                }

            }
            if($count == 0){
                $assigned = intval($assigned) + 28800;
            }

        }
       // print_die($assigned);
        return $assigned;
    }
    public function get_personal_info($id,$month,$year,$all_holidays){
        $min_year = date('Y-m-d',strtotime("$year-$month-01"));
        $max_year = date('Y-m-d',strtotime("$year-$month-31"));
        $personal_info = Times::find(array(
            "conditions" => "user_id = :id: and date >= :min_year: and date <= :max_year:",
            "bind"       => array(
                'id' => $id,
                'min_year' => $min_year,
                'max_year' => $max_year
            )
        ))->toArray();
        $seconds = '';
        $i=0;
        foreach($personal_info as $row){
            //оздадим в каждом элменте пустой стринг, что бы добавлять к нему значения времени
            $personal_info[$i]['seconds'] = '';
            //Переведем в объект и циклом вытащим время прихода и ухода
            $json_string_to_object = json_decode($row['time']);
            foreach($json_string_to_object as $json_row){
                if($json_row->end != 0) {
                    $personal_info[$i]['seconds'] = intval($personal_info[$i]['seconds']) + $json_row->end - $json_row->start;
                }
                else{
                    if($json_row->end != null){
                        $personal_info[$i]['seconds'] = intval($personal_info[$i]['seconds']) + time() - $json_row->start;
                    }
                }
                    //print_die($user_time_array[$i]['seconds']);
            }
            $personal_info[$i]['total'] = $this->secondsToTime($personal_info[$i]['seconds']);
            $seconds = intval($seconds) + $personal_info[$i]['seconds'];
            if($personal_info[$i]['dinner'] == 1 ){
                $seconds -= 3600;
            }
            $i++;
        }

        $personal_info_array = array();
        $personal_info_array['seconds'] = $seconds;
        $personal_info_array['total'] = $this->secondsToTime($seconds);
        $personal_info_array['assigned'] = $this->get_assigned($month,$year,$all_holidays)/3600;
        $personal_info_array['percent'] = round(($seconds/$this->get_assigned($month,$year,$all_holidays))*100,2);
     //   round($number, 1)
       // print_die($personal_info_array);
        return $personal_info_array;
    }
}
