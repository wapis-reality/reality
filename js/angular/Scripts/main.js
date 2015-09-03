require.config({
    baseUrl: '',
    urlArgs: 'v=1.0'
});

require(
    [
        'js/angular/app/test/animations/listAnimations',
        'js/angular/app/app',
        'js/angular/app/test/directives/wcUnique',
        'js/angular/app/test/services/routeResolver',
        'js/angular/app/test/services/config',
        'js/angular/app/test/services/customersBreezeService',
        'js/angular/app/test/services/authService',
        'js/angular/app/test/services/customersService',
        'js/angular/app/test/services/dataService',
        'js/angular/app/test/services/modalService',
        'js/angular/Scripts/angular-dragdrop.min',


        'app/modules/users/js/controllers/usersController',
        'app/modules/system/js/controllers/loginController',
        'app/modules/permission/js/controllers/permissionController',


        'app/modules/companies/js/controllers/companyController',
        'app/modules/real_estates/js/controllers/realEstateController',

        //Factory
        'js/angular/factories/updateProgressBar'

        //'test/controllers/reports/ReportsController',
        //'test/controllers/benefits/trsController'

    ],
    function () {
        angular.bootstrap(document, ['test']);

    });
