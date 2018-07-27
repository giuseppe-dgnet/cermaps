<?php

namespace ES\CerMapBundle\Controller\Traits;

trait BaseCerMapController {

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Map 
     */
    public function getCerMap($lat = null, $lon = null, $settings = array()) {
        if(!$lat) {
            $lat = 41.87194;
        }
        if(!$lon) {
            $lon = 12.56738;
        }
        if(!isset($settings['map'])) {
            $settings['map'] = array();
        }
        if(!isset($settings['circle'])) {
            $settings['circle'] = array();
        }
        if(!isset($settings['marker'])) {
            $settings['marker'] = array();
        }
        $default = array(
            'map' => array(
                'center' => array($lat, $lon),
                'zoomControl' => array(
                    'enable' => true,
                    'position' => \Ivory\GoogleMap\Controls\ControlPosition::RIGHT_BOTTOM,
                    'auto' => false,
                    'scrollwheel' => true,
                    'zoom' => 7,
                    'style' => \Ivory\GoogleMap\Controls\ZoomControlStyle::DEFAULT_,
                ),
                'mapTypeControl' => array(
                    'enable' => true,
                    'style' => \Ivory\GoogleMap\Controls\MapTypeControlStyle::DROPDOWN_MENU,
                ), 
                'styles' => array(
                    array(
                        "stylers" => array(
                            array("lightness" => 35),
                        ),
                    ),
                    array(
                        "featureType" => "landscape",
                        "stylers" => array(
                            array("lightness" => 100),
                        ),
                    ), array(
                        "featureType" => "poi",
                        "stylers" => array(
                            array("lightness" => 48),
                        ),
                    )
                ),
            ),
            'marker' => array(
                'position' => array($lat, $lon),
                'clickable' => true,
                'draggable' => true,
                'flat' => false,
                /*'icon' => '/bundles/escermap/images/marker/marker.png',*/
                'title' => 'Sei quì',
                'zIndex' => 1000
                
            ),
            'circle' => array(
                'center' => array($lat, $lon),
                'clickable' => true,
                'strokeColor' => '#000',
                'strokeWeight' => 1,
                'strokeOpacity' => .4,
                'fillOpacity' => 0.25,
                'fillColor' => '#fff',
                'radius' => 100 * 1000,
            ),
        );

        $map_params = array_merge($default['map'], $settings['map']);
        $map = $this->getMap($map_params);

        $marker_params = array_merge($default['marker'], $settings['marker']);
        $marker = $this->getMarker($marker_params);

        $circle_params = array_merge($default['circle'], $settings['circle']);
        $circle = $this->getCircle($circle_params);

        $map->addMarker($marker);
        $map->addCircle($circle);

        return $map;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Map 
     */
    public function getSimpleMap($lat = null, $lon = null, $settings = array()) {
        if(!$lat) {
            $lat = 41.87194;
        }
        if(!$lon) {
            $lon = 12.56738;
        }
        $default = array(
            'center' => array($lat, $lon),
            'zoomControl' => array(
                'enable' => true,
                'position' => \Ivory\GoogleMap\Controls\ControlPosition::RIGHT_BOTTOM,
                'auto' => false,
                'scrollwheel' => true,
                'style' => \Ivory\GoogleMap\Controls\ZoomControlStyle::SMALL,
            ),
            'mapTypeControl' => array(
                'enable' => true,
                'style' => \Ivory\GoogleMap\Controls\MapTypeControlStyle::DROPDOWN_MENU,
            ), 
            'styles' => array(
                array(
                    "stylers" => array(
                        array("lightness" => 35),
                    ),
                ),
                array(
                    "featureType" => "landscape",
                    "stylers" => array(
                        array("lightness" => 100),
                    ),
                ), array(
                    "featureType" => "poi",
                    "stylers" => array(
                        array("lightness" => 48),
                    ),
                )
            ),
        );

        $map_params = array_merge($default, $settings);

        $map = $this->getMap($map_params);

        return $map;
    }

}