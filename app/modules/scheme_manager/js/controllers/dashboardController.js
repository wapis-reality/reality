'use strict';

define(['js/angular/app/app'], function (app) {

    var injectParams = ['$scope', '$http', '$modal', '$sce', '$location', '$filter', '$window',
        '$timeout', 'authService', 'dataService', 'modalService', '$rootScope'];

    var DashboardController = function ($scope, $http, $modal, $sce, $location, $filter, $window, $timeout, authService, dataService, modalService, $rootScope) {

        $scope.today = new Date();
        $scope.client_scheme_add = []; // Allow to add a new scheme to a client

        /**
         * Open | Close schemes for a client
         * @param $event
         */

        $scope.formatDate = function(dd){ //this function will update the date format like yyyy-mm-dd
            if(dd && typeof dd != "string")
           /* var n = dd.toString();
            if(n.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/))*/
            return dd.getFullYear()+'-'+("0" + (dd.getMonth() + 1)).slice(-2)+'-'+("0" + (dd.getDate())).slice(-2);
            else return dd;
        };

        $scope.open_close = function ($event) {
            var a = $($event.currentTarget),
                p = $(a.parents('div.clearfix')[0]),
                n = $(p.next('.block_schemes')),
                i = $(a.find('i'));

            n.toggleClass('none');
            i.toggleClass('fa-caret-down').toggleClass('fa-caret-up');
            return false;
        }



        /**
         * Get data for the Client row
         */
        $rootScope.setClientMainTopRecord = function () {
            angular.forEach($rootScope.clients, function (client, key) {

                var clientId = client.id,
                    result = null;

                if (angular.isUndefined($rootScope.schemes[clientId])) {
                    result = {
                        status: 'No details',
                        showProgress: false,
                        empty: true,
                        allowEdit: true
                    }
                } else {

                    //Create status / scheme
                    var editclient = 1;

                    for (var i = 0; i < $rootScope.schemes[clientId].length; i++) {
                        var now = new Date();

                        if ($rootScope.schemes[clientId].length > 1 && $rootScope.schemes[clientId][i]['activated'] == 1 && new Date($rootScope.schemes[clientId][i]['effective_date']).setHours(0, 0, 0, 0) <= now.setHours(0, 0, 0, 0) && typeof $rootScope.schemes[clientId][i + 1] != "undefined" && $rootScope.schemes[clientId][i + 1]['activated'] == 1 && new Date($rootScope.schemes[clientId][i + 1]['effective_date']).setHours(0, 0, 0, 0) <= now.setHours(0, 0, 0, 0)) {
                            $rootScope.schemes[clientId][i]['status_scheme'] = 'history';
                            editclient = false;
                        } else if ($rootScope.schemes[clientId][i]['activated'] == 1 && new Date($rootScope.schemes[clientId][i]['effective_date']).setHours(0, 0, 0, 0) <= now.setHours(0, 0, 0, 0)) {
                            $rootScope.schemes[clientId][i]['status_scheme'] = 'live';
                            editclient = false;
                        } else if ($rootScope.schemes[clientId][i]['activated'] == 1 && new Date($rootScope.schemes[clientId][i]['effective_date']).setHours(0, 0, 0, 0) > now.setHours(0, 0, 0, 0)) {
                            $rootScope.schemes[clientId][i]['status_scheme'] = 'submitted';
                            editclient = false;
                        } else if ($rootScope.schemes[clientId][i]['activated'] == 0) {
                            $rootScope.schemes[clientId][i]['status_scheme'] = 'in progress';
                        }

                    }

                    switch ($rootScope.schemes[clientId].length) {
                        case 0:
                            result = {
                                status: 'Empty',
                                showProgress: false,
                                empty: true,
                                allowEdit: true
                            }
                            break;
                        case 1:

                            var percDone = false,
                                total_questions = 0,
                                answered_questions = 0;

                            if (typeof $rootScope.modules[$rootScope.schemes[clientId][0].id] != "undefined") {

                                if ($rootScope.schemes[clientId][0]['activated'] == 0) {
                                    percDone = true;
                                    $.each($rootScope.modules[$rootScope.schemes[clientId][0].id], function (i, modules) {
                                        total_questions += parseInt(modules.total_questions);
                                        answered_questions += parseInt(modules.answered_questions);
                                    });
                                }

                                result = {
                                    allowEdit: editclient,
                                    status: $rootScope.schemes[clientId][0]['activated'] == 1 ? 'live' : 'in progress',
                                    name: $rootScope.schemes[clientId][0]['name'],
                                    effective_date_details: $rootScope.schemes[clientId][0]['effective_date_details'],
                                    effective_date: $rootScope.schemes[clientId][0]['effective_date'],
                                    showProgress: percDone,
                                    progress: Math.round((answered_questions) / total_questions * 100),
                                    progressDeg: 360 / 100 * Math.round((answered_questions) / total_questions * 100)
                                };
                            }
                            break;

                        default:
                            var inactive = null,
                                latest = 0,
                                latestId = 0,
                                latestIndex = null;
                            angular.forEach($rootScope.schemes[clientId], function (scheme, key) {
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
                                answered_questions = 0;


                            if (typeof $rootScope.schemes[clientId][latestIndex] != "undefined" && $rootScope.schemes[clientId][latestIndex]['activated'] == 0) {
                                percDone = true;
                                $.each($rootScope.modules[latestId], function (i, modules) {
                                    total_questions += parseInt(modules.total_questions);
                                    answered_questions += parseInt(modules.answered_questions);
                                });
                            }

                            result = {
                                allowEdit: editclient,
                                status: inactive == null ? 'live' : 'renewal',
                                name: $rootScope.schemes[clientId][latestIndex]['name'],
                                effective_date_details: $rootScope.schemes[clientId][latestIndex]['effective_date_details'],
                                effective_date: $rootScope.schemes[clientId][latestIndex]['effective_date'],
                                showProgress: percDone,
                                progress: Math.round((answered_questions) / total_questions * 100),
                                progressDeg: 360 / 100 * Math.round((answered_questions) / total_questions * 100)
                            };


                            break;
                    }
                }

                $rootScope.clients[key].mainTopRecord = result;
            });
        };

        /**
         * @todo - double loaded issue
         * @param client
         * @returns {*}
         */
        $scope.getClientOpenSchemeDate = function (client, index) {

            var activated = false;
            if ($rootScope.clients[index]) {
                $rootScope.clients[index]['active'] = null;
                if ($rootScope.schemes[client.id]) {
                    $.each($rootScope.schemes[client.id], function (i, k) {
                        if (k.activated == 0) {
                            activated = k.effective_date;
                            $rootScope.clients[index]['active'] = k.id

                        }
                    })
                }
            }
            return activated !== false ? activated : '';
        }

        /**
         * do we need to add class for progress, just for case when deg is over 180
         * @param val
         * @returns {boolean}
         */
        $scope.d180 = function (val) {
            if (val >= 180) {
                return true;
            } else {
                return false;
            }
        }


        $scope.refreshClientSchemeAdd = function () {
            angular.forEach($rootScope.clients, function (client, k) {
                $scope.client_scheme_add[client.id] = true;
                if (typeof $rootScope.schemes[client.id] != 'undefined') {
                    angular.forEach($rootScope.schemes[client.id], function (obj, k) {
                        if (obj.activated == 0) {
                            $scope.client_scheme_add[client.id] = false;
                        }
                    });
                }

            });
        }

        /**
         * Check if all the questions in the modules have had the questions answered
         */
            //$scope.isSchemeComplete = function (scheme_id) {
            //    var result = true;
            //    angular.forEach($rootScope.modules[scheme_id], function (module, key) {
            //        if (module.answered_questions != 0) {
            //            result = false;
            //        }
            //    })
            //    return result;
            //
            //}

        $scope.getModuleKey = function (title) {
            var keyName = title.replace(' ', '_').replace('-', '_').toLowerCase();
            return keyName;
        }

        $scope.getModuleName = function (title) {
            var moduleName = title.replace('_', ' ').toUpperCase();
            return moduleName;
        }

        //Edit functionality
        $scope.template = '' +
            '<div class="modal-header clearfix text-left"><button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button><h5><span id="domwin-title">{{title}}</span></h5></div><div class="modal-body" ng-bind-html="html" dynamic></div>';


        $scope.renewal = function (enc_id) {

            var random = Math.floor(Math.random() * 6) + 1;
            $scope.editableClient = {};
            $scope.edit = {};
            $scope.edit.ClientScheme = {};

            $http.get("/angular_requests/getCurrent/" + enc_id).success(function (response) {

                $scope.edit.modules = response.data.modules;
                $scope.edit.ClientScheme.name = response.data.scheme[0].ClientSchemeModel.name;

                /**
                 * Init modal and pass the $scope
                 */
                var modalInstance = $modal.open({
                    templateUrl: 'app/modules/scheme_manager/views/renewalSchemeModal.ctp?' + random,
                    scope: $scope,
                    controller: [
                        '$scope', '$modalInstance', function ($scope, $modalInstance) {

                            /**
                             * When user submits form save Client on database
                             * If we saved correctly, update the $rootScope.clients
                             */
                            $scope.submitForm = function () {

                                $http.post("angular_requests/createRenewal/" + enc_id, $scope.edit).success(function (response) {

                                    if (response.result) {
                                        var client_id = response.data.client_id;

                                        if (typeof $rootScope.schemes[client_id] == "undefined") {
                                            $rootScope.schemes[client_id] = [];
                                        }

                                        /**
                                         * Update client_scheme_add
                                         * This way we will disable the 'Add Scheme' button
                                         */
                                        $scope.client_scheme_add[client_id] = false;

                                        /**
                                         * Update $rootScope.modules
                                         */
                                        $rootScope.modules[response.data.Scheme.id] = [];
                                        angular.forEach(response.data.Module, function (module, key) {
                                            $rootScope.modules[response.data.Scheme.id].push(module);
                                        });

                                        /**
                                         * Update $rootScope.schemes
                                         */
                                        $rootScope.schemes[client_id].push(response.data.Scheme);
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

        $scope.editClient = function (id, allowEdit) {

            modalService.showLoader();

            if (id == "this") {
                $scope.editClientDetails = true;
                $scope.modalTitle = "Add new client";
            } else {
                $scope.editClientDetails = allowEdit;
                $scope.modalTitle = (allowEdit == true) ? "Edit client" : "Client details";
            }
            var random = Math.floor(Math.random() * 6) + 1;
            $scope.editableClient = {};

            $http.get("/angular_requests/getClientDetail/null/" + id).success(function (response) {

                modalService.hideLoader();

                if (response.data) {
                    $scope.editableClient = response.data;
                    $scope.changeurl = false;
                }
                else $scope.changeurl = true;
            });

            var modalInstance = $modal.open({
                templateUrl: 'app/modules/scheme_manager/views/addClientModal.ctp?' + random,
                scope: $scope,
                keyboard:false,
                backdrop:'static',
                controller: [
                    '$scope', '$modalInstance', function ($scope, $modalInstance) {
                    }
                ]
            });

            /**
             * When user submits form save Client on database
             * If we saved correctly, update the $rootScope.clients
             */
            $scope.submitForm = function (isvalid) {
                /*if(!isvalid) {
                    alert("Please complete the form");
                }
                else {
                */    modalService.showLoader();
                    $http.post("angular_requests/saveClient/", $scope.editableClient).success(function (response) {

                        if (response.result) {
                            /*$rootScope.clients.push({
                             id: response.data,
                             name: $scope.editableClient.ClientModel.name
                             });*/
                            $scope.client_scheme_add[response.data] = true;
                            $scope.getData();
                            $modalInstance.close();
                            $rootScope.setClientMainTopRecord();
                        } else {
                            alert('error');
                        }
                        modalService.hideLoader();
                    });
                //} //form validation else
            };




        }


        //function when clicks on Edit button
        $scope.editEvent = function (link, title, controller, enc_id, s_activated) {


            $scope.title = title;
            $scope.link = link;
            $scope.modal_enc_id = enc_id;
            $rootScope.modal_enc_id = enc_id;
            $rootScope.allowEdit = (s_activated == 1 ? false : true); //require for each view

            var templateUrl,
            //random = Math.floor(Math.random() * 6) + 1;
                random = new Date().getTime();
            switch (controller) {
                case 'trsController':
                    templateUrl = '/app/modules/trs/views/trs/index.ctp';
                    break;
                case 'cmsController':
                    templateUrl = '/app/modules/cms/views/cms/index.ctp';
                    break;
                case 'data_validationController':
                    templateUrl = '/app/modules/data_validation/views/index.ctp'
                    break;
                default:
                    templateUrl = '/app/system/views/actions/angular_modal_index.php';
                    break;
            }


            //modal functionality from ui-bootstrap
            controller = controller.charAt(0).toUpperCase() + controller.slice(1);

            window.modalInstance = $modal.open({
                //template: $scope.template,
                templateUrl: templateUrl + '?' + random,
                controller: controller, //modal is connected this controller in case of adding extra functionality
                backdrop: 'static',
                keyboard: false,
                size: 'lg',
                scope: $scope,
                windowClass: 'full_screen white slide-right '
            })
        };

        /**
         * Get user data
         */
        $scope.getUserData = function() {

            $rootScope.user = {};

            modalService.showLoader();
            $http.get("/system/getUserData/").success(function (res) {

                if (res.result === true) {

                    $rootScope.user = res.data;
                    modalService.hideLoader();
                    return true;

                } else {

                    console.error('Problem with the request (/system/getUserData/)');
                    modalService.hideLoader();
                    return false;

                }


            });
        }
        $scope.getUserData();

        $scope.getData = function () {
            modalService.showLoader();
            $http.get("/angular_requests/getClientSchemes/").success(function (res) {
                if (res.result === true) {
                    $rootScope.schemes = res.data.schemes;
                    $rootScope.modules = res.data.modules;
                    $rootScope.clients = res.data.clients;
                    modalService.hideLoader();
                    $scope.refreshClientSchemeAdd();
                    $rootScope.setClientMainTopRecord();

                    return true;
                } else {
                    console.error('Problem with the request (/angular_requests/getClientSchemes/)');
                    modalService.hideLoader();
                    return false;
                }


            });
        };
        //call main function
        $scope.getData();


        $http.get("/angular_requests/getClientSchemes/").success(function (res) {
            if (res.result === true) {
                //$rootScope.schemes = res.data.schemes;
                $rootScope.schemes = res.data.schemes;
                //$rootScope.modules = res.data.modules;
                $rootScope.modules = res.data.modules;
                $rootScope.clients = res.data.clients;
                $scope.refreshClientSchemeAdd();
                $rootScope.setClientMainTopRecord();
                return;
            } else {
                console.error('Problem with the request (/angular_requests/getClientSchemes/)');

                $scope.getData = function () {
                    $http.get("/angular_requests/getClientSchemes/").success(function (res) {
                        if (res.result === true) {
                            $rootScope.schemes = res.data.schemes;
                            $rootScope.modules = res.data.modules;
                            $rootScope.clients = res.data.clients;
                            $scope.refreshClientSchemeAdd();
                            $rootScope.setClientMainTopRecord();
                            return;
                        } else {
                            console.error('Problem with the request (/angular_requests/getClientSchemes/)');
                        }
                    });
                };
                //call main function
                $scope.getData();
            }
        });

        $scope.editClientAE = function (client_id, index) {

            var id = "",
                random = Math.floor(Math.random() * 6) + 1;


            /**
             * panels
             *
             */
            $scope.panel = {};
            $scope.panel.tab = 1;

            $scope.panel.selectTab = function (setTab) {
                $scope.panel.tab = setTab;
            };
            $scope.panel.isSelected = function (checkTab) {
                return $scope.panel.tab === checkTab;
            };

            $rootScope.schemes = [];
            $scope.users = [];
            $scope.paygroups = [];
            $scope.sections = [];


            $scope.addUser = function () {
                $scope.users.push({
                    'firstname': '',
                    'lastname': '',
                    'email': '',
                })
            };

            $scope.removeUser = function (index) {
                $scope.users.splice(index, 1);
            }


            $scope.addPaygroup = function () {
                $scope.paygroups.push({
                    'group_name': '',
                    'frequency': '',
                    'upload_method': '',
                    'pay_reference_period': '',
                    'cut_off': '',
                    'deduction_date': ''
                })
            };

            $scope.removePaygroup = function (index) {
                $scope.paygroups.splice(index, 1);
            }

            $scope.addScheme = function () {
                $rootScope.schemes.push({
                    'scheme_number': '',
                    'scheme_name': '',
                    'scheme_type': '',
                    'default_retirement_age': '',
                    'microsite': '',
                    'agancy_name': '',
                    'agancy_number': '',
                })


            };

            $scope.addSection = function () {
                $scope.sections.push({
                    'scheme_number': '',
                    'scheme_name': '',
                    'scheme_type': '',
                    'default_retirement_age': '',
                    'microsite': '',
                    'agancy_name': '',
                    'agancy_number': '',
                })
            };

            $scope.removeSection = function (index) {
                $scope.sections.splice(index, 1);
            }

            $scope.addScheme();
            $scope.addSection();


            var modalInstance = $modal.open({
                templateUrl: 'app/modules/scheme_manager/views/addSchemeAE.ctp?' + random,
                scope: $scope,
                size: 'lg',
                keyboard:false,
                backdrop:'static',
                controller: [
                    '$scope', '$modalInstance', function ($scope, $modalInstance) {

                    }
                ]

            });
        };

        /**
         * Add/Edit client schemes
         * @param client_id
         * @param index
         */
        $scope.editClientScheme = function (client_id, index, scheme_id, allowEdit, renew) {

            modalService.showLoader();
            if (scheme_id == undefined) {
                $scope.editSchemeDetails = true;
                $scope.modalTitle = "Create a new scheme";
                $scope.schemeId = "";
            } else {
                if (allowEdit == 1) {
                    $scope.editSchemeDetails = false;
                    $scope.modalTitle = "Scheme details";
                } else {
                    $scope.editSchemeDetails = true;
                    $scope.modalTitle = "Edit scheme";
                }
                $scope.schemeId = scheme_id;
            }

            var id = "",
                random = Math.floor(Math.random());
            $scope.editableClientScheme = {}
            $scope.editableClientScheme.Modules = {};

            $http.get("angular_requests/addSchemeData/null/"+client_id+"/"+ $scope.schemeId).success(function (response) {
            console.log(response.data);
                    if (response.result) {

                        $scope.editableClientScheme.Modules.modul = response.data.modules.modul;
                        $scope.editableClientScheme.Modules.page = response.data.modules.page;



                        $scope.library_list = {
                            'clients': response.data.available_benefits_per_client,
                            'types': response.data.available_benefits_per_type,
                            'all': response.data.available_benefits
                        };
                        $scope.benefitTypes = response.data.benefit_types;
                        $scope.editableClientScheme.selected_benefit_type = response.data.sel_b_types;
                        $scope.editableClientScheme.selected_benefits = {};

                        //get client scheme id
                        if (scheme_id) {
                            $scope.editableClientScheme.ClientScheme = response.data.scheme.ClientSchemesModel;
                            var sec = $scope.editableClientScheme.ClientScheme;
                            $scope.editableClientScheme.ClientScheme.security = {
                                reset_frequency: sec.security_reset_frequency,
                                incorrect_attempts: sec.security_incorrect_attempts,
                                first_login: sec.security_first_login,
                                username: sec.security_username,
                                distribution: sec.security_distribution,
                                annual_reset: sec.security_annual_reset,
                                password_uppercase: (sec.security_password_uppercase == 1)? true: false,
                                password_lowercase: (sec.security_password_lowercase == 1)? true: false,
                                password_numbers: (sec.security_password_numbers == 1)? true: false,
                                password_spec_character: (sec.security_password_spec_character == 1)? true: false
                            };

                        } else {

                            $scope.editableClientScheme.ClientScheme = {
                                life_events_effective_date: '1stFollowingM',
                                payroll_frequency: 'monthly'
                            }
                            $scope.editableClientScheme.ClientScheme.security = {
                                reset_frequency: 90,
                                incorrect_attempts: 5,
                                first_login: 'random',
                                username: 'email',
                                distribution: 'email',
                                annual_reset: 'yes',
                                password_uppercase: true,
                                password_lowercase: true,
                                password_numbers: true,
                                password_spec_character: true
                            };
                        }


                        angular.forEach($scope.benefitTypes, function (e, k) {
                            $scope.editableClientScheme.selected_benefits[k] = [];
                        })


                        //update for renewal scheme
                        $scope.editableClientScheme.selected_benefits = response.data.sel_b;

                        $scope.initDD = function () {
                            $('.dd').draggable({
                                scroll: true,
                                helper: "clone",
                                start: function () {
                                    $('#dd_zone').removeClass('none');
                                },
                                stop: function () {
                                    $('#dd_zone').addClass('none');
                                }
                            });

                            $('#dd_zone').droppable({
                                drop: function (event, ui) {
                                    var el = $(this),
                                        clone = $(ui.helper[0]),
                                        index = clone.attr('data-index'),
                                        client = clone.attr('data-client'),
                                        new_data = $scope.library_list.clients[client][index],
                                        is_duplicate = false;

                                    $scope.$apply(function () {

                                        if (!$scope.editableClientScheme.selected_benefits[new_data.BenefitType.id]) {
                                            $scope.editableClientScheme.selected_benefits[new_data.BenefitType.id] = [];
                                        }

                                        /**
                                         * Pushing two times the same Benefit will corrupt the array.
                                         * Check we don't already have that benefit inside $scope.editableClientScheme.selected_benefits before pushing it
                                         */
                                        angular.forEach($scope.editableClientScheme.selected_benefits[new_data.BenefitType.id], function (benefit, key) {
                                            if (new_data.BenefitModel.id == benefit.BenefitModel.id) {
                                                is_duplicate = true;
                                            }
                                        });

                                        /**
                                         * Push the new data only if we don't have it already
                                         */
                                        if (!is_duplicate) {
                                            $scope.editableClientScheme.selected_benefits[new_data.BenefitType.id].push(new_data);
                                        }

                                        $scope.editableClientScheme.selected_benefit_type[new_data.BenefitType.id] = true;
                                    });

                                }
                            })
                        };

                        $scope.remove_library_item = function (type, index) {
                            if (confirm('Would you like to remove it?')) {
                                $scope.editableClientScheme.selected_benefits[type].splice(index, 1);
                                if ($scope.editableClientScheme.selected_benefits[type].length == 0) {
                                    $scope.editableClientScheme.selected_benefit_type[type] = false;
                                }
                            }
                        }

                        /**
                         * panels
                         *
                         */
                        $scope.panel = {};
                        $scope.panel.tab = 1;

                        $scope.panel.selectTab = function (setTab) {
                            $scope.panel.tab = setTab;
                        };
                        $scope.panel.isSelected = function (checkTab) {
                            return $scope.panel.tab === checkTab;
                        };

                        /**
                         * Library panels
                         *
                         */
                        $scope.libraryPanel = {};
                        $scope.libraryPanel.tab = 1;

                        $scope.libraryPanel.selectTab = function (setTab) {
                            $scope.libraryPanel.tab = setTab;
                        };

                        $scope.libraryPanel.isSelected = function (checkTab) {
                            return $scope.libraryPanel.tab === checkTab;
                        };

                        /**
                         * Benefit Type Checkboxes
                         */
                        $scope.benefitTypeCheckbox = {};
                        $scope.benefitTypeCheckbox.checkboxChange = function (key) {

                            if ($scope.editableClientScheme.selected_benefits[key].length > 0 && ($scope.editableClientScheme.selected_benefit_type[key] != 1 || $scope.editableClientScheme.selected_benefit_type[key] === false)) {

                                /**
                                 *  Ask confirmation before removing the benefits
                                 */
                                if (confirm('Do you want to remove all the related benefits?')) {
                                    $scope.editableClientScheme.selected_benefits[key] = [];
                                } else {
                                    $scope.editableClientScheme.selected_benefit_type[key] = !($scope.editableClientScheme.selected_benefit_type[key]);
                                }
                            }


                        };


                        /**
                         * Init modal and pass the $scope
                         */

                        if (renew == "renew") $scope.schemeId = "";

                        modalService.hideLoader();
                        var modalInstance = $modal.open({
                            templateUrl: 'app/modules/scheme_manager/views/addSchemeModal.ctp?' + random,
                            scope: $scope,
                            keyboard:false,
                            backdrop:'static',
                            size: 'lg',
                            controller: [
                                '$scope', '$modalInstance', function ($scope, $modalInstance) {

                                    /**
                                     * When user submits form save Client on database
                                     * If we saved correctly, update the $rootScope.clients
                                     */
                                    $scope.submitForm = function () {

                                        modalService.showLoader();

//format date come from calendar
$scope.editableClientScheme.ClientScheme.effective_date = $scope.formatDate($scope.editableClientScheme.ClientScheme.effective_date);
                                        $scope.editableClientScheme.ClientScheme.annual_enrolment_period_from = $scope.formatDate($scope.editableClientScheme.ClientScheme.annual_enrolment_period_from);
                                        $scope.editableClientScheme.ClientScheme.annual_enrolment_period_to = $scope.formatDate($scope.editableClientScheme.ClientScheme.annual_enrolment_period_to);
                                        $scope.editableClientScheme.ClientScheme.plan_eligibility_date = $scope.formatDate($scope.editableClientScheme.ClientScheme.plan_eligibility_date);
                                        $scope.editableClientScheme.ClientScheme.coverage_effective_date = $scope.formatDate($scope.editableClientScheme.ClientScheme.coverage_effective_date);


                                        $http.post("angular_requests/saveClientScheme/null/" + client_id + "/" + $scope.schemeId+ "/" + renew, $scope.editableClientScheme).success(function (response) {

                                            console.log(response);
                                            if (response.result) {

                                                console.log(response);

                                                if (typeof $rootScope.schemes[client_id] == "undefined") {
                                                    $rootScope.schemes[client_id] = [];
                                                }

                                                /**
                                                 * Update client_scheme_add
                                                 * This way we will disable the 'Add Scheme' button
                                                 */
                                                $scope.client_scheme_add[client_id] = false;

                                                /**
                                                 * Update $rootScope.modules
                                                 */
                                                /*$rootScope.modules[response.data.Scheme.id] = [];
                                                 angular.forEach(response.data.Module, function (module, key) {
                                                 $rootScope.modules[response.data.Scheme.id].push(module);
                                                 });*/

                                                /**
                                                 * Update $rootScope.schemes
                                                 */
                                                /*if(!$scope.schemeId)
                                                 $rootScope.schemes[client_id].push(response.data.Scheme);*/
                                                $scope.getData();


                                                $timeout(function () {
                                                    modalService.hideLoader();
                                                    $modalInstance.close();
                                                }, 200);


                                                $rootScope.setClientMainTopRecord();

                                            } else {

                                                alert('error');

                                            }
                                        });
                                    };
                                }
                            ]
                        });

                    }
                    else {

                    }

                }
            );
        }

        /**
         * Preview with trust html because of the iframe the variables not working on the view
         * @param client_id
         * @param scheme_id
         */
        $scope.preview = function (client_id, scheme_id) {
            var random = Math.floor(Math.random() * 6) + 1;

            $scope.frame = $sce.trustAsHtml("<iframe height='900px' width='100%' src='http://rewardr-ee:8888/login/?client_id=" + client_id + "&scheme_id=" + scheme_id + "' style='border: 0px;  background: #FFF;'></iframe>");

            var modalInstance = $modal.open({
                templateUrl: "/app/modules/scheme_manager/views/preview.ctp?" + random,
                backdrop: 'static',
                keyboard: false,
                size: 'lg',
                scope: $scope,
                windowClass: 'full_screen white slide-right '
            });
        };


        $scope.unlockScheme = function (scheme_id, client_id, index) {

            if(confirm('Are you sure you want to unlock this scheme?')){
                $http.post("angular_requests/unlockScheme/null/" + scheme_id + "/" + client_id).success(function (response) {
                    $rootScope.schemes[client_id][index].activated = 0;
                    $scope.client_scheme_add[client_id] = false;
                    $rootScope.schemes[client_id][index]['status_scheme'] = 'in progress';
                });
            }
        };

        $scope.locationhref = function (url) {
            location.href = url;
        };

        /**
         * If the scheme is complete the the user can submit the scheme
         * @param scheme_id
         * @returns {boolean}
         */
        $scope.isSchemeComplete = function (scheme_id) {
            var result = true;

            angular.forEach($rootScope.modules[scheme_id], function (module, key) {
                if (module.is_complete != 1) {
                    result = false;
                }
            });

            return result;
        };

        $scope.updateUrl = function () {
            if ($scope.changeurl)
                $scope.editableClient.ClientModel.url = $scope.editableClient.ClientModel.name.replace(/ /g, '-').toLowerCase();
        };

        /**
         * Submit the scheme
         * @param scheme_id
         * @param client_id
         * @param index
         */
        $scope.submitScheme = function (scheme_id, client_id, index) {

            if(confirm('Are you sure you want to submit this scheme?')){
                var ids = $rootScope.schemes[client_id][index].enc_id;

                $http.get("/angular_requests/activateScheme/" + ids).success(function (response) {
                    if (response.result) {
                        $rootScope.schemes[client_id][index].activated = 1;
                        $scope.client_scheme_add[client_id] = true;
                        $rootScope.schemes[client_id][index]['status_scheme'] = 'submitted';
                    } else {
                        alert("The scheme is didn't submitted.");
                    }
                });
            }
        };


    }

    DashboardController.$inject = injectParams;
    app.register.controller('DashboardController', DashboardController)
});
