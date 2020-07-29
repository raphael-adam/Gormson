<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        "employee_id",
        "vacation_id",
        "vacation_begin",
        "vacation_end",
        "leave_type",
        "substitute01_id",
        "substitute02_id",
        "substitute03_id",
    ];

    public function employee() {
        return $this->hasOne(Employee::class, "id","employee_id");
    }

    public function substitute01() {
        return $this->hasOne(Employee::class, "id","substitute01_id");
    }

    public function substitute02() {
        return $this->hasOne(Employee::class, "id","substitute02_id");
    }

    public function substitute03() {
        return $this->hasOne(Employee::class, "id","substitute03_id");
    }

}
