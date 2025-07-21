<?php

namespace App\Controllers;

use App\Models\VaccinationModel;
use App\Models\VaccinationScheduleModel;

class VaccinationScheduleController extends BaseController
{
    public function vaccinationScheduleList()
    {
        $scheduleModel     = new VaccinationScheduleModel();
        $vaccinationModel  = new VaccinationModel();

        $data['vaccination_schedules'] = $scheduleModel
            ->select('vaccination_schedules.*, vaccinations.name AS vaccination_name')
            ->join('vaccinations', 'vaccinations.id = vaccination_schedules.vaccination_id')
            ->orderBy('vaccination_schedules.date', 'DESC')
            ->findAll();

        $data['vaccinations'] = $vaccinationModel->findAll();
        $data['months'] = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        return view('schedule-events/vaccinationSchedule', $data);
    }

    public function addVaccinationSchedule()
    {
        $scheduleModel = new VaccinationScheduleModel();

        $data = [
            'month'          => $this->request->getPost('month'),
            'date'           => $this->request->getPost('date'),
            'vaccination_id' => $this->request->getPost('vaccination_id'),
            'comments'       => $this->request->getPost('comments'),
        ];

        $scheduleModel->insert($data);

        return redirect()->to('/schedule-events/vaccinationSchedule')->with('success', 'Vaccination schedule added successfully.');
    }

    public function editVaccinationSchedule($id)
    {
        $scheduleModel = new VaccinationScheduleModel();

        $data = [
            'month'          => $this->request->getPost('month'),
            'date'           => $this->request->getPost('date'),
            'vaccination_id' => $this->request->getPost('vaccination_id'),
            'comments'       => $this->request->getPost('comments'),
        ];

        $scheduleModel->update($id, $data);

        return redirect()->to('/schedule-events/vaccinationSchedule')->with('success', 'Vaccination schedule updated successfully.');
    }

    public function deleteVaccinationSchedule($id)
    {
        $scheduleModel = new VaccinationScheduleModel();
        $scheduleModel->delete($id);

        return redirect()->to('/schedule-events/vaccinationSchedule')->with('success', 'Vaccination schedule deleted successfully.');
    }
}