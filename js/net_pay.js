(function ($) {
    $.pay_net = Class.create({
        inputs: {
            annual_income: 26000,
            age: 70,
            student_load: false,
            blind: false,
            ni_deduction: 0,
            tax_deduction: 0,
            sex: 'M',
            flex: 0,
            year: '2015'
        },
        setting: {
            symbol: '£',
            2014: {
                allowance: {
                    level1_limit: 100000,
                    level2_limit: 120000,
                    level1_value: 10000
                },
                tax: {
                    level1_limit: 31865,
                    level2_limit: 150000,
                    level1_value: 20,
                    level2_value: 40,
                    level3_value: 45
                },

                ni: {
                    level1_limit: 7956,
                    level2_limit: 41860,
                    level1_value: 0,
                    level2_value: 12,
                    level3_value: 2
                },
                spa: {
                    M: 65,
                    F: 60
                }
            },
            2015: {
                allowance: {
                    level1_limit: 100000,
                    level2_limit: 121200,
                    level1_value: 10600
                },
                tax: {
                    level1_limit: 31785,
                    level2_limit: 150000,
                    level1_value: 20,
                    level2_value: 40,
                    level3_value: 45
                },

                ni: {
                    level1_limit: 8060,
                    level2_limit: 42380,
                    level1_value: 0,
                    level2_value: 12,
                    level3_value: 2
                },

                spa: {
                    M: 65,
                    F: 60
                }
            }
        },

        results: {},

        get_allowance: function () {
            if (this.inputs.year == '2014') {
                return this.get_allowance_2014();
            } else if (this.inputs.year == '2015') {
                return this.get_allowance_2015();
            }
        },

        get_allowance_2015: function () {
            var age = this.inputs.age,
                allowance = 0,
                sal = this.inputs.annual_income - this.inputs.tax_deduction;

            if (age < 78) {
                if (sal < this.setting[this.inputs.year].allowance.level1_limit) {
                    /**
                     * If you are earning up to Â£100,000
                     */
                    allowance = this.setting[this.inputs.year].allowance.level1_value;
                } else if (sal < this.setting[this.inputs.year].allowance.level2_limit) {
                    /**
                     * If you are earning between  Â£100,000 and Â£121,200
                     * Decreases from Â£10,600 by 50% of every pound you earn (above Â£100,000) until it reaches Â£0
                     */
                    allowance = this.setting[this.inputs.year].allowance.level1_value - (sal - this.setting[this.inputs.year].allowance.level1_limit) / 2;
                } else {
                    /**
                     * If you are earning Â£121,200 or more
                     */
                    allowance = 0;
                }
            } else {
                if (sal < 27700) {
                    /**
                     * If you are earning up to Â£27,700
                     */
                    allowance = 10660;
                } else if (sal < 27820) {
                    /**
                     * If you are earning between Â£27,700 and Â£27,820
                     * Decreases from Â£10,660 by 50% of every pound you earn (above Â£27,700) until it reaches Â£10,600
                     */
                    allowance = 10660 - (sal - 27700) / 2;
                } else if (sal < 100000) {
                    /**
                     * If you are earning from Â£27,820 to Â£100,000
                     */
                    allowance = 10660;
                } else if (sal < 121200) {
                    /**
                     * If you are earning between  Â£100,000 and Â£121,200
                     */
                    allowance = this.setting[this.inputs.year].allowance.level1_value - (sal - this.setting[this.inputs.year].allowance.level1_limit) / 2;
                } else {
                    /**
                     * If you are earning Â£121,200 or more
                     */
                    allowance = 0;
                }
            }

            return allowance;
        },

        get_allowance_2014: function () {
            var age = this.inputs.age,
                allowance = 0,
                sal = this.inputs.annual_income - this.inputs.tax_deduction;

            if (age < 65) {
                if (sal < this.setting[this.inputs.year].allowance.level1_limit) {
                    /**
                     * If you are earning up to Â£100,000
                     */
                    allowance = this.setting[this.inputs.year].allowance.level1_value;
                } else if (sal < this.setting[this.inputs.year].allowance.level2_limit) {
                    /**
                     * If you are earning between  Â£100,000 and Â£120,000
                     * Decreases from Â£10,000 by 50% of every pound you earn (above Â£100,000) until it reaches Â£0
                     */
                    allowance = this.setting[this.inputs.year].allowance.level1_value - (sal - this.setting[this.inputs.year].allowance.level1_limit) / 2;
                } else {
                    /**
                     * If you are earning Â£120,000 or more
                     */
                    allowance = 0;
                }
            } else if (age < 75) {
                if (sal < 27000) {
                    /**
                     * If you are earning up to Â£27,000
                     */
                    allowance = 10500;
                } else if (sal < 28000) {
                    /**
                     * If you are earning between Â£27000  and Â£28,000
                     * Decreases from Â£10,500 by 50% of every pound you earn (above Â£27,000) until it reaches Â£10,000
                     */
                    allowance = 10500 - (sal - 27000) / 2;
                } else if (sal < 100000) {
                    /**
                     * If you are earning from Â£28,000 to Â£100,000
                     */
                    allowance = 10000;
                } else if (sal < 120000) {
                    /**
                     * If you are earning between  Â£100,000 and Â£120,000
                     * Decreases from Â£10,000 by 50% of every pound you earn (above Â£100,000) until it reaches Â£0
                     */
                    allowance = this.setting[this.inputs.year].allowance.level1_value - (sal - this.setting[this.inputs.year].allowance.level1_limit) / 2;
                } else {
                    /**
                     * If you are earning Â£120,000 or more
                     */
                    allowance = 0;
                }
            } else {
                if (sal < 27000) {
                    /**
                     * If you are earning up to Â£27,000
                     */
                    allowance = 10660;
                } else if (sal < 28320) {
                    /**
                     * If you are earning between Â£27,000 and Â£28,320
                     * Decreases from Â£10,660 by 50% of every pound you earn (above Â£27,000) until it reaches Â£10,000
                     */
                    allowance = 10660 - (sal - 27700) / 2;
                } else if (sal < 100000) {
                    /**
                     * If you are earning from Â£28,320 to Â£100,000
                     */
                    allowance = 10000;
                } else if (sal < 120000) {
                    /**
                     * If you are earning between  Â£100,000 and Â£120,000
                     * Decreases from Â£10,000 by 50% of every pound you earn (above Â£100,000) until it reaches Â£0
                     */
                    allowance = this.setting[this.inputs.year].allowance.level1_value - (sal - this.setting[this.inputs.year].allowance.level1_limit) / 2;
                } else {
                    /**
                     * If you are earning Â£120,000 or more
                     */
                    allowance = 0;
                }
            }

            return allowance;

        },

        get_tax: function () {
            var allowance = this.get_allowance(),
                sal = Math.max(this.inputs.annual_income - this.inputs.tax_deduction - allowance, 0),
                tax = 0;


            if (sal < this.setting[this.inputs.year].tax.level1_limit) {
                tax = sal / 100 * this.setting[this.inputs.year].tax.level1_value;
            } else if (sal < this.setting[this.inputs.year].tax.level2_limit) {
                tax =
                    this.setting[this.inputs.year].tax.level1_limit / 100 * this.setting[this.inputs.year].tax.level1_value +
                    (sal - this.setting[this.inputs.year].tax.level1_limit) / 100 * this.setting[this.inputs.year].tax.level2_value
            } else {
                tax =
                    this.setting[this.inputs.year].tax.level1_limit / 100 * this.setting[this.inputs.year].tax.level1_value +
                    (this.setting[this.inputs.year].tax.level2_limit - this.setting[this.inputs.year].tax.level1_limit) / 100 * this.setting[this.inputs.year].tax.level2_value +
                    (sal - this.setting[this.inputs.year].tax.level2_limit) / 100 * this.setting[this.inputs.year].tax.level3_value
            }

            return tax;
        },

        get_ni: function () {
            var sal = this.inputs.annual_income - this.inputs.ni_deduction,
                ni = 0;

            if (sal < this.setting[this.inputs.year].ni.level1_limit) {
                ni = sal / 100 * this.setting[this.inputs.year].ni.level1_value;
            } else if (sal < this.setting[this.inputs.year].ni.level2_limit) {
                ni =
                    this.setting[this.inputs.year].ni.level1_limit / 100 * this.setting[this.inputs.year].ni.level1_value +
                    (sal - this.setting[this.inputs.year].ni.level1_limit) / 100 * this.setting[this.inputs.year].ni.level2_value;
            } else {
                ni =
                    this.setting[this.inputs.year].ni.level1_limit / 100 * this.setting[this.inputs.year].ni.level1_value +
                    (this.setting[this.inputs.year].ni.level2_limit - this.setting[this.inputs.year].ni.level1_limit) / 100 * this.setting[this.inputs.year].ni.level2_value +
                    (sal - this.setting[this.inputs.year].ni.level2_limit) / 100 * this.setting[this.inputs.year].ni.level3_value;
            }

            return ni;
        },


        calculate: function () {

            var ni = this.get_ni(),
                tax = this.get_tax(),
                take_home = 0;

            // if the person is over spa, doesn't pay ni
            if (this.inputs.age >= this.setting[this.inputs.year].spa[this.inputs.sex]) {
                ni = 0;
            }

            take_home = this.inputs.annual_income - ni - tax;

            this.results = {
                personal_allowance: parseFloat(this.get_allowance()),
                total_national_insurance: parseFloat(ni),
                totalIncomeTax: parseFloat(tax),
                take_home: parseFloat(take_home)
            }
        },

        number_format: function (num) {
            return num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        },

        update: function (ni_deduction, tax_deduction, total) {
            this.inputs.ni_deduction = ni_deduction;
            this.inputs.tax_deduction = tax_deduction;
            this.inputs.flex = total;
            this.calculate();
            this.draw();

            return this.results;
        },

        draw: function () {
            $('#net-pay-nic').html(this.setting.symbol + this.number_format(this.results.total_national_insurance / 12));
            $("#net-pay-paye").html(this.setting.symbol + this.number_format(this.results.totalIncomeTax / 12));
            $("#net-pay-flex").html(this.setting.symbol + this.number_format(this.inputs.flex / 12));

            $("#net-pay-total").html(this.setting.symbol + this.number_format((this.results.take_home - this.inputs.flex) / 12));
        }
    });

}(jQuery));