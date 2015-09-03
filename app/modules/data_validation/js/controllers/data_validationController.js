'use strict';

define(['js/angular/app/app'], function (app) {
    var injectParams = ['$scope','$http','$sce','$modal','$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService','$rootScope','updateProgressBar'];
    var Data_validationController = function ($scope,$http,$sce,$modal,$location, $filter, $window, $timeout, authService, dataService, modalService,$rootScope,updateProgressBar) {


        $scope.locationhref = function(url) {
            location.href = url;
        };

        $scope.getData = function(){
            modalService.showLoader();
            $http({
                method: 'POST',
                url: 'system/angular_request/getValidateFiles/'+$scope.modal_enc_id
            })
                .success(function (data) {
                    $scope.validated = data.data.files;
                    modalService.hideLoader();
                });

        };

        $scope.downloadTrs = function(){
                $http({
                    method: 'POST',
                    url: 'system/angular_request/downloadTrs/'+$scope.modal_enc_id
                })
                    .success(function (data) {

                        if(data.data != "error"){console.log(data.data);
                            //generate dynamic csv
                            $http({
                                method: 'GET',
                                url: '/app/modules/data_validation/validation/validation.php?columns='+data.data,
                                data: {
                                    columns: "asas"
                                }
                            }).success(function (response) {
                                console.log(response);
                                if(response.file)
                                $scope.locationhref('app/modules/data_validation/validation/csv/'+response.file);
});
                        }
                    })
                    .error(function (data, status) {
                        console.log(data);
                    });
        }

        $scope.getFile = function(event){
            var file = event.target.files[0];
            $scope.file_data = file ? file : undefined;
        },

        $scope.editWindow_data_validation = function (id, index) {
            function initWin(response) {
                console.log(response);
                $scope.edit = response;
        }


        $scope.validate = function() {
            modalService.showLoader();
                        $http({
                            method: 'POST',
                            url: '/app/modules/data_validation/validation/validation.php?file=' + index + '&r=' + (Math.random()*Math.random()),
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            },
                            data: {
                                upload: $scope.file_data
                            },
                            transformRequest: function (data, headersGetter) {
                                var formData = new FormData();
                                angular.forEach(data, function (value, key) {
                                    formData.append(key, value);
                                });
                                var headers = headersGetter();
                                delete headers['Content-Type'];
                                return formData;
                            }
                        })
                            .success(function (data) {
                                $scope.message = data.message;

                                if($rootScope.allowEdit) {
                                    if (data.answer == 1 || data.answer == 0) {
                                        //update values in database
                                        $http.get("system/angular_request/dataValidate/" + $scope.modal_enc_id + "/" + index + "/" + data.answer).success(function (response) {
                                            console.log(response)
                                            updateProgressBar.start("data_validation");
                                            $scope.getData();
                                        });

                                    }
                                }

                                modalService.hideLoader();

                            })
                            .error(function (data, status) {
                                console.log(data);
                            });
                }

                    modalService.showModal({}, {
                        scope: $scope,
                        templateUrl: '/app/modules/data_validation/views/editModal.ctp?r=' + Math.random()
                    }).then(function (result) {
                        $scope.message = "";
                        if (result === 'submit') {
                            console.log($scope.file_data);
                            $http({
                                method: 'POST',
                                //url: 'system/angular_request/fileUpload',
                                url: '/app/modules/data_validation/validation/validation.php?file=e',
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                },
                                data: {
                                    upload: $scope.file_data
                                },
                                transformRequest: function (data, headersGetter) {
                                    var formData = new FormData();
                                    angular.forEach(data, function (value, key) {
                                        formData.append(key, value);
                                    });
                                    var headers = headersGetter();
                                    delete headers['Content-Type'];
                                    return formData;
                                }
                            })
                                .success(function (data) {
                                    console.log(data);
                                })
                                .error(function (data, status) {
                                    console.log(data);
                                });

                        }
                    });
               /* });*/
            }
       /* });*/
        $scope.getData();
    }

    Data_validationController.$inject = injectParams;
    app.controller('Data_validationController', Data_validationController);
});
