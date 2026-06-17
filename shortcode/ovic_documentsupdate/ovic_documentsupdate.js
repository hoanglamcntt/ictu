(function ($) {
    'use strict';
    // $('#ovic_document_list_v2 li.cat-item>a').each(function () {
    //     const _number = $(this).parent().contents().get(1).nodeValue ? $(this).parent().contents().get(1).nodeValue.trim() : '';
    //     if (_number) {
    //         $(this).append('<span class="--count-index">' + _number + '</span>');
    //     }
    //     if ($(this).siblings('.children').length) {
    //         $(this).parent().addClass('--has-children').prepend('<button class="btn btn--toggle-menu"><i class="fa fa-angle-down" aria-hidden="true"></i></button>');
    //     }
    // });

    // $(document).on('click', '.--has-children>.btn--toggle-menu', function (event) {
    //     event.preventDefault();
    //     const parent$ = $(this).parent();
    //     const childMenu$ = $(this).siblings('.children');
    //     if (parent$.hasClass('current-cat-ancestor') && !parent$.hasClass('--open-child')) {
    //         childMenu$.slideDown(300);
    //     }
    //     if (parent$.hasClass('current-cat-ancestor') && !parent$.hasClass('--changed')) {
    //         parent$.removeClass('--open-child');
    //         childMenu$.slideUp(300);
    //     } else {
    //         if (parent$.hasClass('--open-child')) {
    //             parent$.removeClass('--open-child');
    //             childMenu$.slideUp(300);
    //         } else {
    //             parent$.addClass('--open-child');
    //             childMenu$.slideDown(300);
    //         }
    //     }
    //     parent$.addClass('--changed');
    // });
})(window.jQuery);