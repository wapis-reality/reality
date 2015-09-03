function ChartManager(container) {
    if (typeof container === 'undefined') { var container = '#chart-manager'; }
    $container = $(container);

    var active = '';

    // load links
    $container.find('#menu').on('click', 'li', function (event) {
        event.preventDefault();

        var id = $(event.currentTarget).attr('id');
        loadChart(id, $(this));
    });

    // save button
    $container.find('#menu #save').click(function (event) {
        event.preventDefault();
        saveChart();
    });

    // save as button
    $container.find('#menu #save-as').click(function (event) {
        event.preventDefault();
        saveChartAs();
    });
    //Clone button
    $container.find('#menu #clone').click(function (event) {
        event.preventDefault();
        cloneChart();
    });

    // new button
    $container.find('#new').click(function (event) {
        event.preventDefault();
        newChart();
    });

    $container.find('#historyCheck').change(function (event) {
        event.preventDefault();
        loadChartList();
    });
    loadChartList();
    goInit({ makePalette: true, allowKeyChange: true });
    newChart();

    function loadChartList(activeId) {
        $.ajax('flowapi.php', {
            data: {
                action: 'load',
                data: '',
                history: $('#historyCheck').prop('checked')
            },
            type: 'POST',
            dataType: 'json'
        }).done(function (response) {
            if (response.result) {
                $menu = $container.find('#menu ul');
                $menu.html('');

                //for (var i = response.data.length - 1; i >= 0; i--) {
                $.each(response.data, function (id, name) {
                    /* Delete button with his events handling */
                    var deleteBtn = $('<i>').attr('class', 'icon-cancel-circled').on('click', function(e){
                        e.stopPropagation();
                        if(confirm('Do you really want to delete chart: '+name+'?')) {
                            var li = $(e.target).closest('li');
                            $.ajax('flowapi.php', {
                                data: {
                                    action: 'deleteChart',
                                    data: {
                                        id: li.attr('id')
                                    }
                                },
                                type: 'POST',
                                dataType: 'json'
                            }).done(function (response) {
                                li.remove();
                                newChart();
                            });
                        }
                    });
                    var expireBtn = $('<i>').attr('class', 'icon-calendar').attr('title','Effective date').attr('gumby-trigger',"#modal1").on('click', function(e){
                        e.stopPropagation();

                      //  var date = prompt('Please enter effective date of chart: '+name+' (format YYYY-MM-DD)')

                            $.ajax('modal.php', {
                                data: {
                                    action: 'chooseDate',
                                    id: id
                                },
                                type: 'GET',
                                dataType: 'html'
                            }).done(function (html) {
                                $('body').append(html);

                                $('.modal#modal1').addClass('active');
                                $('.close.switch').click(function(e){
                                    $('.modal#modal1').removeClass('active').remove();
                                });
                            });

                    });

                    $menu.append($('<li>').attr('id', id).append($('<a>').attr('rel', id).text(name).attr('href', '#'), deleteBtn).append(expireBtn));
                });
                if (activeId) {
                    $menu.find('li').each(function() {
                        $this = $(this);
                        if ($this.attr('id') == activeId) {
                            $this.addClass('active');
                            return false;
                        }
                    });
                }
            } else {
                alert("Error: " + response.error)
            }
        });
    }

    function loadChart(chartId, obj) {
        if (!chartId) {
            return false;
        }
        active = chartId;

        $.ajax('flowapi.php', {
            data: {
                action: 'load',
                data: chartId
            },
            type: 'POST',
            dataType: 'json'
        }).done(function (response) {
            if (response.result) {
                if (response.data.length) {
                    // clear old active item and set new
                    $container.find('#menu li').removeClass('active');
                    obj.addClass('active');

                    // populate new chart json and display
                    var newJson = response.data.replace(/\\n/g, " ").replace(/\\/g, '');
                    //var newJson = response.data.replace(/\\(?!n\b)/g, "");
                    //console.log(newJson);
                    $container.find('#flowchart-json').text(newJson);
                    //newChart();

                     load();

                } else {
                    alert("Returned true but no data");
                }
            } else {
                alert('Error: ' + response.error);
            }
        });
    }

    function sendChart(chartId, chartName, chartData, parentChartId) {
        $.ajax('flowapi.php', {
            data: {
                action: 'save',
                data: {
                    id: chartId,
                    name: chartName,
                    content: chartData,
                    parent: parentChartId
                }
            },
            type: 'POST',
            dataType: 'json'
        }).done(function (response) {
            if (response.result) {
                if(response.id){
                    chartId = response.id
                }

                loadChartList(chartId);
            } else {
                alert("Error: " + response.error);
            }
        });
    }

    function saveChart() {
        save();
        var id = $container.find('#menu li.active').attr('id');
        if (id) {
            var content = $container.find('#flowchart-json').text();
            var name = $container.find('#menu li.active').text();
            sendChart(id, name, content);
        } else {
            saveChartAs();
        }
    }

    function saveChartAs() {
        save();
        var name = prompt("Enter flowchart name:");
        if (name) {
            active = name;
            var content = $container.find('#flowchart-json').text();
            sendChart(null, name, content);
        }
    }
    function cloneChart() {
        save();
        var id = $container.find('#menu li.active').attr('id');
        if (id) {
            var addName = prompt("Enter flowchart additional name (will be added to curren chart name):");
            if(addName == '') {
                 addName = 'v2';
            }
            var name = $container.find('#menu li.active').text();
                name = name + ' ' + addName;

            var content = $container.find('#flowchart-json').text();

            sendChart(null, name, content, id);

        }else{
            alert('Choose which chart should be cloned first')
        }
    }

    function newChart() {
        active = '';
        $container.find("#flowchart-json").text('{ "class": "go.GraphLinksModel", "linkFromPortIdProperty": "fromPort", "linkToPortIdProperty": "toPort", "nodeDataArray": [], "linkDataArray": []}');
        load();
    }
}

