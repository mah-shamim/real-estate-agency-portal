<?php
namespace Strict;
/*
    Example to call: strict_image.php?d=100x200&f=413539183_cc8e400d9d_o.jpg
*/
/**
 * PHP class to resize and scale images
 */
class ImageResize
{
    const CROPTOP = 1;
    const CROPCENTRE = 2;
    const CROPCENTER = 2;
    const CROPBOTTOM = 3;
    const CROPLEFT = 4;
    const CROPRIGHT = 5;
    
    public $quality_jpg = 90;
    public $quality_png = 0;
    public $quality_webp = 75;
    
    public $back_red = 197;
    public $back_green = 197;
    public $back_blue = 197;

    public $source_type;

    protected $source_image;

    protected $original_w;
    protected $original_h;

    protected $dest_x = 0;
    protected $dest_y = 0;

    protected $source_x;
    protected $source_y;

    protected $dest_w;
    protected $dest_h;

    protected $source_w;
    protected $source_h;
    
    protected $want_w;
    protected $want_h;
    
    private $fixed_ratio = false;

    /**
     * Create instance from a strng
     *
     * @param string $imageData
     * @return ImageResize
     * @throws \exception
     */
    public static function createFromString($image_data)
    {
        $resize = new self('data://application/octet-stream;base64,' . base64_encode($image_data));
        return $resize;
    }

    /**
     * Loads image source and its properties to the instanciated object
     *
     * @param string $filename
     * @return ImageResize
     * @throws \Exception
     */
    public function __construct($filename)
    {
        $image_info = @getimagesize($filename);
        
        if(!$image_info && stripos($filename, '.webp')!==FALSE) {
            $image_webp = @imagecreatefromwebp($filename);
            if($image_webp) {
                $image_info = array();
                $image_info[] =  imagesx($image_webp);
                $image_info[] = imagesy($image_webp);
                $image_info[] = 'image/webp';
            }
        }
        
        if (!$image_info) {
            throw new \Exception('Could not read file');
        }

        list (
            $this->original_w,
            $this->original_h,
            $this->source_type
        ) = $image_info;

        switch ($this->source_type) {
            case IMAGETYPE_GIF:
                $this->source_image = imagecreatefromgif($filename);
                break;

            case IMAGETYPE_JPEG:
                $this->source_image = imagecreatefromjpeg($filename);
                break;

            case IMAGETYPE_PNG:
                $this->source_image = imagecreatefrompng($filename);
                break;
				
            case IMAGETYPE_WEBP:
                $this->source_image = imagecreatefromwebp($filename);
                break;
            
            default:
                throw new \Exception('Unsupported image type'.$this->source_type);
                break;
        }

        return $this->resize($this->getSourceWidth(), $this->getSourceHeight());
    }
    
    /**
     * Saves new image
     *
     * @param string $filename
     * @param string $image_type
     * @param integer $quality
     * @param integer $permissions
     * @return \static
     */
    public function save($filename, $image_type = null, $quality = null, $permissions = null)
    {
        $image_type = $image_type ?: $this->source_type;

        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());

        switch ($image_type) {
            case IMAGETYPE_GIF:
                $background = imagecolorallocatealpha($dest_image, 255, 255, 255, 1);
                imagecolortransparent($dest_image, $background);
                imagefill($dest_image, 0, 0 , $background);
                imagesavealpha($dest_image, true);
                break;

            case IMAGETYPE_JPEG:
                $background = imagecolorallocate($dest_image, 255, 255, 255);
                imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                break;

            case IMAGETYPE_PNG:
                imagealphablending($dest_image, false);
                imagesavealpha($dest_image, true);
                break;
 
            case IMAGETYPE_WEBP:
                imagealphablending($dest_image, false);
                imagesavealpha($dest_image, true);
                break;
        }

        imagecopyresampled(
            $dest_image,
            $this->source_image,
            $this->dest_x,
            $this->dest_y,
            $this->source_x,
            $this->source_y,
            $this->getDestWidth(),
            $this->getDestHeight(),
            $this->source_w,
            $this->source_h
        );
        
