<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicineType extends Model
{
    
    protected $table = 'medicine_type';

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
            if (!MedicineType::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!MedicineType::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
     }

      /**
      * Saving medicine type
      *
      * @param mixed $request Request attributes
      *
      * @return \Illuminate\Http\Response
      */
    public function saveMedicineType($request)
     {
         if (!empty($request)) {
             $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
             $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
             return $this->save();
         }
     }
 
     /**
      * Updating medicine type
      *
      * @param mixed $request Request attributes
      * @param int   $id      get id for updation
      *
      * @return \Illuminate\Http\Response
      */
    public function updateMedicineType($request, $id)
     {
        if (!empty($request)) {
            $medicine_type = self::find($id);
            if ($medicine_type->title != $request['title']) {
                $medicine_type->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $medicine_type->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $medicine_type->save();
         }
    }
}
