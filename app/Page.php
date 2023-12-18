<?php
/**
 * Class Page.
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use File;

/**
 * Class Page
 *
 */
class Page extends Model
{

     /**
      * Fillables for the database
      *
      * @access protected
      *
      * @var array $fillable
      */
    protected $fillable = array('title', 'slug', 'body');

    /**
     * Pages can have multiple meta
     *
     * @return relation
     */
    public function meta()
    {
        return $this->morphMany('App\Meta', 'metable');
    }

    /**
     * Pages can have multiple meta
     *
     * @return relation
     */
    public function metas()
    {
        return $this->morphMany('App\Meta', 'metable');
    }

    /**
     * Pages can have multiple meta
     *
     * @return relation
     */
    public function metaValue($meta_key)
    {
        return $this->morphMany('App\Meta', 'metable')->where('meta_key', $meta_key)->select('id', 'meta_value')->first();
    }

    /**
     * Set slug attribute
     *
     * @param int $value page ID
     *
     * @return array
     */
    public function setSlugAttribute($value)
    {
        if (!empty($value)) {
            $temp = str_slug($value, '-');
            if (!Page::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!Page::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
    }

    /**
     * Get Pages
     *
     * @return array
     */
    public static function getPages()
    {
        $pages = DB::table('pages')->paginate(5);
        return $pages;
    }

    /**
     * Get Parent Pages
     *
     * @param mixed $request $req->attribute
     *
     * @return array
     */
    public function savePage($request)
    {
        if (!empty($request)) {
            $old_path = Helper::PublicPath() . '/uploads/pages/temp';
            if (empty($request['id'])) {
                $page = $this;
                $page->slug = filter_var($request->title, FILTER_SANITIZE_STRING);
            } else {
                $page = self::findOrFail($request['id']);
                $page->meta()->delete();
                if ($page->title != $request['title']) {
                    $page->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
                }
            }
            $page->title = filter_var($request->title, FILTER_SANITIZE_STRING);
            $page->body = !empty($request->body) ? $request->body : 'null';
            if ($request->parent_type == 'page') {
                if ($request['parent_id']) {
                    $page->relation_type = 1;
                } else {
                    $page->relation_type = 0;
                }
            }
            $page->save();
            $page_id =  $page->id;
            $new_path = Helper::PublicPath() . '/uploads/pages/' . $page_id;
            $page = self::findOrFail($page->id);
            if ($request->parent_type == 'custom_link') {
                $meta = new Meta();
                $meta->meta_key = 'custom_link';
                $meta->meta_value = $request->parent_id;
                $page->meta()->save($meta);

            }
            if (!empty($request->parent_type)) {
                $meta = new Meta();
                $meta->meta_key = 'parent_type';
                $meta->meta_value = $request->parent_type;
                $page->meta()->save($meta);
            }
            if (!empty($request['pageMeta'])) {
                $pageMeta = array();
                foreach ($request['pageMeta'] as $page_meta_key => $value) {
                    if ($page_meta_key == 'headerStyling') {
                        foreach ($value as $header_key => $header_value) {
                            if ($header_key == 'logo' && !empty($header_value)) {
                                $filename = $header_value;
                                if (file_exists($old_path . '/' . $header_value)) {
                                    if (!file_exists($new_path)) {
                                        File::makeDirectory($new_path, 0755, true, true);
                                    }
                                    $filename = time() . '-' . $header_value;
                                    rename($old_path . '/' . $header_value, $new_path . '/' . $filename);
                                }
                                $pageMeta[$page_meta_key][$header_key] = $filename;
                            } else {
                                $pageMeta[$page_meta_key][$header_key] = $header_value;
                            }
                        }
                    } else {
                        $pageMeta[$page_meta_key] = $value;
                    }
                }
                $page->meta = serialize($pageMeta);
                $page->save();
            }
            if (!empty($request['meta'])) {
                foreach ($request['meta'] as $key => $value) {
                    if (!empty($value)) {
                        if ($key == 'editors') {
                            foreach ($value as $meta_key => $meta_value) {
                                $content_section = array();
                                $meta = new Meta();
                                foreach ($meta_value as $content_key => $content) {
                                    if ($content_key == 'description') {
                                        $content_section[$content_key] = str_replace('"', "'", $content);
                                    } else {
                                        $content_section[$content_key] = $content;
                                    }
                                }
                                $meta->meta_key = $key . (string) $value[$meta_key]['parentIndex'];
                                $meta->meta_value = serialize($content_section);
                                $page->meta()->save($meta);
                            }
                        } elseif ($key == 'images' && !empty($value)) {
                            foreach ($value as $meta_key => $meta_value) {
                                $image_section = array();
                                $meta = new Meta();
                                foreach ($meta_value as $image_key => $image_value) {
                                    if ($image_key == 'url' && !empty($image_value)) {
                                        $filename = $image_value;
                                        if (file_exists($old_path . '/' . $image_value)) {
                                            if (!file_exists($new_path)) {
                                                File::makeDirectory($new_path, 0755, true, true);
                                            }
                                            $filename = time() . '-' . $image_value;
                                            rename($old_path . '/' . $image_value, $new_path . '/' . $filename);
                                        }
                                        $image_section[$image_key] = $filename;
                                    } else {
                                        $image_section[$image_key] = $image_value;
                                    }
                                }
                                $meta->meta_key = $key . (string) $value[$meta_key]['parentIndex'];
                                $meta->meta_value = serialize($image_section);
                                $page->meta()->save($meta);
                            }
                        } elseif ($key == 'search_forms' && !empty($value)) {
                            foreach ($value as $meta_key => $meta_value) {
                                $search_section = array();
                                $meta = new Meta();
                                foreach ($meta_value as $search_key => $search_value) {
                                    if ($search_key == 'image' && !empty($search_value)) {
                                        $filename = $search_value;
                                        if (file_exists($old_path . '/' . $search_value)) {
                                            if (!file_exists($new_path)) {
                                                File::makeDirectory($new_path, 0755, true, true);
                                            }
                                            $filename = time() . '-' . $search_value;
                                            rename($old_path . '/' . $search_value, $new_path . '/' . $filename);
                                        }
                                        $search_section[$search_key] = $filename;
                                    } else {
                                        $search_section[$search_key] = $search_value;
                                    }
                                }
                                $meta->meta_key = $key . (string) $value[$meta_key]['parentIndex'];
                                $meta->meta_value = serialize($search_section);
                                $page->meta()->save($meta);
                            }
                        } elseif ($key == 'about_sections' && !empty($value)) {
                            foreach ($value as $meta_key => $meta_value) {
                                $about_section = array();
                                $meta = new Meta();
                                foreach ($meta_value as $about_key => $about_value) {
                                    if ($about_key == 'afterSection' && !empty($about_value)) {
                                        $filename = $about_value;
                                        if (file_exists($old_path . '/' . $about_value)) {
                                            if (!file_exists($new_path)) {
                                                File::makeDirectory($new_path, 0755, true, true);
                                            }
                                            $filename = time() . '-' . $about_value;
                                            rename($old_path . '/' . $about_value, $new_path . '/' . $filename);
                                        }
                                        $about_section[$about_key] = $filename;
                                    } else if ($about_key == 'image' && !empty($about_value)) {
                                        $image_section = array();
                                        foreach ($about_value as $image_key => $mage_value) {
                                            if ($image_key == 'url' && !empty($mage_value)) {
                                                $filename = $mage_value;
                                                if (file_exists($old_path . '/' . $mage_value)) {
                                                    if (!file_exists($new_path)) {
                                                        File::makeDirectory($new_path, 0755, true, true);
                                                    }
                                                    $filename = time() . '-' . $mage_value;
                                                    rename($old_path . '/' . $mage_value, $new_path . '/' . $filename);
                                                }
                                                $about_section[$about_key][$image_key] = $filename;
                                            } else {
                                                $about_section[$about_key][$image_key] = $mage_value;
                                            }
                                        }
                                    } else {
                                        $about_section[$about_key] = $about_value;
                                    }
                                }
                                $meta->meta_key = $key . (string) $value[$meta_key]['parentIndex'];
                                $meta->meta_value = serialize($about_section);
                                $page->meta()->save($meta);
                            }
                        } elseif ($key == 'app_sections' && !empty($value)) {
                            foreach ($value as $meta_key => $meta_value) {
                                $app_section = array();
                                $meta = new Meta();
                                foreach ($meta_value as $app_key => $app_value) {
                                    if ($app_key == 'googlePlay' && !empty($app_value['image'])) {
                                        $google_play_section = array();
                                        if (!empty($app_value['image'])) {
                                            $filename = $app_value['image'];
                                            if (file_exists($old_path . '/' . $app_value['image'])) {
                                                if (!file_exists($new_path)) {
                                                    File::makeDirectory($new_path, 0755, true, true);
                                                }
                                                $filename = time() . '-' . $app_value['image'];
                                                rename($old_path . '/' . $app_value['image'], $new_path . '/' . $filename);
                                            }
                                            $google_play_section['image'] = $filename;
                                        } else {
                                            $google_play_section['url'] = $app_value;
                                        }
                                        $app_section[$app_key] = $google_play_section;
                                    } else if ($app_key == 'appStore' && !empty($app_value['image'])) {
                                        $app_store_section = array();
                                        if (!empty($app_value['image'])) {
                                            $filename = $app_value['image'];
                                            if (file_exists($old_path . '/' . $app_value['image'])) {
                                                if (!file_exists($new_path)) {
                                                    File::makeDirectory($new_path, 0755, true, true);
                                                }
                                                $filename = time() . '-' . $app_value['image'];
                                                rename($old_path . '/' . $app_value['image'], $new_path . '/' . $filename);
                                            }
                                            $app_store_section['image'] = $filename;
                                        } else {
                                            $app_store_section['url'] = $app_value;
                                        }
                                        $app_section[$app_key] = $app_store_section;
                                    } else if ($app_key == 'image') {
                                        $app_image_section = array();
                                        if (!empty($app_value['url'])) {
                                            // $filename = $app_value['url'];
                                            if (file_exists($old_path . '/' . $app_value['url'])) {
                                                if (!file_exists($new_path)) {
                                                    File::makeDirectory($new_path, 0755, true, true);
                                                }
                                                // $filename = time() . '-' . $app_value['url'];
                                                rename($old_path . '/' . $app_value['url'], $new_path . '/' . $app_value['url']);
                                            }
                                        } 
                                        $app_section[$app_key] = $app_value;
                                    }
                                    else {
                                        $app_section[$app_key] = $app_value;
                                    }
                                }
                                $meta->meta_key = $key . (string) $value[$meta_key]['parentIndex'];
                                $meta->meta_value = serialize($app_section);
                                $page->meta()->save($meta);
                            }
                        } elseif ($key == 'two_columns' && !empty($value)) {
                            foreach ($value as $meta_key => $meta_value) {
                                $two_column_section = array();
                                $meta = new Meta();
                                foreach ($meta_value as $two_column_key => $two_column_value) {
                                    if ($two_column_key == 'image' && !empty($two_column_value)) {
                                        $filename = $two_column_value;
                                        if (file_exists($old_path . '/' . $two_column_value)) {
                                            if (!file_exists($new_path)) {
                                                File::makeDirectory($new_path, 0755, true, true);
                                            }
                                            $filename = time() . '-' . $two_column_value;
                                            rename($old_path . '/' . $two_column_value, $new_path . '/' . $filename);
                                        }
                                        $two_column_section[$two_column_key] = $filename;
                                    } else {
                                        $two_column_section[$two_column_key] = $two_column_value;
                                    }
                                }
                                $meta->meta_key = $key . (string) $value[$meta_key]['parentIndex'];
                                $meta->meta_value = serialize($two_column_section);
                                $page->meta()->save($meta);
                            }
                        } else if ($key == 'headings' || $key == 'service_tabs' || $key == 'slidersV1' || 'how_work_sections'
                            || 'speciality_sections' || 'article_sections') {
                            foreach ($value as $meta_key => $meta_value) {
                                $meta = new Meta();
                                $meta->meta_key = $key . (string) $value[$meta_key]['parentIndex'];
                                $meta->meta_value = serialize($meta_value);
                                $page->meta()->save($meta);
                            }
                        } 
                    }
                }
            }
            if (!empty(json_decode($request['sections']))) {
                $sections = json_decode($request['sections']);
                $page->sections = serialize($sections);
                $page->save();
            }
            $json['id'] = $page_id;
            $json['slug'] = $page->slug;
            $json['type'] = 'success';
            return $json;
        }
    }

    /**
     * Get Parent Pages
     *
     * @param int   $id      page ID
     * @param mixed $request request
     *
     * @return array
     */
    public function updatePage($id, $request)
    {
        if (!empty($id) && !empty($request)) {
            $pages = Page::find($id);
            if ($pages->title != $request->title) {
                $pages->slug = filter_var($request->title, FILTER_SANITIZE_STRING);
            }
            $pages->title = filter_var($request->title, FILTER_SANITIZE_STRING);
            $pages->body = $request->content;
            if ($request->parent_id == null) {
                $pages->relation_type = 0;
            } elseif ($request->parent_id) {
                $pages->relation_type = 1;
            }
            if (!empty($request['meta'])) {
                $meta = array();
                foreach ($request['meta'] as $key => $value) {
                    $meta[$key] = $value;
                }
                $pages->meta = serialize($meta);
            }
            return $pages->save();
        }
    }

    /**
     * Get Page Data
     *
     * @param int $slug Slug
     *
     * @return array
     */
    public static function getPageData($slug)
    {
        if (!empty($slug) && is_string($slug)) {
            return DB::table('pages')->select('*')->where('slug', $slug)->get()->first();
        }
    }

    /**
     * Get Parent Slug
     *
     * @param int $id page ID
     *
     * @return array
     */
    public static function getPageslug($id)
    {
        if (!empty($id) && is_numeric($id)) {
            return DB::table('pages')->select('slug')->where('id', $id)->get()->first();
        }
    }


    /**
     * Get Parent Pages
     *
     * @param int $id pageID
     *
     * @return array
     */
    public function getParentPages($id = '')
    {
        if (!empty($id)) {
            return DB::table('pages')->where('relation_type', 0)->where('id', '!=', $id)->pluck('title', 'id')->prepend('Select parent', '');
        } else {
            return DB::table('pages')->where('relation_type', '=', 0)->pluck('title', 'id')->prepend('Select parent', '');
        }

    }

    /**
     * Get Page List
     *
     * @return array
     */
    public static function getPageList()
    {
        return DB::table('pages')->select('title', 'slug')->pluck('title', 'slug');
    }

    /**
     * Get Child Pages
     *
     * @param int $child_id page child ID
     *
     * @return array
     */
    public static function getChildPages($child_id)
    {
        return DB::table('pages')->select('title', 'slug', 'id')->where('id', $child_id)->get()->first();
    }

    /**
     * Get pages with child
     *
     * @param int $page_id page ID
     *
     * @return array
     */
    public static function pageHasChild($page_id)
    {
        if (!empty($page_id) && is_numeric($page_id)) {
            return DB::table('pages')
                ->join('child_pages', 'pages.id', '=', 'child_pages.parent_id')
                ->select('pages.id', 'pages.title', 'child_pages.child_id')
                ->where('child_pages.parent_id', '=', $page_id)
                ->get()->all();
        }
    }

    /**
     * Get pages with child
     *
     * @param int $page_id page ID
     *
     * @return array
     */
    public static function pageHasParent($page_id)
    {
        if (!empty($page_id) && is_numeric($page_id)) {
            return DB::table('pages')
                ->join('child_pages', 'pages.id', '=', 'child_pages.parent_id')
                ->select('pages.id', 'pages.title', 'child_pages.child_id')
                ->where('child_pages.child_id', '=', $page_id)
                ->get()->count();
        }
    }
}