        if($this->want_w != null && $this->want_h != null)
        {
            $thumbnail_gd_image = $dest_image;
            $img_disp = imagecreatetruecolor($this->want_w, $this->want_h);
            $backcolor = imagecolorallocate($img_disp, $this->back_red, $this->back_green, $this->back_blue);
            imagefill($img_disp, 0, 0, $backcolor);
            imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp)/2)-(imagesx($thumbnail_gd_image)/2), (imagesy($img_disp)/2)-(imagesy($thumbnail_gd_image)/2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));
            $dest_image = $img_disp;
        }

        switch ($image_type) {
            case IMAGETYPE_GIF:
                imagegif($dest_image, $filename);
                break;

            case IMAGETYPE_JPEG:
                if ($quality === null) {
                    $quality = $this->quality_jpg;
                }

                imagejpeg($dest_image, $filename, $quality);
                break;

            case IMAGETYPE_PNG:
                if ($quality === null) {
                    $quality = $this->quality_png;
                }
				imagepng($dest_image, $filename, $quality);
                break;

            case IMAGETYPE_WEBP:
                if ($quality === null) {
                    $quality = $this->quality_webp;
                }
                imagewebp($dest_image, $filename, $quality);
                break;
        }

        if ($permissions) {
            chmod($filename, $permissions);
        }

        return $this;
    }

    /**
     * Convert the image to string
     *
     * @param int $image_type
     * @param int $quality
     * @return string
     */
    public function getImageAsString($image_type = null, $quality = null)
    {
        $string_temp = tempnam('', '');

        $this->save($string_temp, $image_type, $quality);

        $string = file_get_contents($string_temp);

        unlink($string_temp);

        return $string;
    }

    /**
    * Convert the image to string with the current settings
    *
    * @return string
    */
    public function __toString()
    {
        return $this->getImageAsString();
    }

    /**
     * Outputs image to browser
     * @param string $image_type
     * @param integer $quality
     */
    public function output($image_type = null, $quality = null)
    {
        $image_type = $image_type ?: $this->source_type;
        
        if(version_compare(phpversion(), '7.1', '<'))
        {
            header('Content-Type: image/webp');
        } else{
            header('Content-Type: ' . image_type_to_mime_type($image_type));
            
        }

        $this->save(null, $image_type, $quality);
    }
    
    /**
     * Resizes image according to the given height (width proportional)
     *
     * @param integer $height
     * @param boolean $allow_enlarge
     * @return \static
     */
    public function resizeToHeight($height, $allow_enlarge = false)
    {
        $ratio = $height / $this->getSourceHeight();
        $width = $this->getSourceWidth() * $ratio;

        $this->resize($width, $height, $allow_enlarge);

        return $this;
    }
    
    /**
     * Resizes image according to the given width (height proportional)
     *
     * @param integer $width
     * @param boolean $allow_enlarge
     * @return \static
     */
    public function resizeToWidth($width, $allow_enlarge = false)
    {
        $ratio  = $width / $this->getSourceWidth();
        $height = $this->getSourceHeight() * $ratio;

        $this->resize($width, $height, $allow_enlarge);

        return $this;
    }

    /**
     * Resizes image according to given scale (proportionally)
     *
     * @param type $scale
     * @return \Eventviva\ImageResize
     */
    public function scale($scale)
    {
        $width  = $this->getSourceWidth() * $scale / 100;
        $height = $this->getSourceHeight() * $scale / 100;

        $this->resize($width, $height, true);

        return $this;
    }

    /**
     * Resizes image according to the given width and height
     *
     * @param integer $width
     * @param integer $height
     * @param boolean $allow_enlarge
     * @return \static
     */
    public function resize($width, $height, $allow_enlarge = false, $fixed_ratio = false)
    {
        if (!$allow_enlarge) {
            // if the user hasn't explicitly allowed enlarging,
            // but either of the dimensions are larger then the original,
            // then just use original dimensions - this logic may need rethinking

            if ($width > $this->getSourceWidth() || $height > $this->getSourceHeight()) {
                $width  = $this->getSourceWidth();
                $height = $this->getSourceHeight();
            }
        }
        
        $this->fixed_ratio = $fixed_ratio;
        $this->source_w = $this->getSourceWidth();
        $this->source_h = $this->getSourceHeight();
        $this->source_x = 0;
        $this->source_y = 0;
        
        $source_ratio = $this->getSourceWidth()/$this->getSourceHeight();
        $new_ratio = $width/$height;
            
        if($fixed_ratio)
        {
            if($source_ratio >= $new_ratio)
            {
                $this->resizeToWidth($width, $allow_enlarge);
            }
            else if($source_ratio <= $new_ratio)
            {
                $this->resizeToHeight($height, $allow_enlarge);
            }
            
            /*
            Wrong in some situations
            
            if($width <= $height)
            {
                if($this->source_w >= $this->source_h)
                {
                    $this->resizeToWidth($width, $allow_enlarge);
                }
                else
                {
                    $this->resizeToHeight($height, $allow_enlarge);
                }
            }
            else if($width >= $height)
            {
                if($this->source_w <= $this->source_h)
                {
                    $this->resizeToWidth($width, $allow_enlarge);
                }
                else
                {
                    $this->resizeToWidth($width, $allow_enlarge);
                }
            }
            */
            
            $this->want_w = $width;
            $this->want_h = $height;
        }
        else
        {
            $this->dest_w = $width;
            $this->dest_h = $height;
        }

        return $this;
    }
    
    /**
     * Crops image according to the given width, height and crop position
     *
     * @param integer $width
     * @param integer $height
     * @param boolean $allow_enlarge
     * @param integer $position
     * @return \static
     */
    public function crop($width, $height, $allow_enlarge = false, $position = self::CROPCENTER)
    {
        if (!$allow_enlarge) {
            // this logic is slightly different to resize(),
            // it will only reset dimensions to the original
            // if that particular dimenstion is larger

            if ($width > $this->getSourceWidth()) {
                $width  = $this->getSourceWidth();
            }

            if ($height > $this->getSourceHeight()) {
                $height = $this->getSourceHeight();
            }
        }
        
        $ratio_source = $this->getSourceWidth() / $this->getSourceHeight();
        $ratio_dest = $width / $height;
        
        if ($ratio_dest < $ratio_source) {
            $this->resizeToHeight($height, $allow_enlarge);

            $excess_width = ($this->getDestWidth() - $width) / $this->getDestWidth() * $this->getSourceWidth();

            $this->source_w = $this->getSourceWidth() - $excess_width;
            $this->source_x = $this->getCropPosition($excess_width, $position);

            $this->dest_w = $width;
        } else {
            $this->resizeToWidth($width, $allow_enlarge);

            $excess_height = ($this->getDestHeight() - $height) / $this->getDestHeight() * $this->getSourceHeight();

            $this->source_h = $this->getSourceHeight() - $excess_height;
            $this->source_y = $this->getCropPosition($excess_height, $position);

            $this->dest_h = $height;
        }

        return $this;
    }
    
    /**
     * Gets source width
     *
     * @return integer
     */
    public function getSourceWidth()
    {
        return $this->original_w;
    }
    
    /**
     * Gets source height
     *
     * @return integer
     */
    public function getSourceHeight()
    {
        return $this->original_h;
    }

    /**
     * Gets width of the destination image
     *
     * @return integer
     */
    public function getDestWidth()
    {
        return $this->dest_w;
    }

    /**
     * Gets height of the destination image
     * @return integer
     */
    public function getDestHeight()
    {
        return $this->dest_h;
    }
    
    /**
     * Gets crop position (X or Y) according to the given position
     *
     * @param integer $expectedSize
     * @param integer $position
     * @return integer
     */
    protected function getCropPosition($expectedSize, $position = self::CROPCENTER)
    {
        $size = 0;
        switch ($position) {
            case self::CROPBOTTOM:
            case self::CROPRIGHT:
                $size = $expectedSize;
                break;
            case self::CROPCENTER:
            case self::CROPCENTRE:
                $size = $expectedSize / 2;
                break;
        }
        return $size;
    }
}


