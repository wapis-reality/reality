'use strict';

define(['js/angular/app/app'], function (app) {
    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window',
        '$timeout', 'authService', 'dataService', 'modalService','updateProgressBar'];

    var ReportsController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService,updateProgressBar) {

        modalService.showLoader();
        $http.get("angular_requests/getReports/"+$scope.modal_enc_id).success(function (res) {
            console.log(res);

            $scope.currentPage = 1;
            $scope.numPerPage = 2;
            $scope.maxSize = 5;
            $scope.itemsPerPage = 2;

            if (res.result === true) {
                $scope.detail = res.data.detail;
                $scope.listings = res.data.detail.items;
            }

            modalService.hideLoader();

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
                });
            };


            $scope.transformScope = [];

            $scope.editWindow_reports = function (id, index) {


                modalService.showLoader();

                $scope.window = {};
                if(id == '') {
                    $scope.window.title = "New Report";
                } else {
                    $scope.window.title = "Edit Report";
                }

                $http.get("angular_requests/getReport/" + $scope.modal_enc_id + "/" + id).success(function (response) {

                    if (response.result) {
                        var res = response.data;
                        console.log(res);
                        $scope.edit = {};


                        $scope.htmlEdit = $sce.trustAsHtml(response.html);
                        $scope.report_select = response.data.report_select;

                        $scope.editWindowReponse = response.data;
                        $scope.edit = {};
                        if($scope.editWindowReponse.report) {
                            $scope.edit.name = $scope.editWindowReponse.report.ReportsModel.name;
                            $scope.edit.report_done = $scope.editWindowReponse.report.ReportsModel.report_done;
                            $scope.edit.email = $scope.editWindowReponse.report.ReportsModel.email;
                            $scope.edit.id = $scope.editWindowReponse.report.ReportsModel.id;
                            $scope.edit.conditions = JSON.stringify($scope.editWindowReponse.conditions);
                            $scope.edit.fields = JSON.stringify($scope.editWindowReponse.fields);
                            $scope.edit.type = $scope.editWindowReponse.report.ReportsModel.type;
                        } else {
                            $scope.edit.name = "";
                            $scope.edit.email = "";
                            $scope.edit.conditions = "";
                            $scope.edit.fields = "";
                            $scope.edit.type = "";
                        }


                        console.log($scope.edit);

                        $scope.edit.scheduled = {};
                        $scope.edit.scheduled.id = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.report.ReportsModel.scheduled : '';
                        $scope.edit.scheduled.minute = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.minute : '';
                        $scope.edit.scheduled.hour = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.hour : '';
                        $scope.edit.scheduled.day_of_month = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.day_of_month : '';
                        $scope.edit.scheduled.month = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.month : '';
                        $scope.edit.scheduled.day_of_week = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.day_of_week : '';
                        $scope.edit.scheduled.active = ($scope.editWindowReponse.scheduled[0]) ? $scope.editWindowReponse.scheduled[0].ReportsSchedulesModel.active : 'empty';


                        modalService.hideLoader();

                        modalService.showModal({}, {
                            scope: $scope,
                            templateUrl: '/app/modules/reports/views/reports/editModal.ctp'
                        }).then(function (result) {
                            if (result === 'submit') {


                                modalService.showLoader();
                                $http.post("angular_requests/setReport/" + $scope.modal_enc_id, $scope.edit).success(function (response) {
                                    console.log(response);
                                    if (typeof index == "undefined") {
                                        $scope.edit.id = response.data;
                                        $scope.edit.report_done = ($scope.edit.report_done) ? "Yes" : "No";
                                        $scope.detail.items.reports.push({"ReportsModel": $scope.edit});
                                    } else {
                                        $scope.detail.items.reports[index].ReportsModel.name = $scope.edit.name;
                                        $scope.detail.items.reports[index].ReportsModel.report_done = ($scope.edit.report_done) ? "Agreed" : "Unconfirmed";
                                        $scope.detail.items.reports[index].ReportsModel.type = $scope.edit.type;
                                        $scope.detail.items.reports[index].ReportsModel.scheduled = $scope.edit.scheduled.hour+ " | " +$scope.edit.scheduled.minute+ " | "+ $scope.edit.scheduled.day_of_month+" | "+$scope.edit.scheduled.month+" | "+$scope.edit.scheduled.day_of_week;

                                    }

                                    updateProgressBar.start("reports");
                                    modalService.hideLoader();

                                });
                            }
                            //modalService.showModal({}, {scope: $scope}).then(function (result) {
                            //    if (result === 'submit') {
                            //        $http.post("/reports/edit/", $scope.edit).success(function (response) {
                            //            console.log(response);
                            //            if (response.status) {
                            //                if (typeof index == "undefined") {
                            //                    $scope.edit.id = response.data;
                            //                    $scope.detail.items.reports.push({"ReportsModel": $scope.edit});
                            //                } else {
                            //                    $scope.detail.items.reports[index].ReportsModel.name = $scope.edit.name;
                            //                }
                            //            }
                            //
                            //        });
                            //    }
                            //});
                        })
                    }
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


                modalService.showLoader();

                var random = Math.floor(Math.random() * 6) + 1;
                $scope.editableClient = {}

                $http.get("angular_requests/getScheduled/" + id).success(function (response) {
                //$http.get("reports/scheduled/" + id).success(function (response) {

                    //$scope.edit = jQuery.parseJSON(response.data);
                    $scope.scheduledWindowReponse = response.data;
                    $scope.scheduled = {};


                    modalService.hideLoader();

                    var modalInstance = $modal.open({
                        //templateUrl: 'app/views/reports/scheduled.ctp?'+random,
                        templateUrl: '/app/modules/reports/views/reports/scheduled.ctp?'+random,
                        scope: $scope,
                        windowClass: "slide-right",
                        controller: [
                            '$scope', '$modalInstance', function($scope, $modalInstance){

                                $scope.submitForm = function () {


                                    //modalService.showLoader();
                                    //
                                    //$http.post("/reports/scheduled/", $scope.edit).success(function (response) {
                                    //    console.log(response);
                                    //    if(response.status) {
                                    //        $modalInstance.close();
                                    //    } else {
                                    //        alert('error');
                                    //    }
                                    //
                                    //
                                    //    modalService.hideLoader();
                                    //});
                                };
                            }
                        ]
                    });
                });
            }
        });



    }

    ReportsController.$inject = injectParams;

    app.controller('ReportsController', ReportsController);

});
