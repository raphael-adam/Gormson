<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        "employee_id",
        "absence_id",
        "absence_begin",
        "absence_end",
        "absence_type",
        "substitute_01_id",
        "substitute_02_id",
        "substitute_03_id",
    ];

    public $table = "absence";

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
