(function ($) {
    'use strict';
    const $document = $(document),
        $body = $('body'),
        $head = $('head'),
        $pre_loader = $('#theme-preload'),
        $back_to_top_btn = $('.back-to-top'),
        $wrap_site_content = $('#page');

    const check_header_sticky = function () {
        const vertical_offset = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        if (vertical_offset > header_middle_offset_top) {
            $body.addClass('--header-sticky');
        } else {
            $body.removeClass('--header-sticky');
        }
        if (vertical_offset > 0) {
            $body.addClass('header-sticky--mobile');
        } else {
            $body.removeClass('header-sticky--mobile');
        }
    }

    $.fn._equal_height_elements = function () {
        let height = 0;
        $(this).find('.equal-height-item').height('auto').each(function () {
            height = $(this).height() > height ? $(this).height() : height;
        }).height(height);
        return $(this);
    }

    $.fn.theme_set_height_iframe = function () {
        $(this).each(function () {
            $(this).height(($(this).width() / 2));
        });
    }

    $.fn._init_lazy_load = function () {
        $(this).each(function () {
            const $this = $(this),
                $config = [];

            $config.beforeLoad = function (element) {
                if (element.is('div') === true) {
                    element.addClass('loading-lazy');
                } else {
                    element.parent().addClass('loading-lazy');
                }
            };
            $config.afterLoad = function (element) {
                if (element.is('div') === true) {
                    element.removeClass('loading-lazy');
                } else {
                    element.parent().removeClass('loading-lazy');
                }
            };
            $config.onFinishedAll = function () {
                if (!this.config('autoDestroy'))
                    this.destroy();
            }
            $config.effect = "fadeIn";
            $config.enableThrottle = true;
            $config.throttle = 250;
            $config.effectTime = 600;
            if ($this.closest('.ovic-menu-clone-wrap').find('.ovic-menu-panel').length) {
                $config.appendScroll = $this.closest('.ovic-menu-clone-wrap').find('.ovic-menu-panel');
            }
            $this.lazy($config);
        });
    };

    $.fn.init_slide = function () {
        $(this).each(function () {
            const data_configs = $(this).data('configs') ? $(this).data('configs') : {};
            if (typeof data_configs === 'object') {
                data_configs.on = {
                    dragStart: () => (document.ontouchmove = () => false),
                    dragEnd: () => (document.ontouchmove = () => true)
                }
            }
            $(this).addClass('ovic-slide-init')._equal_height_elements().flickity(data_configs);
        });
        $(window).on('resize', function () {
            $(this).each(function () {
                $(this)._equal_height_elements();
            });
        });
    }

    $.fn.review_twenty_years = function () {
        const parseVimeoIdFromUrl = (url) => {
            const regex = /http:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;
            return url.match(regex) ? RegExp.$2 : url;
        }
        const parseYoutubeIdFromUrl = (url) => {
            const regex = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            return url.match(regex) ? RegExp.$2 : url;
        }
        const createSlide = function (gallery, placeholder, gallery_id) {
            let html = '<div class="ovic-review-twenty-years__carousel">';
            gallery.forEach((slide, index) => {
                const caption = slide.caption ? '<figcaption>' + slide.caption + '</figcaption>' : '';
                if (slide.type === 'image') {
                    // html += '<div class="ovic-review-twenty-years__carousel-cell"><figure><img src="' + slide.url + '" alt="" width="671" height="475">' + caption + '</figure></div>';
                    html += '<a class="ovic-review-twenty-years__carousel-cell" href="#" data-item="' + index + '" data-gallery-id="' + gallery_id + '"><figure><img src="' + placeholder + '"  data-flickity-lazyload="' + slide.url + '" alt="" width="631" height="432">' + caption + '</figure></a>';
                } else {
                    let video_id = slide['url'];
                    const _plyr_options = {
                        type: 'video',
                        ratio: '626:400'
                    }
                    if (slide.provider === 'external') {
                        html += '<div class="ovic-review-twenty-years__carousel-cell" data-item="' + index + '" data-gallery-id="' + gallery_id + '"><video class="js_plyr_video" data-plyr-config=\'' + JSON.stringify(_plyr_options) + '\' data-plyr-provider="' + slide.provider + '" src="' + slide['url'] + '" data-poster="' + slide.poster + '" ></video></div>';
                    } else {
                        video_id = slide.provider === 'youtube' ? parseYoutubeIdFromUrl(slide['url']) : parseVimeoIdFromUrl(slide['url']);
                        if (slide.provider === 'youtube') {
                            _plyr_options['youtube'] = {
                                noCookie: false,
                                rel: 0,
                                showinfo: 0,
                                iv_load_policy: 3,
                                modestbranding: 1
                            };
                        } else {
                            _plyr_options['vimeo'] = {
                                byline: false,
                                portrait: false,
                                title: false,
                                speed: true,
                                transparent: false
                            };
                        }
                        if (video_id) {
                            html += '<div class="ovic-review-twenty-years__carousel-cell" data-item="' + index + '" data-gallery-id="' + gallery_id + '"><div class="js_plyr_video" data-plyr-config=\'' + JSON.stringify(_plyr_options) + '\' data-plyr-provider="' + slide.provider + '" data-plyr-embed-id="' + video_id + '" data-poster="' + slide.poster + '" ></div></div>';
                        }
                    }
                }
            });
            html += '</div>';
            return html;
        }
        $(this).each(function () {
            const configs = $(this).data('configs');
            const store = $(this).data('store');
            const activatedGallery = store['galleries'][store['active']];
            if (configs && typeof configs === 'object') {
                configs['on'] = {
                    ready: () => $body.trigger('__ovic_review_twenty_years_slide_ready')
                }
            }
            if (Array.isArray(activatedGallery) && activatedGallery.length) {
                $(this).html('').append(createSlide(activatedGallery, store['placeholder'], store['active']));
                $('.ovic-review-twenty-years__carousel').flickity(configs)
            }
        });
        if ($(this).length) {
            $document.on('click', '.ovic-review-twenty-years__btn-nav:not(.--element-activated)', function (event) {
                event.preventDefault();
                const $parent = $(this).closest('.ovic-review-twenty-years');
                const element = $(this).data('element');
                const $slide_control = $parent.find('.ovic-review-twenty-years__media-control');
                if ($parent.length && element) {
                    $parent.find('.--element-activated').removeClass('--element-activated');
                    $parent.find(`[data-element=${element}]`).addClass('--element-activated');
                }
                if ($slide_control.length && $slide_control.data('store')) {
                    const configs = $slide_control.data('configs');
                    const store = $slide_control.data('store');
                    if (configs && typeof configs === 'object') {
                        configs.on = {
                            ready: () => $body.trigger('__ovic_review_twenty_years_slide_ready'),
                            change: () => $body.trigger('__ovic_review_twenty_years_slide_change'),
                        }
                    }
                    const activatedGallery = store['galleries'][element];
                    if (Array.isArray(activatedGallery) && activatedGallery.length) {
                        $slide_control.html('').append(createSlide(activatedGallery, store['placeholder'], element));
                        $('.ovic-review-twenty-years__carousel').flickity(configs)
                    }
                }
            });
        }
        $document.on('click', 'a.ovic-review-twenty-years__carousel-cell.is-selected', function (e) {
            e.preventDefault();
            let $this = $(this),
                $parent = $this.closest('.ovic-review-twenty-years__media-control'),
                gallery_id = $this.data('gallery-id'),
                data = $parent.length ? $parent.data('store') : null,
                startIndex = $this.hasClass('is-selected') ? $this.data('item') : $this.siblings('.is-selected').data('item'),
                infinite = false;
            if (data && gallery_id && data.galleries && data.galleries[gallery_id]) {
                const options = data.galleries[gallery_id].map(img => {
                    return img.type === 'video' ? {
                        src: img.url,
                        type: img.type,
                        caption: img.caption,
                        thumb: img.poster
                    } : {src: img.url, type: img.type, caption: img.caption};
                });
                Fancybox.show(options, {infinite, startIndex});
            }
        });
    }

    $.fn.check_element_on_viewport = function () {
        let wh = $(window).height();
    }

    $.fn.show_on_scroll_to = function () {
        if ($(this).length && $(document).width() <= 1199) {
            let wh = $(window).height();
            let $elements = $(this);
            $elements.each(function () {
                let $this = $(this);
                $(window).on('scroll', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const offsetTop = $this.offset().top,
                        eh = $this.height(),
                        _topOffset = (offsetTop - ((wh / 2) - (eh / 4))),
                        documentOffset = $(document).scrollTop();
                    if (documentOffset >= _topOffset) {
                        if (documentOffset < _topOffset + eh) {
                            $this.addClass('--activated');
                        } else {
                            $this.removeClass('--activated');
                        }
                    } else {
                        $this.removeClass('--activated');
                    }
                });
            });
            $(window).on('resize', function () {
                wh = $(window).height();
            });
        }
    }

    $body.on('__ovic_review_twenty_years_slide_ready', (event) => {
        const player = new Plyr('[data-plyr-provider]');
        $body.on('__ovic_review_twenty_years_slide_change', (event) => {
            player.pause();
        });
    });

    $('[data-showOn="scrollTo"]').show_on_scroll_to();

    $('.lazy')._init_lazy_load();

    /**************************************
     * header sticky
     * ***********************************/
    let header_middle_offset_top = 0;

    $document.ready(function ($) {
        if (!$body.hasClass('elementor-editor-active')) {
            setTimeout(() => $('.js_block_carousel').init_slide({option: 'value'}), 1000);
        }
        header_middle_offset_top = $('.header-top').innerHeight()
        check_header_sticky();
    });

    /**************************************
     * ovic review twenty years
     * ***********************************/
    $('.ovic-review-twenty-years__media-control').review_twenty_years();

    /**************************************
     * sidebar control on mobile
     * ***********************************/
    if ($('aside#secondary').length) {
        $document.on('click', '.sidebar-foot-section__button, .js_footer_mobile_button_toggle_sidebar', function (event) {
            event.preventDefault();
            $('body').addClass('sidebar-opened active-overlay');
        });
    } else {
        $('.js_footer_mobile_button_toggle_sidebar').remove();
    }

    /**************************************
     * Back to top
     * ***********************************/
    $(window).on('scroll', function () {
        check_header_sticky();
        if ($(window).scrollTop() > 400) {
            $('.backtotop').addClass('show');
        } else {
            $('.backtotop').removeClass('show');
        }
    });

    $(document).on('click', '.js_toggle_desktop-search', function (e) {
        e.preventDefault();
        $body.toggleClass('--search-header-opened');
    });

    $(document).on('click', '.backtotop, .action-to-top', function (e) {
        $('html, body').animate({scrollTop: 0}, 800);
        e.preventDefault();
    });

    $document.on('click', '.sidebar-head-section__button', function (event) {
        event.preventDefault();
        $('body').removeClass('sidebar-opened active-overlay');
    })

    $document.on('click', '.overlay-body', function (event) {
        event.preventDefault();
        $('body').removeClass('sidebar-opened active-overlay ovic-open-mobile-menu ovic-open-mobile-options show-main-menu show-mobile-search');
    });

    $document.on('click', '.js_open_mobile_search', function (event) {
        event.preventDefault();
        $('body').addClass('active-overlay show-mobile-search');
    });

    $document.on('click', '.header-search__button', function (event) {
        // event.preventDefault();
        $(this).closest('form').submit();
    })
    $('.widget-area-in-document-shortcode').on('click', '.elm-button-control__btn-toggle', function (event) {
        event.preventDefault();
        const $parent = $(this).closest('.ovic-document-list');
        if ($parent.hasClass('--child-expanded')) {
            $parent.removeClass('--child-expanded').find('.--elm-invisible').slideUp(300);
        } else {
            $parent.addClass('--child-expanded').find('.--elm-invisible').slideDown(300);
        }
    })

    $(window).on('resize', function () {
        // $( '.iframe-video' ).theme_set_height_iframe();
        header_middle_offset_top = $('.header-top').innerHeight()
        check_header_sticky();
    });

    $(document).ajaxComplete(function () {
        $('.js_block_carousel:not(.flickity-enabled)').init_slide();
    });

    //
    // Elementor scripts
    //
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope, $) {
            $scope.find('.lazy')._init_lazy_load();
            $scope.find('.js_block_carousel.flickity-enabled').flickity('destroy')
            // setTimeout( () => $scope.find( '.js_block_carousel' ).init_slide(), 50 )
            // console.log( 'asdas' );
            // $scope.find( '.owl-slick' ).biolife_init_carousel();
            // $scope.find( '.elementor-section-slide' ).biolife_init_carousel();
            // $scope.find( '.equal-container.better-height' ).biolife_better_equal_elems();
        });
    });
    
    window.addEventListener("load", function load() {
        /**
         * remove listener, no longer needed
         * */
        window.removeEventListener("load", load, false);

        const $popup = $('#ictu-popup-newsletter');
        if ($popup.length && $.fn.magnificPopup) {
            const effect = $popup.data('effect');
            const delay = $popup.data('delay');
            setTimeout(function () {
                $.magnificPopup.open({
                    items: {src: '#ictu-popup-newsletter'},
                    type: 'inline',
                    removalDelay: 600,
                    callbacks: {
                        beforeOpen: function () {
                            this.st.mainClass = effect;
                        }
                    },
                    midClick: true,
                    closeOnBgClick: false
                });
            }, delay);
        }
    });
})
(window.jQuery);