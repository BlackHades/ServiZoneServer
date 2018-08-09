<?php

/**
 * Created by PhpStorm.
 * User: LordRahl
 * Date: 3/17/17
 * Time: 11:02 PM
 */

namespace App\Http\Controllers;

use Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class Utility {

    /**
     * Utility constructor.
     */
    public function __construct() {
        
    }

    /**
     * @param $data
     * @return mixed
     */
    static function return200($data) {
        $msg = ['code' => 200, 'status' => 'success', 'message' => $data];
        return response()->json($msg)->header('Content-Type', 'application/json');
    }

    /**
     * @param $data
     * @return mixed
     */
    static function return500($data) {
        $msg = ['code' => 500, 'status' => 'error', 'message' => $data];
        return response()->json($msg)->header('Content-Type', 'application/json');
    }

    /**
     * @param $data
     * @return mixed
     */
    static function return405($data) {
        $msg = ['code' => 405, 'status' => 'error', 'message' => $data];
        return response()->json($msg)->header('Content-Type', 'application/json');
    }

    static function uploadItemPhoto($file) {

        ini_set('upload_max_filesize', '100000M');
        ini_set('post_max_size', '1000000');

        //we move the file and return the url
        $folder = Date('Y') . '/' . Date('M');
        $path = storage_path($folder);

        if (!file_exists($path)) {
            Storage::disk('local')->makeDirectory($folder, 0777);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($path, $filename);
        return $folder . '/' . $filename;
    }

    /**
     * @param $items
     * @param $perPage
     * @return LengthAwarePaginator
     */
    static function paginate($items, $perPage) {
        if (is_array($items)) {
            $items = collect($items);
        }

        return new LengthAwarePaginator(
                $items->forPage(Paginator::resolveCurrentPage(), $perPage), $items->count(), $perPage, Paginator::resolveCurrentPage(), ['path' => Paginator::resolveCurrentPath()]
        );
    }

    static function generateRsandomChar($num) {
        return str_random($num);
    }
    
    
    static function createTextImage($text) {
        header('Content-Type: image/png');

        // Create the image
        $im = imagecreatetruecolor(1900, 300);
        $color = rand(0, 255);
        $color2 = rand(0, 255);

        // Create some colors
        $fontColor = imagecolorallocate($im, 255, 255, 255);
        $background = imagecolorallocate($im, $color2, $color, $color);
        imagefilledrectangle($im, 0, 0, 1900, 300, $background);

        $font = '/home/fabuloxi/public_html/finco-assets/ttf/arial.ttf';

        // Add the text
        //imagettftext($im, 50, 0, 390, 170, $fontColor, $font, $text);

        ob_start();
        imagepng($im);

        // Capture the output
        $imagedata = ob_get_contents();

        // Clear the output buffer
        ob_end_clean();
        return $imagedata;
    }

    static function getCapitalLetters($str) {
        if (preg_match_all('/\b[A-Z]+\b/', $str, $matches)) {
            return implode('', $matches[0]);
        } else {
            return false;
        }
    }

    /*-----------------------------------
     * @return Success Message
    -----------------------------------*/
    static function returnSuccess($message = "The operation was successful", $data="") {
        $msg = [
            'status'=>'success',
            'message'=> $message,
            'data' => json_encode($data)];
        return $msg;
    }
    static function returnUnauthorizedUser($message = "Unathorized User") {
        $msg = [
            'status'=>'unauthorised_user',
            'message'=> $message,];
        return $msg;
    }

    /*-----------------------------------
    * @return Error Message
    -----------------------------------*/
    static function returnError($message = "An error occurred", $data="") {
        $msg = [
            'status'=>'error',
            'message'=> $message,
            'data' => json_encode($data)];
        return $msg;
    }



}
