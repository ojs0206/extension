/**
 * Created by ryangwunhyok on 5/5/2017.
 */

var home_path;
var avatar_path_contact;
var avatar_path_point;
var avatar_path;
var avatar_admin_path;
var avatar_path_announce;
var avatar_path_category;
var avatar_path_product;
var avatar_path_member;
var user_setting;
var default_image_path;
var avatar_path_question_category;
var avatar_path_shop;
var avatar_path_shop_product;
var class_url;

/**
 * 금액현시 모듈
 *
 * @param decimals
 * @param decimal_sep	:	character used as deciaml separtor, it defaults to '.' when omitted
 * @param thousands_sep	:	thousands_sep: char used as thousands separator, it defaults to ',' when omitted
 */
Number.prototype.toMoney = function(decimals, decimal_sep, thousands_sep)
{
    var 	n = this,
        c = isNaN(decimals) ? 2 : Math.abs(decimals), 						//if decimal is zero we must take it, it means user does not want to show any decimal
        d = decimal_sep || '.', 											//if no decimal separator is passed we use the dot as default decimal separator (we MUST use a decimal separator)
        t = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep, 	//if you don't want to use a thousands separator you can pass empty string as thousands_sep value
        sign = (n < 0) ? '-' : '',
        i = parseInt(n = Math.abs(n).toFixed(c)) + '',						//extracting the absolute value of the integer part of the number and converting to string
        j = ((j = i.length) > 3) ? j % 3 : 0;

    return sign + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
};

/**
 * 문자렬관련 함수들
 */
var RNSString = window.RNSString = new function()
{
    var pThis = this;

    /**
     * 형식화된 문자렬에 해당한 값을 대응시킨다.
     *
     * @param 	str		형식화된 문자렬
     */
    pThis.format = function(str) {
        for (var i = 1; i < arguments.length; i++) {
            str = str.replace("{" + (i - 1) + "}", arguments[i]);
        }
        return str;
    }
}

var runSetDefaultValidation = function() {
    $.validator.setDefaults({
        errorElement : "span", // contain the error msg in a small tag
        errorClass : 'help-block',
        errorPlacement : function(error, element) {// render error placement for each input type
            if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {// for chosen elements, need to insert the error after the chosen container
                error.insertAfter($(element).closest('.form-group').children('div').children().last());
            } else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
                error.appendTo($(element).closest('.form-group').children('div'));
            } else {
                error.insertAfter(element);
                // for other inputs, just perform default behavior
            }
        },
        ignore : ':hidden',
        success : function(label, element) {
            label.addClass('help-block valid');
            // mark the current input as valid and display OK icon
            $(element).closest('.form-group').removeClass('has-error');
        },
        highlight : function(element) {
            $(element).closest('.help-block').removeClass('valid');
            // display OK icon
            $(element).closest('.form-group').addClass('has-error');
            // add the Bootstrap error class to the control group
        },
        unhighlight : function(element) {// revert the change done by hightlight
            $(element).closest('.form-group').removeClass('has-error');
            // set error class to the control group
        }
    });
};

function showToastrMsg(response)
{
    if( response.type == "ok" )
        toastr.success(response.msg, lang["completed"]);
    else
        toastr.error(response.msg, lang["failed"]);
}

function showToastrMsgFail(msg)
{
        toastr.error(msg, "Failed");
}

function showToastrMsgSuccess(msg)
{
    toastr.success(msg, "Success");
}

function showConfirmMsg(title, text, yeslabel, nolabel, callback)
{
    ret = false;
    swal({
        title: title,
        text: text,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!"
    }, function(isConfirm) {
        if(isConfirm)
            callback.call();
    });

    return ret;
}

function showConfirmMessage(type, title, text, yeslabel, nolabel, callback)
{
    if(type == null || type == "")
        type = "warning";

    if( title == null || title == "" )
        title = "Are you sure";

    if( yeslabel == null || yeslabel == "" )
        yeslabel = "YES";

    if(nolabel == null || nolabel == "")
        nolabel = "NO";


    ret = swal({
        type: type,
        title: title,
        text: text,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: yeslabel,
        cancelButtonText: nolabel
    }, function(isConfirm) {
        if(isConfirm) {
            callback.call();
        }
    });
}

