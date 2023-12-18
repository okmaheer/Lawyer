<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $table = 'medications';

    /**
     * Get the prescription that  belongs to medicine.
     *
     * @return relation
     */
    public function prescription ()
    {
        return $this->belongsTo('App\Prescription');
    }

    /**
     * Get the medicine type that  belongs to medicine.
     *
     * @return relation
     */
    public function medicine_type ()
    {
        return $this->belongsTo('App\MedicineType');
    }

    /**
     * Get the medicine duration that belongs to medicine.
     *
     * @return relation
     */
    public function medicine_duration ()
    {
        return $this->belongsTo('App\MedicineDuration');
    }

    /**
     * Get the medicine usage that belongs to medicine.
     *
     * @return relation
     */
    public function medicine_usage ()
    {
        return $this->belongsTo('App\MedicineUsage');
    }
}
