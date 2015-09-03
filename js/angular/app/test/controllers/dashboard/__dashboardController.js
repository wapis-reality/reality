'use strict';
console.log("There");
define(['app'], function (app) {

    var injectParams = ['$scope', '$http', '$modal', '$sce', '$location', '$filter', '$window',
        '$timeout', 'authService', 'dataService', 'modalService'];

    var DashboardController = function ($scope, $http, $modal, $sce, $location, $filter, $window, $timeout, authService, dataService, modalService) {

            $scope.client_scheme_add = []; // Allow to add a new scheme to a client

            /**
             * Open | Close schemes for a client
             * @param $event
             */
            $scope.open_close = function ($event) {
                var a = $($event.currentTarget),
                    p = $(a.parents('div.clearfix')[0]),
                    n = $(p.next('.block_schemes')),
                    i = $(a.find('i'));

                n.toggleClass('none');
                i.toggleClass('fa-caret-down').toggleClass('fa-caret-up');
                return false;
            }


            $scope.setClientMainTopRecord = function () {
                angular.forEach($scope.clients, function (client, key) {
                    var clientId = client.id,
                        result = null;

                    if (angular.isUndefined($scope.schemes[clientId])) {
                        result = {
                            status: 'Empty',
                            showProgress: false,
                            empty: true
                        }
                    } else {
                        console.log(clientId);
                        console.log($scope.schemes[clientId]);
                        switch ($scope.schemes[clientId].length) {
                            case 0:
                                result = {
                                    status: 'Empty',
                                    showProgress: false,
                                    empty: true
                                }
                                break;
                            case 1:

                                var percDone = false,
                                    total_questions = 0,
                                    remaining_questions = 0;

                                if ($scope.schemes[clientId][0]['activated'] == 0) {
                                    percDone = true;
                                    $.each($scope.modules[$scope.schemes[clientId][0].id], function (i, modules) {
                                        total_questions += parseInt(modules.total_questions);
                                        remaining_questions += parseInt(modules.remaining_questions);
                                    });
                                }

                                result = {
                                    status: $scope.schemes[clientId][0]['activated'] == 1 ? 'live' : 'in progress',
                                    name: $scope.schemes[clientId][0]['name'],
                                    effective_date_details: $scope.schemes[clientId][0]['effective_date_details'],
                                    effective_date: $scope.schemes[clientId][0]['effective_date'],
                                    showProgress: percDone,
                                    progress: Math.round((total_questions - remaining_questions) / total_questions * 100),
                                    progressDeg: 360 / 100 * Math.round((total_questions - remaining_questions) / total_questions * 100)
                                };
                                break;

                            default:
                                var inactive = null,
                                    latest = 0,
                                    latestId = 0,
                                    latestIndex = null;
                                angular.forEach($scope.schemes[clientId], function (scheme, key) {
                                    if (scheme.activated == 0) {
                                        inactive = true;
                                        latestId = scheme.id;
                                        latestIndex = key;
                                    }

                                    if (scheme.effective_date > latest) {
                                        latest = scheme.effective_date;
                                        latestId = scheme.id;
                                        latestIndex = key;
                                    }
                                });

                                var percDone = false,
                                    total_questions = 0,
                                    remaining_questions = 0;


                                if ($scope.schemes[clientId][latestIndex]['activated'] == 0) {
                                    percDone = true;
                                    $.each($scope.modules[latestId], function (i, modules) {
                                        total_questions += parseInt(modules.total_questions);
                                        remaining_questions += parseInt(modules.remaining_questions);
                                    });
                                }

                                result = {
                                    status: inactive == null ? 'live' : 'renewal',
                                    name: $scope.schemes[clientId][latestIndex]['name'],
                                    effective_date_details: $scope.schemes[clientId][latestIndex]['effective_date_details'],
                                    effective_date: $scope.schemes[clientId][latestIndex]['effective_date'],
                                    showProgress: percDone,
                                    progress: Math.round((total_questions - remaining_questions) / total_questions * 100),
                                    progressDeg: 360 / 100 * Math.round((total_questions - remaining_questions) / total_questions * 100)
                                };


                                break;
                        }
                    }

                    $scope.clients[key].mainTopRecord = result;
                });
            };

        /**
         * @todo - double loaded issue
         * @param client
         * @returns {*}
         */
        $scope.getClientOpenSchemeDate = function (client, index) {

            var activated = false;
            if ($scope.clients[index]) {
                $scope.clients[index]['active'] = null;
                if ($scope.schemes[client.id]) {
                    $.each($scope.schemes[client.id], function (i, k) {
                        if (k.activated == 0) {
                            activated = k.effective_date;
                            $scope.clients[index]['active'] = k.id

                        }
                    })
                }
            }
            return activated !== false ? activated : '';
        }

        $scope.d180 = function (val) {
            if (val >= 180) {
                return true;
            } else {
                return false;
            }
        }


        $scope.perc2degree = function (perc) {
            return 360 / 100 * perc;
        }

        $scope.getClientOpenSchemePerc = function (client, index) {
            var remaining_questions = 0,
                total_questions = 0;

            if ($scope.clients[index]) {
                if ($scope.clients[index]['active'] !== null && $scope.modules[$scope.clients[index]['active']]) {
                    $.each($scope.modules[$scope.clients[index]['active']], function (i, modules) {
                        total_questions += parseInt(modules.total_questions);
                        remaining_questions += parseInt(modules.remaining_questions);
                    });
                }
            }

            return Math.round((total_questions - remaining_questions) / total_questions * 100);

        }

        $scope.refreshClientSchemeAdd = function () {

            angular.forEach($scope.clients, function (client, k) {
                $scope.client_scheme_add[client.id] = true;
                if (typeof $scope.schemes[client.id] != 'undefined') {
                    angular.forEach($scope.schemes[client.id], function (obj, k) {
                        if (obj.activated == 0) {
                            $scope.client_scheme_add[client.id] = false;
                        }
                    });
                }

            });
        }

        //console.log($scope.client_scheme_add);

        $http.get("/clients/get_data/").success(function (res) {

            $scope.schemes = res.schemes;
            $scope.modules = res.modules;
            $scope.clients = res.clients;
            $scope.refreshClientSchemeAdd();
            $scope.setClientMainTopRecord();
            //console.log($scope.client_scheme_add);
            //console.log($scope.schemes);
            console.log($scope.clients);
            //console.log($scope.modules);


            /**
             * Check if all the questions in the modules have had the questions answered
             */
            $scope.isSchemeComplete = function (scheme_id) {
                var result = true;
                angular.forEach($scope.modules[scheme_id], function (module, key) {
                    if (module.remaining_questions != 0) {
                        result = false;
                    }
                })
                return result;

            }


            $scope.getPercentage = function (scheme_id) {
                var length = $scope.modules[scheme_id].length;
                var num = 0;
                angular.forEach($scope.modules[scheme_id], function (r) {
                    if (r.is_complete == 1) {
                        num++;
                    }
                });
                return (100 * num) / length;
            };

            $scope.getModuleKey = function (title) {
                var keyName = title.replace(' ', '_').replace('-', '_').toLowerCase();
                return keyName;
            }

            //Edit functionality
            $scope.template = '' +

                '<div class="modal-header clearfix text-left"><button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button><h5><span id="domwin-title">{{title}}</span></h5></div><div class="modal-body" ng-bind-html="html" dynamic></div>';


            $scope.editClient = function (id) {

                var random = Math.floor(Math.random() * 6) + 1;
                $scope.editableClient = {}

                $http.get("/clients/edit/" + id).success(function (response) {

                    /**
                     * Get the list of intermediaries
                     */
                    $scope.editClientIntermediaries = response.data;

                    /**
                     * Init modal and pass the $scope
                     */
                    var modalInstance = $modal.open({
                        templateUrl: 'app/views/clients/editModal.ctp?' + random,
                        scope: $scope,
                        controller: [
                            '$scope', '$modalInstance', function ($scope, $modalInstance) {

                                /**
                                 * When user submits form save Client on database
                                 * If we saved correctly, update the $scope.clients
                                 */
                                $scope.submitForm = function () {

                                    $http.post("/clients/edit/", $scope.editableClient).success(function (response) {

                                        if (response.result) {

                                            $scope.clients.push({
                                                id: response.data,
                                                name: $scope.editableClient.Client.name
                                            });
                                            $scope.client_scheme_add[response.data] = true;
                                            $modalInstance.close();
                                        } else {

                                            alert('error');

                                        }
                                    });
                                };
                            }
                        ]
                    });

                });
            }

            $scope.editTRS = function (id) {
                /**
                 * Set default value to id
                 */
                var id = id || "";

                $http.get("clients/editTRS").
                    success(function (response) {

                        $scope.benefits = response;
                        console.log($scope.benefits);
                    }).
                    error(function (data) {
                        console.log('Error');
                    });

                var modalInstance = $modal.open({
                    templateUrl: "app/views/clients/TRS.ctp",
                    scope: $scope,
                    size: 'lg',
                    controller: [
                        '$scope', '$modalInstance', function ($scope, $modalInstance) {

                            /**
                             * When user submits form
                             */
                            $scope.submitForm = function () {
                                console.log($scope.edit);
                                /**
                                 * Save Client on database
                                 */
                                $http.post("/reports/edit/", $scope.edit).success(function (response) {
                                    /**
                                     * If we saved correctly, update the $scope.clients
                                     */
                                    if (response.status) {
                                        $scope.data[id].id = $scope.edit.id
                                        $modalInstance.close();

                                    } else {

                                        alert('error');

                                    }
                                });
                            };
                        }
                    ]
                });
//        modalService.showModal({}, modalOptions).then(function(scope) {
//            var scope = scope.data;
//
//            $scope.items[scope.index] = scope;
//            $scope.modules[scope.result.id] = scope.result;
//
//            angular.forEach(scope, function(value, key) {
//                $scope.data[key] = value;
//            });
//        });
                return;
            }

            $scope.editParameters = function (id) {
                /**
                 * Set default value to id
                 */
                var id = id || "";

                $http.get("clients/editParameters").
                    success(function (response) {
                        $scope.benefits = response.data;
                        console.log($scope.benefits);
                    }).
                    error(function (data) {
                        console.log('Error');
                    });

                var modalInstance = $modal.open({
                    templateUrl: "app/views/clients/parameters.ctp",
                    scope: $scope,
                    size: 'lg',
                    controller: [
                        '$scope', '$modalInstance', function ($scope, $modalInstance) {

                            /**
                             * When user submits form
                             */
                            $scope.submitForm = function () {
                                console.log($scope.edit);
                                /**
                                 * Save Client on database
                                 */
                                $http.post("/reports/edit/", $scope.edit).success(function (response) {
                                    /**
                                     * If we saved correctly, update the $scope.clients
                                     */
                                    if (response.status) {
                                        $scope.data[id].id = $scope.edit.id
                                        $modalInstance.close();

                                    } else {

                                        alert('error');

                                    }
                                });
                            };
                        }
                    ]
                });
//        modalService.showModal({}, modalOptions).then(function(scope) {
//            var scope = scope.data;
//
//            $scope.items[scope.index] = scope;
//            $scope.modules[scope.result.id] = scope.result;
//
//            angular.forEach(scope, function(value, key) {
//                $scope.data[key] = value;
//            });
//        });
                return;
            }

            /**
             * Add/Edit client schemes
             * @param client_id
             * @param index
             */
            $scope.editClientScheme = function (client_id, index) {

                var id = "",
                    random = Math.floor(Math.random() * 6) + 1;
                $scope.editableClientScheme = {}

                $http.get("/client_schemes/edit/" + client_id + "/" + id).success(function (response) {
                    console.log(response);
                    $scope.editClientSchemeModules = response.data.modul;
                    $scope.editClientSchemePages = response.data.page;

                    /**
                     * Init modal and pass the $scope
                     */
                    var modalInstance = $modal.open({
                        templateUrl: 'app/views/client_schemes/editModal.ctp?' + random,
                        scope: $scope,
                        controller: [
                            '$scope', '$modalInstance', function ($scope, $modalInstance) {

                                /**
                                 * Multiple checkbox
                                 */
                                $scope.editableClientScheme.Modules = [];
                                $scope.toggleSelection = function toggleSelection(item) {
                                    var idx = $scope.editableClientScheme.Modules.indexOf(item);

                                    // is currently selected
                                    if (idx > -1) {
                                        $scope.editableClientScheme.Modules.splice(idx, 1);
                                    }

                                    // is newly selected
                                    else {
                                        $scope.editableClientScheme.Modules.push(item);
                                    }
                                };

                                /**
                                 * When user submits form save Client on database
                                 * If we saved correctly, update the $scope.clients
                                 */
                                $scope.submitForm = function () {

                                    $http.post("/client_schemes/edit/" + client_id + "/" + id, $scope.editableClientScheme).success(function (response) {

                                        if (response.result) {

                                            if (typeof $scope.schemes[client_id] == "undefined") {
                                                $scope.schemes[client_id] = [];
                                            }

                                            /**
                                             * Update client_scheme_add
                                             * This way we will disable the 'Add Scheme' button
                                             */
                                            $scope.client_scheme_add[client_id] = false;

                                            /**
                                             * Update $scope.modules
                                             */
                                            $scope.modules[response.data.Scheme.id] = [];
                                            angular.forEach(response.data.Module, function (module, key) {
                                                $scope.modules[response.data.Scheme.id].push(module);
                                            });

                                            /**
                                             * Update $scope.schemes
                                             */
                                            $scope.schemes[client_id].push(response.data.Scheme);

                                            $modalInstance.close();

                                        } else {

                                            alert('error');

                                        }
                                    });
                                };
                            }
                        ]
                    });

                });
            }

            /**
             * Submit scheme
             */
            $scope.submitScheme = function (client_id, index) {
                console.log('client_id:' + client_id);
                console.log('index:' + index);
                console.log('scheme_id:' + $scope.schemes[client_id][index].id);
                var scheme_id = $scope.schemes[client_id][index].id;
                $http.get("/client_schemes/activate_scheme/" + client_id + "/" + scheme_id).success(function (response) {
                    $scope.schemes[client_id][index].activated = 1;
                });
            }


            //function when clicks on Edit button
            $scope.editEvent = function (link, title, controller, enc_id) {
                console.log(link);
                console.log(title);
                console.log(controller);
                console.log(enc_id);
                console.log($scope.schemes);
                console.log($scope.clients);
                console.log($scope.modules);
                $scope.title = title;
                $scope.link = link;
                $scope.modal_enc_id = enc_id;

//        $scope.$apply();
                //modal functionality from ui-bootstrap
                controller = controller.charAt(0).toUpperCase() + controller.slice(1);

                var modalInstance = $modal.open({
                    template: $scope.template,
                    controller: controller, //modal is connected this controller in case of adding extra functionality
                    backdrop: 'static',
                    keyboard: false,
                    size: 'lg',
                    scope: $scope,
                    windowClass: 'full_screen white slide-right '
                })
            };
        });

        $scope.test = function () {
            console.log('2');
        }

        $scope.preview = function(client_id, scheme_id) {
            $scope.frame = $sce.trustAsHtml("<iframe height='700px' width='100%' src='http://preview.local:8888/login/?client_id="+client_id+"&scheme_id="+scheme_id+"' style='border: 0px;  background: #FFF;'></iframe>");

            var modalInstance = $modal.open({
                templateUrl: "/app/views/pages/preview.ctp",
                backdrop: 'static',
                keyboard: false,
                size: 'lg',
                scope: $scope,
                windowClass: 'full_screen white slide-right '
            });
        }

    }

    DashboardController.$inject = injectParams;

    app.register.controller('DashboardController', DashboardController)

});

