function signin() {

    window.location.href = "/1-php-grading-system/students_page/signin";
}

// Get the modal
function showModal(id, title, theme, size) {
    $('#myModal .modal-header').empty();
    $('#myModal .modal-header').append('<h2>' + title + '</h2>');
    $('#myModal .modal-header').append('<span class="close" onclick="closeModal()">&times;</span>');
    if (theme === 'dark') {
        $('#myModal .modal-content').css('background-color', '#757575');
        $('#myModal .modal-content').css('color', 'white');
        $('#myModal .modal-header').css('border-bottom', '3px solid black');
        $('#myModal .modal-body').addClass('d-flex-center');
    } else if (theme === 'gray') {
        $('#myModal .modal-content').css('background-color', '#c3c3c3');
        $('#myModal .modal-content').css('color', 'white');
        $('#myModal .modal-header').css('border-bottom', '3px solid black');
    } else {
        $('#myModal .modal-content').css('background-color', '#fff');
        $('#myModal .modal-header').css('border-bottom', '3px solid #80808038');
        $('#myModal .modal-content').css('color', 'black');
        $('#myModal .modal-body').removeClass('d-flex-center');
    }

    $('#myModal').css('display', 'block');
    $('body').css('overflow', 'hidden');
    $('#myModal .modal-body .modal-child').addClass('d-none');
    $('#' + id).removeClass('d-none');
    localStorage.getItem('topArrow') === '1' ? $('.top-icon').css('display', 'none') : $('.top-icon').css('display', '');

    if(size === 'small') {
        $('#myModal .modal-content').css('width', '50%');
        $('#myModal .modal-body').css('height', '37em');
    }
}

function showModalAdminSettings(id, title, theme, size) {
    $('#myModalAdminSettings .modal-header').empty();
    $('#myModalAdminSettings .modal-header').append('<h2>' + title + '</h2>');
    $('.modal-header').append('<span class="close" onclick="closeModal()">&times;</span>');
    if (theme === 'dark') {
        $('#myModalAdminSettings .modal-content').css('background-color', '#757575');
        $('#myModalAdminSettings .modal-content').css('color', 'white');
        $('#myModalAdminSettings .modal-header').css('border-bottom', '3px solid black');
        $('#myModalAdminSettings .modal-body').addClass('d-flex-center');
    } else if (theme === 'gray') {
        $('#myModalAdminSettings .modal-content').css('background-color', '#c3c3c3');
        $('#myModalAdminSettings .modal-content').css('color', 'white');
        $('#myModalAdminSettings .modal-header').css('border-bottom', '3px solid black');
    } else {
        $('#myModalAdminSettings .modal-content').css('background-color', '#fff');
        $('#myModalAdminSettings .modal-header').css('border-bottom', '3px solid #80808038');
        $('#myModalAdminSettings .modal-content').css('color', 'black');
        $('#myModalAdminSettings .modal-body').removeClass('d-flex-center');
    }

    $('#myModalAdminSettings').css('display', 'block');
    $('body').css('overflow', 'hidden');
    $('#myModalAdminSettings .modal-body .modal-child').addClass('d-none');
    $('#' + id).removeClass('d-none');
    localStorage.getItem('topArrow') === '1' ? $('.top-icon').css('display', 'none') : $('.top-icon').css('display', '');

    if(size === 'small') {
        $('#myModalAdminSettings .modal-content').css('width', '50%');
        $('#myModalAdminSettings .modal-body').css('height', '37em');
    }
}



function closeModal() {
    $('#myModal').css('display', 'none');
    $('#myModalAdminSettings').css('display', 'none');
    $('body').css('overflow', 'auto');

    var url = window.location.href;
    var arr = ['&&lrn=', '&&teachers_lrn=','&&searchGrade=','&&grade_promoted=','&&lrnexist=','&&promoted=','&&addPromoted='];

    arr.map((item) => {
        item = url.substring(url.lastIndexOf(item))
        if (!item.includes('http')) {
            window.location.href = url.replace(item, '');
        }
    })
}

function backModal(id, title, theme) {
    showModal(id, title, theme);
}

function loadCustomGrid() {
    $(".custom-grid-container").each(function () {
        $(this).css('grid-template-columns', 'repeat(' + $(this).attr('tabindex') + ', 1fr)')
        $(this).css('display', 'grid')
    })
}

function Post(s, param2) {
    var param = param2;
    var url = s;
    var form = $('<form></form>');
    form.attr("method", "post");
    form.attr("action", url);
    $.each(param, function (key, value) {
        var field = $('<input></input>');
        field.attr("type", "hidden");
        field.attr("name", key);
        field.attr("value", value);
        form.append(field);
    });
    $(document.body).append(form);
    form.submit();
}


loadCustomGrid();