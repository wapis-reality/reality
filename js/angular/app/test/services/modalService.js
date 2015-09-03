"use strict";

define(['js/angular/app/app'], function (app) {

    var injectParams = ['$modal'];

    var modalService = function ($modal) {

        var modalDefaults = {
            backdrop: false,
            keyboard: false,
            modalFade: true,
            //template: '<div ng-bind-html="htmlEdit" dynamic></div>',
            templateUrl: '',
            windowClass: '',
            scope: '',
            size: 'lg',
        };

        var modalOptions = {
            templateUrl: '',
            windowClass: '',
            scope: '',
            size: '',
            keyboard: ''

        };

        this.showModal = function (customModalDefaults, customModalOptions) {
            customModalOptions.template =  (typeof customModalOptions.template != 'undefined') ? customModalOptions.template : modalDefaults.template;
            customModalOptions.size =  (typeof customModalOptions.size != 'undefined') ? customModalOptions.size : modalDefaults.size;
            customModalDefaults.windowClass =  (customModalOptions.windowClass != '') ? customModalOptions.windowClass : modalDefaults.windowClass;
            customModalDefaults.scope =  (customModalOptions.scope != '') ? customModalOptions.scope : modalDefaults.scope;
            customModalDefaults.templateUrl =  (customModalOptions.templateUrl != '') ? customModalOptions.templateUrl : modalDefaults.templateUrl;

            if (!customModalDefaults) customModalDefaults = {};
            customModalDefaults.backdrop = 'static';
            return this.show(customModalDefaults, customModalOptions);
        };

        this.show = function (customModalDefaults, customModalOptions) {

            //Create temp objects to work with since we're in a singleton service
            var tempModalDefaults = {};
            var tempModalOptions = {};

            //Map angular-ui modal custom defaults to modal defaults defined in this service
            angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);

            //Map modal.html $scope custom properties to defaults defined in this service
            angular.extend(tempModalOptions, modalOptions, customModalOptions);

            if (!tempModalDefaults.controller) {
                tempModalDefaults.controller = function ($scope, $modalInstance) {
                    $scope.modalOptions = tempModalOptions;
                    $scope.modalOptions.submit = function(func) {
                        if(typeof func == "function") {
                            func($modalInstance);
                        } else {
                            $modalInstance.close('submit');
                        }
                    };

                    $scope.modalOptions.close = function (result) {
                        $modalInstance.dismiss('cancel');
                    };
                };

                tempModalDefaults.controller.$inject = ['$scope', '$modalInstance'];
            }

            return $modal.open(tempModalDefaults).result;
        };

        //additional function fro preloader
        this.showLoader = function(){
            $("#loader").css("display","block");
        };
        this.hideLoader = function(){
            $("#loader").css("display","none");
        }
    };

    modalService.$inject = injectParams;

    app.service('modalService', modalService);

    app.directive('dynamic', function ($compile, $timeout) {
        return {
            restrict: 'A',
            replace: true,
            link: function (scope, ele, attrs) {
                $timeout(function () {
                    console.log(attrs);
                    scope.$watch(attrs.dynamic, function (html) {
                        ele.html(html);
                        $compile(ele.contents())(scope);
                    });

                }, 500);
            }
        }
    });


});