function JourneyViewer(container) {
    if (typeof container === 'undefined') { var container = '#journey-viewer'; }
    $container = $(container);



    //console.log('insidejounernyviewer');

    //loadChartList();
    goInit({ makePalette: false, allowKeyChange: false, readOnly: true });
    setEvents();

    $('#date').datepicker({dateFormat: 'yy-mm-dd',onSelect: function(dateText){ var choosed = null; if($('#flow').val() != ''){ choosed = $('#flow').val(); } loadChartList(choosed, dateText); }});

    function loadChartList(idChartActive, date) {
        var $menu = $container.find('select#flow');
        $menu.html('');

        var dataObj = {
            action: 'load',
            data: ''
        };
        if(typeof date != 'undefined'){
            dataObj['date'] = date;
        }
        $.ajax('flowapi.php', {
            data: dataObj,
            type: 'POST',
            dataType: 'json'
        }).done(function (response) {
            if (response.result) {
                $.each(response.data, function (id, name) {
                //for (var i = response.data.length - 1; i >= 0; i--) {
                    //var name = response.data[i];
                    var opt = $('<option>').attr('value', id).text(name);
                    if(typeof idChartActive != 'undefined' && id == idChartActive){
                        opt.attr('selected','selected');
                    }
                    $menu.append(opt);
                });
                if(typeof idChartActive != 'undefined'){
                    var choosed = idChartActive;
                }else {
                    var choosed = $menu.find('option:selected').val()
                }
                loadChart(choosed); // load an initial one
            } else {
                alert("Error: " + response.error)
            }
        });
    }

    function loadChart(chartId, obj) {
        if (!chartId) {
            return false;
        }

        $.ajax('flowapi.php', {
            data: {
                action: 'load',
                data: chartId
            },
            type: 'POST',
            dataType: 'json'
        }).done(function (response) {
            if (response.result) {
                if (response.data.length) {
                    // populate new chart json and display
                    var newJson = response.data.replace(/\\n/g, " ").replace(/\\/g, '');

                    //var newJson = response.data.replace(/\\(?!n\b)/g, "");
                    //console.log(newJson);
                    $container.find('#flowchart-json').text(newJson);
                    //newChart();
                    //console.log(response.data);
                    load();
                } else {
                    alert("Returned true but no data");
                }
            } else {
                alert('Error: ' + response.error);
            }
        });
    }

    function loadJourney(firstcoll_id, date, flowId) {
        if (!(firstcoll_id && date && flowId)) {
            alert('Please make sure you\'ve entered a NINO, date and flowchart');
        } else {




            // preloader on

            var $jsonContainer = $('#flowchart-json');

            var chartData = JSON.parse($jsonContainer.text());

            var nodes = chartData.nodeDataArray;
            var links = chartData.linkDataArray;
            if(nodes) {
                for (var j = 0; j < nodes.length; j++) {
                    delete(nodes[j].color);
                }
            }
            if(links) {
                for (var j = 0; j < links.length; j++) {
                    delete(links[j].color);
                }
            }
//'test.json'
            $.ajax('flowapi.php', {
                data: { action: 'search', firstcoll_id: firstcoll_id, date: date, flowId: flowId},
                dataType: 'json',
                type: 'POST'
            }).done(function(response) {

                //console.log(response);
                if (response.result) {
                    if (response.data) {



                        nodes = highlightNodes(nodes, response.data);

                        links = highlightLinks(links, response.data);

                        // update chart data with new node array
                        chartData.nodeDataArray = nodes;

                        // update JSON and reload chart
                        $jsonContainer.text(JSON.stringify(chartData));

                        load();

                    } else {
                        if(response.error){
                            alert(response.error);
                        }else {
                            alert("Returned true but no data");
                        }
                    }
                } else {
                    alert("Server says no (" + JSON.stringify(response) + ")");
                }
            })

            // preloader off
        }       
    }

    function highlightNodes(nodes, keys) {

        // highlights nodes which are in list of key
        var visitedColor = '#ff6600';
        // loop through the 'visited' keys
        for (var i = keys.length - 1; i >= 0; i--) {


            var visitedKey = keys[i].key;


            // for each one find the matching node in the full chart and add 'visited' prop
            for (var j = nodes.length - 1; j >= 0; j--) {

                //console.log(nodes[j].key);
                if (nodes[j].key === visitedKey) {

                    var formated = '';
                    if( keys[i].data &&  keys[i].data != '') {

                        // enrico commented this
//                        var objData = JSON.parse((keys[i].data).replace(/\\/g,''));
//                        if(objData) {
//                            jQuery.each(objData, function(atr, val){
//                                formated += '\n'+atr+':'+val;
//                            });
//                        }
//                        nodes[j].data = formated;
                    }else{

                        nodes[j].data = '';
                    }
                    nodes[j].visited = true;
                    nodes[j].color = visitedColor;

                }
            }
        };



        return nodes;
    }

    function highlightLinks(links, keys) {
        // highlight links where both nodes are in keys

        for (var i = keys.length - 1; i >= 0; i--) {
            keys[i] = keys[i].key;
        }
        var visitedColor = '#ff6600';
        if(keys.length > 0) {
            for (var i = links.length - 1; i >= 0; i--) {
                var link = [links[i].from, links[i].to];
                var firstKey = -1;

                // check if link's from and to are both visited
                if ((firstKey = keys.indexOf(link[0])) > -1 && keys.indexOf(link[1]) === firstKey + 1) {
                    links[i].visited = true;
                    links[i].color = visitedColor;
                }
            }
            ;
        }
        return links;
    }

    function setEvents() {
        var $form = $container.find('#journey-finder'),
            firstcoll_id = $form.find('#firstcoll_id').val(),
            date = $form.find('#date').val(),
            flow = $form.find('#flow').val();

        if(firstcoll_id != '' && date != '' && flow != ''){
            loadJourney(firstcoll_id, date, flow);
        }

        $container.find('#journey-finder select#flow').change(function() {
                loadChart($(this).val());
        });

        $container.find('#load-journey').click(function() {
            var $form = $container.find('#journey-finder'),
                firstcoll_id = $form.find('#firstcoll_id').val(),
                date = $form.find('#date').val(),
                flow = $form.find('#flow').val();

            loadJourney(firstcoll_id, date, flow);
        });


        $('body').delegate('.load_flow', 'click', function(e){
            e.preventDefault();

            var my_date = $(this).data('date'),
                my_firstcolls_id = $(this).data('eeid'),
                flow = 3;

            loadJourney(my_firstcolls_id, my_date, flow);
            $('a[href="#tabFlows"]').click();
        });




    }


}

// set up the page //

$(function () {

//    function adjustFlowContainer() {
//        var $chart = $('#flowchart');
//        $chart.height(
//            $(window).height() - $chart.offset().top
//        );
//    }
//    $(window).resize(adjustFlowContainer);
//    adjustFlowContainer();
    
    var chartMgrContainer = '#chart-manager',
        journeyViewContainer = '#journey-viewer';

    if ($(journeyViewContainer).length) {
        var vwr = new JourneyViewer();
    }


});