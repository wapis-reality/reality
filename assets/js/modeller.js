var DCModellerApp = Class.create({
    init: function (setting) {
        this.calc = new calculation(setting || {});

        this.others();
        //$('#modeller').addClass('none');
    },

    others: function(){
        //$('#tou_label').on('click', jQuery.proxy(function(){
            $('#terms').addClass('none');
            $('#modeller').removeClass('none');
            $('.printpage').removeClass('none');
            this.calc.loadDefaultInputData();
            this.calc.calculate();
            this.calc.display();
            this.initSliders();
            //this.initRadios();
            //this.initTooltip();

            this.redrawGraph();
        //}, this));
    },
    initTooltip: function () {
        var objts = $('.tooltip_icon, .title_tooltip'),
            html = '<div class="tooltip"><span class="clt"></span><span class="cct"></span><span class="crt"></span><span class="content"></span><span class="clb"></span><span class="ccb"></span><span class="crb"></span><span class="arrow left"></span></div>',
            tooltip = $(html);

        objts.on('click', function (e) {
            var obj = $(this),
                rel = obj.attr('title'),
                title = rel.split('|'),
                heading = title[0],
                text = rel,//title[1],
                pos = obj.position(),
                content = tooltip.find('.content'),
                arrow = tooltip.find('.arrow');

            if (obj.hasClass('bottom')) {
                pos = $("#bus").position();
                tooltip.css({
                    left: pos.left + 60, //100,
                    top: pos.top + 60
                });

                arrow.removeClass('left').addClass('top');

            } else {

                tooltip.css({
                    left: pos.left + 55,
                    top: pos.top - 20
                });
                arrow.removeClass('top').addClass('left');
            }


            a = text.split('#');
            p = '';

            for(var i=0; i< a.length; i++){
                p+= '<p>'+ a[i] +'</p>';
            }

            //content.html('<h3>' + heading + '</h3>' + p );
            content.html(p);

            $('.dc-modellerWrapper').append(tooltip);
        });


        $('.dc-modellerWrapper').on('click', function (e) {
            if (!$(e.target).hasClass('tooltip_icon') && !$(e.target).hasClass('title_tooltip')){
                $('.tooltip').remove();
            }
            //
        })


    },

    initSliders: function () {


        this.cashSlider = new $.Sliders('sliderWrapper1', {min: 0, max: Math.round(this.calc.options.result.maximum_cash), prefix: '£', suffix: '', value: 0, onUpdate: jQuery.proxy(function (val) {
            this.calc.setVal('cashTaken', val);
            this.redrawGraph();
        }, this)});
        new $.Sliders('sliderWrapper2', {min: 3, max: 15, suffix: '%', value: this.calc.options.inputData.contribution, onUpdate: jQuery.proxy(function (val) {
            this.calc.setVal('cashContribution', val / 100);
            this.cashSlider.update('max', Math.round(this.calc.options.result.maximum_cash));
            this.redrawGraph();
        }, this)});

        new $.Sliders('sliderWrapper3', {min: this.calc.options.config.min_ret_age, max: 75, suffix: '', value: this.calc.options.inputData.pension_age, onUpdate: jQuery.proxy(function (val) {
            this.calc.setVal('chosenAgeToRetire', val);
            this.cashSlider.update('max', Math.round(this.calc.options.result.maximum_cash));
            this.redrawGraph();
        }, this)});


        $('#more').on('click', function (e) {
            e.preventDefault();
            var wrapper = $($(this).parents('div.wrapper')[0]);

            if (!Modernizr.csstransitions) {
                wrapper.animate({left: -223, duration: 200});
                $($('.bus_wrapper')[0]).animate({left: 20, duration: 200});
                $($('.pot_wrapper')[0]).animate({right: 0, duration: 200});
            } else {
                wrapper.addClass('left');
                $('.bus_wrapper').addClass('left');
                $('.pot_wrapper').addClass('left');
            }


        })
    },

    redrawGraph: function () {
        var percentage = (this.calc.options.result.total_income / this.calc.options.result.pensionable_pay * 100);

        $('.pot_wrapper .filling').css({
            height: 130 / 100 * (this.calc.options.inputs.cashTaken / this.calc.options.result.maximum_cash * 100)
        });



        $('.bus_wrapper > .filling').css({
            //height: 140 / 100 * (this.calc.options.result.total_income / this.calc.options.result.pensionable_pay * 100)
            height: (percentage >= 70 ? 140 : (140 / 70 * (percentage)))
        });

        $('.bus_wrapper  .text').html('£' + numberWithCommas(Math.round(this.calc.options.result.total_income)));
        $('.pot_wrapper  .text').html('£' + numberWithCommas(Math.round(this.calc.options.inputs.cashTaken)));

        if(this.calc.options.inputs.chosenAgeToRetire < (this.calc.options.result.SPA)){
            $('.bus_red').css( "display", "block" );
            $('.statePensionText').css( "display", "block" );
        } else {
            $('.bus_red').css( "display", "none" );
            $('.statePensionText').css( "display", "none" );
        }
    },

    initRadios: function () {
        var listObj = $('input[type=radio]');
        jQuery.each(listObj, function (i, obj) {
            new radioButton($(obj));
        })
    }
});

