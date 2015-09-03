'use strict';

define(['app'], function (app) {

    var injectParams = ['$scope','$http','$sce','$location', '$filter', '$window',
        '$timeout', 'authService', 'dataService', 'modalService'];

    var TrsController = function ($scope,$http,$sce,$location, $filter, $window,
                                      $timeout, authService, dataService, modalService) {

        $http.get($scope.link).success(function (data) {
            $scope.html = $sce.trustAsHtml(data.html);
            $scope.detail = data.detail;
            $scope.listings = data.detail.items;

        }).error(function (data) {
            console.log('Error');
        });

    }

    TrsController.$inject = injectParams;

    //app.register.controller('TrsController', TrsController)
    app.controller('TrsController', TrsController);
});
