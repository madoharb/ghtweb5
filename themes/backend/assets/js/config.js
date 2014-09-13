(function(){

    'use strict';

    var removeSuccessErrorClasses = function(){
        $('form')
            .find('.has-success').removeClass('has-success')
            .end()
            .find('.has-error').removeClass('has-error');
    };

    var submitted = function(e){

        e.preventDefault();

        var $form      = $(this),
            params     = $form.serializeForm(),
            $button    = $form.find('.tab-pane.active').find('[type=submit]'),
            buttonText = $button.text();

        $button.prop('disabled', true).text('Сохранение данных...');

        removeSuccessErrorClasses();

        $.post($form[0].action, params, function(response){
            if(response == 'ok'){
                $button.prop('disabled', false).text(buttonText);
            }
        });

    };

    var resetDefault = function(e){

        e.preventDefault();

        var this_  = $(this),
            form   = this_.parents('form'),
            parent = this_.parents('.form-group'),
            field  = $(this).data('fieldname'),
            csrf   = form.find('[type=hidden]');

        this_.hide();

        var params = {
            Reset: {
                field: field
            }
        };

        params[csrf.attr('name')] = csrf.val();

        $.post('', params, function(response){
            if(response != 'fail'){
                parent.addClass('has-success').find('[name="Config[' + field +']"]').val(response);
            }else{
                parent.addClass('has-error');
            }
            this_.show();
        });

    };

    $(function(){

        $('#config-form').on('submit', submitted);
        $('#config-form .glyphicon-retweet').on('click', resetDefault);

    });

})();