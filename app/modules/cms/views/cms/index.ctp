<link type="text/css" rel="stylesheet" href="/app/modules/cms/assets/css/style.css?r={{random}}">
<link type="text/css" rel="stylesheet" href="/app/modules/cms/assets/css/docs.css?r={{random}}">
<link type="text/css" rel="stylesheet" href="/app/modules/cms/assets/css/bootstrap-colorpicker.css?r={{random}}">
<script src="/app/modules/cms/js/scripts/cms.js" type="application/javascript"></script>

<script src="/app/modules/cms/js/scripts/docs.js"></script>
<script src="http://rewardr-core:8888/app/widgets" type="application/javascript"></script>

<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close">
        <i class="pg-close fs-14"></i>
    </button>
    <h5><span id="domwin-title">{{title}}</span></h5>
</div>
<div class="modal-body" style="padding: 0;">
    <div class="toolbar" style="">
        <input type="hidden" id="ClientId" value="{{modal_enc_id}}"/>

        <div class="group">
            <div class="elements">
                <div class="element">
                    <div class="col-sm-6" style="padding: 0 2px;font-size: 10px;text-transform: uppercase;position: relative;top: 5px;text-align: center;">Page</div>
                    <div class="col-sm-6" style="padding: 0 2px;font-size: 10px;text-transform: uppercase;position: relative;top: 5px;text-align: center;">Version</div>
                    <br class="clearfix"/>

                    <div class="col-sm-6" style="padding: 2px">
                        <select id="ClientPageId" ng-model="selectedPage" class="d-select full-width">
                            <option value="{{item.MenuItemModel.id}}" ng-repeat="item in menu_items" ng-selected="item.MenuItemModel.id==selectedPage">{{item.MenuItemModel.name}}</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="padding: 2px">
                        <select id="ClientVersionPageId" data-init-plugin="" class="d-select full-width">

                        </select>
                    </div>
                </div>
                <div class="element">
                    <i class="fa fa-tasks" data-toggle="modal" data-target="#menuPanel" style="position: relative; text-outline: none;px"></i>
                </div>

            </div>
            <div class="title">Pages</div>
        </div>
        <div class="sep"></div>


        <div class="group wisiwyg_tool none">
            <div class="wysiwyg elements">
                <button rel='bold' class="btnTool"><i class="fa fa-bold"></i></button>
                <button rel='italic' class="btnTool"><i class="fa fa-italic"></i></button>
                <button rel='underline' class="btnTool"><i class="fa fa-underline"></i></button>
                <button rel='justifyleft' class="btnTool"><i class="fa fa-align-left"></i></button>
                <button rel='justifycenter' class="btnTool"><i class="fa fa-align-center"></i></button>
                <button rel='justifyright' class="btnTool"><i class="fa fa-align-right"></i></button>
                <br/>
                <button rel='link' class="btnTool"><i class="fa fa-link"></i></button>
                <button rel='InsertOrderedList' class="btnTool"><i class="pg-ordered_list"></i></button>
                <button rel='InsertUnorderedList' class="btnTool"><i class="fa  fa-list-ul"></i></button>
                <button rel='unlink' class="btnTool"><i class="fa fa-unlink"></i></button>
                <button rel='removeFormat' class="btnTool"><i class="fa fa-remove"></i></button>
                <button rel='image' class="btnTool"><i class="fa  fa-image"></i></button>
                <button rel='insertParagraph' class="btnTool">p</button>

                <input rel="foreColor" type="color" class="btnTool" style="position: relative; width: 41px; border: 0px none; background: none repeat scroll 0% 0% transparent; height: 20px; top: 6px;"/>
            </div>
            <div class="title">Text format</div>
        </div>
        <div class="sep wisiwyg_tool none"></div>

        <!--div class="group">
            <div class="elements layout">
                <div class="element" style="min-width: 45px">
                    <div style="border:1px solid #ccc; margin:0; " class="row layout_option" rel="1">
                        <div class="b-b b-grey" style="height:5px"></div>
                        <div class="col-sm-8 b-r b-grey no-padding" style="height: 20px;"></div>
                        <div class="col-sm-4 no-padding" style="height: 20px;"></div>
                        <div class="b-t b-grey" style="clear:both; height:5px"></div>
                    </div>
                </div>
                <div class="element" style="min-width: 45px">
                    <div style="border:1px solid #ccc; margin:0;" class="m-t-15 row layout_option" rel="2">
                        <div class="b-b b-grey" style="height:5px"></div>
                        <div class="col-sm-3 b-r b-grey no-padding" style="height: 20px;"></div>
                        <div class="col-sm-6 b-r b-grey no-padding" style="height: 20px;"></div>
                        <div class="col-sm-3 no-padding" style="height: 20px;"></div>
                        <div class="b-t b-grey" style="clear:both; height:5px"></div>
                    </div>
                </div>
                <div class="element" style="min-width: 45px">
                    <div style="border:1px solid #ccc; margin:0; " class="m-t-15 row layout_option" rel="3">
                        <div class="b-b b-grey" style="height:5px"></div>
                        <div class="col-sm-12 no-padding" style="height: 20px;"></div>
                        <div class="b-t b-grey" style="clear:both; height:5px"></div>
                    </div>
                </div>
            </div>
            <div class="title">Layout options</div>
        </div-->

