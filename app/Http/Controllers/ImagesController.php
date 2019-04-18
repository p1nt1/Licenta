<?php

namespace Colorize\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Threaded;

//class Task extends Threaded{
//
//}

class ImagesController extends Controller
{

    public function save(Request $request)
    {

        $image = $request->string;  // your base64 encoded
//        $image = str_replace('data:image/png;base64,', '', $image);
//        $image = str_replace(' ', '+', $image);
//
//        Storage::put('public/clients/' . $request->client . '/original.png', base64_decode($image));
//        Storage::put('public/clients/' . $request->client . '/modified.png', base64_decode($image));
        $image = Image::make($image);

        $image->save('storage/clients/' . $request->client . '/modified.png');
        $image = Image::make('storage/clients/' . $request->client . '/modified.png');

        $image->save();
    }

    public function color(Request $request)
    {

        $img = Image::make('storage/clients/' . $request->client . '/modified.png');

        if ($request->greyscale) {
            $img->greyscale();
        }

        $this::crop($request);

        $img->brightness($request->gamma);

        $img->colorize($request->red, $request->green, $request->blue);

        $img->save();


        $img_original = Image::make('storage/clients/' . $request->client . '/modified.png');
        $img_cropped = Image::make('storage/clients/' . $request->client . '/modified2.png');
        $img_original->insert($img_cropped);
        $img_original->save();
        return response()->json([
            'message' => 'good job'
        ], 201);

    }

    /**
     * Color The picture stored in a folder  clientname/original.png (Activate)
     *
     * @param picture base64 picture
     * @param  client client's name
     * @param  red red value
     * @param  green green value
     * @param  blue blue value
     * @param  alpha alpha value
     * @return code success and picture stored in clientname/modified.png
     */
    public function pixelColor(Request $request)
    {
        $rgba[0]=$request->red;
        $rgba[1]=$request->green;
        $rgba[2]=$request->blue;
        $rgba[3]=$request->alpha;

        $time1=microtime(true);

//        return response()->json($request->picture, 201);

        $img = Image::make($request->picture);
        $img->save('storage/clients/' . $request->client . '/original.png');
        $img->save('storage/clients/' . $request->client . '/modified.png');

        $this::crop($request);

        $averagecolor = [0,0,0];

        $colorTones = [];
        $tonesCount = 0;

        $totalPixelCount = 0;
        $pixelSampleSize = 2000;
        $toneSampleSize=500;
        $upperThreshold = 55;
        $lowerThreshold=55;
        $booleanBreak = false;

        $time2=microtime(true);
        $times[0]=$time2-$time1;

        for ($y = 25; $y < $img->height(); $y++) {
            for ($x = 170; $x < 500; $x++) {
                $currentPixel = $img->pickColor($x, $y);
                if (($currentPixel[0] < 190 or $currentPixel[1] < 190 or $currentPixel[2] < 190) and
                    $currentPixel[3] > 0) {
                    $averagecolor[0] += $currentPixel[0];
                    $averagecolor[1] += $currentPixel[1];
                    $averagecolor[2] += $currentPixel[2];
                    $totalPixelCount++;
                    if ($totalPixelCount % 500 == 0) {
                        $averagecolor[0] = $averagecolor[0] / $toneSampleSize;
                        $averagecolor[1] = $averagecolor[1] / $toneSampleSize;
                        $averagecolor[2] = $averagecolor[2] / $toneSampleSize;
                        $colorTones[$tonesCount] = $averagecolor;
                        $tonesCount++;
                    }
                }
                if ($totalPixelCount == $pixelSampleSize) {
                    $booleanBreak = true;
                    break;
                }
            }
            if ($booleanBreak) {
                break;
            }
        }
        $averagecolor[0] = $averagecolor[0] / $totalPixelCount;
        $averagecolor[1] = $averagecolor[1] / $totalPixelCount;
        $averagecolor[2] = $averagecolor[2] / $totalPixelCount;

        $time3=microtime(true);
        $times[1]=$time3-$time2;

        for ($y = 0; $y < $img->height() / 1.7; $y++) {
            for ($x = 100; $x < $img->width()-100; $x++) {

                $currentPixel = $img->pickColor($x, $y);


                if ($this::isColorInRange($colorTones, $currentPixel, $upperThreshold, $lowerThreshold)) {

                    $currentPixelColorRgb=($currentPixel[0]+$currentPixel[1]+$currentPixel[2])/3;

                    $currentPixelColorRgbArray[0]=($currentPixelColorRgb + $rgba[0])/2;
                    $currentPixelColorRgbArray[1]=($currentPixelColorRgb + $rgba[1])/2;
                    $currentPixelColorRgbArray[2]=($currentPixelColorRgb + $rgba[2])/2;

                    $currentPixelColorRgbArray[3]=$rgba[3];

                    $img->pixel($currentPixelColorRgbArray, $x, $y);
                }
            }
        }
//        $img->save();

//        $img_original = Image::make('storage/clients/' . $request->client . '/modified.png');
        $img_cropped = Image::make('storage/clients/' . $request->client . '/modified2.png');
        $img->insert($img_cropped);
        $img->blur();
        $img->save('storage/clients/' . $request->client . '/modified.png');

        return response()->json('data:image/png;base64,' . base64_encode($img), 201);
    }
}
