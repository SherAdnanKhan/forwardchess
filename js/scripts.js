(function ($) {
    'use strict';

    /*===================================================================================*/
    /*  WOW
    /*===================================================================================*/

    $(document).ready(function () {

            $("header").sticky({
                topSpacing: 0
            });

        $('#headerSearchForm').on('submit', function (event) {
            var searchValue = $(this).find('input[name="search"]').val();
            if (!searchValue) {
                event.preventDefault();
            }
        })
    });


    /*===================================================================================*/
    /*  SHOW PASSWORD
    /*===================================================================================*/


    $(document).ready(function () {

        $('.show-password').on('click', function () {
            let password = $('#password').attr("type");

            if (password === 'password') {
                $('#password').attr("type", "text");
            } else {
                $('#password').attr("type", "password");
            }
        });
    });

    /*===================================================================================*/
    /*  STAR RATING
    /*===================================================================================*/

    $(document).ready(function () {

        if ($('.star').length > 0) {
            $('.star').each(function () {
                var $star = $(this);

                if ($star.hasClass('big')) {
                    $star.raty({
                        starOff: 'images/star-big-off.png',
                        starOn : 'images/star-big-on.png',
                        space  : false,
                        score  : function () {
                            return $(this).attr('data-score');
                        }
                    });
                } else {
                    $star.raty({
                        starOff: 'images/star-off.png',
                        starOn : 'images/star-on.png',
                        space  : false,
                        score  : function () {
                            return $(this).attr('data-score');
                        }
                    });
                }
            });
        }
    });

    /*===================================================================================*/
    /*  SHARE THIS BUTTONS
    /*===================================================================================*/

    $(document).ready(function () {
        if ($('.social-row').length > 0) {
            stLight.options({publisher: '2512508a-5f0b-47c2-b42d-bde4413cb7d8', doNotHash: false, doNotCopy: false, hashAddressBar: false});
        }
    });

    /*===================================================================================*/
    /*  CUSTOM CONTROLS
    /*===================================================================================*/

    $(document).ready(function () {

        // Select Dropdown
        if ($('.le-select').length > 0) {
            $('.le-select select').customSelect({customClass: 'le-select-in'});
        }

        // Checkbox
        if ($('.le-checkbox').length > 0) {
            $('.le-checkbox').after('<i class="fake-box"></i>');
        }

        //Radio Button
        if ($('.le-radio').length > 0) {
            $('.le-radio').after('<i class="fake-box"></i>');
        }

        // Buttons
        $('.le-button.disabled').click(function (e) {
            e.preventDefault();
        });

        // Quantity Spinner
        $('.le-quantity a').click(function (e) {
            e.preventDefault();
            var currentQty = $(this).parent().parent().find('input').val();
            if ($(this).hasClass('minus') && currentQty > 0) {
                $(this).parent().parent().find('input').val(parseInt(currentQty, 10) - 1);
            } else {
                if ($(this).hasClass('plus')) {
                    $(this).parent().parent().find('input').val(parseInt(currentQty, 10) + 1);
                }
            }
        });

        // Price Slider
        if ($('.price-slider').length > 0) {
            var value = [1, 20];
            if ($('.price-slider').val()) {
                value = $('.price-slider').val().split(',');
            }

            $('.price-slider').slider({
                min   : 1,
                max   : 50,
                step  : 2,
                value : value,
                handle: 'square'

            });
        }

        $(document).ready(function () {
            $('select.styled').customSelect();
        });

        // Data Placeholder for custom controls

        $('[data-placeholder]').focus(function () {
            var input = $(this);
            if (input.val() == input.attr('data-placeholder')) {
                input.val('');

            }
        }).blur(function () {
            var input = $(this);
            if (input.val() === '' || input.val() == input.attr('data-placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('data-placeholder'));
            }
        }).blur();

        $('[data-placeholder]').parents('form').submit(function () {
            $(this).find('[data-placeholder]').each(function () {
                var input = $(this);
                if (input.val() == input.attr('data-placeholder')) {
                    input.val('');
                }
            });
        });

    });

    /*===================================================================================*/
    /*  LIGHTBOX ACTIVATOR
    /*===================================================================================*/
    $(document).ready(function () {
        if ($('a[data-rel="prettyphoto"]').length > 0) {
            //$('a[data-rel="prettyphoto"]').prettyPhoto();
        }
    });


    /*===================================================================================*/
    /*  SELECT TOP DROP MENU
    /*===================================================================================*/
    $(document).ready(function () {
        $('.top-drop-menu').change(function () {
            var loc = ($(this).find('option:selected').val());
            window.location = loc;
        });
    });

    /*===================================================================================*/
    /*  LAZY LOAD IMAGES USING ECHO
    /*===================================================================================*/
    $(document).ready(function () {
        echo.init({
            offset  : 100,
            throttle: 250,
            unload  : false
        });
    });

    /*===================================================================================*/
    /*  GMAP ACTIVATOR
    /*===================================================================================*/

    $(document).ready(function () {
        var zoom = 16;
        var latitude = 51.539075;
        var longitude = -0.152424;
        var mapIsNotActive = true;
        setupCustomMap();

        function setupCustomMap() {
            if ($('.map-holder').length > 0 && mapIsNotActive) {

                var styles = [
                    {
                        'featureType': 'landscape',
                        'elementType': 'geometry',
                        'stylers'    : [
                            {
                                'visibility': 'simplified'
                            },
                            {
                                'color': '#E6E6E6'
                            }
                        ]
                    }, {
                        'featureType': 'administrative',
                        'stylers'    : [
                            {
                                'visibility': 'simplified'
                            }
                        ]
                    }, {
                        'featureType': 'road',
                        'elementType': 'geometry',
                        'stylers'    : [
                            {
                                'visibility': 'on'
                            },
                            {
                                'saturation': -100
                            }
                        ]
                    }, {
                        'featureType': 'road.highway',
                        'elementType': 'geometry.fill',
                        'stylers'    : [
                            {
                                'color': '#808080'
                            },
                            {
                                'visibility': 'on'
                            }
                        ]
                    }, {
                        'featureType': 'water',
                        'stylers'    : [
                            {
                                'color': '#CECECE'
                            },
                            {
                                'visibility': 'on'
                            }
                        ]
                    }, {
                        'featureType': 'poi',
                        'stylers'    : [
                            {
                                'visibility': 'on'
                            }
                        ]
                    }, {
                        'featureType': 'poi',
                        'elementType': 'geometry',
                        'stylers'    : [
                            {
                                'color': '#E5E5E5'
                            },
                            {
                                'visibility': 'on'
                            }
                        ]
                    }, {
                        'featureType': 'road.local',
                        'elementType': 'geometry',
                        'stylers'    : [
                            {
                                'color': '#ffffff'
                            },
                            {
                                'visibility': 'on'
                            }
                        ]
                    }, {}
                ];

                var lt, ld;
                if ($('.map').hasClass('center')) {
                    lt = (latitude);
                    ld = (longitude);
                } else {
                    lt = (latitude + 0.0027);
                    ld = (longitude - 0.010);
                }

                var options = {
                    mapTypeControlOptions: {
                        mapTypeIds: ['Styled']
                    },
                    center               : new google.maps.LatLng(lt, ld),
                    zoom                 : zoom,
                    disableDefaultUI     : true,
                    scrollwheel          : false,
                    mapTypeId            : 'Styled'
                };
                var div = document.getElementById('map');

                var map = new google.maps.Map(div, options);

                var styledMapType = new google.maps.StyledMapType(styles, {
                    name: 'Styled'
                });

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(latitude, longitude),
                    map     : map
                });

                map.mapTypes.set('Styled', styledMapType);

                mapIsNotActive = false;
            }

        }
    });


    /*===================================================================================*/
    /*  Yamm Dropdown
    /*===================================================================================*/
    $(document).on('click', '.yamm .dropdown-menu', function (e) {
        e.stopPropagation();
    });

})(jQuery);



