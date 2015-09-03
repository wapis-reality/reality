'use strict';

define(['app'], function (app) {

    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService'];
    var PermissionController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService) {

        $http.get("angular/getJsonData/getPermissions").success(function (res) {
            $scope.permissions = res.data;

            $scope.editWindow_permission = function (id, index) {

                $http.get("system/permission_edit/" + id).success(function (response) {
                    $scope.htmlEdit = $sce.trustAsHtml(response.html);
                    $scope.permissionData = response.data.data[0];
                    $scope.edit = {};
                    $scope.edit.Group = {};

                    if($scope.permissionData) {
                        $scope.edit.Group.id = $scope.permissionData.UserGroupsModel.id;
                        $scope.edit.Group.name = $scope.permissionData.UserGroupsModel.name;
                    } else {
                        $scope.edit.Group.id = "";
                        $scope.edit.Group.name = "";
                    }


                    modalService.showModal({}, {scope: $scope}).then(function (result) {
                        if (result === 'submit') {
                            $http.post("system/permission_edit_save/", $scope.edit).success(function (response) {
                                if(typeof index == "undefined") {
                                    $scope.permissions.push({"UserGroupsModel":$scope.edit.Group});
                                } else {
                                    $scope.permissions[index].UserGroupsModel.name = $scope.edit.Group.name;
                                }


                            });
                        }
                    });
                });

            }


        });


    }

    PermissionController.$inject = injectParams;

    app.controller('PermissionController', PermissionController);
});