var radioButton = Class.create({
    obj: null,
    init: function (obj) {
        this.obj = obj;
        this.build();
    },
    build: function () {
        var RadioObj = $('<span class="radio"></span>').on('click', jQuery.proxy(function (e) {
            e.preventDefault();
            var obj = $(e.target);

            if (!this.obj.is(':checked')) {
                var name = this.obj.attr('name'),
                    all = $('input[type=radio][name=' + name + ']');

                jQuery.each(all, function (i, inp) {
                    $(inp).next().removeClass('checked');
                    $(inp).removeAttr('checked');
                    $(inp).checked = false;
                });

                this.obj.attr("checked", "checked");
                this.obj.checked = true;
                obj.addClass('checked');
            }
        }, this));

        var wrapper = $('<span class="input_wrapper"></span>');
        this.obj.before(wrapper);
        wrapper.append(this.obj)
        this.obj.after(RadioObj);

        if (this.obj.is(':checked')) {
            RadioObj.addClass('checked');
        }
    }
});

var calculation = Class.create({

    options: {
        
        inputData: {
            dob: '1982-12-20',
            doj: '2013-10-01',
            pensionable_pay: 70000,
            current_fund: 10000,
            contribution: 3,
            pension_age: 63
        },
        config: {
            min_ret_age: 55,
            db_earnings_ca: 30660,
            state_pension: 7488,
            annuity: { '55': 20.000, '56': 20.000, '57': 20.000, '58': 20.000, '59': 20.000, '60': 20.000, '61': 20.000, '62': 20.000, '63': 20.000, '64': 20.000, '65': 20.000, '66': 20.000, '67': 20.000, '68': 20.000, '69': 20.000, '70': 20.000, '71': 20.000, '72': 20.000, '73': 20.000, '74': 20.000, '75': 20.000 },
            commutation_factor: { '55': 19.206, '56': 18.829, '57': 18.449, '58': 18.064, '59': 17.671, '60': 17.268, '61': 16.851, '62': 16.418, '63': 15.966, '64': 15.496, '65': 15.009, '66': 14.517, '67': 14.030, '68': 13.554, '69': 13.091, '70': 12.635, '71': 12.181, '72': 11.724, '73': 11.264, '74': 10.804, '75': 10.348 },
            investmentReturnAssumption: {'low': 1 / 100, 'medium': 2.75 / 100, 'high': 3.65 / 100 },
            dbAccrual: {'gold': 1 / 80, 'silver': 1 / 100, 'bronze': 0},		// no benefits on bronze level, this is accrual for above bronze
            erf: {'0': 1.000, '1': 0.940, '2': 0.890, '3': 0.840, '4': 0.790, '5': 0.750, '6': 0.710, '7': 0.670, '8': 0.630, '9': 0.600, '10': 0.560, '11': 0.530, '12': 0.500, '13': 0.470, '14': 0.450, '15': 0.420},
            spa: {"19370406": "65.00", "19531206": "65.25", "19540106": "65.33", "19540206": "65.42", "19540306": "65.50", "19540406": "65.58", "19540506": "65.67", "19540606": "65.75", "19540706": "65.83", "19540806": "65.92", "19540906": "66.00", "19680406": "66.08", "19680506": "66.17", "19680606": "66.25", "19680706": "66.33", "19680806": "66.42", "19680906": "66.50", "19681006": "66.58", "19681106": "66.67", "19681206": "66.75", "19690106": "66.83", "19690206": "66.92", "19690306": "67.00", "19770406": "67.08", "19770506": "67.17", "19770606": "67.25", "19770706": "67.33", "19770806": "67.42", "19770906": "67.50", "19771006": "67.58", "19771106": "67.67", "19771206": "67.75", "19780106": "67.83", "19780206": "67.92", "19780306": "68.00"}
        },
        inputs: {

            /**
             * Your contribution rate
             */
            cashContribution: 0.05,

            /**
             * Your chosen cash amount
             */
            cashTaken: 0,

            /**
             * assigned ee contribution
             */
            memberRate: 10,				// update by slider

            /**
             * Your chosen retirement date
             */
            chosenAgeToRetire: 55,
//            memberAge: { total: 0, years: 0, months: 0},

            /**
             * Future pension level
             */
            schemeMembership: 'bronze',

            /**
             *  Chosen investment strategy
             */
            investmentStrategy: 'medium'	// chosen with modeller
        },
        result: {

            /**
             * Date of calculation
             */
            doc: null,

            /**
             * Date joined pension scheme
             */
            doj: null,

            /**
             * Accumulated fund value at date of calculation
             */
            accumulated_fund: null,

            /**
             * Your age
             */
            age: null,

            /**
             * Your pensionable pay
             */
            pensionable_pay: null,

            /**
             * Reduction in your weekly take-home pay
             */
            reductions: null,

            /**
             * Total amount paid to your Bronze level account each week including Company contribution
             */
            week_paid_total_amount: null,

            /**
             * Eligible for DB section in future
             */
            eligible_future: null,

            /**
             * Projected Bronze level fund value at retirement
             */
            fund_at_retirement: null,

            /**
             * Maximum cash available to you at
             */
            maximum_cash: null,

            /**
             * Scheme pension
             */
            scheme_pension: null,

            /**
             * State pension
             */
            state_pension: null,

            /**
             * Your total income in retirement from the Scheme and the State
             */
            total_income: null,

            /**
             * pensionable_pay / total in percentage value
             */
            pensionable_pay_percentage: null
        }


    },

    calcTmp: {},

    tab: 0,

    init: function (config) {
        jQuery.extend(true, this.options, config || {});
    },

    loadDefaultInputData: function(){
        this.options.inputs.cashContribution = this.options.inputData.contribution/100;
        this.options.inputs.chosenAgeToRetire = this.options.inputData.pension_age;
    },

    setVal: function (item, value) {
        this.options.inputs[item] = value;

        this.calcTmp = {};
        this.calculate();
        this.display();
    },

    display: function () {
        var objs = $('#breakdown .auto_update'),
            html = '';


        jQuery.each(objs, jQuery.proxy(function (i, el) {
            var el = $(el),
                rel = el.attr('rel'),
                arr = rel.split('.'),
                rounding = (el.attr('data-round')) ? el.attr('data-round') : null,
                prefix = (el.attr('data-prefix')) ? el.attr('data-prefix') : '',
                postfix = (el.attr('data-postfix')) ? el.attr('data-postfix') : '';

            if (arr[0] && arr[1] && this.options[arr[0]][arr[1]]) {
                if (rounding == null){
                    val = Math.round(this.options[arr[0]][arr[1]]).toString();
                } else if (rounding == 'floor'){
                    val = Math.floor(this.options[arr[0]][arr[1]]).toString();
                }
                val = numberWithCommas(val);
            } else {
                val = "0";
            }

            el.html(prefix + val.toString() + postfix);
        }, this));

    },
    getDateDiff: function (date1_t, date2_t, format) {
        this.tab++;

        if($('#ietype').length){
            if (date1_t instanceof Date) {
                var date1 = date1_t;
            } else {
                var date1 = parseISO8601(date1_t);
            }
            if (date2_t instanceof Date) {
                var date2 = date2_t;
            } else {
                var date2 = parseISO8601(date2_t);
            }
        } else {
            var date1 = new Date(date1_t);
            var date2 = new Date(date2_t);
        }

        var result = {},
            //date1 = new Date(date1_t),
            //date2 = new Date(date2_t),
            years = date2.getFullYear() - date1.getFullYear(),
            months = date2.getMonth() - date1.getMonth(),
            tmpDate = null,
            age = null;

        tmpDate = parseISO8601(date2_t); //new Date(date2_t);
        tmpDate.setYear(date1.getYear());
        

        if (months < 0 || (months === 0 && date2.getDate() < date1.getDate())) {
            years--;
        }
        months = Math.abs(months);
        age = years + months / 12;

        switch (format) {
            case 'y':

                result = years;
                break;
            case 'm':
                result = months;
                break;
            case 'ym':


                result = date2.getMonth() - date1.getMonth();
                if (result < 0) {
                    result += 12;
                }

                if (date2.getDay() < date1.getDay()) {
                    result--;
                }

                break;
            default:
                result = {
                    total: age,
                    years: years,
                    months: months
                }
        }


        this.tab--;

        return result;
    },

    /**
     * calculate age of member
     * @returns int, age
     */
    getMemberAge: function () {
        if (this.calcTmp.memberAge) {
            return this.calcTmp.memberAge;
        }
        this.tab++;
        var result = this.getDateDiff(this.options.inputData.dob, new Date())
        this.tab--;

        return this.calcTmp.memberAge = result;
    },

    /**
     * Row 2
     */
    getAgeOfDOC: function () {
        if (this.calcTmp.ageOfDOC) {
            return this.calcTmp.ageOfDOC;
        }
        this.tab++;
        /**
         * =DATEDIF(DATA!B2,Summary!B2,"y")+DATEDIF(DATA!B2,Summary!B2,"ym")/12
         */

        this.tab--;
        return this.calcTmp.ageOfDOC = this.getDateDiff(this.options.inputData.dob, new Date(), "y") + this.getDateDiff(this.options.inputData.dob, new Date(), "ym") / 12
    },

    /**
     * Row 3
     */
    getNPA: function () {
        if (this.calcTmp.npa) {
            return this.calcTmp.npa;
        }
        var list = this.options.config.spa,
            dob = utils.DATE2NUM(this.options.inputData.dob),
            result = 68;



        jQuery.each(list, function (date, dor) {

            if (date <= dob) {
                result = dor;


            }
        })

        return this.calcTmp.npa = result;
    },
    /**
     * Row 5
     */
    getTerm2ChosenRetirement: function () {
        if (this.calcTmp.term2ChosenRetirement) {
            return this.calcTmp.term2ChosenRetirement;
        }
        this.tab++;
        /**
         * =B4-B2
         */

        this.tab--;
        return this.calcTmp.term2ChosenRetirement = this.options.inputs.chosenAgeToRetire - this.getAgeOfDOC();
    },
    /**
     * Row 7
     */
    getDateCompleted9YrsService: function () {
        if (this.calcTmp.dateCompleted9YrsService) {
            return this.calcTmp.dateCompleted9YrsService;
        }
        this.tab++;
        /**
         * =DATE(YEAR(DATA!B3)+9,MONTH(DATA!B3),DAY(DATA!B3))
         */

        this.tab--;
        return this.calcTmp.dateCompleted9YrsService =
            utils.DATE(parseInt(utils.YEAR(this.options.inputData.doj)) + 9, utils.MONTH(this.options.inputData.doj), utils.DAY(this.options.inputData.doj))
    },
    /**
     * Row 8
     */
    getDate1Eligible4DBSection: function () {
        if (this.calcTmp.date1Eligible4DBSection) {
            return this.calcTmp.date1Eligible4DBSection;
        }
        this.tab++;
        /**
         * =IF(B7<=DATE(YEAR(B7),4,1),DATE(YEAR(B7),4,1),DATE(YEAR(B7)+1,4,1))
         */

        this.tab--;
        return this.calcTmp.date1Eligible4DBSection = (utils.DATE2NUM(this.getDateCompleted9YrsService()) <= utils.DATE2NUM(utils.DATE(utils.YEAR(this.getDateCompleted9YrsService()), '04', '01')) ?
            utils.DATE(utils.YEAR(this.getDateCompleted9YrsService()), '04', '01') :
            utils.DATE(parseInt(utils.YEAR(this.getDateCompleted9YrsService())) + 1, '04', '01'));
    },

    /**
     * Row 9
     */
    getRemainingTermUntilEligible4DBSection: function () {
        if (this.calcTmp.remainingTermUntilEligible4DBSection) {
            return this.calcTmp.remainingTermUntilEligible4DBSection;
        }
        this.tab++;
        /**
         * =DATEDIF(Summary!B2,CALCULATIONS!B8,"y")+DATEDIF(Summary!B2,CALCULATIONS!B8,"ym")/12
         */
        this.tab--;
        return this.calcTmp.remainingTermUntilEligible4DBSection = this.getDateDiff(new Date(), this.getDate1Eligible4DBSection(), "y") + this.getDateDiff(new Date(), this.getDate1Eligible4DBSection(), "ym") / 12
    },


    /**
     * Row 10
     */
    getChosenPensionDate: function () {
        if (this.calcTmp.chosenPensionDate) {
            return this.calcTmp.chosenPensionDate;
        }
        this.tab++;
        /**
         * =DATE(YEAR(DATA!B2)+Summary!B10,MONTH(DATA!B2),DAY(DATA!B2))
         */

        this.tab--;
        return this.calcTmp.chosenPensionDate =
            utils.DATE(parseInt(utils.YEAR(this.options.inputData.dob)) + parseInt(this.options.inputs.chosenAgeToRetire), utils.MONTH(this.options.inputData.dob), utils.DAY(this.options.inputData.dob))
    },


    /**
     * Row 12
     */
    isEligible4DBSectionInFuture: function () {
        if (this.calcTmp.eligible4DBSectionInFuture) {
            return this.calcTmp.eligible4DBSectionInFuture;
        }
        this.tab++;
        /**
         * =IF(B10<=B8,"N","Y")
         */

        this.tab--;

        return this.calcTmp.eligible4DBSectionInFuture = (utils.DATE2NUM(this.getChosenPensionDate()) <= utils.DATE2NUM(this.getDate1Eligible4DBSection())) ? false : true;
    },

    /**
     * Row 14
     */
    getPotentialDbService: function () {
        if (this.calcTmp.potentialDbService) {
            return this.calcTmp.potentialDbService;
        }
        this.tab++;
        /**
         * =MAX(DATEDIF(B8,B10,"y")+DATEDIF(B8,B10,"ym")/12,0)
         */

        this.tab--;

        return this.calcTmp.potentialDbService = Math.max(this.getDateDiff(this.getDate1Eligible4DBSection(), this.getChosenPensionDate(), "y") + this.getDateDiff(this.getDate1Eligible4DBSection(), this.getChosenPensionDate(), "ym") / 12, 0)
    },

    /**
     * Row 16
     */
    getAnnualMemberContributionGross: function () {
        if (this.calcTmp.annualMemberContributionGross) {
            return this.calcTmp.annualMemberContributionGross;
        }
        this.tab++;
        /**
         * =Summary!B9*DATA!B4
         */
        this.tab--;
        return this.calcTmp.annualMemberContributionGross = this.options.inputData.pensionable_pay * this.options.inputs.cashContribution;
    },

    /**
     * Row 17
     */
    getAnnualMemberContributionNet: function () {
        if (this.calcTmp.annualMemberContributionNet) {
            return this.calcTmp.annualMemberContributionNet;
        }
        this.tab++;
        /**
         * =Summary!B9*DATA!B4
         */
        this.tab--;
        return this.calcTmp.annualMemberContributionNet = this.getAnnualMemberContributionGross() * 0.68;
    },

    /**
     * Row 19
     */
    getWeeklyTakeHomePayReduction: function () {
        if (this.calcTmp.weeklyTakehomePayReduction) {
            return this.calcTmp.weeklyTakehomePayReduction;
        }
        this.tab++;
        /**
         * =B17/52.14
         */
        this.tab--;

        return this.calcTmp.weeklyTakehomePayReduction = this.getAnnualMemberContributionNet() / 52.14;
    },


    /**
     * Row 21
     */
    getAnnualCompanyContributionGross: function () {
        if (this.calcTmp.annualCompanyContributionGross) {
            return this.calcTmp.annualCompanyContributionGross;
        }
        this.tab++;
        /**
         * =MIN(0.05 * DATA!B4, CALCULATIONS!B16)
         */
        this.tab--;
        return this.calcTmp.annualCompanyContributionGross = Math.min(0.05 * this.options.inputData.pensionable_pay, this.getAnnualMemberContributionGross())
    },

    /**
     * Row 22
     */
    getTotalAnnualContributionGross: function () {
        if (this.calcTmp.totalAnnualContributionGross) {
            return this.calcTmp.totalAnnualContributionGross;
        }
        this.tab++;
        /**
         * B21+B16
         */
        this.tab--;
        return this.calcTmp.totalAnnualContributionGross = this.getAnnualCompanyContributionGross() + this.getAnnualMemberContributionGross();
    },

    /**
     * Row 24
     */
    getWeeklyTotalGrossContribution: function () {
        if (this.calcTmp.weeklyTotalGrossContribution) {
            return this.calcTmp.weeklyTotalGrossContribution;
        }
        this.tab++;
        /**
         * B22/52.14
         */

        this.tab--;
        return this.calcTmp.weeklyTotalGrossContribution = this.getTotalAnnualContributionGross() / 52.14;
    },

    getWeeklyMemberGrossContribution: function () {
        if (this.calcTmp.weeklyMemberGrossContribution) {
            return this.calcTmp.weeklyMemberGrossContribution;
        }
        this.tab++;
        this.tab--;
        return this.calcTmp.weeklyMemberGrossContribution = this.getAnnualMemberContributionGross() / 52.14;
    },

    getWeeklyCompanyGrossContribution: function () {
        if (this.calcTmp.weeklyCompanyGrossContribution) {
            return this.calcTmp.weeklyCompanyGrossContribution;
        }
        this.tab++;
        this.tab--;
        //return this.calcTmp.weeklyCompanyGrossContribution = this.getAnnualCompanyContributionGross() / 52.14;
        return this.calcTmp.weeklyCompanyGrossContribution = this.calcTmp.weeklyTotalGrossContribution - this.calcTmp.weeklyMemberGrossContribution;
    },

    /**
     * Row 26
     */
    getInvestmentReturnAssumption: function () {
        this.tab++;

        this.tab--;
        return this.options.config.investmentReturnAssumption[ this.options.inputs.investmentStrategy];
    },


    /**
     * Row 33D
     */
    getErf: function () {
        if (this.calcTmp.getErf) {
            return this.calcTmp.getErf;
        }
        /**
         * =MOD(E30,1)*E31+(1-MOD(E30,1))*E32
         */

        /**
         ERF0 =MAX(B3-B4,0)
         ERF1 =INDEX(DATA!E17:E32,MATCH(E30,DATA!D17:D32,1)+1);
         ERF2 =INDEX(DATA!E17:E32,MATCH(E30,DATA!D17:D32,1))
         */

        var ERF0 = Math.max(this.getNPA() - this.options.inputs.chosenAgeToRetire, 0),
            ERF1 = this.options.config.erf[ERF0 + 1],
            ERF2 = this.options.config.erf[ERF0]

        this.calcTmp.getErf0 = ERF0;
        this.calcTmp.getErf1 = ERF1;
        this.calcTmp.getErf2 = ERF2;
        return this.calcTmp.getErf = (ERF0 % 1) * ERF1 + (1 - (ERF0 % 1)) * ERF2;

    },

    /**
     * Row 33F
     */
    getCF: function () {
        if (this.calcTmp.getCf) {
            return this.calcTmp.getCf;
        }
        /**
         * =MOD(B4,1)*H32+(1-MOD(B4,1))*H31
         */

        /**
         CF1 =INDEX(DATA!B17:B37,MATCH(B4,DATA!A17:A37,1))
         CF2 =INDEX(DATA!B17:B37,MATCH(B4,DATA!A17:A37,1)+1)
         */

        var CF1 = this.options.config.commutation_factor[this.options.inputs.chosenAgeToRetire],
            CF2 = this.options.config.commutation_factor[this.options.inputs.chosenAgeToRetire + 1];

        this.calcTmp.getCf1 = CF1;
        this.calcTmp.getCf2 = CF2;
        return this.calcTmp.getCf = (this.options.inputs.chosenAgeToRetire % 1) * CF2 + (1 - (this.options.inputs.chosenAgeToRetire % 1)) * CF1;
    },

    /**
     * Row 39
     */
    getAccumulatedFundAtRetirement: function (type) {
        if (this.calcTmp.accumulatedFundAtRetirement) {
            return this.calcTmp.accumulatedFundAtRetirement;
        }
        this.tab++;
        /**
         * =(1-(1+B26)^-B5)/LN(1+B26)*(1+B26)^B5*B22+DATA!B5*(1+B26)^B5
         */
        this.tab--;

        if (type == 1) {
            return this.calcTmp.accumulatedFundAtRetirement = (1 - Math.pow(1 + this.getInvestmentReturnAssumption(), -this.getTerm2ChosenRetirement()))
                /
                Math.log(1 + this.getInvestmentReturnAssumption()) *
                Math.pow(1 + this.getInvestmentReturnAssumption(),
                    this.getTerm2ChosenRetirement()) *
                this.getTotalAnnualContributionGross() +

                this.options.inputData.current_fund * Math.pow(1 + this.getInvestmentReturnAssumption(), this.getTerm2ChosenRetirement())
        } else {
            return this.calcTmp.accumulatedFundAtRetirement = (1 - Math.pow(1 + this.getInvestmentReturnAssumption(), -this.getRemainingTermUntilEligible4DBSection()))
                /
                Math.log(1 + this.getInvestmentReturnAssumption()) *
                Math.pow(1 + this.getInvestmentReturnAssumption(),
                    this.getTerm2ChosenRetirement()) *
                this.getTotalAnnualContributionGross() +

                this.options.inputData.current_fund * Math.pow(1 + this.getInvestmentReturnAssumption(), this.getTerm2ChosenRetirement())
        }
    },


    /**
     * Row 44
     */
    getResidualFundValue: function (type) {
        if (this.calcTmp.residualFundValue) {
            return this.calcTmp.residualFundValue;
        }
        this.tab++;
        if (type == 1) {
            /**
             * =B39-Summary!B24
             */
            this.tab--;
            return this.calcTmp.residualFundValue = this.getAccumulatedFundAtRetirement(1) - this.options.inputs.cashTaken;
        } else {
            /**
             * =MAX(E39-E42,0)
             */
            return this.calcTmp.residualFundValue = Math.max(this.getAccumulatedFundAtRetirement(2) - this.options.inputs.cashTaken, 0);
        }
    },
    /**
     * Row 46
     */
    getFundConverted2Pension: function (type) {
        if (this.calcTmp.fundConverted2Pension) {
            return this.calcTmp.fundConverted2Pension;
        }
        this.tab++;
        if (type == 1) {
            /**
             * B44/ (B33 = Annuity factor)
             */
            this.tab--;
            return this.calcTmp.fundConverted2Pension = this.getResidualFundValue(1) / this.options.config.annuity[ this.options.inputs.chosenAgeToRetire]
        } else {
            /**
             * =E44/B33
             */
            return this.calcTmp.fundConverted2Pension = this.getResidualFundValue(2) / this.options.config.annuity[ this.options.inputs.chosenAgeToRetire]
        }
    },

    /**
     * Row 48
     */
    getSchemePension: function (type) {
        if (this.calcTmp.schemePension) {
            return this.calcTmp.schemePension;
        }
        this.tab++;
        if (type == 1) {
            this.tab--;
            return this.calcTmp.schemePension = this.getFundConverted2Pension(1);
        } else {
            this.tab--;
            return this.calcTmp.schemePension = this.getFundConverted2Pension(2) + this.getDBPension()
        }
    },


    /**
     * Row 40
     */
    getDBPension: function () {
        if (this.calcTmp.dBPension) {
            return this.calcTmp.dBPension;
        }

        /**
         * =MIN(DATA!B4,DATA!B7)*B13*B14*E33
         */
        return this.calcTmp.dBPension =
            Math.min(this.options.inputData.pensionable_pay, this.options.config.db_earnings_ca)
                * this.options.config.dbAccrual[ this.options.inputs.schemeMembership] // B13
                * this.getPotentialDbService() // B14
                * this.getErf(); // E33

    },


    /**
     * Row 41
     */
    getMaximumCash: function (type) {
        if (this.calcTmp.maximumCash) {
            return this.calcTmp.maximumCash;
        }

        if (type == 1) {
            return this.calcTmp.maximumCash = this.getAccumulatedFundAtRetirement() * 0.25;
        } else {
            /**
             * =IF(E40<0.15*E39,5*E40+0.25*E39,(H33*E40+E39)/(0.15*H33+1))
             */

            if (this.getDBPension() < 0.15 * this.getAccumulatedFundAtRetirement(2)) {
                return this.calcTmp.maximumCash = 5 * this.getDBPension() + 0.25 * this.getAccumulatedFundAtRetirement(2);
            } else {
                return this.calcTmp.maximumCash = (this.getCF() * this.getDBPension() + this.getAccumulatedFundAtRetirement(2)) / (0.15 * this.getCF() + 1);
            }

        }
    },

    calculate: function () {

        this.options.result.age = this.getMemberAge().total;

        if (this.options.config.min_ret_age < this.getMemberAge().years){
            this.options.config.min_ret_age = this.getMemberAge().years + 1;
        }
        this.options.result.accumulated_fund = this.options.inputData.current_fund;
        this.options.result.doj = this.options.inputData.doj;
        this.options.result.pensionable_pay = this.options.inputData.pensionable_pay;


        if (this.isEligible4DBSectionInFuture() == false) {
            this.options.result.scheme_pension = this.getSchemePension(1);
            this.options.result.maximum_cash = this.getMaximumCash(1);
            this.options.result.fund_at_retirement = this.getAccumulatedFundAtRetirement(1);
        } else {
            if (this.options.inputs.schemeMembership == 'bronze') {
                this.options.result.scheme_pension = this.getSchemePension(1);
                this.options.result.maximum_cash = this.getMaximumCash(1);
                this.options.result.fund_at_retirement = this.getAccumulatedFundAtRetirement(1);
            } else {
                this.options.result.scheme_pension = this.getSchemePension(2);
                this.options.result.maximum_cash = this.getMaximumCash(2);
                this.options.result.fund_at_retirement = this.getAccumulatedFundAtRetirement(2);
            }
        }

        if(this.options.inputs.chosenAgeToRetire < this.getNPA()) {
            this.options.result.state_pension = 0;
        } else {
            this.options.result.state_pension = this.options.config.state_pension;
        }
        //this.options.result.state_pension = this.options.config.state_pension;
        this.options.result.total_income = this.options.result.state_pension + this.options.result.scheme_pension;
        this.options.result.reductions = this.getWeeklyTakeHomePayReduction();
        this.options.result.eligible_future = this.isEligible4DBSectionInFuture();
        this.options.result.week_paid_total_amount = this.getWeeklyTotalGrossContribution();
        this.options.result.week_paid_member_amount = this.getWeeklyMemberGrossContribution();
        this.options.result.week_paid_company_amount = this.getWeeklyCompanyGrossContribution();
        this.options.result.pensionable_pay_percentage = this.options.result.total_income / this.options.result.pensionable_pay * 100;
        this.options.result.SPA = this.getNPA();
    }


});


