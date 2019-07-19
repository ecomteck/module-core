/**
 * Ecomteck
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Ecomteck.com license that is
 * available through the world-wide-web at this URL:
 * https://ecomteck.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Ecomteck
 * @package     Ecomteck_Core
 * @copyright   Copyright (c) 2018 Ecomteck (https://ecomteck.com/)
 * @license     https://ecomteck.com/LICENSE.txt
 */

var config = {
    paths: {
        'ecomteck/core/jquery/popup': 'Ecomteck_Core/js/jquery.magnific-popup.min',
        'ecomteck/core/owl.carousel': 'Ecomteck_Core/js/owl.carousel.min',
        'ecomteck/core/bootstrap': 'Ecomteck_Core/js/bootstrap.min',
        mpIonRangeSlider: 'Ecomteck_Core/js/ion.rangeSlider.min',
        touchPunch: 'Ecomteck_Core/js/jquery.ui.touch-punch.min',
        mpDevbridgeAutocomplete: 'Ecomteck_Core/js/jquery.autocomplete.min'
    },
    shim: {
        "ecomteck/core/jquery/popup": ["jquery"],
        "ecomteck/core/owl.carousel": ["jquery"],
        "ecomteck/core/bootstrap": ["jquery"],
        mpIonRangeSlider: ["jquery"],
        mpDevbridgeAutocomplete: ["jquery"],
        touchPunch: ['jquery', 'jquery/ui']
    }
};