<!--        <div class="sep"></div>-->
        <div class="group">
            <div class="elements widgets">
                <div class="copy_drag element" ng-bind-html="widgetsHtml">

                </div>
            </div>
            <div class="title">Modules</div>
        </div>
        <div class="sep"></div>
        <div class="group">
            <div class="element">
                <div class="col-sm-6" style="padding: 0 2px;font-size: 10px;text-transform: uppercase;position: relative;top: 5px;text-align: center;">Primary</div>
                <div class="col-sm-6" style="padding: 0 2px;font-size: 10px;text-transform: uppercase;position: relative;top: 5px;text-align: center;">Secondary</div>
<!--                <div class="col-sm-3" style="padding: 0 2px;font-size: 10px;text-transform: uppercase;position: relative;top: 5px;text-align: center;">Background</div>-->
<!--                <div class="col-sm-3" style="padding: 0 2px;font-size: 10px;text-transform: uppercase;position: relative;top: 5px;text-align: center;">Text</div>-->
                <br class="clearfix"/>

                <div class="col-sm-6" style="padding: 2px"><input type="text" class="primary_color" value="#5367ce"/></div>
                <div class="col-sm-6" style="padding: 2px"><input type="text" class="secondary_color" value="#5367ce"/></div>
<!--                <div class="col-sm-3" style="padding: 2px"><input type="text" class="background_color" value="#5367ce"/></div>-->
<!--                <div class="col-sm-3" style="padding: 2px"><input type="text" class="text_color" value="#5367ce"/></div>-->
            </div>
            <div class="title">Colours</div>
        </div>
        <div class="sep"></div>
    </div>

    <div class="row no-padding bc_bg_color" style="background: #fff; height:100%">
        <div class="col-sm-12 m-t-10">
            <div id="layout" class="b-grey b-t b-r b-l" style="max-width:980px; margin-left: auto; margin-right: auto"></div>
        </div>

        <div id="tabPreference" class="none padding-20" style="position: absolute; right: 0; top: 0; bottom: 0; background:#FFF; z-index:999; box-shadow: 0 0 3px rgba(0,0,0,.3);">
            <form action="cms/save_setting/" method="post" id="preferenePanelForm">
                <input type="hidden" name="data[widget_id]" id="preferenePanelWidgetId"/>

                <div id="setting_content"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <input type="reset" id="cancel_submit_params" value="Cancel"/>
                    </div>
                    <div class="col-sm-6">
                        <input type="submit" id="submit_params" value="Save"/>
                    </div>
                </div>
            </form>
        </div>

        <div id="tabWys" class="none padding-20" style="position: absolute; right: 0; top: 0; bottom: 0; background:#FFF; z-index:999; box-shadow: 0 0 3px rgba(0,0,0,.3);">
            <div class="padding-10">
                <div class="form-group">
                    <label>URL</label>
                    <input class="form-control link_add_edit_href" type="text" id="LinkEditor">
                </div>
                <div class="form-group">
                    <label>Target</label>
                    <select class='form-control link_add_edit_targetlist' style='width:100%'>
                        <option value="_self">self</option>
                        <option value="_blank">blank</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input value="" type="text" class='form-control link_add_edit_title' id='LinkEditorTitle'>
                </div>


                <p class="text_right" style='margin-top:10px'>
                    <input type='button' title='Add link' value='Add link' class='close_button'/>
                    <input type='button' title='Cancel' value='Cancel' class='close_button_strict'/>
                </p>

            </div>
        </div>
    </div>
</div>
    <!--div class="col-sm-3">
    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-primary m-t-10">
        <li class="active llmg"><a href="#tabLayouts" data-toggle="tab">Layout</a></li>
        <li class="llmg"><a href="#tabThemes" data-toggle="tab">Elements</a></li>
        <li class="llmg"><a href="#tabContent" data-toggle="tab">Colors</a></li>
        <li class="llmg none"><a href="#tabPreference" data-toggle="tab">Preference</a></li>
    </ul>
    <div class="tab-content m-b-30 p-l-30">
        <div id="tabLayouts" class="tab-pane active tcmg">
            <div class="scrollable">
                <div class="p-l-10 p-r-50">
                    <h5 class="semi-bold">Layout options</h5>

                    <div style="border:1px solid #ccc; margin:0; width: 70%" class="row layout_option" rel="1">
                        <div class="b-b b-grey" style="height:30px"></div>
                        <div class="col-sm-8 b-r b-grey" style="height: 100px;"></div>
                        <div class="col-sm-4" style="height: 100px;"></div>
                        <div class="b-t b-grey" style="clear:both; height:30px"></div>
                    </div>
                    <br class="clearfix"/>

                    <div style="border:1px solid #ccc; margin:0; width: 70%" class="m-t-15 row layout_option" rel="2">
                        <div class="b-b b-grey" style="height:30px"></div>
                        <div class="col-sm-3 b-r b-grey" style="height: 100px;"></div>
                        <div class="col-sm-6 b-r b-grey" style="height: 100px;"></div>
                        <div class="col-sm-3" style="height: 100px;"></div>
                        <div class="b-t b-grey" style="clear:both; height:30px"></div>
                    </div>

                    <br class="clearfix"/>

                    <div style="border:1px solid #ccc; margin:0; width: 70%" class="m-t-15 row layout_option" rel="3">
                        <div class="b-b b-grey" style="height:30px"></div>
                        <div class="col-sm-12" style="height: 100px;"></div>
                        <div class="b-t b-grey" style="clear:both; height:30px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="tabThemes" class="tab-pane tcmg">
            <div class="scrollable">
                <div class="p-l-10 p-r-50">
                    <h5 class="semi-bold">Moduls</h5>

                    <div class="copy_drag">

                    </div>
                </div>
            </div>
        </div>
        <div id="tabContent" class="tab-pane tcmg"></div>

    </div>
</div>
</div-->

    <?php //$this->cms = true;?>
