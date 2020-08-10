<?php

namespace App\Contracts;

interface MessageServiceContract
{
   public function setCurrentlyAbsent($currentlyAbsent);

   public function setAbsentNextWeek($absentNextWeek);

   public function setAbsentUpdate($absentUpdate);

   public function setAbsentMonday($absentMonday);

   public function send();
}
