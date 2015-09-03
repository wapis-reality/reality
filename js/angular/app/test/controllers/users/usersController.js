'use strict';

define(['app'], function (app) {

    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService'];
    var UsersController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService) {

        $http.get("angular/getJsonData/getUserList").success(function (res) {
            $scope.users = res.data;



            $scope.editWindow_user = function (id, index) {


                $http.get("system/user_edit/" + id).success(function (response) {
                    $scope.htmlEdit = $sce.trustAsHtml(response.html);
                    $scope.UserData = response.data.data;
                    var user = response.data.data.user;

                    $scope.edit = {};
                    $scope.edit.User = {};
                    if(user) {
                        $scope.edit.User.id = user.UserModel.id;
                        $scope.edit.User.first_name = user.UserModel.first_name;
                        $scope.edit.User.last_name = user.UserModel.last_name;
                        $scope.edit.User.image = user.UserModel.image;
                        $scope.edit.User.phone = user.UserModel.phone;
                        $scope.edit.User.company = user.UserModel.company;
                        $scope.edit.User.email = user.UserModel.email;
                        $scope.edit.User.group_id = user.UserModel.group_id;
                        $scope.groups = response.data.data.groups;
                    } else {
                        $scope.edit.User.id = "";
                        $scope.edit.User.first_name = "";
                        $scope.edit.User.last_name = "";
                        $scope.edit.User.image = "";
                        $scope.edit.User.phone = "";
                        $scope.edit.User.company = "";
                        $scope.edit.User.email = "";
                        $scope.edit.User.group_id = "";
                    }

                    modalService.showModal({}, {scope: $scope}).then(function (result) {
                        if (result === 'submit') {
                            $http.post("system/user_edit_save/", $scope.edit).success(function (response) {
                                if(typeof index == "undefined") {
                                    $scope.edit.User.id = response.data;
                                    $scope.users.push({"UserModel":$scope.edit.User});
                                } else {
                                    $scope.users[index].UserModel.first_name = $scope.edit.User.first_name;
                                    $scope.users[index].UserModel.last_name = $scope.edit.User.last_name;
                                }


                            });
                        }
                    });
                });

            }



        });


    }

    UsersController.$inject = injectParams;

    app.controller('UsersController', UsersController);
});
