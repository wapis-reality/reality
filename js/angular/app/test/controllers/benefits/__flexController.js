'use strict';

define(['app'], function (app) {
    var injectParams = ['$scope','$http','$sce','$modal', '$location', '$filter', '$window',
        '$timeout', 'authService', 'dataService', 'modalService'];

    var FlexController = function ($scope,$http, $sce ,$modal,$location, $filter, $window,
                                      $timeout, authService, dataService, modalService) {


        $scope.currentPage = 1;
        $scope.numPerPage = 2;
        $scope.maxSize = 5;
        $scope.itemsPerPage = 2;
        $scope.activeTab = "";
        $scope.matrix = [];
        $scope.selectedItem = "";


        $scope.updateMatrix = function(selected,event_id,benefit_id) {
            $http.get("/clients/set_matrix/"+selected+"/"+event_id+"/"+benefit_id).success(function (res) {

            });
        }

        $http.get($scope.link).success(function (data) {

            $scope.html = $sce.trustAsHtml(data.html);
            $scope.detail = data.detail;
            $scope.listings = data.detail.items;
            $scope.matrix = $scope.listings.matrix_legend;

            //$scope.paging = data.data.paging;
            //$scope.todos = $scope.paging.total;
            //$scope.buttonsPossibility = data.data.buttonsPossibility;
            //$scope.pagination();



            $scope.editWindow_benefits = function (id, index) {

                $http.get("benefits/edit/" + $scope.modal_enc_id + "/" + id).success(function (response) {

                    console.log(response);

                    if(response.result){

                        var res = response.data;
                        $scope.edit = {};
                        $scope.htmlEdit = $sce.trustAsHtml(res.html);

                        /**
                         * If we are editing an item, we will need to save it into the $scope.edit
                         * Otherwise it means we are adding a new item so $scope.edit will be empty
                         */
                        if(res.data.length > 0){
                            $scope.edit = res.data[0];
                        }

                        modalService.showModal({}, {scope: $scope}).then(function (result) {

                            if (result === 'submit') {

                                /**
                                 * Save to core
                                 */
                                $http.post("benefits/edit/" + $scope.modal_enc_id, $scope.edit).success(function (response) {

                                    if(response.result){
                                        if(typeof index == "undefined") {
                                            $scope.edit.BenefitModel.id = response.data;
                                            $scope.detail.items.benefits.push($scope.edit);
                                        } else {
                                            $scope.detail.items.benefits[index].BenefitModel.name = $scope.edit.BenefitModel.name;
                                        }
                                    } else {
                                        alert('Error in saving data to core');
                                    }
                                });
                            }
                        });

                    } else {
                        alert('Error in receiving both html and data');
                    }
                });
            }

            $scope.editWindow_life_events = function (id, index) {

                $http.get("benefits_events/edit/" + $scope.modal_enc_id + "/" + id).success(function (response) {

                    if(response.result){

                        var res = response.data;
                        $scope.edit = {};
                        $scope.htmlEdit = $sce.trustAsHtml(res.html);

                        /**
                         * If we are editing an item, we will need to save it into the $scope.edit
                         * Otherwise it means we are adding a new item so $scope.edit will be empty
                         */
                        if(res.data.length > 0){
                            $scope.edit = res.data[0];
                        }

                        modalService.showModal({}, {scope: $scope}).then(function (result) {

                            if (result === 'submit') {

                                /**
                                 * Save to core
                                 */
                                $http.post("benefits_events/edit/" + $scope.modal_enc_id, $scope.edit).success(function (response) {

                                    if(response.result){
                                        if(typeof index == "undefined") {
                                            $scope.edit.BenefitEventsModel.id = response.data;
                                            $scope.detail.items.life_events.push($scope.edit);
                                        } else {
                                            $scope.detail.items.life_events[index].BenefitEventsModel.name = $scope.edit.BenefitEventsModel.name;
                                        }
                                    } else {
                                        alert('Error in saving data to core');
                                    }
                                });
                            }
                        });

                    } else {
                        alert('Error in receiving both html and data');
                    }
                });
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

    }
    FlexController.$inject = injectParams;

    //app.register.controller('FlexController', FlexController)
    app.controller('FlexController', FlexController);
});
