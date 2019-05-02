/*!
 * translation.js
 *
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @copyright 2014 University Library of Freiburg
 * @copyright 2014 Leibniz Institute for Regional Geography
 * @license   GNU General Public License - http://www.gnu.org/copyleft/gpl.html 
 */

var translationActive = false;

//Wenn DOM-Baum fertig ist
$(document).ready(function() {
    initTranslation();
});

function initTranslation() {
    $('span#languageSelector form').css('display', 'inline');
    translationActive = false;
    redrawTranslation();
    $('a#translate').click(toggleTranslation);
    //$( "#common-modal" ).modal({ autoOpen: false, resizable: false, width:'auto', height:'auto'});
}

function toggleTranslation() {
    
    translationActive = !translationActive;
    redrawTranslation();
}

function redrawTranslation() {

    if (translationActive) {
        $('a#translate').addClass('active');
        $('span.languageTranslate').css('border','1px solid #6AF');
        $('span.languageTranslate img').removeClass('hidden'); 
        $('span.languageTranslate').parent('a').each(function() {
           $(this).attr('data-tmp-href', $(this).attr('href')).removeAttr('href');
        });
        $('span.languageTranslate').bind('click', clickTranslation);

    } else {
        $('a#translate').removeClass('active');
        $('span.languageTranslate').css('border','');
        $('span.languageTranslate img').addClass('hidden');
        $('span.languageTranslate').parent('a').each(function() {
           $(this).attr('href', $(this).attr('data-tmp-href')).removeAttr('data-tmp-href');
        });
        $('span.languageTranslate').unbind('click', clickTranslation);
        $('#common-modal button.submit').unbind('click', addTranslation);
        $('#common-modal').closest('.ui-modal-content').modal('close');
    }
}

function clickTranslation() {
    var selectedCategory = $(this).attr('data-category');
    var selectedMessage  = $(this).attr('data-message');
    $.ajax({        
        url: baseUrl + '/index.php/translation/language/translate?'
                 +'&lang='+currentLanguage
                 +'&category='+selectedCategory
                 +'&message='+selectedMessage,     
        success: function(result) {
            
            $('#common-modal .modal-body').html(result);
            $('#common-modal .modal-content').attr('style','min-width:800px');
            $("#common-modal" ).modal();
            $('div#common-modal button.submit').bind('click', addTranslation);
            $('div#common-modal #_message').bind('change', selectMessageOrId);
            $('div#common-modal #_lang2').bind('change', selectMessageOrId);
            $('div#common-modal #_category').bind('change', selectCategory);            
        }
    });
}

function sleep(delay) {
        var start = new Date().getTime();
        while (new Date().getTime() < start + delay);
      }

function selectCategory() {
    var selectedCategory = $('#common-modal #_category option:selected').attr('value'); 
    //alert('Category now: ' + categoryMessage);
    //selectedCategory = $(this).attr('data-category');
    selectedMessage  = '';
    $.ajax({
        url:  baseUrl + '/index.php/translation/language/translate?'
                 +'&lang='+currentLanguage
                 +'&category='+selectedCategory
                 +'&message='+selectedMessage,
        success: function(result) {
            $('#common-modal .modal-body').html(result);
            $('#common-modal').modal();
            $('#common-modal button.submit').bind('click', addTranslation);
            $('#common-modal #_message').bind('change', selectMessageOrId);
            $('#common-modal #_lang2').bind('change', selectMessageOrId);
            $('#common-modal #_category').bind('change', selectCategory);
            //
            sleep(100);
            selectMessageOrId();
        }
    });
}

function selectMessageOrId() {
   var idMessage  = $('#common-modal #_message option:selected').attr('value'); 
   var translatedLanguage = $('#common-modal #_lang2 option:selected').attr('value');
    $.ajax({
        url: baseUrl + '/index.php/translation/language/get-translation?'
                 +'&lang='+translatedLanguage
                 +'&id='+idMessage,
         dataType: "json",
         success: function(result) {
            $('#common-modal .hint').html(result['feedback']);
            $('#common-modal #_translation').val(result['translation']);
            $('#common-modal ul#_suggestions').html(result['suggestions']);  
        }
    });    
}

function addTranslation() {
    
    var idMessage  = $('#common-modal #_message option:selected').attr('value');
    var translatedLanguage   = $('#common-modal #_lang2 option:selected').attr('value');
    var translatedMessage = $('#common-modal #_translation').val();
    var csrfToken = $('#common-modal').find("[name='YII_CSRF_TOKEN']").val();
    $.ajax({
        type: "POST",
        url: baseUrl + '/index.php/translation/language/add-translation?'
                 +'&lang='+translatedLanguage
                 +'&id='+idMessage
                 +'&translation='+translatedMessage,
         data: { lang: translatedLanguage, 
                 id:   idMessage,
                 translation: translatedMessage,
                 YII_CSRF_TOKEN: csrfToken},
         dataType: "json",
         success: function(result) {
            //In Kategoriebereich einfuegen
            $('#common-modal .hint').html(result['feedback']);
            //change the span to reflect the change immediatly
            var queryString = "span.languageTranslate[data-category='"+ result['category'] +"'][data-message='"+ result['message'] +"']";
            $(queryString).text(result['translation']);
            // $( "#common-modal" ).modal(  );
            
        }
    }); 
}

