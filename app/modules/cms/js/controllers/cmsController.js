'use strict';

define(['js/angular/app/app'], function (app) {
    var injectParams = ['$scope', '$http', '$sce', '$modal', '$location', '$filter', '$window', '$timeout', 'authService', 'dataService', 'modalService'],
        CmsController = function ($scope, $http, $sce, $modal, $location, $filter, $window, $timeout, authService, dataService, modalService) {

            $http.get('angular_requests/initEditor/' + $scope.modal_enc_id).success(function (res) {
                if (res.result === true) {
                    console.log(res.data.menu_items);
                    $scope.layouts = res.data.layouts;
                    $scope.menu_items = res.data.menu_items;
                    $scope.widgets = res.data.widgets;

                    var ww = '';
                    angular.forEach($scope.widgets, function(w){
                        ww += w.content;
                    })
                    $scope.widgetsHtml = $sce.trustAsHtml(ww);


                    $scope.selectedPage = $scope.menu_items[0].MenuItemModel.id;
                    $scope.random = Math.random();

                    window.openedToolbar = false;


                    window.updatePrimaryColor = function(color){
                        $('.bt_primary_color').css({'border-top-color':color});
                        $('.f_primary_color').css({'color':color});
                        $('.bg_primary_color').css({'background-color':color});
                    };
                    window.updateSecondaryColor = function(color){
                        $('.bt_secondary_color').css({'border-top-color':color});
                        $('.f_secondary_color').css({'color':color});
                        $('.bg_secondary_color').css({'background-color':color});
                    };

                    setTimeout(function() {
                        pages.clientDesign();

                        $('.primary_color').colorpicker().on('changeColor.colorpicker', function(event){
                            window.updatePrimaryColor(event.color.toHex());
                        }).on('hidePicker.colorpicker', function(event){
                            var param = 'primary_color', ClientId = $('#ClientId').val(), url = '/angular_requests/clientSettingEditor/' + ClientId + '/' + param + '/', post = {data: {}};
                            post.data[param] = event.color.toHex();
                            $.ajax({dataType: "json", url: url, type: "POST", data: post}).done(function (json) {if (json) {if (json.result === true) {}}});
                        });

                        $('.secondary_color').colorpicker().on('changeColor.colorpicker', function(event){
                            window.updateSecondaryColor(event.color.toHex());
                        }).on('hidePicker.colorpicker', function(event){
                            var param = 'secondary_color', ClientId = $('#ClientId').val(), url = '/angular_requests/clientSettingEditor/' + ClientId + '/' + param + '/', post = {data: {}};
                            post.data[param] = event.color.toHex();
                            $.ajax({dataType: "json", url: url, type: "POST", data: post}).done(function (json) {if (json) {if (json.result === true) {}}});
                        });

                        $('.background_color').colorpicker().on('changeColor.colorpicker', function(event){
                            $('.bc_bg_color, .bg-white').css({background:event.color.toHex()});
                        });

                        $('.text_color').colorpicker().on('changeColor.colorpicker', function(event){
                            $('.bc_tt_color').css({color:event.color.toHex()});
                        });
                    }, 500);


                } else {
                    console.error('Problem with the request (' + $scope.link + ')');
                }

            }).error(function (data) {
                console.log('Error');
            });
        };

    CmsController.$inject = injectParams;
    app.controller('CmsController', CmsController);
});
