'use strict';

define(['js/angular/app/app'], function (app) {

    var injectParams = ['$scope', '$rootScope', '$http', '$sce', '$modal', '$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService'];
    var RealEstateController = function ($scope, $rootScope, $http, $sce, $modal, $location, $filter, $window, $timeout, authService, dataService, modalService) {

        $http.get("system/angular_request/getRealEstateSetting").success(function (res) {
            console.log(res.data);
            $scope.lists = res.data;
        });
        /**
         * load index data
         */
        $http.get("system/angular_request/getRealEstateList").success(function (res) {
            $scope.requests = res.data;

            $scope.editWindow_real_estate = function (id, index) {
                $scope.files = [];

                function initWin(response) {
                    $scope.edit = response;

                    $scope.panel = {};
                    $scope.panel.tab = 1;

                    $scope.panel.selectTab = function (setTab) {
                        $scope.panel.tab = setTab;
                    };
                    $scope.panel.isSelected = function (checkTab) {
                        return $scope.panel.tab === checkTab;
                    };

                    modalService.showModal({}, {
                        scope: $scope,
                        templateUrl: '/app/modules/real_estates/views/editModal.ctp?r=' + Math.random()
                    }).then(function (result) {

                        if (result === 'submit') {
                            $http.post("system/angular_request/saveRealEstateDetail", $scope.edit).success(function (response) {
                                if (typeof index == "undefined") {
                                    $scope.edit.RealEstateModel.id = response.data['id'];
                                    $scope.requests.push({"RealEstateModel": $scope.edit.RealEstateModel});
                                } else {
                                    $scope.requests[index].RealEstateModel.name = $scope.edit.RealEstateModel.name;
                                }
                            });
                        }
                    });
                }

                $scope.window = {};
                if (id == "-1") {
                    $scope.window.title = "Nová nemovitost";
                    initWin({});
                } else {
                    $http.get("system/angular_request/getRealEstateDetail/null/" + id).success(function (response) {
                        $scope.window.title = "Editace nemovitosti";
                        initWin(response.data);
                    })
                }
            };
        })
    }

    RealEstateController.$inject = injectParams;
    app.controller('RealEstateController', RealEstateController);

});
