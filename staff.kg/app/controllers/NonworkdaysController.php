<?php
namespace Staff\Controllers;

use http\Params;
use Staff\Forms\LoginForm;
use Staff\Models\Users;
use Staff\Models\Times;
use Staff\Models\Nonworkdays;
use DateTime;

class NonworkdaysController extends ControllerBase
{

    public function indexAction()
    {
        $holidays = Nonworkdays::find()->toArray();

        $this->view->setVars(array(
            'holidays' => $holidays,
        ));
    }
    public function newHolidayAction(){
        $date = $this->request->getPost('date');
        $name = $this->request->getPost('name');
        $date_pieces = explode('-',$date);
        $non_work_days = new Nonworkdays();
        $non_work_days->date = mktime(12, 0, 0,$date_pieces[1],$date_pieces[2],$date_pieces[0]);
        $non_work_days->name = $name;
        $non_work_days->to_repeat = 0;
        $non_work_days->save();
        $data = array(
            'date' => date('Y-m-d',mktime(12, 0, 0,$date_pieces[1],$date_pieces[2],$date_pieces[0])),
            'name' => $name
        );
        return json_encode($data);
    }
    public function holidayRepeatAction(){
        $check_info = $this->filter->sanitize($this->request->getPost('check_info'), 'int');
        $date = $this->filter->sanitize($this->request->getPost('date'), 'int');

        $non_work_day = Nonworkdays::findFirst(array(
            "conditions" => "date = :date:",
            "bind" => array(
                'date' => "$date"
            )
        ));

        if($check_info == 1){
            $non_work_day->to_repeat = 1;
            $non_work_day->save();
        }
        else{
            $non_work_day->to_repeat = 0;
            $non_work_day->save();
        }
        $data = array(
            'date' => strtotime($date),
            'check_info' => $check_info
        );
        return json_encode($data);
    }
    public function holidayDeleteAction(){
        $check_info = $this->filter->sanitize($this->request->getPost('check_info'), 'int');
        $date = $this->filter->sanitize($this->request->getPost('date'), 'int');

        $non_work_day = Nonworkdays::findFirst(array(
            "conditions" => "date = :date:",
            "bind" => array(
                'date' => "$date"
            )
        ));

        if($check_info == 1){
            $non_work_day->to_repeat = 1;
            $non_work_day->save();
        }
        else{
            $non_work_day->to_repeat = 0;
            $non_work_day->save();
        }
        $data = array(
            'date' => strtotime($date),
            'check_info' => $check_info
        );
        return json_encode($data);
    }
}

