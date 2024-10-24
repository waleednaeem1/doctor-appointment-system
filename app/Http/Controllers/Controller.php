<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Laramin\Utility\Onumoti;
use App\Models\Country;
use App\Models\States;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $activeTemplate;

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();

        $className = get_called_class();
        Onumoti::mySite($this,$className);
    }
    public static function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function getAllCountries()
    {
        $data['allCountries'] = Country::all(array('id','name'));
        return response()->json($data, 200);
    }
    public function getAllStates($country_id)
    {
        $data['allStates'] = States::where('country_id', $country_id)->orderBy('name', 'asc')->get(array('id','name'));
        return response()->json($data, 200);
    }
}
