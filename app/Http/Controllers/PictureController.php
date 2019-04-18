<?php

namespace Colorize\Http\Controllers;

use Colorize\Color;
use Illuminate\Support\Facades\Storage;
use Colorize\Picture;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PictureController extends Controller
{
    public function get(){
        return Picture::with(['user', 'color'])->get();
    }

    public function create(Request $request){
        $request->validate([
            'color' => 'required',
            'user' => 'required',
            'photo' => 'required'
        ]);

        $color = Color::where('color_hex', $request->color)->first();

        $picture = new Picture([
            'color' => $color->id,
            'user' => $request->user,
            'photo' => 'placeholder'
        ]);

        $picture->save();

        $image = $request->photo;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        Storage::put('/public/clients/'. $request->user .'/' . $picture->id . '.png', base64_decode($image));

        $picture->update([
            'photo' => '/storage/clients/' . $request->user . '/' . $picture->id . '.png'
        ]);

        return response()->json([
            'message' => 'Picture added!'
        ]);
    }

    public function find($id){
        return response()->json(Picture::where('id', '=', $id)->get());
    }

    public function delete($id){
        $user = Picture::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Client not found!'
            ], 404);
        }

        $user->delete();

        return response()->json(
            ['message'=>'Client deleted']
        );
    }

    public function crop($picture)
    {

        $img = Image::make($picture);
//        $img->encode('png');

        $mask = Image::canvas(640, 480);

        //face
        $mask->ellipse(160, 220, 320, 220, function ($draw) {
            $draw->background('#fff');
        });

//        //bottom
//        $mask->rectangle(0, 318, 640, 480, function ($draw) {
//            $draw->background('#fff');
//        });

        $img->mask($mask, false);
//        $img->blur();
        $img->save('storage/tmp/image578nb.png');
    }


    public function isColorInRange($range, $color, $upperThreshold, $lowerThreshold)
    {

        for ($i = 0; $i < sizeof($range); $i++) {
            if (($color[0] < $range[$i][0] + $upperThreshold and
                    $color[0] > $range[$i][0] - $lowerThreshold) and
                ($color[1] < $range[$i][1] + $upperThreshold and
                    $color[1] > $range[$i][1] - $lowerThreshold) and
                ($color[2] < $range[$i][2] + $upperThreshold and
                    $color[2] > $range[$i][2] - $lowerThreshold)) {
                return true;
            }
        }
        return false;
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
    public function pictureOperation(Request $request){
        $rgba[0]=$request->red;
        $rgba[1]=$request->green;
        $rgba[2]=$request->blue;
        $rgba[3]=$request->alpha;

        $img = Image::make($request->picture);
        $img->encode('png');

//        $img->brightness(10);

        $this::crop($img);
        $img_cropped= Image::make('storage/tmp/image578nb.png');
        $img = Image::make($request->picture);

        $averagecolor = [0,0,0];

        $colorTones = [];
        $tonesCount = 0;

        $totalPixelCount = 0;
        $pixelSampleSize = 2000;
        $toneSampleSize=500;
        $upperThreshold = 15;
        $lowerThreshold=15;
        $booleanBreak = false;

        for ($y = 25; $y < $img->height(); $y++) {
            for ($x = 170; $x < 500; $x++) {
                $currentPixel = $img->pickColor($x, $y);
                if (($currentPixel[0] < 150 or $currentPixel[1] < 150 or $currentPixel[2] < 150) and
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

        for ($y = 0; $y < $img->height(); $y++) {
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
        $img->save('storage/tmp/image578nb.png');

        $img->insert($img_cropped);
        $img->save('storage/tmp/image578nb.png');

        $img= Image::make('storage/tmp/image578nb.png');
        $img->encode('png');

        return response()->json('data:image/png;base64,' . base64_encode($img), 201);
    }

    public function search(Request $request){
        $word = $request->word;

        return response()->json(
            Picture::with(['user', 'color'])
                ->whereHas('user',
                    function ($q) use($word){
                        $q->where('name', 'like', '%' . $word . '%');
                })
        ->get());
    }
}
