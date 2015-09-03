'use strict';

define(['js/angular/app/app'], function (app) {

    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService'];
    var PermissionController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService) {

        $http.get("system/angular_request/getPermissions").success(function (res) {
            $scope.permissions = res.data;

            $scope.editWindow_permission = function (id, index) {

                function initWin(response) {
                    console.log(response);
                    $scope.edit = response;
                }

                $http.get("system/angular_request/getPermissions/null/" + id).success(function (response) {
                    if(id == "-1") {
                        initWin({"UserGroupsModel":{}});
                    } else {
                        initWin(response.data);
                    }

                    modalService.showModal({}, {
                        scope: $scope,
                        templateUrl: '/app/modules/permission/views/editModal.ctp?r=' + Math.random()
                    }).then(function (result) {
                        if (result === 'submit') {
                            $http.post("system/angular_request/setPermission", $scope.edit).success(function (response) {
                                if(typeof index == "undefined") {
                                    $scope.edit.UserGroupsModel.id = response.data;
                                    $scope.permissions.push({"UserGroupsModel":$scope.edit.UserGroupsModel});
                                } else {
                                    $scope.permissions[index].UserGroupsModel.name = $scope.edit.UserGroupsModel.name;
                                }
                            });
                        }
                    });
                });
            }


            $scope.editWindow_permission_info = function (id, index) {

                $http.get("system/angular_request/getPermissions/null/" + id).success(function (response) {
                    $scope.edit = response.data;

                    modalService.showModal({}, {
                        scope: $scope,
                        templateUrl: '/app/modules/permission/views/infoModal.ctp?r=' + Math.random()
                    }).then(function (result) {
                        if (result === 'submit') {

                        }
                    });
                });
            }

        });
    }

    PermissionController.$inject = injectParams;

    app.controller('PermissionController', PermissionController);
});
