var ShareCalculation = {
    option_prices:{
        '2012/13' :1.17339, '2013/14':0.92800, '2014/15':0.97040
    },
    scheme_year : '2014/15',
    options : {
        forecast : 1.05,
        slider1_value : 20,
        after3y : 0,
        number_shares : 0,
        value_shares : 0,
        profit : 0,
        leaving : 0
    },
    options2 : {
        slider2_value : 20,
        forecast2 : 1.05,
        today_share_price : 0,
        shares_worth : 0,
        investing : 0,
        cost_basic : 0,
        cost_high : 0,
        'own_units' : 0,
        'company_units' : 0,
        'sum_units' : 0
    },
    init: function(){
        $('#forecast').val(this.options.forecast);
        $('#forecast2').val(this.options2.forecast2);
        this.calculate();
        this.setElements();
    },
    getOptionPrice: function(){
        this.scheme_year = $($('.scheme_year:checked')[0]).val();
        if(typeof this.option_prices[this.scheme_year] != 'undefined'){
            return this.option_prices[this.scheme_year];
        }else{
            return 0;
        }
    },
    loadElements : function(){
        jQuery.each( this.options ,function(key, item){
            if($('#'+key)){
                if( $('#'+key).val()){
                    var val = parseFloat($('#'+key).val());
                }else if($('#'+key).html() ){
                    var val = parseFloat($('#'+key).html());
                }else{
                    var val = 1;
                }
                this.options[key] = val;
            }
        }.bind(this));
        jQuery.each( this.options2 ,function(key, item){
            if($('#'+key)){
                if( $('#'+key).val()){
                    var val = parseFloat($('#'+key).val());
                }else if($('#'+key).html() ){
                    var val = parseFloat($('#'+key).html());
                }else{
                    var val = 1;
                }
                this.options2[key] = val;
            }
        }.bind(this));
    },
    setElements : function(){
        $('#option_price').html(this.getOptionPrice());
        jQuery.each( this.options ,function(key, item){
            if($('#'+key)){
                if( $('#'+key).prop("tagName") == 'input'){
                    $('#'+key).val(item);
                }else {
                    $('#'+key).html(item);
                }
            }
        });
        jQuery.each( this.options2 ,function(key, item){
            if($('#'+key)){
                if( $('#'+key).prop("tagName") == 'input'){
                    $('#'+key).val(item);
                }else {
                    $('#'+key).html(item);
                }
            }
        });
    },
    calculate: function(){
        var company_maximum_contribution = 20;
        var basic_discount = 0.32; //32%
        var high_discount = 0.42; //42%
        var option_price = this.getOptionPrice();

        this.options.after3y = this.options.slider1_value * 36;
        this.options.number_shares = Math.floor(this.options.after3y / option_price);
        this.options.value_shares = Math.floor(this.options.number_shares * this.options.forecast, 1);
        this.options.profit = this.options.value_shares - this.options.after3y;
        if (this.options.profit < 0){
            this.options.profit = 0
        }
        this.options.leaving = this.options.value_shares;


        this.options2.investing = this.options2.slider2_value;
        this.options2.cost_basic = Math.round( this.options2.slider2_value * ( 1 - basic_discount) * 100) / 100;
        this.options2.cost_high = Math.round( this.options2.slider2_value * ( 1 - high_discount) * 100) / 100;

        this.options2.own_units = Math.floor( this.options2.slider2_value / this.options2.forecast2);
        this.options2.company_units = Math.floor((this.options2.own_units / 3) * 2);

        var company_max = Math.floor(company_maximum_contribution / this.options2.forecast2);
        if(this.options2.company_units > company_max ){
            this.options2.company_units = company_max;
        }
        this.options2.today_share_price = this.options2.forecast2;

        this.options2.sum_units = this.options2.own_units + this.options2.company_units;
        this.options2.shares_worth = Math.round(this.options2.forecast2 * this.options2.sum_units * 100) / 100;
    },
    update : function(){
        this.loadElements();
        this.calculate();
        this.setElements();
    }
}

$(document).ready(function() {

    if ($('#shares')) {
        ShareCalculation.init();

        var sliders = $('.share_slider');
        if (sliders) {
            $.each(sliders,function(index, value){
                $(value).slider({
                    orientation: "horizontal",
                    range: "min",
                    value: parseInt($(value).data('startat'),10),
                    min: parseInt($(value).data('start'),10),
                    max: parseInt($(value).data('end'),10),
                    slide: function(event, ui){
                        $(ui.handle).attr('data-progress','£' + ui.value);

                        var hidden = $('.slider > .value');
                        if($(hidden)) {
                            $(hidden).val(ui.value);
                        }
                        ShareCalculation.update();
                    },
                    change: function(event, ui){
                        $(ui.handle).attr('data-progress','£' + ui.value);
                        var hidden = $('.slider > .value');
                        if($(hidden)) {
                            $(hidden).val(ui.value);
                        }
                        ShareCalculation.update();
                    }
                });
                $(value).find('span.ui-slider-handle').attr('data-progress','£' + parseInt($(value).data('startat'),10));
            });
        }

        $('#forecast').on('change', function (e) { ShareCalculation.update(); });
        $('#forecast2').on('change', function (e) { ShareCalculation.update(); });

        $('.in_upd').on('click', function (e) {
            var step = 0.01;
            var parent = $(this).closest($('.wrap-input-btns'))[0];
            var input = $(parent).children($('.target'))[0];
            var val = parseFloat($(input).val());

            if($(this).hasClass('up')){
                val += step;
            }
            if($(this).hasClass('down')){
                val -= step;
            }
            $(input).val( Math.round(val * 100) / 100 );
            ShareCalculation.update();
        });

        $('input[name="scheme_year"]').on('click', function (e) { $('label.active').removeClass('active'); $(this).next($('label')).addClass('active');  ShareCalculation.update();});
    }
});