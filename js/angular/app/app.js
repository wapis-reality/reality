﻿'use strict';

define(['js/angular/app/test/services/routeResolver'], function () {

    var app = angular.module('test', ['ngRoute', 'ngAnimate', 'routeResolverServices',
        'wc.directives', 'wc.animations', 'ui.bootstrap', 'breeze.angular']);

    app.config(['$routeProvider', 'routeResolverProvider', '$controllerProvider',
        '$compileProvider', '$filterProvider', '$provide', '$httpProvider', '$locationProvider',

        function ($routeProvider, routeResolverProvider, $controllerProvider,
                  $compileProvider, $filterProvider, $provide, $httpProvider, $locationProvider) {

            //Change default views and controllers directory using the following:
            //routeResolverProvider.routeConfig.setBaseDirectories('/app/views', '/app/controllers');

            app.register =
            {
                controller: $controllerProvider.register,
                directive: $compileProvider.directive,
                filter: $filterProvider.register,
                factory: $provide.factory,
                service: $provide.service
            };

            //Define routes - controllers will be loaded dynamically
            var route = routeResolverProvider.route;

            $locationProvider.html5Mode({ //Remove hash from url
                enabled: true,
                requireBase: true
            });
            $routeProvider
                //route.resolve() now accepts the convention to use (name of controller & view) as well as the
                //path where the controller or view lives in the controllers or views folder if it's in a sub folder.
                //For example, the controllers for customers live in controllers/customers and the views are in views/customers.
                //The controllers for orders live in controllers/orders and the views are in views/orders
                //The second parameter allows for putting related controllers/views into subfolders to better organize large projects
                //Thanks to Ton Yeung for the idea and contribution


                //.when('/', route.resolve('Dashboard', 'scheme_manager/', 'vm', false, '/app/modules/scheme_manager/views/dashboard'))
                .when('/users', route.resolve('Users', 'users/', 'vm', false, '/app/modules/users/views/users'))
                .when('/permission', route.resolve('Permission', 'permission/', 'vm', false, '/app/modules/permission/views/permission'))


                .when('/companies', route.resolve('Company', 'companies/', 'vm', false, '/app/modules/companies/views/index'))
                .when('/real_estates', route.resolve('RealEstate', 'real_estates/', 'vm', false, '/app/modules/real_estates/views/index'))

                .when('/login', route.resolve('Login', 'system/', 'vm', false, '/app/system/views/actions/system/login'))
                .otherwise({ redirectTo: '/login' });


        }]);

    app.run(['$rootScope', '$location', 'authService','$http','modalService',
        function ($rootScope, $location, authService,$http,modalService) {

            //Client-side security. Server-side framework MUST add it's 
            //own security as well since client-based security is easily hacked
            $rootScope.$on("$routeChangeStart", function (event, next, current) {
                if (next && next.$$route && next.$$route.secure) {
                    if (!authService.user.isAuthenticated) {
                        $rootScope.$evalAsync(function () {
                            authService.redirectToLogin();
                        });
                    }
                }
            });

            /**
             * Load the current user details and add to rootScope
             */
            //$http.get("syste/getUser").success(function (response) {
            //    $rootScope.user.last_name = response.user.name;
            //    $rootScope.user.first_name = response.user.name;
            //});



            //Global function (My profile, Change password)

            /**
             * Edit my profile
             * @param id
             * @param index
             */
            $rootScope.editWindow_myprofile = function(id,index) {
                function initWin(response) {
                    $rootScope.edit = response;
                }

                $http.get("system/angular_request/getUser/null/"+id).success(function (response) {
                    initWin(response.data);

                    modalService.showModal({}, {
                        scope: $rootScope,
                        templateUrl: '/app/modules/users/views/change_details.ctp?r=' + Math.random()
                    }).then(function (result) {
                        if (result === 'submit') {
                            $http.post("system/angular_request/saveUser", $rootScope.edit).success(function (response) {
                                //Update scope
                            });
                        }

                        delete $rootScope.edit; //Remove this rootScope
                    });
                });
            };

            /**
             * Edit my profile
             * @param id
             * @param index
             */


                    $rootScope.user = {};
                    $http.get("/system/getUserData/").success(function (res) {

                        if (res.result === true) {
                            $rootScope.user = res.data;
                            return true;
                        } else {
                            console.error('Problem with the request (/system/getUserData/)');
                            return false;

                        }


                    });



            /**
             * Change my password
             * @param id
             * @param index
             */
            $rootScope.editWindow_change_password = function(id,index) {

                $rootScope.edit = {};
                $rootScope.edit.user = {};

                $rootScope.edit.changePw = function($modalInstance) {
                    $http.post("system/change_password", $rootScope.edit).success(function (response) {
                        if(!response.data.error) {

                            $modalInstance.close('close');
                        } else {
                            alert(response.data.message);
                        }
                    });
                }

                modalService.showModal({}, {
                    scope: $rootScope,
                    templateUrl: '/app/modules/users/views/change_password.ctp?r=' + Math.random()
                }).then(function (result) {
                    if (result === 'submit') {

                    }

                    delete $rootScope.edit; //Remove this rootScope
                });
            }


        }]).directive('bindUnsafeHtml', ['$compile', function ($compile) {
        return function(scope, element, attrs) {
            console.log("in directive");
            scope.$watch(
                function(scope) {
                    // watch the 'bindUnsafeHtml' expression for changes
                    return scope.$eval(attrs.bindUnsafeHtml);
                },
                function(value) {
                    // when the 'bindUnsafeHtml' expression changes
                    // assign it into the current DOM
                    element.html(value);

                    // compile the new DOM and link it to the current
                    // scope.
                    // NOTE: we only compile .childNodes so that
                    // we don't get into infinite loop compiling ourselves
                    $compile(element.contents())(scope);
                }
            );
        };
    }]).directive('editButton', function () {
        return {
            restrict: 'E',
            link: function(scope, $element){
                scope.editAuto = function(id, prefix, index){
                    scope["editWindow_"+prefix](id,index)
                }
            }
        }

    })

        .directive('datePicker', function () {
        return function ($scope, $element) {

            $scope.dateOpen = function($event,opened) {
                if(opened == "effective_date"){
                    var diff = 0;
                     if($scope.editableClientScheme.ClientScheme.created) {
                         //find diff between 2 dates
                         var date1 = new Date();
                         var date2 = new Date($scope.editableClientScheme.ClientScheme.created);
                         diff = Math.round((date1 - date2) / (1000 * 3600 * 24));
                     }
                     var num = 42 - diff; console.log(num);
                    $scope.minDate = new Date();
                    $scope.minDate.setDate($scope.minDate.getDate() + num);

                    //setting default value
                    /*if(!$scope.editableClientScheme.ClientScheme.effective_date)
                        $scope.editableClientScheme.ClientScheme.effective_date = $scope.minDate;*/
                }
                else
                    $scope.minDate = "";
                $scope.datestatus[opened] = true;
            };
            $scope.datestatus = {
                opened: false
            };
        }
    })

    .directive('showErrors', function() {
        return {
            restrict: 'A',
            require: '^form',
            link: function (scope, el, attrs, formCtrl) {
                // find the text box element, which has the 'name' attribute
                var inputEl   = el[0].querySelector("[name]");
                // convert the native text box element to an angular element
                var inputNgEl = angular.element(inputEl);
                // get the name on the text box
                var inputName = inputNgEl.attr('name');

                // only apply the has-error class after the user leaves the text box
                inputNgEl.bind('blur', function() {
                    el.toggleClass('has-error', formCtrl[inputName].$invalid);
                });

                scope.$watch(function() {
                    return scope.showErrorsCheckValidity;
                }, function(newVal, oldVal) {
                    if (!newVal) { return; }
                    el.toggleClass('has-error', formCtrl[inputName].$invalid);
                });
            }
        }
    })


    .directive('calendar', function () {
        return {
            require: 'ngModel',
            link: function (scope, el, attr, ngModel) {
                $(el).datepicker({
                    dateFormat: 'yyyy-mm-dd',
                    format: 'yyyy-mm-dd',
                    onSelect: function (dateText) {
                        scope.$apply(function () {
                            ngModel.$setViewValue(dateText);
                        });
                    }
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });
            }
        };
    }).filter('nl2br', ['$sce', function ($sce) {
        return function (text) {
            console.log(text);
            return text ? $sce.trustAsHtml(text.replace(/\n/g, '<br/>')) : '';
        };
    }]);

    return app;

});