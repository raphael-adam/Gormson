<?php

namespace App\Service;

interface MessageServiceContract
{
    /**
     * @return mixed
     */
    public function getOnVacation();

    /**
     * @param mixed $onVacation
     */
    public function setOnVacation($onVacation): void;

    /**
     * @return mixed
     */
    public function getNextWeek();

    /**
     * @param mixed $nextWeek
     */
    public function setNextWeek($nextWeek): void;

    /**
     * @return mixed
     */
    public function getMessage();

    /**
     * @param mixed $message
     */
    public function setMessage($message): void;

    public function send();
}