function dtRender_CType(data, type, row, meta)
{
    return lang.ctypes[data];
}

function dt_Render_aunnounce_status(data, type, row, meta)
{
    return lang.status[data];
}

function dt_Render_category_status(data, type, row, meta)
{
    return lang.category[data];
}

function dt_Render_product_status(data, type, row, meta)
{
    return lang.status[data];
}



function dtRender_CmsAdmin_tools(data, type, row, meta)
{
    field_id = row.id;
    str = ['<button name="btnEditField" type="button" class="btn btn-primary btn-xs btn-squared" data-id="',field_id, '" data-no="', row.no, '">',
        lang['edit'],
        '</button>&nbsp;',
        '<button name="btnDeleteField" type="button" class="btn btn-danger btn-xs btn-squared" data-id="', field_id,'" data-no="', row.no, '">',
        lang['delete'],
        '</button>&nbsp;'].join('');

    return str;
}

function dtRender_field_inuse(data, type, row, meta)
{
    test_id = row.id;
    field_id = test_id;

    str = '<div class="checkbox clip-check check-success checkbox-inline ">';

    str += '<input name="changeUseStatus" type="checkbox" id="checkbox' + field_id + '" data-field-id="' + field_id + '">';

    str += '<label for="checkbox' + field_id + '"></label>';
    str += '</div>';

    return str;
}

function dtRender_tools(data, type, row, meta)
{
    field_id = row.id;
    str = ['<button name="btnEditField" type="button" class="btn btn-primary btn-xs btn-squared" data-id="',field_id, '" data-no="', row.no, '">',
        'Modify',
        '</button>&nbsp;',
        '<button name="btnDeleteField" type="button" class="btn btn-danger btn-xs btn-squared" data-id="', field_id,'" data-no="', row.no, '">',
        'Delete',
        '</button>&nbsp;'].join('');

    return str;
}

function dtRender_Edit_button(data, type, row, meta)
{
    field_id = row.id;
    str = ['<button name="btnEditField" class="btn btn-primary edit-button" data-id="',field_id, '" data-no="', row.no, '">',
        'Edit',
        '</button>'].join('');

    return str;
}

function dtRender_Edit_button_new(data, type, row, meta)
{
    let id = row.store_id;
    field_id = row.id;
    str = ['<button name="btnEditField" class="btn btn-primary edit-button" onclick="save_bill(',id,')" data-id="',field_id, '" data-no="', row.no, '">',
        'Edit',
        '</button>'].join('');

    return str;
}

function dtRender_redirect(data, type, row, meta) {
    return '<span style="width: 250px;word-wrap:break-word; display:inline-block;">' + data + '</span>'
}

function dtRender_click_rate(data, type, row, meta) {
    return '<textarea style="width: 50%; margin: auto;" class = "click_cut">' + data + '</textarea>';
}

function dtRender_count(data, type, row, meta)
{
    field_id = row.id;
    var activate = row.active;
    var title = "Deactivate";
    var className="fa fa-unlock fa fa-white";
    var type = "deactive-url";
    if(activate != 'Active') {
        title = 'Activate';
        className = "fa fa-lock fa fa-white";
        type="active-url";
    }

    str = ['<div class="visible-md visible-lg hidden-sm hidden-xs"><a href="#" url-id="',field_id, '" type="', type, '" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="',
        title,
        '"><i class="', className, '"></i></a>',
        '<a href="#" url-id="', field_id, '" type="delete-url" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-times fa fa-white"></i></a>',
        '<a href="#" url-id="', field_id, '" type="more-url" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="Statistics"><i class="far fa-eye fa fa-white"></i></a>', '</div>',
        '<div class="visible-xs visible-sm hidden-md hidden-lg"> <div class="btn-group dropdown "> <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">',
        '<i class="fa fa-cog"></i>&nbsp;<span class="caret"></span></button><ul class="dropdown-menu pull-right dropdown-light" role="menu"><li><a href="#" url-id="', field_id,
        '" type="', type, '" >', title, '</a></li><li> <a href="#" url-id="', field_id, '" type="delete-url" >Delete</a></li><li> <a href="#" url-id="\', field_id, \'" type="more-url" >Statistics</a></li></ul></div>'].join('');

    return str;
}

