export default function theme() {
// Panels
    (function ($) {

        $(function () {
            $('.panel')
                .on('panel:toggle', function () {
                    var $this,
                        direction;

                    $this     = $(this);
                    direction = $this.hasClass('panel-collapsed') ? 'Down' : 'Up';

                    $this.find('.panel-body, .panel-footer')['slide' + direction](200, function () {
                        $this[(direction === 'Up' ? 'add' : 'remove') + 'Class']('panel-collapsed');
                    });
                })
                .on('panel:dismiss', function () {
                    var $this = $(this);

                    if (!!( $this.parent('div').attr('class') || '' ).match(/col-(xs|sm|md|lg)/g) && $this.siblings().length === 0) {
                        $row = $this.closest('.row');
                        $this.parent('div').remove();
                        if ($row.children().length === 0) {
                            $row.remove();
                        }
                    } else {
                        $this.remove();
                    }
                })
                .on('click', '[data-panel-toggle]', function (e) {
                    e.preventDefault();
                    $(this).closest('.panel').trigger('panel:toggle');
                })
                .on('click', '[data-panel-dismiss]', function (e) {
                    e.preventDefault();
                    $(this).closest('.panel').trigger('panel:dismiss');
                })
                /* Deprecated */
                .on('click', '.panel-actions a.fa-caret-up', function (e) {
                    e.preventDefault();
                    var $this = $(this);

                    $this
                        .removeClass('fa-caret-up')
                        .addClass('fa-caret-down');

                    $this.closest('.panel').trigger('panel:toggle');
                })
                .on('click', '.panel-actions a.fa-caret-down', function (e) {
                    e.preventDefault();
                    var $this = $(this);

                    $this
                        .removeClass('fa-caret-down')
                        .addClass('fa-caret-up');

                    $this.closest('.panel').trigger('panel:toggle');
                })
                .on('click', '.panel-actions a.fa-times', function (e) {
                    e.preventDefault();
                    var $this = $(this);

                    $this.closest('.panel').trigger('panel:dismiss');
                });
        });

    })(jQuery);

// Bootstrap Toggle
    (function ($) {

        'use strict';

        var $window = $(window);

        var toggleClass = function ($el) {
            if (!!$el.data('toggleClassBinded')) {
                return false;
            }

            var $target,
                className,
                eventName;

            $target   = $($el.attr('data-target'));
            className = $el.attr('data-toggle-class');
            eventName = $el.attr('data-fire-event');


            $el.on('click.toggleClass', function (e) {
                e.preventDefault();
                $target.toggleClass(className);

                var hasClass = $target.hasClass(className);

                if (!!eventName) {
                    $window.trigger(eventName, {
                        added  : hasClass,
                        removed: !hasClass
                    });
                }
            });

            $el.data('toggleClassBinded', true);

            return true;
        };

        $(function () {
            $('[data-toggle-class][data-target]').each(function () {
                toggleClass($(this));
            });
        });

    }).apply(this, [jQuery]);
}
