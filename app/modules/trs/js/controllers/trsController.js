'use strict';

define(['js/angular/app/app'], function (app) {

    var injectParams = ['$scope', '$http', '$sce', '$location', '$filter', '$window',
        '$timeout', 'authService', 'dataService', 'modalService','$rootScope','updateProgressBar'];

    var TrsController = function ($scope, $http, $sce, $location, $filter, $window,
                                  $timeout, authService, dataService, modalService,$rootScope,updateProgressBar) {

        modalService.showLoader();
        $http.get($scope.link).success(function (res) {

            //$scope.html = $sce.trustAsHtml(data.html);
            console.log(res);
            if (res.result === true) {
                $scope.detail = res.data.detail;
                $scope.listings = res.data.detail.items;
            } else {
                console.error('Problem with the request (' + $scope.link + ')');
            }
            modalService.hideLoader();

            $scope.trsSelectNames = ['Yes', 'No'];

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

        $scope.trsSubmit = function(){

            modalService.showLoader();
            /**
             * Save to core
             */
            $http.post("angular_requests/saveTrsSetting/" + $scope.modal_enc_id, $scope.detail.items.benefits).success(function (response) {
                if (response.result) {
                    updateProgressBar.start("trs");

                    window.modalInstance.close('submit');
                } else {
                    alert('Error in saving data to core');
                }
                modalService.hideLoader();
            });
        }
    };





    TrsController.$inject = injectParams;
    //app.register.controller('TrsController', TrsController)
    app.controller('TrsController', TrsController);



});