$.Sliders = Class.create({
    init: function (id, config) {
        /**
         * extend/modify options by config
         */
        this.options = jQuery.extend(true, {}, this.options, config || {});

        this.container = $('#' + id);

//        if ( this.container.find('.slider_over')[0])
//        return;
        if ( this.container.find('.slider_over')[0]) {
            $(this.container).find('.slider_over')[0].remove();
        }

        this.createSlider();
    },

    options: {
        min: 0, //276.2,
        max: 100, //329.7,
        value: 50, // 309.6,
        suffix: '',
        prefix: '',
        width: 180,
        onUpdate: function () {
        }
    },

    update: function (prop, value) {

        this.options[prop] = value;
        //console.log(this.options);

        if (prop == 'max') {
            this.container.find('.title.right').html(this.options.prefix + numberWithCommas(this.options.max) + this.options.suffix);
            var obj = this.container.find('.slider_over'),
                marker = obj.find('span.marker'),
                inner = obj.find('span.inner'),
                px1 = (parseInt(this.options.max) - parseInt(this.options.min)) / (parseInt(obj.css('width')) - parseInt($(marker).css('width')) + 1);

            marker.attr('px1', px1);

            if (this.options.value > this.options.max) {
                this.options.value = this.options.max;

                $(this.container.find('.num')).html(this.options.prefix + numberWithCommas(this.options.value) + this.options.suffix + '<b></b>');

                this.options.onUpdate(this.options.value);
            }

            marker.css({left: (this.options.value - parseInt(this.options.min)) / px1});
            inner.css({width: parseInt(marker.css('left')) + 5});
        }
    },

    createSlider: function () {
        var self = this;
        var html =
                '<span class="slider_over">' +
                    '<span class="bg"></span><span class="inner"></span><span class="marker"><span class="num"> ' + this.options.prefix + this.options.value + this.options.suffix + '<b></b></span></span>' +
                    '<span class="cover_left"></span>' +
                    '<span class="cover_right"></span>' +
                    '</span>',
            obj = $(html);


        this.container.append(obj);

        var marker = $(obj.find('span.marker')),
            inner = obj.find('span.inner'),
            px1 = (parseInt(this.options.max) - parseInt(this.options.min)) / (parseInt(this.options.width) - parseInt(22) + 1);

        marker.attr('px1', px1);



        var containmentType;

        var ParentDiv = this.container.parents('.sll3'),
            offsetX =  21,
            containmentType = [ ParentDiv.offset().left + offsetX , 0, ParentDiv.offset().left + (this.options.width - 19) + offsetX, 0 ];


        marker.draggable({
            containment: containmentType,
            axis: "x",
            drag: function (p) {
                var px1 = $(this).attr('px1'),
                    val = Math.round((parseInt($(this).css('left')) * px1 + parseInt(self.options.min)));

                if ($(this).closest('#sliderWrapper2').length){
                    val = Math.round((parseInt($(this).css('left')) * px1 + parseInt(self.options.min))*2)/2;
                }

                //alert("val" + val);

                if (val > self.options.max) {
                    val = self.options.max;
                } else if (val < self.options.min) {
                    val = self.options.min;
                }



                $($(this).find('.num')).html(self.options.prefix + numberWithCommas(val) + self.options.suffix + '<b></b>');
                self.options.value = val;

                //console.log(self.options);

                inner.css({width: parseInt($(this).css('left')) + 5});
                self.options.onUpdate(val);

            }

        });

        marker.css({
            left: (self.options.value - parseInt(self.options.min)) / px1
        });
        inner.css({width: parseInt(marker.css('left')) + 5});
        $(this.container.find('.slider_over')).append($('<span class="title left">' + self.options.prefix + numberWithCommas(self.options.min) + self.options.suffix + '</span><span class="title right">' + self.options.prefix + numberWithCommas(self.options.max) + self.options.suffix + '</span>'));

    }
});


