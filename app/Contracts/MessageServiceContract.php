<?php

namespace App\Contracts;

interface MessageServiceContract
{
    /**
     * @return mixed
     */
    public function getCurrentlyAbsent();

    /**
     * @param mixed $currentlyAbsent
     */
    public function setCurrentlyAbsent($currentlyAbsent): void;

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
