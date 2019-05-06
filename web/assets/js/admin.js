$(document).ready(function(){

    $(document).on('change', '#trailer-select', function(){
        var id = $(this).val();
        var target = $(this).data('target');
        if(target === 'mods'){
            $('#addmodform-picture').attr('value', '');
            $('#addmodform-picture').parent().parent().parent().find('input[type=text]').val('');
        }
        if(id !== '0' && id !== '-1'){
            renderTrailersPreview(target);
        }else{
            $('.trailer-preview img').attr('src', '/images/'+target+'/default.jpg');
            $('#trailer-description').html('');
            if(target === 'mods') $('#trailer-name').html('');
            else $('#trailer-name').html(id === '0' ? 'Любой прицеп' : 'Без прицепа');
        }
    });

    $('#addconvoyform-picture_full').change(function(){
    	if($(this).hasClass('convoy-validate-img-size') && validateImgSize($(this))){
			this.files[0].size > 2500000 ? $('.picture-small').show() : $('.picture-small').hide().find('[type=file]').val('');
		}
    });

    $('#addconvoyform-picture_full').change(function(){
        readURL(this, '#preview');
    });

    $('#addmodform-picture, #trailersform-picture, #achievementsform-image').change(function(){
        $('#trailer-description').html('');
        $('#trailer-name').html('');
        $('#trailer-select').val('0').trigger("change");
        readURL(this);
    });

    $('.action-dropdown-button').click(function(){
        $('.action-dropdown').not('#action-dropdown-'+$(this).data('id')).removeClass('active');
        var list = $('#action-dropdown-'+$(this).data('id'));
        $(list).hasClass('active') ? $(list).removeClass('active') : $(list).addClass('active');
    });

    $('#recruitform-status').change(function(){
    	$(this).val() == 2 ? $('#claim-reasons').show() : $('#claim-reasons').hide();
	});

    $('.var-img [type=file]').change(function(){
        readURL(this, '#preview');
    });

}); // end of document ready

function readURL(input, selector) {
	if(selector === undefined) selector = '#trailer-image, #preview';
    if (input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function (e) {
            $(selector).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function renderTrailersPreview(){
    var trailers = [];
    $.each($('.trailers-select'), function(i, select){
        trailers.push(select.value);
    });
    var info = $('#trailer-info');
    $.ajax({
        cache: false,
        dataType : 'json',
        type : 'POST',
        url : '/trailers/getinfo',
        data : {
            trailers : trailers
        },
        beforeSend : function(){
            info.animate({opacity : 0.5}, 200, function(){
                info.append(getPreloaderHtml('tiny'));
            });
        },
        success : function(response){
            if(response.status == 'OK'){
				$('#trailer-name').html(response.trailers[0].name);
				$('#trailer-description').html(response.trailers[0].description);
				$('#trailer-image').attr('src', '/images/trailers/'+response.trailers[0].picture);
            }
        },
        complete : function(){
            info.animate({opacity : 1}, 200, function(){
                info.find('.preloader-wrapper').remove();
            });
        }
    });
}

function loadMembersBans(steamid64){
    $.ajax({
        cache: false,
        dataType : 'json',
        type : 'POST',
        url : '/members/getbans',
        data : {
            steamid64 : steamid64
        },
        beforeSend : function(){
            Materialize.toast('Загружаем баны...', 3000);
            $('th.first').append(getPreloaderHtml('tiny'));
        },
        success : function(response){
            if(response.status == 'OK'){
                var countBans = 0;
                $.each(response.bans, function(uid, banned){
                    if(banned == true){
                        $('tr[data-uid='+uid+']').removeClass('yellow lighten-4').addClass('red lighten-4');
                        countBans++;
                    }
                });
                if(countBans == 0){
                    Materialize.toast('Банов не найдено', 6000);
                }else if(countBans == 1){
                    Materialize.toast('Найден 1 бан!', 6000);
                }else if(countBans >= 2 && countBans <= 4){
                    Materialize.toast('Найдено '+countBans+' бана!!', 6000);
                }else{
                    Materialize.toast('Найдено '+countBans+' банов!!!', 6000);
                }
            }
        },
        error : function(jqXHR, error){
            $('th.first').find('.preloader-wrapper').remove();
            console.log(error);
        },
        complete : function(){
            $('th.first').find('.preloader-wrapper').remove();
        }
    });
} // end of loadMembersBans