(function(){

    'use strict';

    $(function(){

        var $spa, $spo;

        $spa = $('#Gs_services_premium_allow');
        $spo = $('.services-premium-settings');

        if($spa.val() == 1){
            $spo.show();
        } else {
            $spo.hide();
        }

        $spa.on('change',function(){
            if($(this).val() == 1){
                $spo.show();
            } else {
                $spo.hide();
            }
        });

        var $block, $plus;

        $block = $('.services-premium-settings');
        $plus  = $block.find('.glyphicon-plus');

        $plus.on('click',function(){
            var $ul, $li, $clone, id;

            $ul    = $block.find('ul:first');
            $li    = $ul.find('li:last');
            $clone = $li.clone();
            id     = parseInt($li.attr('data-id'));

            id++;

            $clone.attr('data-id', id);
            $clone.find('input:first').attr('name', 'Gs[services_premium_cost][' + id + '][days]');
            $clone.find('input:last').attr('name', 'Gs[services_premium_cost][' + id + '][cost]');
            
            $ul.append($clone);
        });

        $block.delegate('.glyphicon-minus', 'click',function(){
            if($block.find('ul:first > li').length <= 1){
                return;
            }
            $(this).parent().remove();
        });

    });

})();