<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    protected $table = 'vital_sign';

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
            if (!VitalSign::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!VitalSign::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
     }

      /**
      * Saving Vital Sign
      *
      * @param mixed $request Request attributes
      *
      * @return \Illuminate\Http\Response
      */
    public function saveVitalSign($request)
     {
         if (!empty($request)) {
             $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
             $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
             return $this->save();
         }
     }
 
     /**
      * Updating Vital Sign
      *
      * @param mixed $request Request attributes
      * @param int   $id      get id for updation
      *
      * @return \Illuminate\Http\Response
      */
    public function updateVitalSign($request, $id)
     {
        if (!empty($request)) {
            $vital_sign = self::find($id);
            if ($vital_sign->title != $request['title']) {
                $vital_sign->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $vital_sign->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $vital_sign->save();
         }
    }
}
