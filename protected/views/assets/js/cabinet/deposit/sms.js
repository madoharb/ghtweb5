$(function(){

    'use strict';

    console.info(smsList);

    $('.js-countries').on('change', function(){
        var $self = $(this),
            val   = $self.val();

        $('.sms-block-operators, .sms-block-rates, .sms-block-info').hide();

        if(val in smsList) {
            var operators = smsList[val],
                $operatorsSelect = $('.js-operators');
            $operatorsSelect.find('option:not(:first)').remove();
            $.each(operators, function(id, data){
                var operatorName = '';
                for(var i in data) {
                    operatorName = data[i];
                    break;
                }
                $operatorsSelect.append('<option value="' + id + '">' + operatorName['operator_name'] + '</option>');
            });
            $('.sms-block-operators').show();
        }
    });

    $('.js-operators').on('change', function(){
        var $self = $(this),
            countryCode = $('.js-countries').val(),
            val = $self.val();

        $('.sms-block-rates, .sms-block-info').hide();

        if(smsList[countryCode][val]) {
            var rates = smsList[countryCode][val],
                $ratesSelect = $('.js-rates');
            $ratesSelect.find('option:not(:first)').remove();
            $.each(rates, function(id, item){
                $ratesSelect.append('<option value="' + item['count_items'] + '">' + item['count_items'] + ' ' + itemName + '</option>');
            });
            $('.sms-block-rates').show();
        }
    });

    $('.js-rates').on('change', function(){
        var $self = $(this),
            countryCode = $('.js-countries').val(),
            operatorId = $('.js-operators').val(),
            count = $('.js-rates').val();

        if(smsList[countryCode][operatorId][count]) {
            var $infoBlock = $('.sms-block-info'),
                smsInfo = smsList[countryCode][operatorId][count];
            $infoBlock.find('.sms-block-number').text(smsInfo['number'])
                .end()
                .find('.sms-block-text').text(smsInfo['prefix'] + ' ' + prefix)
                .end()
                .find('.sms-block-cost').text(smsInfo['price_nds'] + ' ' + smsInfo['currency'])
                .end()
                .show();
        }
    });


});