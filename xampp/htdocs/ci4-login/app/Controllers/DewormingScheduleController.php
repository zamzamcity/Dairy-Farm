<?php

namespace App\Controllers;

use App\Models\DewormingModel;
use App\Models\DewormingScheduleModel;

class DewormingScheduleController extends BaseController
{
    public function dewormingScheduleList()
    {
        $scheduleModel   = new DewormingScheduleModel();
        $dewormingModel  = new DewormingModel();

        $data['deworming_schedules'] = $scheduleModel
            ->select('deworming_schedules.*, deworming.name AS deworming_name')
            ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
            ->orderBy('deworming_schedules.date', 'DESC')
            ->findAll();

        $data['dewormings'] = $dewormingModel->findAll();
        $data['months'] = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        return view('schedule-events/dewormingSchedule', $data);
    }

    public function addDewormingSchedule()
    {
        $scheduleModel = new DewormingScheduleModel();

        $data = [
            'month'        => $this->request->getPost('month'),
            'date'         => $this->request->getPost('date'),
            'deworming_id' => $this->request->getPost('deworming_id'),
            'comments'     => $this->request->getPost('comments'),
        ];

        $scheduleModel->insert($data);

        return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule added successfully.');
    }

    public function editDewormingSchedule($id)
    {
        $scheduleModel = new DewormingScheduleModel();

        $data = [
            'month'        => $this->request->getPost('month'),
            'date'         => $this->request->getPost('date'),
            'deworming_id' => $this->request->getPost('deworming_id'),
            'comments'     => $this->request->getPost('comments'),
        ];

        $scheduleModel->update($id, $data);

        return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule updated successfully.');
    }

    public function deleteDewormingSchedule($id)
    {
        $scheduleModel = new DewormingScheduleModel();
        $scheduleModel->delete($id);

        return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule deleted successfully.');
    }
}