'use strict';

define(['app'], function (app) {
    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window',
        '$timeout', 'authService', 'dataService', 'modalService'];

    var ReportsController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService) {
        $http.get("angular/getJsonData/getReports").success(function (res) {

            $scope.currentPage = 1;
            $scope.numPerPage = 2;
            $scope.maxSize = 5;
            $scope.itemsPerPage = 2;

            $http.get($scope.link).success(function (data) {
                $scope.html = $sce.trustAsHtml(data.html);
                $scope.detail = data.detail;
                $scope.listings = data.detail.items;


                //$scope.paging = data.data.paging;
                //$scope.todos = $scope.paging.total;
                //$scope.buttonsPossibility = data.data.buttonsPossibility;
                //$scope.pagination();

            }).
                error(function (data) {
                    console.log('Error');
                });

            $scope.activeTab = "";
            $scope.activeTabModel = "";
            $scope.activeTabPrefix = "";

            $scope.changeTab = function(tab,model,prefix) {
                console.log(tab);
                console.log($scope.activeTabPrefix);
                $scope.activeTab = tab;
                $scope.activeTabModel = model;
                $scope.activeTabPrefix = prefix;
            }

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

            //var data = JSON.parse(res.data);

            //$scope.data = data.items;
            //$scope.buttonsPossibility = data.buttonsPossibility;
            //$scope.setting = data.setting;
            //$scope.model = data.model;

            $scope.transformScope = [];
            //$scope.editWindow = function (item) {
            //    var test = $(item).attr("data-test")
            //    console.log(test);
            //
            //    item = item.target;
            //    test = item.attributes['data-test'].value;
            //    console.log(test);
            //
            //    var modalOptions = {
            //        templateUrl: 'app/views/reports/test.ctp',
            //        save: "angular/getJsonData/getReports",
            //        get: "angular/getJsonData/getReports",
            //        size: "lg"
            //    };
            //
            //    $scope.transformScope = ['name','id','created'];
            //
            //    modalService.showModal({},modalOptions).then(function(scope) {
            //        console.log("asd");
            //        //$.each($scope.transformScope, function(i,prop){
            //        //    $scope[prop] = scope[prop];
            //        //})
            //
            //        //console.log(scope.data.title);
            //        //console.log("Saved");
            //    });
            //}


            //$scope.editWindow = function(id, prefix, index) {
            //    $scope["editWindow_"+prefix](id,index);
            //}

            $scope.editWindow_reports = function (id, index) {
                $http.get("reports/edit/" + id).success(function (response) {
                    $scope.htmlEdit = $sce.trustAsHtml(response.html);

                    $scope.editWindowReponse = response.data.data;
                    $scope.edit = {};

                    if($scope.editWindowReponse.report.length > 0) {
                        $scope.edit.name = $scope.editWindowReponse.report[0].ReportsModel.name;
                        $scope.edit.email = $scope.editWindowReponse.report[0].ReportsModel.email;
                        $scope.edit.id = $scope.editWindowReponse.report[0].ReportsModel.id;
                        $scope.edit.conditions = JSON.stringify($scope.editWindowReponse.conditions);
                        $scope.edit.fields = JSON.stringify($scope.editWindowReponse.fields);
                    }

                    $scope.edit.scheduled = {};
                    $scope.edit.scheduled.id = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.report[0].ReportsModel.scheduled : '' ;
                    $scope.edit.scheduled.minute = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.minute : '';
                    $scope.edit.scheduled.hour = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.hour : '';
                    $scope.edit.scheduled.day_of_month = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.day_of_month : '';
                    $scope.edit.scheduled.month = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.month : '';
                    $scope.edit.scheduled.day_of_week = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.day_of_week : '';
                    $scope.edit.scheduled.active = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.active : 'empty';

                    modalService.showModal({}, {scope: $scope}).then(function (result) {
                        if (result === 'submit') {
                            $http.post("/reports/edit/", $scope.edit).success(function (response) {
                                if(response.status) {
                                    if(typeof index == "undefined") {
                                        $scope.edit.id = response.data;
                                        $scope.detail.items.reports.push({"ReportsModel":$scope.edit});
                                    } else {
                                        $scope.detail.items.reports[index].ReportsModel.name = $scope.edit.name;
                                    }
                                }

                            });
                        }
                    });
                });


                //var random = Math.floor(Math.random() * 600000) + 1;
                //$scope.editableClient = {}
                //
                //$http.get("reports/edit/" + id).success(function (response) {
                //    /**
                //     * Get the list of intermediaries
                //     */
                //    $scope.editWindowReponse = response.data;
                //    $scope.edit = {};
                //
                //    if($scope.editWindowReponse.report.length <= 0) {
                //        //Add empty if need
                //    } else {
                //        $scope.edit.name = $scope.editWindowReponse.report[0].ReportsModel.name;
                //        $scope.edit.email = $scope.editWindowReponse.report[0].ReportsModel.email;
                //        $scope.edit.id = $scope.editWindowReponse.report[0].ReportsModel.id;
                //        $scope.edit.conditions = JSON.stringify($scope.editWindowReponse.conditions);
                //        $scope.edit.fields = JSON.stringify($scope.editWindowReponse.fields);
                //    }
                //
                //    $scope.edit.scheduled = {};
                //
                //    if($scope.editWindowReponse.scheduled[0]) {
                //        $scope.edit.scheduled.id = $scope.editWindowReponse.report[0].ReportsModel.scheduled;
                //        $scope.edit.scheduled.minute = $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.minute;
                //        $scope.edit.scheduled.hour = $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.hour;
                //        $scope.edit.scheduled.day_of_month = $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.day_of_month;
                //        $scope.edit.scheduled.month = $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.month ;
                //        $scope.edit.scheduled.day_of_week = $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.day_of_week;
                //        $scope.edit.scheduled.active = $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.active;
                //    } else {
                //        $scope.edit.scheduled.id = '';
                //        $scope.edit.scheduled.minute = '';
                //        $scope.edit.scheduled.hour = '';
                //        $scope.edit.scheduled.day_of_month = '';
                //        $scope.edit.scheduled.month = '';
                //        $scope.edit.scheduled.day_of_week = '';
                //        $scope.edit.scheduled.active = 'empty';
                //    }
                //
                //    /**
                //     * Init modal and pass the $scope
                //     */
                //    var modalInstance = $modal.open({
                //        templateUrl: 'app/views/reports/edit.ctp?'+random,
                //        scope: $scope,
                //        size: 'lg',
                //        //windowClass: "slide-right",
                //        controller: [
                //            '$scope', '$modalInstance', function($scope, $modalInstance) {
                //
                //                /**
                //                 * When user submits form
                //                 */
                //                $scope.submitForm = function () {
                //                    /**
                //                     * Save Client on database
                //                     */
                //                    $http.post("/reports/edit/", $scope.edit).success(function (response) {
                //                        /**
                //                         * If we saved correctly, update the $scope.clients
                //                         */
                //                        if(response.status) {
                //                            if(typeof index == "undefined") {
                //                                $scope.edit.id = response.data;
                //                                $scope.detail.items.reports.push({"ReportsModel":$scope.edit});
                //                            } else {
                //                                $scope.detail.items.reports[index].ReportsModel.name = $scope.edit.name;
                //                            }
                //                            $modalInstance.close();
                //                        } else {
                //                            alert('error');
                //                        }
                //                    });
                //                };
                //            }
                //        ]
                //    });
                //
                //});
            }

            $scope.scheduledWindow = function (id) {

                var random = Math.floor(Math.random() * 6) + 1;
                $scope.editableClient = {}

                $http.get("reports/scheduled/" + id).success(function (response) {

                    //$scope.edit = jQuery.parseJSON(response.data);
                    $scope.scheduledWindowReponse = response.data;
                    $scope.scheduled = {};

                    var modalInstance = $modal.open({
                        templateUrl: 'app/views/reports/scheduled.ctp?'+random,
                        scope: $scope,
                        windowClass: "slide-right",
                        controller: [
                            '$scope', '$modalInstance', function($scope, $modalInstance){

                                $scope.submitForm = function () {
                                    $http.post("/reports/scheduled/", $scope.edit).success(function (response) {
                                        console.log(response);
                                        if(response.status) {
                                            $modalInstance.close();
                                        } else {
                                            alert('error');
                                        }
                                    });
                                };
                            }
                        ]
                    });
                });
            }
        });



    }

    ReportsController.$inject = injectParams;

    //app.register.controller('ReportsController', ReportsController)
    app.controller('ReportsController', ReportsController);

});
