<style>
    .wrapper {
        background-color: white;
        border: 1px solid #cdd6db;
        border-radius: 3px;
        padding: 20px;
    }

</style>


    <input type="hidden" ng-model="edit.User.id" />

<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" data-ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button>

    <h5><span id="domwin-title">Edit</span></h5>
</div>

    <div class="modal-body">
        <ul class="nav nav-tabs nav-tabs-simple">
            <li class="active"><a data-toggle="tab" href="#tabReport">User</a></li>
        </ul>

        <div class="tab-content">
            <div class="row">
                <div class="col-sm-6">
                    <div style="width: 100px; position:relative;">
                        <div class="thumbnail-wrapper d96 bordered circular inline m-t-5 m-b-20" id="image_preview" style="position:relative;">
                            <img width="76" height="76" src="data:image/png;base64,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"/>
                        </div>
                        <a style="position:absolute;   top: 81px; right: -20px;" href="/users/load_photo/" data-domwin-style="slide-right" data-action="domwin" class="text-black" data-toggle="tooltip" data-placement="top" title="Detail">
                            <img src="/assets/img/pen_icon.png" style="width: 20px"/>
                        </a>
                    </div>
                    <input type="hidden" ng-model="edit.User.image" class="form-control" />
                </div>
                <div class="col-sm-6 update-fields" data-trigger-btn=".user-update-info"
                     data-trigger-item="#UserEmployeeId"
                     data-where="UserFirstName|UserLastName|UserEmail|UserCompany"
                     data-structure='{"firstname":"UserFirstName", "lastname":"UserLastName", "email":"UserEmail", "company":"UserCompany"}',
                     data-ajax-url="employees/get_user_info"
                    >

                    <!-- IF ID IS NOT EMPTY-->
                        <div class="row" data-ng-if="edit.User.id">
                            <div class="col-sm-4 font-montserrat all-caps fs-11 bold">Last login:</div>
                            <div class="col-sm-6 fs-11">21/12/2014</div>
                        </div>

                        <div class="row m-t-10" data-ng-if="edit.User.id">
                            <div class="col-sm-4 font-montserrat all-caps fs-11 bold">Created date:</div>
                            <div class="col-sm-6 fs-11"><?= ($this->data['User']['created'] == "0000-00-00 00:00:00" || $this->data['User']['created'] == "") ? "" : $wapis->f_date($this->data['User']['created']); ?></div>
                        </div>
                    <!-- END IF -->
    <!---->
    <!--                <div class="row m-t-10">-->
    <!--                    <div class="col-sm-4 font-montserrat all-caps fs-11 bold" style="margin-top: 12px;">Link employee:</div>-->
    <!--                    <div class="col-sm-6 fs-11">--><?//=
    //                        $html->input('User/employee_id', array('class' => 'full-width select2',
    //                            'type' => 'hidden',
    //                            'data-ajax--url' => 'employees/user_list',
    //                            'data-ajax--url-default-set' => 'employees/get_name_by_id',
    //                            'data-multiple' => 'false')); ?>
    <!--                    </div>-->
    <!--                    <div class="col-sm-2">-->
    <!--                        <button type="button" class="btn btn-success blue user-update-info" aria-label="Left Align" style="margin-left: -11px; height: 35px;">Assign</button>-->
    <!--                    </div>-->
    <!--                </div>-->
                </div>
            </div>

            <div class="row m-t-20">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Forename</label>
                        <input type="text" ng-model="edit.User.first_name" class="form-control" />
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Surname</label>
                        <input type="text" ng-model="edit.User.last_name" class="form-control" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="text" ng-model="edit.User.email" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" ng-model="edit.User.company" class="form-control" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Phone number</label>
                    <input type="text" ng-model="edit.User.phone" class="form-control" />
                </div>

                <div class="col-md-6 form-group">
                    <label>Group</label>
                    <select class="form-control" ng-model="edit.User.group_id">
                        <option ng-repeat="group in groups" value="{{group.UserGroupsModel.id}}" ng-selected="edit.User.group_id == group.UserGroupsModel.id">{{group.UserGroupsModel.name}}</option>
                    </select>
                </div>
            </div>


                <div class="row" data-ng-if="!edit.User.id">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Password</label>
                            <?= $html->input('User/password',array('class'=>'form-control', 'type' => 'password'));?>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Confirm password</label>
                            <?= $html->input('User/re_password',array('class'=>'form-control', 'type' => 'password'));?>
                        </div>
                    </div>
                </div>


            </div>


        <hr>
        <div class="row m-t-20">
            <div class="col-sm-6">
                <button type="button" class="btn btn-default close_btn" data-ng-click="$close()"><i class="pg-close"></i>Close</button>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-default blue pull-right form_submit" data-ng-click="modalOptions.submit();">Save</button>
            </div>
        </div>
    </div>



<script>
    $(function(){

        $('#image_preview').on('dragover', function (event) {
            event.stopPropagation();
            event.preventDefault();
        }).on('drop', function (event, ui) {
            event.stopPropagation();
            event.preventDefault();

            var obj = $(this),
                files = event.originalEvent.dataTransfer.files,
                file = files[0];

            if (file.type.indexOf("image") == 0) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = $(obj.find('img')[0]);
                    img.attr('src', e.target.result);
                    $('#UserImage').val(e.target.result);
                }
                reader.readAsDataURL(file);
            }
        })

        $(".user-update-info").click(function(e){

            e.preventDefault();
            console.log('test');

        });
    })

</script>