function dtRender_item(data, type, row, meta)
{
    return '<a href="/item_id/statistics/' + data + '">' + data + '</a>'
}

function dtRender_rate_type(data, type, row, meta)
{
    // var dataset = getRate();
    //
    // var str = '<select id="rate_type" name ="rate_type" required>\n' +
    //     '                        <option>Default Rate Type</option>'
    // function getRate() {
    //     $.ajax({
    //         type: "POST",
    //         url: '/get/all-rate-type',
    //         dataType: "json",
    //         success: function (resp) {
    //             console.log(resp.length);
    //             for (var i = 0; i < resp.length; i ++){
    //                 str += "<option value='" + resp[i].id + "'>" + resp[i].rate_type + "</option>";
    //             }
    //         },
    //         error: function () {
    //             console.log('error---')
    //         }
    //     });
    // }
    // setTimeout(function () {
    //     str += '</select>';
    //     console.log(str)
    //     return str;
    // }, 3000)
    return '<textarea style="margin: auto" class="ratetype">' + data + '</textarea>';

}

function dt_Render_billing(data, type, row, meta)
{
    field_id = row.id;
    var activate = row.active;
    var title = "Deactivate";
    var className="fa fa-unlock fa fa-white";
    var type = "deactive-url";
    if(activate != 'Active') {
        title = 'Activate';
        className = "fa fa-lock fa fa-white";
        type="active-url";
    }

    str = ['<div class="visible-md visible-lg hidden-sm hidden-xs"><a href="#" url-id="',field_id, '" type="', type, '" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="',
        title,
        '"><i class="', className, '"></i></a>',
        '<a href="#" url-id="', field_id, '" type="delete-url" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-times fa fa-white"></i></a>',
        '<a href="#" url-id="', field_id, '" type="more-url" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="far fa-eye fa fa-white"></i></a>', '</div>',
        '<div class="visible-xs visible-sm hidden-md hidden-lg"> <div class="btn-group dropdown "> <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">',
        '<i class="fa fa-cog"></i>&nbsp;<span class="caret"></span></button><ul class="dropdown-menu pull-right dropdown-light" role="menu"><li><a href="#" url-id="', field_id,
        '" type="', type, '" >', title, '</a></li><li> <a href="#" url-id="', field_id, '" type="delete-url" >Delete</a></li><li> <a href="#" url-id="\', field_id, \'" type="more-url" >Edit</a></li></ul></div>'].join('');

    return str;
}

function dt_Render_budget(data, type, row, meta){
    if (row.budget_type == 0) {
        str = '<input type="radio" style="width: 15px;" id="billing_profile'+row.no+'" name="radio_'+row.store_id+'" class="budget_radio" onclick="check_radio('+row.store_id+','+row.no+')" value="0" checked>';
    }
    else {
        str = '<input type="radio" style="width: 15px;" id="billing_profile'+row.no+'" name="radio_'+row.store_id+'" class="budget_radio" onclick="check_radio('+row.store_id+','+row.no+')" value="0">';
    }
    return str;
}

function dt_Render_item_budget(data, type, row, meta){
    if (row.budget_type == 1) {
        str = '<input type="radio" style="width: 15px;" id="item'+row.no+'" name="radio_'+row.store_id+'" class="budget_radio" onclick="check_radio('+row.store_id+','+row.no+')" value="1" checked>';
    }
    else {
        str = '<input type="radio" style="width: 15px;" id="item'+row.no+'" name="radio_'+row.store_id+'" class="budget_radio" onclick="check_radio('+row.store_id+','+row.no+')" value="1">';
    }
    return str;
}

function dt_Render_set_budget(data, type, row, meta){
    if(row.budget_type != null)
        str = '<input type="radio" style="width: 15px;" id="set'+row.no+'" checked>';
    else
        str = '<input type="radio" style="width: 15px;" id="set'+row.no+'">';
    return str;
}

