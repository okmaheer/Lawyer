<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicineDuration extends Model
{
      
    protected $table = 'medicine_duration';

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
            if (!MedicineDuration::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!MedicineDuration::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
     }

      /**
      * Saving medicine duration
      *
      * @param mixed $request Request attributes
      *
      * @return \Illuminate\Http\Response
      */
    public function saveMedicineDuration($request)
     {
         if (!empty($request)) {
             $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
             $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
             return $this->save();
         }
     }
 
     /**
      * Updating medicine duration
      *
      * @param mixed $request Request attributes
      * @param int   $id      get id for updation
      *
      * @return \Illuminate\Http\Response
      */
    public function updateMedicineDuration($request, $id)
     {
        if (!empty($request)) {
            $medicine_duration = self::find($id);
            if ($medicine_duration->title != $request['title']) {
                $medicine_duration->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $medicine_duration->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $medicine_duration->save();
         }
    }
}
