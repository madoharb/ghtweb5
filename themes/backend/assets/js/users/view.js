(function() {

    'use string';

    $(function(){

        // Добавление бонуса
        $('.js-add-bonus').on('click', function(e){
            e.preventDefault();

            var $self;

            $self = $(this);

            APP.globalAjaxLoading('start');

            $.getJSON($self[0].href, function(res){
                APP.globalAjaxLoading('stop');
                if(res.status){
                    APP.Modal({
                        title: 'Adding bonus',
                        body: res.view
                    });
                }
            });
        });

        // Form submit
        $('body').delegate('.js-add-bonus-form, .js-add-message-form, .js-edit-data-form', 'submit', function(e){
            e.preventDefault();

            var $self, params, $button;

            $self   = $(this);
            params  = $self.serializeForm();
            $button = $self.find('[type=submit]');

            $button.prop('disabled', true);

            $.post($self[0].action, params, function(res){
                $button.prop('disabled', false);
                $self.find('span.errors').remove();
                if(res.status){
                    $self.parents('.modal').find('button.close').trigger('click');
                    APP.Notice({
                        type: 'success',
                        text: res.msg
                    });
                } else {
                    for(var i in res.msg){
                        var $ele = $self.find('#' + i),
                            msg  = res.msg[i].join('<br>');

                        if($ele.length){
                            var $eleParent = $ele.parent(),
                                $eleParentErrorBlock = $eleParent.find('span.errors');

                            if($eleParentErrorBlock.length){
                                $eleParentErrorBlock.html(msg);
                            } else {
                                $eleParent.append('<span class="errors">' + msg + '</span>');
                            }
                        }
                    }
                }
            }, 'json').error(function(){
                $button.prop('disabled', false);
            });
        });

        // Удаление бонуса
        $('.js-remove-bonus').on('click', function(e){
            e.preventDefault();

            if(confirm('Точно удалить?')){
                $self = $(this);

                APP.globalAjaxLoading('start');

                $.getJSON($self[0].href, function(res){
                    APP.globalAjaxLoading('stop');
                    if(res.status){
                        $self.parents('li').fadeOut();
                    }

                    APP.Notice({
                        type: res.status ? 'success' : 'error',
                        text: res.msg
                    });
                });
            }
        });

        // Отправка сообщения
        $('.js-send-message').on('click', function(e){
            e.preventDefault();

            var $self;

            $self = $(this);

            APP.globalAjaxLoading('start');

            $.getJSON($self[0].href, function(res){
                APP.globalAjaxLoading('stop');
                if(res.status){
                    APP.Modal({
                        title: 'Adding message',
                        body: res.view
                    });
                }
            });
        });

        // Отправка сообщения
        $('.js-edit-data').on('click', function(e){
            e.preventDefault();

            var $self;

            $self = $(this);

            APP.globalAjaxLoading('start');

            $.getJSON($self[0].href, function(res){
                APP.globalAjaxLoading('stop');
                if(res.status){
                    APP.Modal({
                        title: 'Edit data',
                        body: res.view
                    });
                }
            });
        });

    });

})();