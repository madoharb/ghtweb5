(function(APP){

    'use strict';



    var globalAjaxLoadingQueue = [];

    APP.globalAjaxLoading = function(action) {
        var html, fadeClassName, modalClassName;

        fadeClassName  = 'ajax-loading-fade';
        modalClassName = 'ajax-loading-modal';

        if(action == 'start')
        {
            globalAjaxLoadingQueue.push(1);

            html = '<div class="' + fadeClassName + '"></div>\
                <div class="' + modalClassName + '"></div>';

            $('body').append(html);
        }
        else if(action == 'stop')
        {
            globalAjaxLoadingQueue.splice(0, 1);

            if(globalAjaxLoadingQueue.length == 0) {
                $('body').find('.' + fadeClassName + ',.' + modalClassName).remove();
            }
        }
    }

    /**
     * Модальное окошко
     *
     * @param data
     * <code>
     *    title: string
     *    body: string
     * </code>
     */
    APP.Modal = function(data){
        var src  = $('#modal-box-tpl').html(),
            tpl  = Handlebars.compile(src),
            html = tpl(data);

        $(html).modal('show');
    };

    /**
     * Быстрые сообщения
     *
     * @param data
     * <code>
     *    type: success|error|info
     *    text: string
     *    title: string
     * </code>
     */
    APP.Notice = function(data){
        $.notification({
            title: data.title || null,
            content: data.text,
            timeout: data.timeout || 4000,
            type: data.type || 'error'
        });
    };

    $(function(){

        // Подтверждение удаления
        $('.glyphicon-remove').on('click',function(){
            return confirm('Точно удалить?');
        });

        $('[rel=tooltip]').tooltip();

        $('.glyphicon-info-sign').popover({
            placement: 'top',
            trigger  : 'hover'
        });

        if($.isFunction($.fancybox))
        {
            $('.fancybox').fancybox({
                padding: 0
            });
        }

        // Clear cache
        $('.js-clear-cache').on('click', function(e){
            e.preventDefault();

            var $self;

            $self = $(this);

            APP.globalAjaxLoading('start');

            $.get($self[0].href, function(res){
                APP.globalAjaxLoading('stop');
                APP.Notice({
                    text: res,
                    type: 'success'
                });
            });

        });

    });

})(APP);