function dt_Render_rate(data, type, row, meta)
{
    field_id = row.store_id;
    var activate = row.active;
    var title = "Deactivate";
    var className="fa fa-unlock fa fa-white";
    var type = "deactive-url";
    if(activate != 'Active') {
        title = 'Activate';
        className = "fa fa-lock fa fa-white";
        type="active-url";
    }

    str = ['<div class="visible-md visible-lg hidden-sm hidden-xs"><a href="#" url-id="',field_id, '" type="', type, '" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="',
        title,
        '"><i class="', className, '"></i></a>',
        '<a href="#" url-id="', field_id, '" type="delete-url" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-times fa fa-white"></i></a>',
        '<a href="#" url-id="', field_id, '" type="more-url" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="far fa-eye fa fa-white"></i></a>', '</div>',
        '<div class="visible-xs visible-sm hidden-md hidden-lg"> <div class="btn-group dropdown "> <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">',
        '<i class="fa fa-cog"></i>&nbsp;<span class="caret"></span></button><ul class="dropdown-menu pull-right dropdown-light" role="menu"><li><a href="#" url-id="', field_id,
        '" type="', type, '" >', title, '</a></li><li> <a href="#" url-id="', field_id, '" type="delete-url" >Delete</a></li><li> <a href="#" url-id="\', field_id, \'" type="more-url" >Edit</a></li></ul></div>'].join('');

    return str;
}

function dtRender_img(data, type, row, meta) {
    return '<img src="' + row.source + '" style="width:50px; height: 50px">';
}

function dtRender_date_tools(data, type, row, meta)
{
    field_id = row.id;
    str = [
        '<button name="btnDeleteField" type="button" class="btn btn-danger btn-xs btn-squared" data-id="', field_id,'" data-no="', row.no, '">',
        lang['delete'],
        '</button>&nbsp;'].join('');

    return str;
}

function dtRender_processtools(data, type, row, meta)
{
    process_id = row.id;
    var disabled = "";
    if(row.grade_name == 'attendance') disabled = "disabled = 'true'";
    str = ['<button name="btnEditField" type="button" class="btn btn-primary btn-xs btn-squared" data-id="', process_id, '"data-no="', row.no, '"' +  '>',
        lang['edit'],
        '</button>&nbsp;',
        '<button name="btnDeleteField" type="button" class="btn btn-danger btn-xs btn-squared" data-id="', process_id,'"data-no="', row.no, '"' +  disabled + '>',
        lang['delete'],
        '</button>&nbsp;',
        '<button name="btnMoveUp" type="button" class="btn btn-info btn-xs btn-squared" data-process-id="', process_id,'"',  '>',
        '<i class="fa fa-arrow-up"></i>',
        '</button>&nbsp;',
        '<button name="btnMoveDown" type="button" class="btn btn-info btn-xs btn-squared" data-process-id="', process_id,'"', '>',
        '<i class="fa fa-arrow-down"></i>',
        '</button>'].join('');

    return str;
}

function dtRender_present(data, type, row, meta)
{
    field_id = row.id;
    var value = row.result;
    var check_value = "";
    if(value == "PRESENT") check_value = "checked = ''"
    str = '<input type="radio" id="0' + field_id +  '"name="' + field_id + '" value="PRESENT"' + check_value + '>' + '<label for="present'  + field_id + '"> </label>';
    return str;
}

function dtRender_partial(data, type, row, meta)
{
    field_id = row.id;
    var value = row.result;
    var check_value = "";
    if(value == "PARTIAL") check_value = "checked = ''"
    str = '<input type="radio" id="1' + field_id +  '"name="' + field_id + '" value="PARTIAL"' + check_value + '>' + '<label for="partial'  + field_id + '"> </label>';
    return str;
}

function dtRender_absent(data, type, row, meta)
{
    field_id = row.id;
    var value = row.result;
    var check_value = "";
    if(value == "ABSENT") check_value = "checked = ''"
    str = '<input type="radio" id="2' + field_id +  '"name="' + field_id + '" value="ABSENT"' + check_value + '>' + '<label for="absent'  + field_id + '"> </label>';
    return str;
}


