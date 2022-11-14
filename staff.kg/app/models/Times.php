<?php
namespace Staff\Models;

use MongoDB\BSON\Timestamp;
use Phalcon\Mvc\Model;

class Times extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $date;

    /**
     *
     * @var integer
     */
    public $time;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'date' => 'date',
            'time' => 'time',
            'dinner' => 'dinner',
            'late' => 'late',
            'user_id' => 'user_id'
        );
    }
    public function change($id,$action,$date,$input_value,$key){
        $user_time = Times::findFirst(array(
            "conditions" => "user_id = :id: and date = :date:",
            "bind" => array(
                'id' => "$id",
                'date' => "$date"
            )
        ));
        if (!$user_time) {
            return 1;
        }
        $all_times = json_decode($user_time->toArray()['time']);
        $time_pieces = explode("-", $input_value);
        $date_pieces = explode('-',$date);
        $new_time = mktime($time_pieces[0],$time_pieces[1],0,$date_pieces[1],$date_pieces[2],$date_pieces[0]);
        $all_times[$key]->$action = $new_time;
        $json_time_array = json_encode($all_times);
        $user_time->date = $date;
        $user_time->time = $json_time_array;
        $user_time->user_id = $id;
        $user_time->update();
    }
    public function stop($id){
        $date = date('Y-m-d');
        $user_time = Times::findFirst(array(
            "conditions" => "user_id = :id: and date = :date:",
            "bind" => array(
                'id' => "$id",
                'date' => "$date"
            )
        ));

        //Проверим на наличие
        if (!$user_time) {
            return 1;
        }
        //Найдем посследний элемент в time и изменим end_time на текущее время
        $time_array = json_decode($user_time->toArray()['time']);
        end($time_array);
        $last_time_array_key = key($time_array);
        //Вставляем в последний элемент, end_time
        $user_come_time = time();
        $time_array[$last_time_array_key]->end = $user_come_time;


        $json_time_array = json_encode($time_array);
        $user_time->date = $date;
        $user_time->time = $json_time_array;
        $user_time->user_id = $id;
        $user_time->update();


        return date("h.i",$user_come_time);;
    }
    public function start($id)
    {
        $date = date('Y-m-d');
        //Находим элемент в таблице times
        //По результату проверяем, есть ли в нем элементы
        $user_time = Times::findFirst(array(
            "conditions" => "user_id = :id: and date = :date:",
            "bind" => array(
                'id' => "$id",
                'date' => "$date"
            )
        ));
        //Если элементов нет, то создаем новый, тк юзер чикинится за день 1 раз
        if (!$user_time) {
            $user_come_time = time();
            $time_array = array(
                array(
                    'start' => $user_come_time,
                    'end' => 0
                )
            );
            $json_time_array = json_encode($time_array);

            $this->date = $date;
            $this->time = $json_time_array;
            $this->user_id = $id;
            $this->save();

            return date("h.i",$user_come_time);;
        }
        else{
            //Так как элемент существует, возьмем его json time и сделаем array_push
            $user_come_time = time();
            $time_array = json_decode($user_time->toArray()['time']);

            $last_time_array_element = end($time_array);
            //существует ли end, нажать старт нельзя если я до этого уже нажимал и не остановил
            if($last_time_array_element->end == 0){
                return 0;
            }
            else{
                array_push($time_array, array(
                    'start' => $user_come_time,
                    'end' => 0
                ));
                $json_time_array = json_encode($time_array);
                $user_time->date = $date;
                $user_time->time = $json_time_array;
                $user_time->user_id = $id;
                $user_time->update();
                return date("h.i",$user_come_time);
            }
        }
    }
}