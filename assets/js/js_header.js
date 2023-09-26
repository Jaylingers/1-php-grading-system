function signin() {
    window.location.href = "/1-php-grading-system/students_page/signin";
}

// Get the modal
function showModal(id, title, theme) {
    $('.modal-header').empty();
    $('.modal-header').append('<h2>' + title + '</h2>');
    $('.modal-header').append('<span class="close" onclick="closeModal()">&times;</span>');
    if (theme === 'dark') {
        $('.modal-content').css('background-color', '#757575');
        $('.modal-content').css('color', 'white');
        $('.modal-header').css('border-bottom', '3px solid black');
        $('.modal-body').addClass('d-flex-center');
    } else if (theme === 'gray') {
        $('.modal-content').css('background-color', '#c3c3c3');
        $('.modal-content').css('color', 'white');
        $('.modal-header').css('border-bottom', '3px solid black');
    } else {
        $('.modal-content').css('background-color', '#fff');
        $('.modal-header').css('border-bottom', '3px solid #80808038');
        $('.modal-content').css('color', 'black');
        $('.modal-body').removeClass('d-flex-center');
    }

    $('#myModal').css('display', 'block');
    $('body').css('overflow', 'hidden');
    $('.modal-body .modal-child').addClass('d-none');
    $('#' + id).removeClass('d-none');
    localStorage.getItem('topArrow') === '1' ? $('.top-icon').css('display', 'none') : $('.top-icon').css('display', '');
}

function closeModal() {
    $('#myModal').css('display', 'none');
    $('body').css('overflow', 'auto');
    var url = window.location.href;
    var id = url.substring(url.lastIndexOf('&&lrn='));
    if(url.includes('lrn')){
        window.location.href = url.replace(id, '');
    }
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


loadCustomGrid();