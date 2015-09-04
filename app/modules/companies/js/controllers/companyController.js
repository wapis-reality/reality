'use strict';

define(['js/angular/app/app'], function (app) {

    var injectParams = ['$scope', '$rootScope', '$http', '$sce', '$modal', '$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService'];
    var CompanyController = function ($scope, $rootScope, $http, $sce, $modal, $location, $filter, $window, $timeout, authService, dataService, modalService) {

        //values for file drag and drop functionality
        $scope.deleteFile = function (index) {
            $scope.files.splice(index, 1);
        };

        /**
         * load index data
         */
        $http.get("system/angular_request/getCompanyList").success(function (res) {
            $scope.requests = res.data;

            $scope.editWindow_company = function (id, index) {
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


                    if (!$scope.edit.CompanyContactModel) {
                        $scope.edit.CompanyContactModel = [];
                    }

                    $scope.add_contact_person = function () {
                        $scope.edit.CompanyContactModel.push({
                            first_name: '',
                            last_name: '',
                            phone: '',
                            email: ''
                        });
                    }

                    $scope.remove_contact_person = function (index) {
                        $scope.edit.CompanyContactModel.splice(index, 1);
                    }

                    modalService.showModal({}, {
                        scope: $scope,
                        templateUrl: '/app/modules/companies/views/editModal.ctp?r=' + Math.random()
                    }).then(function (result) {

                        if (result === 'submit') {
                            $http.post("system/angular_request/saveCompanyDetail", $scope.edit).success(function (response) {
                                if (typeof index == "undefined") {
                                    $scope.edit.CompanyModel.id = response.data;
                                    $scope.requests.push({"CompanyModel": $scope.edit.CompanyModel});
                                } else {
                                    $scope.requests[index].CompanyModel.name = $scope.edit.CompanyModel.name;
                                    $scope.requests[index].CompanyModel.city = $scope.edit.CompanyModel.city;
                                    $scope.requests[index].CompanyModel.IDNUM = $scope.edit.CompanyModel.IDNUM;
                                    $scope.requests[index].CompanyModel.www = $scope.edit.CompanyModel.www;
                                }
                            });
                        }
                    });
                }

                $scope.window = {};
                if (id == "-1") {
                    $scope.window.title = "Nova spolecnost";
                    initWin({});
                } else {
                    $http.get("system/angular_request/getCompanyDetail/null/" + id).success(function (response) {
                        $scope.window.title = "Editace spolecnosti";
                        initWin(response.data);
                    })
                }
            };
        })
    }

    CompanyController.$inject = injectParams;
    app.controller('CompanyController', CompanyController);


    app.directive("fileRead", function ($http) {
        return {
            scope: false,
            link: function (scope, element, attributes) {
                element.bind("change", function (changeEvent) {
                    //send request here
                    $http({
                        method: 'POST',
                        url: '/classes/upload.php',
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        data: {
                            upload: event.target.files
                        },
                        transformRequest: function (data, headersGetter) {
                            var formData = new FormData();
                            angular.forEach(data, function (value, key) {
                                angular.forEach(value, function (value1, key1) {
                                    formData.append(key1, value1);
                                });
                            });
                            var headers = headersGetter();
                            delete headers['Content-Type'];
                            return formData;
                        }
                    })
                        .success(function (data) {
                            if(data){
                                for(var j = 0; j < data.length; j++){
                                    scope.edit.items.push(data[j]);
                                }
                            }
                        })
                        .error(function (data) {
                            console.log("error");
                        });
                });
            }
        }
    });
});
