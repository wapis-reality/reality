'use strict';

define(['js/angular/app/app'], function (app) {

    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService'];
    var UsersController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService) {

        $http.get("system/angular_request/getUserList").success(function (res) {
            $scope.users = res.data;


            $scope.editWindow_user = function (id, index) {

                function initWin(response) {
                    $scope.edit = response;
                }

                $http.get("system/angular_request/getUser/null/"+id).success(function (response) {

                    console.log(response);
                    $scope.window = {};

                    if(id == "-1") {
                        $scope.window.title = "New User";
                        initWin({"user":{"UserModel":{}},"groups":response.data.groups,"clients":response.data.clients});
                    } else {
                        $scope.window.title = "Edit User";
                        initWin(response.data);
                    }

                    $scope.save = function($modalInstance) {

                            if(typeof angular.fromJson($scope.edit.user.UserModel.group_id) != "undefined" && typeof angular.fromJson($scope.edit.user.UserModel.group_id) != "number") {
                                var permission = angular.fromJson($scope.edit.user.UserModel.group_id).name
                                $scope.edit.user.UserModel.group_id = angular.fromJson($scope.edit.user.UserModel.group_id).id;
                            } else {
                                var permission = "";
                            }

                            if(typeof angular.fromJson($scope.edit.user.UserModel.client_id) != "undefined" && typeof angular.fromJson($scope.edit.user.UserModel.client_id) != "number") {

                                var client = angular.fromJson($scope.edit.user.UserModel.client_id).name
                                $scope.edit.user.UserModel.client_id = angular.fromJson($scope.edit.user.UserModel.client_id).id;
                            } else {
                                var client = "";
                            }


                            $http.post("system/angular_request/saveUser", $scope.edit).success(function (response) {

                                if(!response.data.error) {
                                    if(typeof index == "undefined") {
                                        $scope.edit.user.UserModel.id = response.data;
                                        if(typeof permission != "undefined") {
                                            $scope.edit.user.UserModel.permission = permission;
                                        }
                                        if(typeof client != "undefined") {
                                            $scope.edit.user.UserModel.client = client;
                                        }

                                        $scope.users.push({"UserModel":$scope.edit.user.UserModel});
                                    } else {
                                        $scope.users[index].UserModel.first_name = $scope.edit.user.UserModel.first_name;
                                        $scope.users[index].UserModel.last_name = $scope.edit.user.UserModel.last_name;
                                        $scope.users[index].UserModel.email = $scope.edit.user.UserModel.email;
                                        if(typeof permission != "undefined" && permission != "") {
                                            $scope.users[index].UserModel.permission = permission;
                                        }

                                        if(typeof client != "undefined" && client != "") {
                                            $scope.users[index].UserModel.client = client;
                                        }
                                    }

                                    $modalInstance.close('submit');

                                } else {
                                    alert(response.data.message);
                                }
                            });
                    }

                    modalService.showModal({}, {
                        scope: $scope,
                        templateUrl: '/app/modules/users/views/editModal.ctp?r=' + Math.random()
                    }).then(function (result) {



                        //if (result === 'submit') {
                        //    if(typeof angular.fromJson($scope.edit.user.UserModel.group_id) != "undefined") {
                        //        var permission = angular.fromJson($scope.edit.user.UserModel.group_id).name
                        //        $scope.edit.user.UserModel.group_id = angular.fromJson($scope.edit.user.UserModel.group_id).id;
                        //    } else {
                        //        var permission = "";
                        //        $scope.edit.user.UserModel.group_id = "";
                        //    }
                        //
                        //    if(typeof angular.fromJson($scope.edit.user.UserModel.client_id) != "undefined") {
                        //        var client = angular.fromJson($scope.edit.user.UserModel.client_id).name
                        //        $scope.edit.user.UserModel.client_id = angular.fromJson($scope.edit.user.UserModel.client_id).id;
                        //    } else {
                        //        var client = "";
                        //        $scope.edit.user.UserModel.client_id = "";
                        //    }
                        //
                        //    $http.post("system/angular_request/saveUser", $scope.edit).success(function (response) {
                        //
                        //        console.log(response);
                        //        if(!response.data.error) {
                        //            if(typeof index == "undefined") {
                        //                $scope.edit.user.UserModel.id = response.data;
                        //                $scope.users.push({"UserModel":$scope.edit.user.UserModel});
                        //            } else {
                        //                $scope.users[index].UserModel.first_name = $scope.edit.user.UserModel.first_name;
                        //                $scope.users[index].UserModel.last_name = $scope.edit.user.UserModel.last_name;
                        //                $scope.users[index].UserModel.email = $scope.edit.user.UserModel.email;
                        //                if(typeof permission != "undefined") {
                        //                    $scope.users[index].UserModel.permission = permission;
                        //                }
                        //                if(typeof client != "undefined") {
                        //                    $scope.users[index].UserModel.client = client;
                        //                }
                        //            }
                        //        } else {
                        //
                        //            alert(response.data.message);
                        //        }
                        //    });
                        //}
                    });
                });

            }





        });


    }

    UsersController.$inject = injectParams;

    app.controller('UsersController', UsersController);
});