utils = {
    DATE: function (year, month, day) {
        return year + '-' + month + '-' + day;
    },
    YEAR: function (dateString) {
        var arr = dateString.split('-');
        return arr[0];
    },
    MONTH: function (dateString) {
        var arr = dateString.split('-');
        return arr[1];
    },
    DAY: function (dateString) {
        var arr = dateString.split('-');
        return arr[2];
    },
    DATE2NUM: function (dateString) {
        var arr = dateString.split('-');
        return parseInt(arr[0] + '' + arr[1] + '' + arr[2]);
    }
}



function numberWithCommas(x) {
    x = Math.round(x * 100) / 100;
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function sr(s, n) {
    var a = [];
    while (a.length < n) {
        a.push(s);
    }
    return a.join('');
}

/**Parses string formatted as YYYY-MM-DD to a Date object.
 * If the supplied string does not match the format, an 
 * invalid Date (value NaN) is returned.
 * @param {string} dateStringInRange format YYYY-MM-DD, with year in
 * range of 0000-9999, inclusive.
 * @return {Date} Date object representing the string.
 */

  function parseISO8601(dateStringInRange) {
    var isoExp = /^\s*(\d{4})-(\d\d)-(\d\d)\s*$/,
        date = new Date(NaN), month,
        parts = isoExp.exec(dateStringInRange);

    if(parts) {
      month = +parts[2];
      date.setFullYear(parts[1], month - 1, parts[3]);
      if(month != date.getMonth() + 1) {
        date.setTime(NaN);
      }
    }
    return date;
  }