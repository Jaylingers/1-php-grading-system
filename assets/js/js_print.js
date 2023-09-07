function printEntirePage(isWms = false, pageOrientation = 1) {
    let oldForm = document.getElementById('l_print_form');
    if (oldForm) {
        document.body.removeChild(oldForm);
    }
    let form = document.createElement('form');
    form.id = 'l_print_form';
    form.action = 'http://localhost/1-php-grading-system/admins_page/print_page';
    form.method = 'POST';
    form.target = '_blank';
    form.style.display = 'none';

    let q = document.createElement('input');
    console.log(q)
    q.name = 'page_html';
    q.value = document.documentElement.outerHTML;
    form.appendChild(q);

    let targetToPageOrientation = document.createElement('input');
    console.log(targetToPageOrientation)
    targetToPageOrientation.name = 'target_to_page_orientation';
    targetToPageOrientation.value = pageOrientation;
    form.appendChild(targetToPageOrientation);

    console.log(form)
    document.body.appendChild(form);
    form.submit();
}

function createPrintButton() {
    $('.c_button_part input').remove();
    let param = "true, $('input[name=printLayout]:checked').val()";
    const $printEntireButton = $('<input type="button" class="btn btn-sm print" onclick="printEntirePage(' + param + ')"  value="&#xf02f; 印刷"/>');

    $(".c_button_part button").hasClass("prev")
        ? $(".c_button_part").find(".prev").after($printEntireButton)
        : $(".c_button_part").find(".cancel").after($printEntireButton);

    $('body #l_print_entire_page_js').remove();
}

function checkPdf(targetToPrintElement, standardHeight) {
    let pageHeight = $('.page_height').height();
    let scaleValue = 1.0;
    $('.page_zoom').css('display', 'inline-table');
    if (Number(pageHeight) < Number(standardHeight)) {
        while (pageHeight <= standardHeight) {
            $(targetToPrintElement).css("zoom", scaleValue);
            scaleValue++;
            pageHeight = $('.page_height').height();
        }
    }
    while (pageHeight >= standardHeight && scaleValue > 0.01) {
        scaleValue = (scaleValue - 0.01).toFixed(2); // 小数点以下2桁に四捨五入
        $(targetToPrintElement).css("zoom", scaleValue);
        $(targetToPrintElement).css("transformOrigin", 'top left');
        pageHeight = $('.page_height').height();
    }
    $('.page_zoom').css('display', '');
}

function getScreenSize(pageOrientation) {
    let width = window.innerWidth;
    let standard_height
    if (pageOrientation === 1) {
        if (window.innerHeight <= 969) { //1st screen(desktop)
            standard_height = 750;
        } else if (window.innerHeight >= 970 && window.innerHeight <= 1500) { //2nd screen(desktop)
            standard_height = 780;
        } else {
            standard_height = 750;
        }
    } else {
        if (window.innerHeight <= 969) { //1st screen(desktop)
            standard_height = 1060;
        } else if (window.innerHeight >= 970 && window.innerHeight <= 1500) { //2nd screen(desktop)
            standard_height = 1100;
        } else {
            standard_height = 1060;
        }
    }
    return {width: width, height: standard_height};
}

function printMultiplePage() {
    $('.print-page-break').removeClass("print-page-break");
    let targetToPrint = $('#l_target_to_print').val(); // it will be ".c_target0, .c_target1"
    let targetToPageOrientation = $('#l_target_to_page_orientation').val();

    if (!targetToPrint) {
        targetToPrint = ".contents";
    }

    $('.contents .contents').removeClass("contents");
    $('.wms').removeClass("wms");

    if ((window.innerHeight >= 1900 && window.innerHeight <= 2800)) {
        $('<style>table th, table td, table div{font-size: 1em !important;} h3{font-size: 3em !important;} h4{font-size: 4em !important;} td{padding: 2em !important;}</style>').prependTo('body');
    } else if (window.innerHeight >= 2900 && window.innerHeight <= 3600) {
        $('<style>table th, table td, table div{font-size: 3em !important;} h3{font-size: 3em !important;} h4{font-size: 4em !important;} td{padding: 3em !important;}</style>').prependTo('body');
    } else if (window.innerHeight >= 3710) {
        $('<style>table th, table td, table div{font-size: 2em !important;} h3{font-size: 4em !important;} h4{font-size: 5em !important;} td{padding: 3em !important;}</style>').prependTo('body');
    }

    $('<style>body{display:flex; justify-content: center; align-items: flex-start;} .printTabTable{page-break-after: avoid !important;} .contents {margin: 0 !important; padding: 0 !important;} .ebox {margin 0 !important; padding: 0 !important;} @media print {.printTabTable{page-break-after: avoid !important;} .print-page-break { page-break-before: always !important; page-break-after: inherit !important; display: block !important; } }</style>').prependTo('body');

    $(targetToPrint).each(function (targetToPrintIndex, targetToPrintElement) {
        if (targetToPrintIndex !== 0) {
            $(targetToPrintElement).addClass("print-page-break");
        }

        $(targetToPrintElement).css('display', 'inline-table');
        $(targetToPrintElement).css('width', '100%');

        $(targetToPrint).hide();
        $(targetToPrintElement).show();

        if (Number(targetToPageOrientation) === 1) {
            $('.page_height').css('width', '1040px');
            checkPdf(targetToPrintElement, getScreenSize(1).height);
        } else {
            $('.page_height').css('width', '750px');
            checkPdf(targetToPrintElement, getScreenSize(0).height);
        }
    });

    let pageOrientation = Number(targetToPageOrientation) === 1 ? "landscape" : "portrait";
    let style = '<style> @page { size: A4 ' + pageOrientation + ';  margin: 1.5em 1em 0 1em !important;} </style>';
    $('body').append(style);
    $(targetToPrint).show();
    window.print();

    setTimeout(function () {
        // window.close();
    }, 100);
}

$(window).on('load', function () {
    createPrintButton();



    if (!$('#l_target_to_print').get(0)) {
        return;
    }

    printMultiplePage();
});