$width = null;
$height = null;
$filename = null;

if (!defined('IMAGETYPE_WEBP')) {
    define('IMAGETYPE_WEBP', 'image/webp');
}

if(isset($_GET['d']))
{
    $dim = explode('x', $_GET['d']);
    if(count($dim) >= 2)
    {
        $width = $dim[0];
        $height = $dim[1];
    }
}

if($width < 800)
{
    $_GET['cut']=true; // add this like to cut image instead of add background   
}

if(isset($_GET['f']))
{
    $filename = $_GET['f'];
}

if(empty($width) || empty($height) || empty($filename) || !file_exists('files/'.$filename))
{
    output_error_image();
}

if(!is_dir('files/strict_cache'))
    mkdir('files/strict_cache');

if(file_exists('files/strict_cache/'.$width.'x'.$height.$filename))
{
    $image_info = @getimagesize('files/strict_cache/'.$width.'x'.$height.$filename);

    if (!$image_info) {
        unlink('files/strict_cache/'.$width.'x'.$height.$filename);
        output_error_image();
    }

    header('Content-Type: '.$image_info['mime']);
    readfile('files/strict_cache/'.$width.'x'.$height.$filename);
}
else
{
    $image = new ImageResize('files/'.$filename);
    
    if(isset($_GET['cut']))
    {
        //$image->resize($width, $height, true, false);
        $image->crop($width, $height, true);
    }
    else
    {
        $image->resize($width, $height, true, true);
    }

    $image->output();
    $image->save('files/strict_cache/'.$width.'x'.$height.$filename);
}

function output_error_image()
{
    $img = imagecreatetruecolor(500, 500);
    $bg = imagecolorallocate ( $img, 255, 255, 255 );
    imagefilledrectangle($img,0,0,500,500,$bg);
    $color = imagecolorallocate($img, 0, 0, 0);
    $text = "Error in image";
    imagettftext($img, 12, 0, 190, 20, $color, 'admin-assets/font/verdana.ttf', $text);

    
    header('Content-Type: image/jpg');
    imagejpeg($img, null, 80);
    exit();
}


?>
