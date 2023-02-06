$(document).ready(function(){

    $('.init-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 600,
                settings: {
                    centerMode: true,
                    centerPadding: '25px',
                    slidesToShow: 1,
                }
            }
        ]
    });

    $('.sliders-init').slick({
       slidesToShow: 6,
       slidesToScroll: 1,
       arrows: true,
        prevArrow: '<div class="prev-arrow"><svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M10 14.1199L3.81916 7.99988L10 1.87988L8.09717 -0.00012207L0 7.99988L8.09717 15.9999L10 14.1199Z" fill="#F96F34"/>\n' +
            '</svg>\n</div>',
        nextArrow: '<div class="next-arrow"><svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M0 14.1199L6.18084 7.99988L0 1.87988L1.90283 -0.00012207L10 7.99988L1.90283 15.9999L0 14.1199Z" fill="#F96F34"/>\n' +
            '</svg>\n</div>',

        responsive: [
            {
                breakpoint:1150,
                settings: {
                    slidesToShow: 5,

                }
            },
            {
                breakpoint:992,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint:600,
                settings: {
                    centerMode: true,
                    centerPadding: '45px',
                    slidesToShow: 1,
                    arrows: false,
                }
            }
       ]
    });
});