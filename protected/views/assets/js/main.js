(function(){

    'use strict';

    var Message;

    Message = function(type, message, title){
        $.notification({timeout: 5000, title: title, content: message, type: type, img: ""});
    };


    $(function(){

        // Chosen
        $('select:not(.not-chosen)').chosen({
            disable_search_threshold: 10
        });

        // Tooltip
        $('[rel=tooltip]').tooltip();

        $('#DepositForm_count').on('keyup',function(){
            var this_   = $(this),
                field   = this_.parents('form').find('.total-pay b'),
                val     = this_.val(),
                perItem = parseInt(this_.data('per-item')) || 0,
                sum     = val * perItem;
            field.text(0);
            if(val.substr(0, 1) == '0')
            {
                this_.val(val.substr(1));
            }
            if(!$.isNumeric(val))
            {
                this_.val(0);
            }
            if(sum >= 1)
            {
                field.text(sum);
            }
        });

        if($.isFunction($.fancybox))
        {
            $('.fancybox').fancybox({
                padding: 0
            });
        }

        $('#regFormButton').on('click',function(){
            $('#regForm').arcticmodal();
        });

        $('#fast-register-form').on('submit',function(){
            var $self, params, $button, buttonOldText;

            $self         = $(this);
            params        = $self.serialize();
            $button       = $self.find('[type=submit]');
            buttonOldText = $button.text();

            if(!$button.prop('disabled'))
            {
                $button.text('Загрузка...').prop('disabled', true);

                $.post($self.attr('action'), params, function(response){
                    $button.text(buttonOldText).prop('disabled', false);
                    var msg = '';
                    for(var i in response){
                        msg += response[i] + '<br>';
                    }
                    if(msg){
                        Message('error', msg);
                    }else{
                        Message('success', 'It`s okay');
                    }
                }, 'json').error(function(){
                    $button.text(buttonOldText).prop('disabled', false);
                    console.log(['Register ajax Error', arguments[0]['responseText']]);
                });
            }

            return false;
        });

    });

})();