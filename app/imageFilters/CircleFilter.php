<?php

namespace App\imageFilters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Facades\Image as ImageFacade;
use Intervention\Image\Gd\Color;
use Intervention\Image\Image;

class CircleFilter implements FilterInterface
{
    private $imagine;

    public function __construct(Image $imagine,
                                BoxInterface $size)
    {
        $this->imagine = $imagine;
        $this->size = $size;
    }

    public function applyFilter(Image $image)
    {
        // create a thumbnail
        $thumbnail = $image->thumbnail(
            $this->size,
            ImageFacade::THUMBNAIL_OUTBOUND
        );

        // create a new image to hold our mask
        // make the background white
        $mask = $this->imagine->create($this->size, new Color('fff'));

        // draw a black circle at the center of our new image
        // use $this->size to make it full width and height
        $mask->draw()
            ->ellipse(
                new Imagine\Image\Point\Center($this->size),
                $this->size,
                new Imagine\Image\Color('000', 0),
                true
            );

        // apply the mask to the thumbnail and return it
        return $thumbnail->applyMask($mask);
    }
}