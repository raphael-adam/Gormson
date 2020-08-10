<?php


namespace App\Repository;


interface AbsenceRepositoryInterface
{

    public function getAll();

    public function getByName($employee);

    public function currentlyAbsent();

    public function absentInDayRange($start, $end);

    public function absenceUpdated();

    public function create($absence);

    public function deleteObsolete($events);

    public function delete($id);


}
