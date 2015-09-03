'use strict';

define(['js/angular/app/app'], function (app) {

    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService'];
    var LoginController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService) {
            $scope.login = {};
            $scope.login.email = "";
            $scope.login.password = "";

            $scope.editWindow_login = function (id, index) {
                $http.post("/system/login/", $scope.login).success(function (response) {
                    if(response.result) {
                        location.href = response.data;
                    } else {
                        alert(response.message);
                    }
                });
            }
    }

    LoginController.$inject = injectParams;

    app.controller('LoginController', LoginController);
});
