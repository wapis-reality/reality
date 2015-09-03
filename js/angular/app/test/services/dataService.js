'use strict';

define(['js/angular/app/app', 'js/angular/app/test/services/customersBreezeService',
        'js/angular/app/test/services/customersService'], function (app) {

    var injectParams = ['config', 'customersService', 'customersBreezeService'];

    var dataService = function (config, customersService, customersBreezeService) {
        return (config.useBreeze) ? customersBreezeService : customersService;
    };

    dataService.$inject = injectParams;

    app.factory('dataService',
        ['config', 'customersService', 'customersBreezeService', dataService]);

});

