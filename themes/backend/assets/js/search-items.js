(function() {

    'use string';

    $(function(){

        if($.isFunction(jQuery.fn.typeahead))
        {
            var xhr = null;

            $('.js-item-name').typeahead({
                minLength: 3
            },{
                source: function (query, process) {
                    if(xhr !== null) {
                        APP.globalAjaxLoading('stop');
                        xhr.abort();
                    }
                    APP.globalAjaxLoading('start');
                    xhr = $.getJSON(urlItemInfo, {query: query}, function (response){
                        APP.globalAjaxLoading('stop');
                        xhr = null;
                        return process(response);
                    });

                    return xhr;
                }
            }).on('typeahead:selected', function (object, datum) {
                $('.js-item-id').val(datum.id);
                $('.img-block .img').html(datum.icon);
            });

            $('.js-item-id').on('change', function(){
                var $self, $form;

                $self = $(this);
                $form = $self.parents('form');

                if(xhr !== null) {
                    xhr.abort();
                }

                APP.globalAjaxLoading('start');

                xhr = $.getJSON(urlItemInfo + '?item-id=' + $self.val(), {}, function(response){
                    APP.globalAjaxLoading('stop');
                    xhr = null;
                    if(response.status == 'success') {
                        $form.find('.js-item-name').val(response.msg);
                    } else {
                        alert('Item not found!');
                    }
                }).error(function(){
                    console.log(arguments);
                });
            });
        }
    });

})();