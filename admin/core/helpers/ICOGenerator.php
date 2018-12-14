<?php

namespace core\helpers;

class ICOGenerator {

    /**
     * Necessary functions for working with pictures
     * @var array
     */
    private $_required_funcs = [
        'imagesx',
        'imagesy',
        'getimagesize',
        'imagesavealpha',
        'imagealphablending',
        'imagecopyresampled',
        'imagecreatetruecolor',
        'imagecolortransparent',
        'imagecreatefromstring',
        'imagecolorallocatealpha',
    ];

    /**
     * Images
     * @var array
     */
    private $_images = [];

    /**
     * ICOGenerator constructor.
     * @param bool $file Path to the source img file.
     * @param array $sizes Optional. An array of sizes
     * @throws \Exception
     */
    public function __construct( $file = false, $sizes = array() ) {

        foreach($this->_required_funcs as $nameFunc) {
            if ( ! function_exists( $nameFunc ) ) {
                throw new \Exception($nameFunc.' function not found. Since this function was not found, will be unable to create ICO files =(');
            }
        }
        if ($file !==  false){
            $this->addImage( $file, $sizes );
        }
    }

    /**
     * @param $file
     * @param $sizes
     * @return bool
     */
    public function addImage($file, $sizes)
    {
        if(($img = $this->_loadImgFile($file)) === false){
            return false;
        }
        if(empty( $sizes )){
            $sizes = [imagesx($img), imagesy($img)];
        }

        foreach ($sizes as $size) {
            list($width, $height) = $size;
            $newImg = imagecreatetruecolor($width,$height);
            imagecolortransparent($newImg, imagecolorallocatealpha($newImg, 0, 0, 0, 127 ));
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            $srcWidth = imagesx( $img );
            $srcHeight = imagesy( $img );
            if (imagecopyresampled( $newImg, $img, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight ) === false){
                continue;
            }

            $this->_addImgData($newImg);
        }
        return true;
    }

    /**
     * @param $file
     * @return bool
     */
    function saveICO($file) {
        if(($data = $this->_getIcoData()) === false){
            return false;
        }
        if(($fh = fopen( $file, 'w' )) === false){
            return false;
        }
        if(fwrite($fh, $data) === false){
            fclose($fh);
            return false;
        }
        fclose($fh);
        return true;
    }

    /**
     * @return bool|string
     */
    private function _getIcoData() {
        if(!is_array($this->_images) || empty($this->_images)){
            return false;
        }
        $data = pack( 'vvv', 0, 1, count($this->_images));
        $pixel_data = '';
        $icon_dir_entry_size = 16;
        $offset = 6 + ($icon_dir_entry_size * count($this->_images));
        foreach($this->_images as $image) {
            $data .= pack( 'CCCCvvVV', $image['width'], $image['height'], $image['color_palette_colors'], 0, 1, $image['bits_per_pixel'], $image['size'], $offset );
            $pixel_data .= $image['data'];
            $offset += $image['size'];
        }
        $data .= $pixel_data;
        unset( $pixel_data );
        return $data;
    }

    /**
     * @param $img
     */
    private function _addImgData($img) {
        $width = imagesx($img);
        $height = imagesy($img);
        $pixelData = [];
        $opacityData = [];
        $current_opacity_val = 0;
        for ($y = $height - 1; $y >= 0; $y--) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($img, $x, $y);
                $alpha = ($color & 0x7F000000) >> 24;
                $alpha = (1 - ( $alpha / 127 )) * 255;
                $color &= 0xFFFFFF;
                $color |= 0xFF000000 & ($alpha << 24);
                $pixelData[] = $color;
                $opacity = ($alpha <= 127) ? 1 : 0;
                $current_opacity_val = ($current_opacity_val << 1) | $opacity;
                if ((( $x + 1 ) % 32 ) == 0 ) {
                    $opacityData[] = $current_opacity_val;
                    $current_opacity_val = 0;
                }
            }
            if (($x % 32) > 0 ) {
                while (( $x++ % 32) > 0 ){
                    $current_opacity_val = $current_opacity_val << 1;
                }
                $opacityData[] = $current_opacity_val;
                $current_opacity_val = 0;
            }
        }
        $image_header_size = 40;
        $color_mask_size = $width * $height * 4;
        $opacity_mask_size = (ceil( $width / 32 ) * 4) * $height;
        $data = pack( 'VVVvvVVVVVV', 40, $width, ( $height * 2 ), 1, 32, 0, 0, 0, 0, 0, 0 );
        foreach ( $pixelData as $color ){
            $data .= pack( 'V', $color );
        }
        foreach ( $opacityData as $opacity ){
            $data .= pack( 'N', $opacity );
        }
        $image = array(
            'width'                => $width,
            'height'               => $height,
            'color_palette_colors' => 0,
            'bits_per_pixel'       => 32,
            'size'                 => $image_header_size + $color_mask_size + $opacity_mask_size,
            'data'                 => $data,
        );
        $this->_images[] = $image;
    }

    /**
     * @param $file
     * @return bool|resource
     */
    private function _loadImgFile($file)
    {
        if(getimagesize($file) === false){
            return false;
        }
        if(($fileData = file_get_contents($file)) === false){
            return false;
        }
        if(($img = imagecreatefromstring($fileData)) === false){
            return false;
        }
        unset($file_data);
        return $img;
    }
}