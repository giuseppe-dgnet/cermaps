<?php

namespace ES\FotoBundle\Services;

class gestioneImmagini {

    protected $options;

    /**
     * $option è formato da tutti gli armomenti che sono nel sevizio(services.yml dentro Resources) dentro 
     * services
     *  gestioneImmagini:
     *      class:
     *      arguments:
     * @param array $options
     */
    public function __construct($options) {
        $this->options = $options;
    }

    /**
     * mi restituisce tutte le varie cartelle che creo, tranne originals
     * @return array
     */
    public function getSizes() {
        return $this->options["sizes"];
    }

    

    public function handleFileUpload($file_name, $options = array()) {
        if (!isset($options['folder'])) {
            throw new \Exception("You must pass the 'folder' option to distinguish this set of files from others");
        }

        $options = array_merge($this->options, $options);


        $allowedExtensions = $options['allowed_extensions'];

        // Build a regular expression like /(\.gif|\.jpg|\.jpeg|\.png)$/i
        $allowedExtensionsRegex = '/(' . implode('|', array_map(function($extension) {
                                    return '\.' . $extension;
                                }, $allowedExtensions)) . ')$/i';

        $sizes = (isset($options['sizes']) && is_array($options['sizes'])) ? $options['sizes'] : array();

        $filePath = $options['file_base_path'] . '/' . $options['folder'];
        $webPath = $options['web_base_path'] . '/' . $options['folder'];

        foreach ($sizes as &$size) {
            $size['upload_dir'] = $filePath . '/' . $size['folder'] . '/';
            $size['upload_url'] = $webPath . '/' . $size['folder'] . '/';
        }

        $originals = $options['originals'];

        $uploadDir = $filePath . '/' . $originals['folder'] . '/';

        foreach ($sizes as &$size) {
            @mkdir($size['upload_dir'], 0777, true);
        }

        @mkdir($uploadDir, 0777, true);
        
        //se non passo coordinate, ovvero no Jcrop
        isset($options["coordinate"]) ? $options["coordinate"] = $options["coordinate"]:$options["coordinate"] = null;
        
        $opz = array(
            'upload_dir' => $uploadDir,
            'upload_url' => $webPath . '/' . $originals['folder'] . '/',
            'image_versions' => $sizes,
            'accept_file_types' => $allowedExtensionsRegex,
            'max_file_size' => $options["dimensione_massima_immagine"],
            'min_width' => $options["larghezza_minima_immagine"],
            'min_height' => $options["altezza_minima_immagine"],
            'coordinate' => $options["coordinate"],
        );

        //Copio il file dalla root web alla cartella originals e poi cancello il file
        copy($file_name, $opz["upload_dir"] . $file_name);
        unlink($file_name);

        //Faccio un ciclo su image_versions ovvero
        /**
         *  thumbnail:
          folder: thumbnails
          max_width: 80
          max_height: 80
          small:
          folder: small
          max_width: 320
          max_height: 480
          medium:
          folder: medium
          max_width: 640
          max_height: 960
          large:
          folder: large
          max_width: 1140
          max_height: 1140
         * 
         * 
         * Filename = nome del file jpg
         * 
         */
        foreach ($opz['image_versions'] as $versioni) {
            $this->create_scaled_image($file_name, $opz, $versioni);
        }
    }

    /**
     * DA AGGIUNGERE WATERMARK
     * 
     * ridimensiona e posiziona le immagini in diverse cartelle
     * 
     * 
     * file_name = nome del file.jpg
     * file_path = orginales(path)/nomefile.jpg
     * new_file_path = thumb,medium,large....(path)/nomefile.jpg
     * 
     * @param type $file_name string percorso dell'immagine
     * @param type $options array opzioni necessarie per la creazione del file
     * @param type $versioni array opzioni dentro le cartelle, ovvero le dimensioni delle immagini
     * @return boolean
     */
    protected function create_scaled_image($file_name, $options, $versioni) {
        //$file_path = $this->options['upload_dir'].$file_name;
        $file_path = $options["upload_dir"] . $file_name;
        //var_dump($file_name);
        $new_file_path = $versioni['upload_dir'] . $file_name;

        if (isset($options["coordinate"]) && $versioni['folder'] == 'profilo') {
            $new_width = $new_height = 200;
        } else {


            list($img_width, $img_height) = @getimagesize($file_path);
            if (!$img_width || !$img_height) {
                return false;
            }
            $scale = min(
                    $versioni['max_width'] / $img_width, $versioni['max_height'] / $img_height
            );
            if ($scale >= 1) {
                if ($file_path !== $new_file_path) {
                    return copy($file_path, $new_file_path);
                }
                return true;
            }

//        $new_width = $img_width * $scale;
//        $new_height = $img_height * $scale;
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
        }


        $new_img = @imagecreatetruecolor($new_width, $new_height);
        switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
            case 'jpg':
            case 'jpeg':
                $src_img = @imagecreatefromjpeg($file_path);
                $write_image = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                        $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                $src_img = @imagecreatefromgif($file_path);
                $write_image = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                @imagealphablending($new_img, false);
                @imagesavealpha($new_img, true);
                $src_img = @imagecreatefrompng($file_path);
                $write_image = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                        $options['png_quality'] : 9;
                break;
            default:
                $src_img = null;
        }


//        $success = $src_img && @imagecopyresampled(
//                        $new_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height
//                ) && $write_image($new_img, $new_file_path, $image_quality);
        $success = $src_img && @imagecopyresampled(
                        $new_img, $src_img, 0, 0, isset($options["coordinate"]["x"]) && $versioni['folder'] == 'profilo' ? $options["coordinate"]["x"] : 0, 
                isset($options["coordinate"]["y"]) && $versioni['folder'] == 'profilo' ? $options["coordinate"]["y"] : 0, 
                $new_width, $new_height, 
                isset($options["coordinate"]["w"]) && $versioni['folder'] == 'profilo' ? $options["coordinate"]["w"] : $img_width, 
                isset($options["coordinate"]["h"]) && $versioni['folder'] == 'profilo' ? $options["coordinate"]["h"] : $img_height
                ) && $write_image($new_img, $new_file_path, $image_quality);
        // Free up memory (imagedestroy does not delete files):
        @imagedestroy($src_img);
        @imagedestroy($new_img);
        return $success;
    }

}
