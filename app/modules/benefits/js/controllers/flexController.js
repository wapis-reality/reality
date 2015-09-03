'use strict';

define(['js/angular/app/app'], function (app) {
    var injectParams = ['$scope', '$http', '$sce', '$modal', '$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService', 'updateProgressBar', '$rootScope'],
        FlexController = function ($scope, $http, $sce, $modal, $location, $filter, $window,
                                   $timeout, authService, dataService, modalService, updateProgressBar, $rootScope) {

            //values for file drag and drop functionality
            $scope.deleteFile = function (index) {
                $scope.items.splice(index, 1);
            };

            $scope.hideWizard = function (e) {
                e.preventDefault();
            };


            $scope.currentPage = 1;
            $scope.numPerPage = 2;
            $scope.maxSize = 5;
            $scope.itemsPerPage = 2;
            $scope.activeTab = "";
            $scope.matrix = [];
            $scope.selectedItem = "";


            $scope.updateMatrix = function (selected, event_id, benefit_id, flag) {

                modalService.showLoader();
                $http.get("/angular_requests/setMatrix/" + $scope.modal_enc_id + "/" + selected + "/" + event_id + "/" + benefit_id + "/" + flag).success(function (res) {
                    updateProgressBar.start("flex");
                    modalService.hideLoader();
                });
            }



            modalService.showLoader();
            $http.get($scope.link).success(function (res) {

                if (res.result === true) {
                    $scope.detail = res.data.detail;
                    $scope.listings = res.data.detail.items;
                    //$scope.matrix = $scope.listings.matrix_legend;


                } else {
                    console.error('Problem with the request (' + $scope.link + ')');
                }
                modalService.hideLoader();

                //$scope.paging = data.data.paging;
                //$scope.todos = $scope.paging.total;
                //$scope.buttonsPossibility = data.data.buttonsPossibility;
                //$scope.pagination();


                $scope.editWindow_benefits = function (id, index) {
                    /**
                     * Show preloader
                     */
                    modalService.showLoader();

                    /**
                     * default scope
                     */
                    $scope.items = [];
                    $scope.window = {};

                    /**
                     * popup title
                     */
                    if (id == '') {
                        $scope.window.title = "New benefit"
                    } else {
                        $scope.window.title = "Edit benefit"
                    }


                    /**
                     * init inner functions and scope for add & edit
                     * @param dataObj
                     */
                    function initWin(dataObj) {

                        $scope.panel = {};
                        $scope.panel.tab = 1;

                        $scope.panel.selectTab = function (setTab) {
                            $scope.panel.tab = setTab;
                        };
                        $scope.panel.isSelected = function (checkTab) {
                            return $scope.panel.tab === checkTab;
                        };

                        console.log('dataObj');
                        console.log(dataObj);

                        $scope.edit = dataObj;

                        //for file attachments
                        if (typeof dataObj.files == "object") {
                            for (var i = 0; i <= dataObj.files.length - 1; i++) {
                                $scope.items.push(dataObj.files[i]["BenefitFilesModel"].filename);
                            }
                        }
                        $scope.edit.items = $scope.items;

                        /**
                         * Initialising $scope.BenefitRate
                         */
                        $scope.BenefitRate = {};
                        $scope.BenefitRate.genderCheckbox = {};
                        $scope.BenefitRate.eligibilityGroupCheckbox = {};

                        if (!$scope.edit.BenefitEligibilityModel) {
                            $scope.edit.BenefitEligibilityModel = [];
                        }
                        if (!$scope.edit.BenefitCoversModel) {
                            $scope.edit.BenefitCoversModel = [];
                        }
                        if (!$scope.edit.BenefitPensionModel) {
                            $scope.edit.BenefitPensionModel = [];
                        }
                        if (!$scope.edit.BenefitRate) {
                            $scope.edit.BenefitRate = {};
                        }

                        if (!$scope.edit.BenefitRate.rates_list) {
                            $scope.edit.BenefitRate.rates_list = [];
                        }


                        if (!$scope.edit.BenefitRate.price) {
                            $scope.edit.BenefitRate.price = [];
                        }

                        if (!$scope.edit.BenefitRate.rates) {
                            $scope.edit.BenefitRate.rates = [];
                        }

                        if (!$scope.edit.BenefitRate.rateData) {
                            $scope.edit.BenefitRate.rateData = {};
                        }

                        if (!$scope.edit.BenefitRate.ages) {
                            $scope.edit.BenefitRate.ages = [];
                        }

                        if (!$scope.edit.BenefitRate.covers) {
                            $scope.edit.BenefitRate.covers = [];
                        }

                        if (!$scope.edit.BenefitRate.validation) {
                            $scope.edit.BenefitRate.validation = [];
                        }

                        if (!$scope.edit.BenefitRate.gender) {
                            $scope.edit.BenefitRate.gender = [];
                            $scope.edit.BenefitRate.gender.push('None');
                            $scope.BenefitRate.genderCheckbox['None'] = true;
                        }

                        if (!$scope.edit.BenefitRate.eligibilityGroup) {
                            $scope.edit.BenefitRate.eligibilityGroup = [];
                            $scope.edit.BenefitRate.eligibilityGroup.push('All');
                            $scope.BenefitRate.eligibilityGroupCheckbox['All'] = true;
                        }

                        angular.forEach(dataObj.BenefitRate.gender, function (item, index) {
                            $scope.BenefitRate.genderCheckbox[item] = true;
                        });

                        angular.forEach(dataObj.BenefitRate.eligibilityGroup, function (item, index) {
                            $scope.BenefitRate.eligibilityGroupCheckbox[item] = true;
                        });


                        /**
                         * Utility functions to use in the Benefit Rates tab
                         *
                         * @type {{}}
                         */

                        $scope.BenefitRate.genderOptions = [{name: 'None'}, {name: 'Male'}, {name: 'Female'}];

                        if(dataObj.checkboxOptions.eligibility_group_options.length > 0){
                            $scope.BenefitRate.eligibilityGroupOptions = dataObj.checkboxOptions.eligibility_group_options;
                        } else {
                            $scope.BenefitRate.eligibilityGroupOptions = [{name: 'All'}];
                        }


                        /**
                         * Watch the edit.BenefitRate.ages and update the ageSummary when the object changes
                         */
                        $scope.$watch('edit.BenefitRate.ages', function (newVal, oldVal) {

                            if ($scope.edit.BenefitRate.ages.length > 0) {

                                $scope.BenefitRate.ageSummary = "[";
                                angular.forEach($scope.edit.BenefitRate.ages, function (item, index) {
                                    if ($scope.edit.BenefitRate.ages.length == 1) {
                                        $scope.BenefitRate.ageSummary += item.ageFrom + "-" + item.ageTo;
                                    } else {
                                        if (index == 0) {
                                            $scope.BenefitRate.ageSummary += item.ageFrom + "-" + item.ageTo;
                                        } else {
                                            $scope.BenefitRate.ageSummary += ", " + item.ageFrom + "-" + item.ageTo;
                                        }
                                    }
                                });
                                $scope.BenefitRate.ageSummary += "]";
                            } else {
                                $scope.BenefitRate.ageSummary = "[]";
                            }

                        }, true);

                        /**
                         * Watch the edit.BenefitRate.eligibilityGroup and update the eligibilityGroupSummary when the object changes
                         */
                        $scope.$watch('edit.BenefitRate.eligibilityGroup', function (newVal, oldVal) {

                            if ($scope.edit.BenefitRate.eligibilityGroup.length > 0) {

                                $scope.BenefitRate.eligibilityGroupSummary = "[";
                                angular.forEach($scope.edit.BenefitRate.eligibilityGroup, function (item, index) {
                                    if ($scope.edit.BenefitRate.eligibilityGroup.length == 1) {
                                        $scope.BenefitRate.eligibilityGroupSummary += item;
                                    } else {
                                        if (index == 0) {
                                            $scope.BenefitRate.eligibilityGroupSummary += item;
                                        } else {
                                            $scope.BenefitRate.eligibilityGroupSummary += ", " + item;
                                        }
                                    }
                                });
                                $scope.BenefitRate.eligibilityGroupSummary += "]";
                            } else {
                                $scope.BenefitRate.eligibilityGroupSummary = "[]";
                            }

                        }, true);

                        /**
                         * Watch the edit.BenefitRate.gender and update the genderSummary when the object changes
                         */
                        $scope.$watch('edit.BenefitRate.gender', function (newVal, oldVal) {

                            if ($scope.edit.BenefitRate.gender.length > 0) {

                                $scope.BenefitRate.genderSummary = "[";
                                angular.forEach($scope.edit.BenefitRate.gender, function (item, index) {
                                    if ($scope.edit.BenefitRate.gender.length == 1) {
                                        $scope.BenefitRate.genderSummary += item;
                                    } else {
                                        if (index == 0) {
                                            $scope.BenefitRate.genderSummary += item;
                                        } else {
                                            $scope.BenefitRate.genderSummary += ", " + item;
                                        }
                                    }
                                });
                                $scope.BenefitRate.genderSummary += "]";
                            } else {
                                $scope.BenefitRate.genderSummary = "[]";
                            }

                        }, true);

                        /**
                         * Update the data objects ($scope.BenefitRate.eligibilityGroupCheckbox, $scope.edit.BenefitRate.eligibilityGroup) when the user selects any checkbox
                         *
                         * @param key
                         * @param type
                         */
                        $scope.BenefitRate.eligibilityGroupCheckboxChange = function (key, type) {

                            $scope.edit.BenefitRate.eligibilityGroup = [];
                            var all_selected = true;

                            /**
                             * If the user selects 'All', then untick the other checkboxes and update the $scope.BenefitRate.genderCheckbox
                             */
                            if (type.name == 'All' && $scope.BenefitRate.eligibilityGroupCheckbox['All'] == true) {
                                $scope.edit.BenefitRate.eligibilityGroup = [];
                                $scope.edit.BenefitRate.eligibilityGroup.push('All');
                                $scope.BenefitRate.eligibilityGroupCheckbox['All'] = true;
                                angular.forEach($scope.BenefitRate.eligibilityGroupCheckbox, function (item, key) {

                                    if (key != 'All') {
                                        $scope.BenefitRate.eligibilityGroupCheckbox[key] = false;
                                    }
                                });
                                return;
                            }

                            /**
                             * Check which checkboxes have been selected and update the $scope.BenefitRate.genderCheckbox
                             */
                            angular.forEach($scope.BenefitRate.eligibilityGroupCheckbox, function (item, key) {

                                if (item == true && key != 'All') {
                                    all_selected = false;
                                    $scope.BenefitRate.eligibilityGroupCheckbox['All'] = false;
                                    $scope.edit.BenefitRate.eligibilityGroup.push(key);
                                }

                            });

                            /**
                             * If no checkbox has been selected, select the 'None' checkbox
                             */
                            if (all_selected) {
                                $scope.edit.BenefitRate.eligibilityGroup = [];
                                $scope.edit.BenefitRate.eligibilityGroup.push('All');
                                $scope.BenefitRate.eligibilityGroupCheckbox['All'] = true;
                                angular.forEach($scope.BenefitRate.eligibilityGroupCheckbox, function (item, key) {

                                    if (key != 'All') {
                                        $scope.BenefitRate.eligibilityGroupCheckbox[key] = false;
                                    }
                                });
                            }

                        };

                        /**
                         * Update the data objects ($scope.BenefitRate.genderCheckbox, $scope.edit.BenefitRate.gender) when the user selects any checkbox
                         *
                         * @param key
                         * @param type
                         */
                        $scope.BenefitRate.genderCheckboxChange = function (key, type) {

                            $scope.edit.BenefitRate.gender = [];
                            var none_selected = true;

                            /**
                             * If the user selects 'None', then untick the other checkboxes and update the $scope.BenefitRate.genderCheckbox
                             */
                            if (type.name == 'None' && $scope.BenefitRate.genderCheckbox['None'] == true) {
                                $scope.edit.BenefitRate.gender = [];
                                $scope.edit.BenefitRate.gender.push('None');
                                $scope.BenefitRate.genderCheckbox['None'] = true;
                                angular.forEach($scope.BenefitRate.genderCheckbox, function (item, key) {

                                    if (key != 'None') {
                                        $scope.BenefitRate.genderCheckbox[key] = false;
                                    }
                                });
                                return;
                            }

                            /**
                             * Check which checkboxes have been selected and update the $scope.BenefitRate.genderCheckbox
                             */
                            angular.forEach($scope.BenefitRate.genderCheckbox, function (item, key) {

                                if (item == true && key != 'None') {
                                    none_selected = false;
                                    $scope.BenefitRate.genderCheckbox['None'] = false;
                                    $scope.edit.BenefitRate.gender.push(key);
                                }

                            });

                            /**
                             * If no checkbox has been selected, select the 'None' checkbox
                             */
                            if (none_selected) {
                                $scope.edit.BenefitRate.gender = [];
                                $scope.edit.BenefitRate.gender.push('None');
                                $scope.BenefitRate.genderCheckbox['None'] = true;
                                angular.forEach($scope.BenefitRate.genderCheckbox, function (item, key) {

                                    if (key != 'None') {
                                        $scope.BenefitRate.genderCheckbox[key] = false;
                                    }
                                });
                            }

                        };


                        $scope.updateAge = function (idx, eItem) {
                            $scope.edit.BenefitRate.ages[idx] = eItem;
                        };

                        $scope.rateGroups = [
                            'directors',
                            'non-directors'
                        ];

//                        $scope.rateData = {
//                            'directors':{
//                                'Cover Level 1': {
//                                    '1-2': {
//                                        'M': {
//                                            'ee': 0
//                                        },
//
//                                        'F': {
//                                            'ee': 0
//                                        }
//                                    }
//                                }
//                            }
//                        };


//                        $scope.$watch('edit.BenefitRate.ages', function (newVal, oldVal) {
//
//                            $scope.rateData = [];
//
//                        }, true);
//
//                        $scope.$watch('edit.BenefitRate.eligibilityGroup', function (newVal, oldVal) {
//
//                            $scope.rateData = [];
//
//                        }, true);
//E
//                        $scope.$watch('edit.BenefitRate.gender', function (newVal, oldVal) {
//
//                            $scope.rateData = [];
//
//                        }, true);

                        $scope.cleanRateData = function (id) {
//                            console.log('cleanRateData');
//                            console.log(id);
//
//                            angular.forEach($scope.rateData, function (item, index) {
//                                if(id != index){
//                                    $scope.rateData.splice(index);
//                                }
//                            });
                        }

                        $scope.rateGender = ['M', 'F'];
                        $scope.rateCoverLevel =
                        {
                            covers: [
                                {
                                    name: 'Cover Level 1',
                                    validation: 'employee only'

                                },
                                {
                                    name: 'Cover Level 2',
                                    validation: 'employee only'

                                }
                            ]
                        };

                        $scope.ageGroups_open = false;
                        $scope.open_ageGroups = function () {
                            if ($scope.ageGroups_open) {
                                $scope.ageGroups_open = false;
                            } else {
                                $scope.ageGroups_open = true;
                                $scope.eligibilityGroups_open = false;
                                $scope.genderGroups_open = false;
                            }
                        };

                        $scope.eligibilityGroups_open = false;
                        $scope.open_eligibilityGroups = function () {
                            if ($scope.eligibilityGroups_open) {
                                $scope.eligibilityGroups_open = false;
                            } else {
                                $scope.eligibilityGroups_open = true;
                                $scope.ageGroups_open = false;
                                $scope.genderGroups_open = false;
                            }
                        };

                        $scope.genderGroups_open = false;
                        $scope.open_genderGroups = function () {
                            if ($scope.genderGroups_open) {
                                $scope.genderGroups_open = false;
                            } else {
                                $scope.genderGroups_open = true;
                                $scope.eligibilityGroups_open = false;
                                $scope.ageGroups_open = false;
                            }
                        };

                        // $scope.edit.BenefitModel.benefitUniqueId = $scope.edit.BenefitModel.name + $scope.edit.BenefitModel.provider;

                        //$scope.recalc_rates = function () {
                        //    $scope.edit.BenefitRate.rates_list = [];
                        //
                        //    angular.forEach($scope.edit.BenefitRate.rates, function (rate, rIndex) {
                        //        if ($scope.edit.BenefitRate.ages.length == 0 && $scope.gender.length == 0) {
                        //
                        //            var newObj = {
                        //                'coverIndex': rIndex,
                        //                'coverLevel': rate.coverLevel
                        //            }
                        //
                        //            $scope.edit.BenefitRate.rates_list.push(newObj);
                        //
                        //            //} else if ($scope.edit.BenefitRate.ages.length == 0 && $scope.gender.length > 0){
                        //            //    angular.forEach( $scope.gender, function(gend){
                        //            //        edit.BenefitRate.rates_list.push(rate);
                        //            //    })
                        //            //} else if ($scope.edit.BenefitRate.ages.length > 0 && $scope.gender.length == 0){
                        //        } else {
                        //            //console.log($scope.edit.BenefitRate.ages);
                        //            angular.forEach($scope.edit.BenefitRate.ages, function (age, aIndex) {
                        //                //rate.ageFrom = age.ageFrom;
                        //                var newObj = {
                        //                    'coverIndex': rIndex,
                        //                    'ageIndex': aIndex,
                        //                    'coverLevel': rate.coverLevel
                        //                }
                        //                $scope.edit.BenefitRate.rates_list.push(newObj);
                        //                console.log(newObj);
                        //            })
                        //        }
                        //        //else {
                        //        //    angular.forEach( $scope.gender, function(gend){
                        //        //        angular.forEach( $scope.edit.BenefitRate.ages, function(age){
                        //        //            edit.BenefitRate.rates_list.push(rate);
                        //        //        })
                        //        //    })
                        //        //}
                        //
                        //    });
                        //
                        //    //console.log(edit.BenefitRate.rates_list);
                        //    //$scope.edit.BenefitRate.rates_list = edit.BenefitRate.rates_list;
                        //
                        //}
                        //$scope.checkPriceList = function () {
                        //    angular.forEach($scope.edit.BenefitRate.rates, function (rObj, rIndex) {
                        //        console.log('row:' + rIndex);
                        //        if (!$scope.edit.BenefitRate.price[rIndex]) {
                        //            $scope.edit.BenefitRate.price.push({});
                        //        }
                        //        angular.forEach($scope.edit.BenefitRate.ages, function (lObj, aIndex) {
                        //            console.log('cell:' + aIndex);
                        //            if (!$scope.edit.BenefitRate.price[rIndex][aIndex]) {
                        //                $scope.edit.BenefitRate.price[rIndex][aIndex] = {eeRate: 0, erRate: 0};
                        //            }
                        //        })
                        //
                        //    });
                        //}

                        $scope.removeAllRowAges = function () {
                            $scope.edit.BenefitRate.ages = [];
                        };

                        $scope.removeRowAges = function (agesIndex) {

//                            console.log('removeRowAges');
//                            var removeKey = $scope.edit.BenefitRate.ages[agesIndex].key;
//                            console.log('removeKey' + removeKey);

                            $scope.edit.BenefitRate.ages.splice(agesIndex, 1);

//                            angular.forEach($scope.rateData, function (item1, index1) {
//
//                                if(item1.key == key){
//                                    console.log('found it');
//                                }
//
//                                angular.forEach($scope.rateData[index1], function (item2, index2) {
//
//                                });
//                            });
                        };

                        $scope.removeRowCovers = function (coversIndex) {

//                            console.log($scope.rateData);
//
//                            console.log('removeRowCovers');
//                            var removeKey = $scope.edit.BenefitRate.covers[coversIndex].key;
//                            console.log('removeKey' + removeKey);

                            $scope.edit.BenefitRate.covers.splice(coversIndex, 1);

//                            angular.forEach($scope.rateData, function (item1, index1) {
//
//                                console.log('--- first foreach');
//                                console.log(index1);
//                                console.log(item1);
//                                console.log(removeKey);
//
//                                angular.forEach($scope.rateData[index1], function (item2, index2) {
//
//                                    console.log('--- second foreach');
//                                    console.log(index2);
//                                    console.log(item2);
//                                    console.log(removeKey);
//
//                                    if(index2 == removeKey){
//                                        delete $scope.rateData[index1][index2];
//                                        if(angular.equals({}, $scope.rateData[index1])){
//                                            delete $scope.rateData[index1];
//                                        }
//                                    }
//
//                                });
//                            });

                        };

                        $scope.removeRowRates = function (ratesIndex) {
                            $scope.edit.BenefitRate.rates.splice(ratesIndex, 1);
                            $scope.edit.BenefitRate.price.splice(ratesIndex, 1);
                            //  $scope.recalc_rates();
                        };

                        $scope.clearFlex = function () {
                            $scope.edit.BenefitModel.selection_type = null;
                            $scope.edit.BenefitModel.display_option = null;
                            $scope.edit.BenefitModel.minimum = null;
                            $scope.edit.BenefitModel.maximum = null;
                            $scope.edit.BenefitModel.increment = null;
                            $scope.edit.BenefitModel.salary_multiple_cap = null;
                            $scope.edit.BenefitModel.renewal_selection = null;
                            $scope.edit.BenefitModel.termination = null;
                            $scope.edit.BenefitModel.charging = null;
                            $scope.edit.BenefitModel.default = null;
                            $scope.edit.BenefitModel.salarySacChecked = null;
                            $scope.edit.BenefitModel.taxFreeChecked = null;
                            $scope.edit.BenefitModel.nIFreeChecked = null;
                            $scope.edit.BenefitModel.p11DReportChecked = null;
                            $scope.edit.BenefitModel.sameLevel = null;
                            $scope.edit.BenefitModel.stepType = null;
                            $scope.edit.BenefitModel.step_up_per_year = null;
                            $scope.edit.BenefitModel.step_up_per_enrol = null;
                            $scope.edit.BenefitModel.step_down_per_year = null;
                            $scope.edit.BenefitModel.step_down_per_enrol = null;
                            $scope.edit.BenefitModel.fundingValue = null;
                            $scope.edit.BenefitModel.tradeDownChecked = null;
                            $scope.edit.BenefitModel.trade = null;
                            $scope.edit.BenefitModel.spouseBenefitChecked = null;
                            $scope.edit.BenefitModel.stepRestrictionChecked = null;
                            $scope.edit.BenefitModel.fundingChecked = null;
                            $scope.edit.BenefitModel.tradeDownChecked = null;
                            $scope.edit.BenefitRate.ages = [];
                            $scope.edit.BenefitRate.rates = [];
                            $scope.edit.BenefitRate.price = [];
                            $scope.edit.BenefitRate.validation = [];
                            $scope.edit.BenefitRate.ratesList = [];
                            $scope.edit.BenefitModel.penSalaryDefinition = null;
                            $scope.edit.BenefitModel.holSalaryDefinition = null;
                            $scope.edit.BenefitModel.maximumTotalDays = null;
                            $scope.edit.BenefitModel.minimumTotalDays = null;
                            $scope.edit.BenefitModel.penAdvancedChecked = null;
                            $scope.edit.BenefitModel.holAdvancedChecked = null;
                            $scope.edit.BenefitModel.core_amount = null;
                            $scope.edit.BenefitModel.initialDefault = null;
                            $scope.edit.BenefitModel.ageBasis = null;
                        };

                        $scope.clearSelectionType = function () {
                            // exclude Pension (14) and Holidays (9) from executing
                            if ($scope.edit.BenefitModel.benefit_type != '14' || $scope.edit.BenefitModel.benefit_type != '9') {
                                $scope.edit.BenefitModel.display_option = null;
                                $scope.edit.BenefitModel.minimum = null;
                                $scope.edit.BenefitModel.maximum = null;
                                $scope.edit.BenefitModel.increment = null;
                                $scope.edit.BenefitModel.salary_multiple_cap = null;
                                $scope.edit.BenefitModel.penSalaryDefinition = null;
                                $scope.edit.BenefitModel.holSalaryDefinition = null;
                                $scope.edit.BenefitModel.maximumTotalDays = null;
                                $scope.edit.BenefitModel.minimumTotalDays = null;
                                $scope.edit.BenefitModel.penAdvancedChecked = null;
                                $scope.edit.BenefitModel.holAdvancedChecked = null;
                            }

                        };

                        $scope.clearSpouse = function () {
                            $scope.edit.BenefitModel.sameLevel = null;
                        };

                        $scope.clearStep = function () {
                            $scope.edit.BenefitModel.stepType = null;
                            $scope.edit.BenefitModel.step_up_per_year = null;
                            $scope.edit.BenefitModel.step_up_per_enrol = null;
                            $scope.edit.BenefitModel.step_down_per_year = null;
                            $scope.edit.BenefitModel.step_down_per_enrol = null;
                        };

                        $scope.clearStepType = function () {
                            $scope.edit.BenefitModel.step_up_per_year = null;
                            $scope.edit.BenefitModel.step_up_per_enrol = null;
                            $scope.edit.BenefitModel.step_down_per_year = null;
                            $scope.edit.BenefitModel.step_down_per_enrol = null;
                        };
                        $scope.clearFunding = function () {
                            $scope.edit.BenefitModel.fundingValue = null;
                            $scope.edit.BenefitModel.tradeDownChecked = null;
                            $scope.edit.BenefitModel.trade = null;
                        };

                        $scope.clearTrade = function () {
                            $scope.edit.BenefitModel.trade = null;
                        };

                        $scope.clearAges = function () {
                            $scope.edit.BenefitRate.ages = null;
                            $scope.edit.BenefitRate.ages.ageFrom = null;
                            $scope.edit.BenefitRate.ages.ageTo = null;
                        };

                        $scope.gender = [];

                        $scope.addRowAges = function (ages) {
                            $scope.edit.BenefitRate.ages.push({'ageFrom': 0, 'ageTo': 0, 'key': new Date().getTime()});
                        };

                        $scope.addRowCovers = function (covers) {
                            $scope.edit.BenefitRate.covers.push({'coverName': '', 'validation': '', 'key': new Date().getTime()});

                        };
                        $scope.addRowRates = function (rates) {
                            $scope.edit.BenefitRate.rates.push({
                                'coverLevel': $scope.coverLevel,
                                'coverBand': $scope.coverBand,
                                'selectVal': $scope.selectVal,
                                'ageFrom': $scope.ageFrom,
                                'ageTo': $scope.ageTo,
                                'selectGender': $scope.selectGender,
                                'eeRate': $scope.eeRate,
                                'erRate': $scope.erRate
                            });
                            // $scope.recalc_rates();
                            //  $scope.checkPriceList();
                        };

                        $scope.selectOptions = [];
                        angular.forEach(dataObj.selectOptions, function (sobj, sIndex) {
                            $scope.selectOptions[sIndex] = sobj;
                        });

                        $scope.selectionTypes = ['Cover Levels', 'Type-In'];
                        $scope.displayNames = ['Drop Down', 'Radio Buttons', 'Grid'];
                        $scope.catNames = ['Protection', 'Wellbeing', 'Lifestyle', 'Finance'];
                        $scope.calcNames = ['Rate Lookup', 'Multiple of Salary', 'Value / 12', 'Other'];
                        $scope.renewalNames = ['Saved', 'Default'];
                        $scope.terminationNames = ['ProRata', 'Start of Period', 'End of Period', 'Recover Full Cost'];
                        $scope.chargingNames = ['Monthly', 'Annually'];
                        $scope.valNames = ['None', 'Employee Only', 'Couple', 'Family', 'Single Parent Family'];
                        $scope.genderNames = ['Male', 'Female'];
                        $scope.varNames = ['Age', 'Salary', 'Join Date'];
                        $scope.logicNames = ['>', '>=', '=', '!=', '<', '<='];
                        $scope.sameLevelNames = ['Yes', 'No'];
                        $scope.sameTypeNames = ['Up', 'Down', 'Both'];
                        $scope.tradeNames = ['Cash Lost', 'Cash Gain', 'Flex Fund Gain'];
                        $scope.penSalNames = ['Reference Salary', 'Pensionable Salary'];
                        $scope.holSalNames = ['Reference Salary', 'Holiday Salary'];
                        $scope.ageBasisNames = ['At Scheme Start', 'At Scheme End', 'At Next Birthday', 'At 1st of the Next Month'];
                        //$scope.recalc_rates();

                        $scope.addRowConditions = function () {
                            $scope.edit.BenefitEligibilityModel.push({'variable': '', 'symbol': '', 'value': ''});
                        };

                        $scope.removeRowConditions = function (conditionsIndex) {
                            $scope.edit.BenefitEligibilityModel.splice(conditionsIndex, 1);
                        };

                        $scope.addRowPension = function () {
                            $scope.edit.BenefitPensionModel.push({'eligibility': '', 'eevalue': '', 'symbol': '', 'ervalue': ''});
                        };

                        $scope.removeRowPension = function (PensionsIndex) {
                            $scope.edit.BenefitPensionModel.splice(PensionsIndex, 1);
                        };

                        ///**
                        // * When user submits form
                        // */
                        //$scope.submitForm = function () {
                        //    console.log($scope.edit);
                        //    /**
                        //     * Save Client on database
                        //     */
                        //    $http.post("/reports/edit/", $scope.edit).success(function (response) {
                        //        /**
                        //         * If we saved correctly, update the $scope.clients
                        //         */
                        //        if (response.status) {
                        //            $scope.data[id].id = $scope.edit.id
                        //            $modalInstance.close();
                        //
                        //        } else {
                        //
                        //            alert('error');
                        //
                        //        }
                        //    });
                        //};


                        modalService.showModal({}, {
                            scope: $scope,
                            templateUrl: '/app/modules/benefits/views/benefits/editModal.ctp?r=' + Math.random()
                        }).then(function (result) {


                            if (result === 'submit') {

                                modalService.showLoader();
                                /**
                                 * Save to core
                                 */
                                $http.post("angular_requests/saveBenefitItem/" + $scope.modal_enc_id, $scope.edit).success(function (response) {
                                    modalService.hideLoader();
                                    if (response.result) {
                                        console.log(response.result);

                                        updateProgressBar.start("trs");
                                        updateProgressBar.start("flex");

                                        if (typeof index == "undefined") {
                                            //$scope.edit.BenefitModel.id = response.data;
                                            //$scope.detail.items.benefits.push($scope.edit);
                                            $scope.detail.items.benefits.push(response.data);
                                        } else {
                                            $scope.detail.items.benefits[index].BenefitModel.name = $scope.edit.BenefitModel.name;
                                            $scope.detail.items.benefits[index].BenefitModel.provider = response.data.BenefitModel.provider;
                                            $scope.detail.items.benefits[index].BenefitModel.category = response.data.BenefitModel.category;
                                            $scope.detail.items.benefits[index].BenefitModel.benefit_type = response.data.BenefitModel.benefit_type;
                                            $scope.detail.items.benefits[index].BenefitModel.flex = response.data.BenefitModel.flex;
                                        }
                                    } else {
                                        alert('Error in saving data to core');
                                    }

                                    /*  $timeout(function () {

                                     }, 500);*/
                                });
                            }
                        });
                    }

                    $http.get("angular_requests/findBenefitItem/" + $scope.modal_enc_id + "/" + id).success(function (response) {

                        if (response.result) {
                            var res = response.data;
                            initWin(res);
                        } else {
                            alert('Error in receiving both html and data');
                        }

                        modalService.hideLoader();
                    });
                }

                $scope.editWindow_eligibility_groups = function (id, index) {
                    var modal = function () {

                        modalService.showModal({}, {
                            scope: $scope,
                            templateUrl: '/app/modules/benefits/views/eligibility_groups/editModal.ctp'
                        }).then(function (result) {

                            if (result === 'submit') {

                                modalService.showLoader();
                                /**
                                 * Save to core
                                 * @todo not done
                                 */
                                $http.post("angular_requests/saveEligibilityGroup/" + $scope.modal_enc_id, $scope.edit).success(function (response) {
                                    if (response.result) {
                                        updateProgressBar.start("flex");

                                        if (typeof index == "undefined") {
                                            $scope.edit.BenefitEligibilityGroupModel.id = response.data;
                                            $scope.detail.items.eligibility_groups.push($scope.edit);
                                        } else {
                                            $scope.detail.items.eligibility_groups[index].BenefitEligibilityGroupModel.name = $scope.edit.BenefitEligibilityGroupModel.name;
                                            $scope.detail.items.eligibility_groups[index].BenefitEligibilityGroupModel.description = $scope.edit.BenefitEligibilityGroupModel.description.split(/\s+/).slice(0,20).join(" ");
                                        }
                                    } else {
                                        alert('Error in saving data to core');
                                    }
                                    modalService.hideLoader();
                                });
                            } else {
                                modalService.hideLoader();
                            }
                        });
                    }


                    if (id == '') {
                        $scope.edit = {};
                        $scope.window = {};
                        $scope.window.title = "New eligibility group"
                        modal();
                    } else {
                        $http.get("angular_requests/findEligibilityGroup/" + $scope.modal_enc_id + "/" + id).success(function (response) {
                            console.log(response);
                            if (response.result) {
                                $scope.edit = response.data;
                                $scope.window = {};
                                $scope.window.title = "Edit eligibility group"
                                modal();
                            } else {
                                alert('Error');
                            }
                        });
                    }
                }


                $scope.editWindow_life_events = function (id, index) {
                    var modal = function () {
                        modalService.showModal({}, {
                            scope: $scope,
                            templateUrl: '/app/modules/benefits/views/benefit_events/editModal.ctp'
                        }).then(function (result) {
                            if (result === 'submit') {


                                modalService.showLoader();

                                /**
                                 * Save to core
                                 */
                                $http.post("angular_requests/saveBenefitLifeEvent/" + $scope.modal_enc_id, $scope.edit).success(function (response) {
                                    if (response.result) {

                                        updateProgressBar.start("flex");

                                        if (typeof index == "undefined") {
                                            $scope.edit.BenefitEventsModel.id = response.data;
                                            $scope.detail.items.life_events.push($scope.edit);
                                        } else {
                                            $scope.detail.items.life_events[index].BenefitEventsModel.name = $scope.edit.BenefitEventsModel.name;
                                            $scope.detail.items.life_events[index].BenefitEventsModel.description = $scope.edit.BenefitEventsModel.description.split(/\s+/).slice(0,20).join(" ");
                                        }
                                    } else {
                                        alert('Error in saving data to core');
                                    }

                                    modalService.hideLoader();
                                });
                            }
                        });
                    }

                    $scope.window = {};

                    if (id == '') {
                        $scope.window.title = "New Life Event";
                        $scope.edit = {};
                        modal();
                    } else {

                        modalService.showLoader();
                        $scope.window.title = "Edit Life Event";
                        $http.get("angular_requests/findBenefitLifeEvent/" + $scope.modal_enc_id + "/" + id).success(function (response) {
                            if (response.result) {
                                $scope.edit = response.data;
                                modal();
                            } else {
                                alert('Error');
                            }


                            modalService.hideLoader();
                        });
                    }
                }

            }).error(function (data) {
                console.log('Error');
            });

            //pagination bootstrap component. This function will update the record according to page number and other options
            $scope.pagination = function () {
                $scope.$watch('currentPage + numPerPage', function () {
                    var begin = (($scope.currentPage - 1) * $scope.numPerPage)
                        , end = begin + $scope.numPerPage;

                    //$http.get($scope.currentUrl.replace("/index", "/get_data") + "/?page=" + $scope.currentPage)
                    //    .success(function (data) {
                    //        $scope.listings = data.data.global_items;
                    //    });

                });
            };


        };

    FlexController.$inject = injectParams;
    app.controller('FlexController', FlexController);


    app.directive("fileRead", function ($http) {
        return {
            scope: false,
            link: function (scope, element, attributes) {
                element.bind("change", function (changeEvent) {
                    //send request here
                    $http({
                        method: 'POST',
                        url: '/classes/upload.php',
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        data: {
                            upload: event.target.files
                        },
                        transformRequest: function (data, headersGetter) {
                            var formData = new FormData();
                            angular.forEach(data, function (value, key) {
                                angular.forEach(value, function (value1, key1) {
                                    formData.append(key1, value1);
                                });
                            });
                            var headers = headersGetter();
                            delete headers['Content-Type'];
                            return formData;
                        }
                    })
                        .success(function (data) {
                            if(data){
                                for(var j = 0; j < data.length; j++){
                                    scope.edit.items.push(data[j]);
                                }
                            }
                        })
                        .error(function (data) {
                            console.log("error");
                        });
                });
            }
        }
    });

});