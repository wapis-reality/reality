'use strict';

define(['js/angular/app/app'], function (app) {
    app.factory('updateProgressBar', ['$http','$rootScope', function ($http,$rootScope) {
        return  {
            start: function(module) {
                this.getData(module);
            },

            getData: function(module) {
                $http.post("angular_requests/calculateQuestions/" + $rootScope.modal_enc_id, {"action": "getStatus","module": module}).success(function (response) {
                    var scheme = response.data.scheme;

                    var scheme_id = scheme.SchemeModuleModel.scheme_id;
                    var total_questions = scheme.SchemeModuleModel.total_questions;
                    var answered_questions = scheme.SchemeModuleModel.answered_questions;
                    var index = "";

                    angular.forEach($rootScope.modules[scheme_id], function (moduleJson, key) {
                        if(moduleJson.module == module) {
                            index = key;
                        }
                    });

                    //$rootScope.modules[scheme_id][index].answered_questions = 1;
                    if(typeof answered_questions != "undefined") {
                        $rootScope.modules[scheme_id][index].answered_questions = answered_questions;
                    }

                    if(typeof total_questions != "undefined") {
                        $rootScope.modules[scheme_id][index].total_questions = total_questions;
                    }

                    $rootScope.setClientMainTopRecord();

                    if($rootScope.modules[scheme_id][index].answered_questions == $rootScope.modules[scheme_id][index].total_questions) {
                        $rootScope.modules[scheme_id][index].is_complete = 1;

                    } else {
                        $rootScope.modules[scheme_id][index].is_complete = 0;
                    }
                });
            }
        };
    }]);
});