<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 3000000); //300 seconds = 5 minutes

include('simple_html_dom.php');

use App\Expert;
use App\ProfileLinks;
use App\Service;
use Storage;
use Sunra\PhpSimple\HtmlDomParser;

class ExpertExtractController extends Controller
{
    var $x = 0;

    public function extractLinks()
    {
        $this->recursiveUrlLoader(33);
    }

    function recursiveUrlLoader($i){
        $url = "https://jiji.ng/security-cvs";
        $profession_id = 138;

//        $max 190
        if($i == 0){
            $html = file_get_contents($url);
        }
        else{
            $html = file_get_contents($url.'/page'.$i);
        }
//        echo $html;
//        return;
        $html = str_get_html($html);

        foreach($html->find('a.js-advert-link') as $element) {

            try{
                $link = new ProfileLinks();
                $link->link = $element->href;
                $link->profession_id = $profession_id;
                $link->save();

                echo "Stored " . $element->href. $this->x .'<br/><br/>';
            }
            catch (\Exception $ex){
                echo "ERR " . $ex->getMessage() . "<br/><br/>";
            }

            $this->x++;
        }


        if($i == 190) {
            echo "Ended";
            return;
        }
        else {
            echo "Loading Next Page " . $i;
            $this->recursiveUrlLoader($i + 1);
        }
    }

    function registerLinks(){
        $allLinks = ProfileLinks::where('is_processed', 0)->get();

        foreach ($allLinks as $link){
            try {
                $this->x++;

//                if ($this->x >= 7)
//                    return;

                $html = file_get_contents($link->link);
                $html = str_get_html($html);

                $img = $html->find('.b-advert-img__main-slider img');
                $name = $html->find('a.h-pointer')[0]->plaintext;
                $about = $html->find('.h-mb-20 .word-wrap-break')[0]->plaintext;
                $mobile = $html->find('.h-nowrap')[2]->plaintext;
                $gender = $html->find('.b-render-attr')[2]->find('.h-min-width-50p')[0]->plaintext;
                $age = $html->find('.b-render-attr')[4]->find('.h-min-width-50p')[0]->plaintext;

                $image_contents = file_get_contents($img[0]->src);
                $file_name = substr($img[0]->src, strrpos($img[0]->src, '/') + 1);
                $safeName = time() . "-" . $file_name;
                $destinationPath = storage_path() . "/finco-data/users/";
//                file_put_contents($destinationPath . $safeName, $image_contents);


                /*-------------------------------
                 |REGISTER THE USER
                 |-------------------------------*/
                $expert = new Service();
                $expert->role_id = 2;
                $expert->avatar = "users/default.jpg";
//                $expert->avatar = "/finco-data/users/" . $safeName;
                $expert->name = ltrim($name);
                $expert->mobile = preg_replace('/\s+/', '', $mobile);
                $expert->profession_id = $link->profession_id;
                $expert->gender = preg_replace('/\s+/', '', $gender);
                $expert->about = ltrim($about);
                $expert->save();


                /*-------------------------------
                |MARK ROW AS PROCESSED
                |-------------------------------*/
                $row_link = ProfileLinks::find($link->id);
                $row_link->is_processed = 1;
                $row_link->save();

                echo "Registered. " . $this->x;
                echo '<br/><br/>';
            }catch (\Exception $ex)
            {
                $row_link = ProfileLinks::find($link->id);
                $row_link->is_processed = 1;
                $row_link->save();

                echo "Error Processing data ". $ex->getMessage().'<br/>';
                echo "Line ". $ex->getLine().'<br/><br/>';
            }


        }
    }

    public function phone(){
        $experts = Expert::where('id', '>', 120)->get(['mobile']);
        $i = 0;

        foreach($experts as $expert)
        {
            echo $expert->mobile . ",<br/>";
        }
//
//        Expert::chunk(100, function ($experts) {
//            foreach($experts as $expert)
//            {
//                echo $expert->mobile . ",<br/>";
//            }
//        });
    }


}
