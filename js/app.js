var angularDynamic = angular.module('angularDynamic', ['ui.bootstrap','dndLists']);

var angularRoute = angular.module('angularRoute', [
    'ngRoute',
    'angularDynamic',
    'dndLists'
]);

/**
 * @todo In the url we need the # character, we have to chande that. (#/page to /page)
 * Routing It will decide which controller you will use and which template.
 */
angularRoute.config(function($routeProvider,$locationProvider) {

    $locationProvider.html5Mode(true);

    $routeProvider.
        when('/dashboard', { templateUrl: 'app/views/pages/dashboard.ctp',  controller: 'DashboardController' }).
        when('/', { templateUrl: 'app/views/pages/dashboard.ctp',  controller: 'DashboardController' }).
        when('/reports', { template: '', controller: 'getJsonData'}).
        otherwise({ template: '',  controller: 'getJsonData' });

    });

//=============================================================================================================================================================================================
//Template controller

angularDynamic.controller("getJsonData", ['$scope', '$http', function ($scope, $http) {
    $http.get("angular/getJsonData/getReports").success(function (res) {
        $scope.data = res;
    });
}]);



/**
 * Render Index
 */
angular.module('angularDynamic').controller('renderController', function ($scope, $http, $sce, $modalInstance) {

    $scope.currentPage = 1;
    $scope.numPerPage = 2;
    $scope.maxSize = 5;
    $scope.itemsPerPage = 2;

    $http.get($scope.link).
        success(function (data) {
            $scope.html = $sce.trustAsHtml(data.html);
            $scope.detail = data.detail;
            $scope.listings = data.detail.items;
        }).
        error(function (data) {
            console.log('Error');
        });

    //pagination bootstrap component. This function will update the record according to page number and other options
    $scope.pagination = function () {
        $scope.$watch('currentPage + numPerPage', function () {
            var begin = (($scope.currentPage - 1) * $scope.numPerPage)
                , end = begin + $scope.numPerPage;

        });
    };
});


/**
 * modal
 */
angular.module('angularDynamic').controller('modalController', function ($scope, $http, $sce, $modalInstance) {
    //this function will be called when clicks on submit button
    $scope.submit = function () {
        $http({
            url: $(".form-default").attr("action") + "?" + $(".form-default").serialize(),
            method: "get"
        }).success(function (data) {
            if (data.result) {
                $scope.getTemplate();
                $modalInstance.close();
            }
        });
    };

    //To bring edit view and json object from a url
    $http.get($scope.link).
        success(function (data) {
            $scope.html = $sce.trustAsHtml(data.html);
            $scope.detail = data.detail;
        }).
        error(function (data) {
            console.log('Error');
        });
});


/**
 * Dynamic directive compiles the angular code with the html (dynamically generated by modal controller)
 */
angular.module('angularDynamic').directive('dynamic', function ($compile, $timeout) {
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

angular.module("demo", ["dndLists"])


//================================================================================================================================================================================================
//Global controller

//var angularDynamic = angular.module('angularDynamic', []);
//var angularRoute = angular.module('angularRoute', [
//    'ngRoute',
//    'angularDynamic'
//]);
//
//angularRoute.config(['$routeProvider',
//    function($routeProvider) {
//        console.log($routeProvider);
//        $routeProvider.
//            when('/reports', {
//                controller: 'getJsonData'
//                //redirectTo: '/login'
//            }).
//            otherwise({
//                controller: 'getJsonData',
//                templateUrl: "views/controller/index.ctp"
//                //redirectTo: '/login'
//            });
//}]);
//
//
////Fix template
//angularDynamic.controller("getJsonData", ['$scope', '$http', function ($scope, $http) {
//    alert("asdd");
//        $http.get("ajax/getJsonData/userAuth").success(function (res) {
//            $scope.data = res;
//        });
//}]);


//phonecatControllers.controller('PhoneListCtrl', ['$scope', '$http',
//    function ($scope, $http) {
//        $http.get('phones/phones.json').success(function(data) {
//            $scope.phones = data;
//        });
//
//        $scope.orderProp = 'age';
//    }]);
//
//phonecatControllers.controller('PhoneDetailCtrl', ['$scope', '$routeParams',
//    function($scope, $routeParams) {
//        $scope.phoneId = $routeParams.phoneId;
//    }]);


//angular.module('sipModule', ['ui.bootstrap']); //create a angular module by using ui-bootstrap service
//
//angular.module('sipModule').controller('mainController', ["$rootScope", "$scope", "$filter", "$modal", "$http", function ($rootScope, $scope, $filter, $modal, $http) {
//
//
////View functionality
//    $scope.currentUrl = window.location.pathname;
////paging options
//    $scope.currentPage = 1
//        , $scope.numPerPage = 5
//        , $scope.maxSize = 5
//        , $scope.itemsPerPage = 2;
//
//    $scope.getTemplate = function () {
//        console.log('HERE');
//
//        $http.get($scope.currentUrl.replace("/index", "/get_data"))
//            .success(function (data) {
//                console.log(data);
//                $scope.global_view = data.data.global_view;
//                $scope.global_items = data.data.global_items;
//                $scope.paging = data.data.paging;
//                $scope.buttonsPossibility = data.data.buttonsPossibility;
//                $scope.todos = $scope.paging.total;
//                $scope.enc_id = data.data.enc_id;
//                $scope.listings = data.data.global_items;
//                $scope.pagination();
//            });
//
//        //pagination bootstrap component. This function will update the record according to page number and other options
//        $scope.pagination = function () {
//            $scope.$watch('currentPage + numPerPage', function () {
//                var begin = (($scope.currentPage - 1) * $scope.numPerPage)
//                    , end = begin + $scope.numPerPage;
//
//                $http.get($scope.currentUrl.replace("/index", "/get_data") + "/?page=" + $scope.currentPage)
//                    .success(function (data) {
//                        $scope.listings = data.data.global_items;
//                    });
//
//            });
//        };
//    };
//
//    console.log('QQQQ');
//    $scope.getTemplate();
//}]);
//
//
////Edit functionality
////    $scope.template = '' +
////        '<div class="modal-header clearfix text-left"><button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button><h5><span id="domwin-title">{{title}}</span></h5></div><div class="modal-body" ng-bind-html="html" dynamic></div>';
////
////    //function when clicks on Edit button
////    $scope.editEvent = function (link, obj) {
////        $scope.title = obj.target.attributes['title'].value;
////        $scope.link = link;
////        //modal functionality from ui-bootstrap
////        var modalInstance = $modal.open({
////            template: $scope.template,
////            controller: 'modalController', //modal is connected this controller in case of adding extra functionality
////            backdrop: 'static',
////            keyboard: false,
////            size: 'lg',
////            scope: $scope
////        })
////    };
////}]);
//
//
//

//

//
////var sip = angular.module("sip",[]);