function dtRender_field_inuse(data, type, row, meta)
{
    test_id = row.id;
    field_id = test_id;

    str = '<div class="checkbox clip-check check-success checkbox-inline ">';

    if(row.is_use == 'YES')
        str += '<input name="changeUseStatus" type="checkbox" id="checkbox' + field_id + '" value="1" checked="" data-field-id="' + field_id + '">';
    else
        str += '<input name="changeUseStatus" type="checkbox" id="checkbox' + field_id + '" data-field-id="' + field_id + '">';

    str += '<label for="checkbox' + field_id + '"></label>';
    str += '</div>';

    return str;
}


function dtRender_classname(data, type, row, meta)
{
    var id = row.id;
    var url = class_url + "?id=" + id;

    return "<a href = '" + url +  "'>" + data + "</a>";
}

function dtRender_email(data, type, row, meta)
{
    var id = row.id;

    return "<a href = '#' name = 'email' id = '" + id + "'" + "<lable>" + data + "</label>" + "</a>";
}

function dtRender_facebook(data, type, row, meta)
{
    if(data == "") {
        return "<label>NO</label>";
    }
    return "<label>YES</label>";
}


function dtRender_photo_announce(data, type, row, meta)
{
    var path = avatar_path_announce + data;

    return "<img class='img-rounded' src='" + path + "'  style='width:24px;height:24px;'/>";
}

function dtRender_photo_shop_product(data, type, row, meta)
{
    var imgpath = avatar_path_shop_product + data;

    var default_image = avatar_path + "dummy.png";

    return "<img class='img-rounded' src='" + imgpath +"' onerror=\"this.src='" + default_image + "'\" style='width:24px;height:24px;'/>";
}


function dtRender_photo_shop(data, type, row, meta)
{
    var imgpath = avatar_path_shop + data;

    var default_image = avatar_path + "male.png";

    return "<img class='img-rounded' src='" + imgpath +"' onerror=\"this.src='" + default_image + "'\" style='width:24px;height:24px;'/>";
}

function dtRender_photo_member(data, type, row, meta)
{
    var imgpath = avatar_path_member;

    var default_image = avatar_path + "male.png";
    if(row["gender"] == 'FEMALE')
        default_image = avatar_path + "female.png";

    return "<img class='img-rounded' src='" + imgpath + row.avatar +"' onerror=\"this.src='" + default_image + "'\" style='width:24px;height:24px;'/>";
}

function dtRender_photo_category(data, type, row, meta)
{
    var path = avatar_path_category + data;
    var default_image = default_image_path + "dummy.png";

    return "<img class='img-rounded' src='" + path + "' onerror=\"this.src='" + default_image + "'\" style='width:24px;height:24px;'/>";
}

function dtRender_photo_question_category(data, type, row, meta)
{
    var path = avatar_path_question_category + data;
    var default_image = default_image_path + "male.png";

    return "<img class='img-rounded' src='" + path + "' onerror=\"this.src='" + default_image + "'\" style='width:24px;height:24px;'/>";
}


function dtRender_photo_product(data, type, row, meta)
{
    var path = avatar_path_product + data;
    var default_image = default_image_path + "dummy.png";

    return "<img class='img-rounded' src='" + path + "' onerror=\"this.src='" + default_image + "'\" style='width:24px;height:24px;'/>";
}


function dtRender_photo_point(data, type, row, meta)
{
    var path = avatar_path_point + data;

    return "<img class='img-rounded' src='" + path + "'  style='width:24px;height:24px;'/>";
}

function dtRender_photo_contact(data, type, row, meta)
{
    var path = avatar_path_contact + data;

    return "<img class='img-rounded' src='" + path + "'  style='width:24px;height:24px;'/>";
}



function dtRender_Dirty_word_tools(data, type, row, meta)
{
    field_id = row.id;
    str = ['<button name="btnEditField" type="button" class="btn btn-primary btn-xs btn-squared" data-id="',field_id, '" data-no="', row.no, '">',
        lang['edit'],
        '</button>&nbsp;',
        '<button name="btnDeleteField" type="button" class="btn btn-danger btn-xs btn-squared" data-id="', field_id,'" data-no="', row.no, '">',
        lang['delete'],
        '</button>&nbsp;'].join('');

    return str;
}