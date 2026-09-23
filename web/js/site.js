/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*
 * NOTE
 * function is dependency of helper function in tmbExplore
 * see @ modules/explore/tmbExplore/service/helperFunctions disableSlider() l. 122
 */
function busyStatus(status) {
    if (status) {
          $(".navbar-header a").addClass('busy'); 
         $("#ajaxActive").show(); 
    } else {
         $("#ajaxActive").hide(); 
          $(".navbar-header a").removeClass('busy');
    }
}

busyStatus(true);
$(document).ready(function () {
    $('.modalItem').bind('click', show_item_modal_fnc);
        $('a').not('.no-wait').not('.introjs-button').not('a[data-toggle]').not('a[data-confirm]').bind('click', function(){
       busyStatus(true);
    });
});

$(window).add(document).load(function() {
    busyStatus(false);
});
$(window).add(document).unload(function() {
    busyStatus(true);
});


var ajaxActive = false;
$(document).bind("ajaxSend", function(){
    ajaxActive = true;
    busyStatus(true);
 }).bind("ajaxComplete", function(){
    ajaxActive = false;
    busyStatus(false);
 });


function callAjaxComplete(fn) {
    var args = [].slice.call(arguments, 1);
    if (ajaxActive) {
        $(document).one("ajaxComplete", function () {
            //fn.apply(this, args);
            
            setTimeout(function () {
                fn.apply(this, args)
            }, 1000, this, args);
            
            console.log('waiting');
        });
    } else {
        //fn.apply(this, args);
        
                    setTimeout(function () {
                fn.apply(this, args)
            }, 1000, this, args);
        
        console.log('hurried');
    }
}

//------------------------------------------

function post_item_modal_fnc(itemPost, urlPost) {
    $.ajax({
        type: 'get',
        url: urlPost,
        context: itemPost,
        success: function (result)
            {
                if (result.success) {

                    var element = $(this);
                    element.html(result.html);
                    $('.lastModification').removeClass('lastModification');
                    element.addClass('lastModification');
                }
            },
        timeout: 30000    
        });
    }
//------------------------------------------

function save_item_modal_fnc() {

    var form = $("#common-modal form");
    var urlModify = form.attr('action');
    var submitType = form.attr('method');
    $.ajax({
        type: submitType,
        url: urlModify,
        data: form.serialize(), // serializes the form's elements.
        context: form.get(0),
        success: function (result)
        {
            var form = $("#common-modal form");
            if (result.success) {
                $('#common-modal').modal('hide');
                var urlSucc = $('#common-modal .modal-body .btn-modal-save').attr("data-url-succ");
                if (typeof urlSucc === 'string' || urlSucc instanceof String)
                {
                    var itemSucc = $('#common-modal .modal-body .btn-modal-save').attr("data-item-succ");
                    if (typeof itemSucc === 'string' || itemSucc instanceof String)
                    {
                        var item = $(itemSucc);
                        post_item_modal_fnc(item, urlSucc)
                    } else {
                        document.location = urlSucc;
                    }
                }
            }
            $('#common-modal .modal-body').html(result.html);
            $('#common-modal .modal-body .btn-modal-save').bind('click', save_item_modal_fnc);
            initNgModules(document);
            // hide waiting spinner
            ajaxActive = false; 
            busyStatus(false);
        }
    });
    return false; // avoid to execute the actual submit of the form.
}

//------------------------------------------
function close_modal_func(){
    $('#common-modal').modal("hide");
}
//------------------------------------------

function show_item_modal_fnc() {
    var element = $(this);
    var title = element.attr("data-title");
    var style = element.attr("data-style");   
    var urlCreate = element.attr("data-url");
    var urlHelp = element.attr("data-url-help");
    var urlSucc = element.attr("data-url-succ");
    var itemSucc = element.attr("data-item-succ");

    $.ajax({
        type: "GET",
        url: urlCreate,
        context: element.get(0),
        success: function (result) {
            $('#common-modal .modal-body').html(result.html);
            $('#common-modal .modal-title').html(title);
            $('#common-modal .modal-content').removeAttr('style');
            $('#common-modal .modal-content').attr('style',style);   
            if (typeof(urlHelp) != "undefined") {
                $('#common-modal a.modal-help').attr('href',urlHelp); 
                $("#common-modal span.modal-help").show(); 
            } else {
                $("#common-modal span.modal-help").hide(); 
            }
            $('#common-modal .modal-body .btn-modal-save').attr('data-url-succ', urlSucc);
            $('#common-modal .modal-body .btn-modal-save').attr('data-item-succ', itemSucc);
            $('#common-modal').modal({
               // backdrop: 'static',
               // keyboard: false
            });    
            $('#common-modal .modal-body .modalItem').bind('click', show_item_modal_fnc);
            $('#common-modal .modal-body .btn-modal-save').bind('click', save_item_modal_fnc);
            initNgModules(document);
        },
        error: function (result) {
            var a = 1;
        },
        complete: function (result) {
            var b = 2;
        },
        timeout: 30000
    });
    
    // dynamic
    $("#common-modal").draggable({
       handle: ".modal-header"
    });
    $('.modal-content').resizable({
    alsoResize: ".modal-dialog",
    minHeight: 256,
    minWidth: 256
    });
    }


//------------------------------------------

// data-intro=   data-step='1'