<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicineUsage extends Model
{
      
    protected $table = 'medicine_usage';

    /**
      * Set slug before saving in DB
      *
      * @param string $value value
      *
      * @access public
      *
      * @return string
      */
     public function setSlugAttribute($value)
     {
        if (!empty($value)) {
            $temp = str_slug($value, '-');
            if (!MedicineUsage::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!MedicineUsage::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
     }

      /**
      * Saving medicine usage
      *
      * @param mixed $request Request attributes
      *
      * @return \Illuminate\Http\Response
      */
    public function saveMedicineUsage($request)
     {
         if (!empty($request)) {
             $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
             $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
             return $this->save();
         }
     }
 
     /**
      * Updating medicine usage
      *
      * @param mixed $request Request attributes
      * @param int   $id      get id for updation
      *
      * @return \Illuminate\Http\Response
      */
    public function updateMedicineUsage($request, $id)
     {
        if (!empty($request)) {
            $medicine_usage = self::find($id);
            if ($medicine_usage->title != $request['title']) {
                $medicine_usage->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $medicine_usage->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $medicine_usage->save();
         }
    }
}
