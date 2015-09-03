'use strict';

define(['js/angular/app/app'], function (app) {
    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window',

        '$timeout', 'authService', 'dataService', 'modalService','updateProgressBar','$rootScope'];

    var FaqController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService,updateProgressBar,$rootScope) {

        $scope.getData = function() {
            $http.get("angular_requests/getFaq/"+$scope.modal_enc_id).success(function (res) {

                modalService.hideLoader();
                console.log(res);

                $scope.currentPage = 1;
                $scope.numPerPage = 2;
                $scope.maxSize = 5;
                $scope.itemsPerPage = 2;

                if (res.result === true) {
                    $scope.detail = res.data.detail;
                    $scope.listings = res.data.detail.items;
                }

                $scope.activeTab = "";
                $scope.activeTabModel = "";
                $scope.activeTabPrefix = "";

                $scope.changeTab = function (tab, model, prefix) {

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
            });
            };


                //$scope.transformScope = [];

                $scope.editWindow_faq = function (id, index){

                    modalService.showLoader();
                    function initWin(response) {
                        $scope.edit = response;
                    }
                    $http.get("system/angular_request/getFaqItem/null/" + id).success(function (response) {
                        $scope.window = {};

                        if(id == "") {
                            initWin({"FaqItemsModel":{}});
                            $scope.window.title = "New FAQ";
                        } else {
                            initWin(response.data);
                            $scope.window.title = "Edit FAQ";
                        }
                        modalService.hideLoader();

                        var modalInstance = $modal.open({
                            templateUrl: '/app/modules/faq/views/editModal.ctp?r=' + Math.random(),
                            controller: ModalInstanceCtrl,
                            scope: $scope,
                            backdrop: 'static',
                            keyboard: false,
                            size: 'lg',
                            resolve: {
                                userForm: function () {
                                    return $scope.userForm;
                                }
                            }
                        });
                    });
                };

                var ModalInstanceCtrl = function ($scope, $modalInstance, userForm) {
                    $scope.form = {}
                    $scope.submitForm = function () {
                        $scope.showErrorsCheckValidity = true;
                        if ($scope.form.userForm.$valid) {

                            console.log($scope.edit);

                            modalService.showLoader();
                            $http.post("system/angular_request/setFaq/"+$scope.modal_enc_id, $scope.edit).success(function (response) {
                                if(typeof $scope.edit.FaqItemsModel.id == "undefined") {
                                    $scope.edit.FaqItemsModel.id = response.data;
                                    $scope.listings.faq.push({"FaqItemsModel":$scope.edit.FaqItemsModel});
                                } else {
                                    $scope.getData();
                                }

                                updateProgressBar.start("faq");
                                modalService.hideLoader();
                            });

                            $modalInstance.close('closed');
                        } else {
                            console.log('userform is not in scope');
                        }
                    };
                };


        modalService.showLoader();
        $scope.getData();

        }

    FaqController.$inject = injectParams;

    app.controller('FaqController', FaqController);

});
