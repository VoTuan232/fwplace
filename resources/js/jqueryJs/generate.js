$(document).ready(function() {
    var idWorkspace = $('#idWorkspace').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: 'post',
        url: 'workspace/getColorLocation/' + idWorkspace
    }).done(function(result) {
        var paintLocation = JSON.parse(result);

        for (var i = 0; i < paintLocation.length; i++) {
            var colorNote = paintLocation[i][0]['color'];
            var nameLocation = paintLocation[i][0]['location'];
            var locationId = paintLocation[i][0]['location_id'];
            var workspace_id = idWorkspace;
            $('#noteaddlocation').append(
                '<div class="seat seat-info" info-id = "' +
                    locationId +
                    '" info-name = "' +
                    nameLocation +
                    '" info-color = "' +
                    colorNote +
                    '" style="background-color: ' +
                    colorNote +
                    '; width: 15px; height: 15px;">\n' +
                    '</div> : ' +
                    nameLocation +
                    '<br>'
            );
            for (var j = 0; j < paintLocation[i].length; j++) {
                var id = paintLocation[i][j]['name'];
                var user_name = paintLocation[i][j]['user_name'];
                var avatar = paintLocation[i][j]['avatar'];
                var color = paintLocation[i][j]['color'];
                var user_id = paintLocation[i][j]['user_id'];
                var program = paintLocation[i][j]['program'];
                var position = paintLocation[i][j]['position'];
                var status =
                    '<small><i class="fa fa-circle" style="color: green; padding-right: 10px"></i></small>';
                $('#' + id + '').attr(
                    'seat_id',
                    paintLocation[i][j]['seat_id']
                );
                $('#' + id + '').css({
                    'background-color': color,
                    'font-size': '18px',
                    color: '#232121'
                });
                $('#' + id + '').removeClass('ui-selectee');
                $('#' + id + '').addClass('disabled');

                var x = '';
                if (Array.isArray(user_name) && Array.isArray(avatar)) {
                    var result = Object.assign.apply(
                        {},
                        user_name.map((v, i) => ({ [v]: avatar[i] }))
                    );
                    var count_user = 0;
                    for (var k in result) {
                        if (result.hasOwnProperty(k)) {
                            if (user_name.length <= 1) {
                                x +=
                                    '<button type="button" class="btn m-btn--pill  btn-outline-success button-hover seat-name" data-toggle="modal" href="#modal-info-user-1" data-id="' +
                                    user_id[count_user] +
                                    '" position = "' +
                                    position +
                                    '" program="' +
                                    program +
                                    '" id="' +
                                    k +
                                    '" info-avatar="' +
                                    result[k] +
                                    '">' +
                                    status +
                                    k +
                                    ' </button><br><a class="btn m-btn--pill m-btn--air btn-secondary" data-toggle="modal" data-target="#modal-info-user" data-placement="left" data-original-title="Add Workspace"> <i class="flaticon-add"></i></a>';
                                count_user++;
                            } else {
                                x +=
                                    '<button type="button" class="btn m-btn--pill  btn-outline-success button-hover seat-name" data-toggle="modal" href="#modal-info-user-1" data-id="' +
                                    user_id[count_user] +
                                    '" position = "' +
                                    position +
                                    '"program="' +
                                    program +
                                    '" id="' +
                                    k +
                                    '" info-avatar="' +
                                    result[k] +
                                    '">' +
                                    status +
                                    k +
                                    ' </button>';
                                count_user++;
                            }
                        }
                    }
                    $('#' + id + '')
                        .parent()
                        .removeAttr('href ata-toggle');
                } else {
                    x =
                        '<span class="seat-name"><small><i class="fa fa-circle" style="color: #dcdcdc; padding-right: 10px"></i></small>' +
                        user_name +
                        '</span>';
                }
                $('#' + id + '').html(x);
                $('#' + id + '').attr('full_name', x);
                $('#' + id + '').attr('avatar', avatar);
                $('#' + id + '').attr('user_id', user_id);
                if (paintLocation[i][j]['usable'] != 1) {
                    $('#' + id + '')
                        .parent('a')
                        .addClass('disabled');
                }
            }
        }

        $('#show').click(function() {
            var array = [];
            $('.ui-selected').each(function() {
                array.push($(this).attr('id'));
            });
            $('#seats').val(array);
            if (array.length > 0) {
                $('.form-workspace').show();
            } else {
                alert(
                    'Please select location before click button Add location'
                );
            }
        });
        $('.all_seat').selectable({
            filter: '.seat',
            cancel: '.disabled',
            selected: function(event, ui) {
                $('.disabled').each(function() {
                    if ($(this).hasClass('ui-selected')) {
                        $(this).removeClass('ui-selected');
                    }
                });
            }
        });

        $(document).on('click', '.seat-info', function() {
            var id = $(this).attr('info-id');
            var array = [];
            $('.ui-selected').each(function() {
                array.push($(this).attr('id'));
            });
            var seat = array;
            var url = route('save_location_color');
            if (seat.length < 1) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Please select location'
                });
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: { id: id, seat: seat },
                success: function(data) {
                    var val = $('.ui-selected').each(function() {
                        $(this).css('background-color', data.color);
                        location.reload();
                    });
                }
            });
        });

        // Alert ID-User
        $('.all_seat .seat button').hover(
            function() {
                var checkID = $(this).attr('id');
                var checkAvatar = $(this).attr('info-avatar');
                $(this).append(
                    '<div id="hello"><span><small><i class="fa fa-circle" style="color: green; padding-right: 10px"></i></small> ' +
                        checkID +
                        ' <br> <br> <img id="check-avatar" src="storage/user/' +
                        checkAvatar +
                        '" alt="" > </span></div>'
                );
            },
            function() {
                $(this)
                    .find('#hello')
                    .remove();
            }
        );

        //img-info-location
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css(
                        'background-image',
                        'url(' + e.target.result + ')'
                    );
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $('#imageUpload').change(function() {
            readURL(this);
        });

        function rgbToHex(red, green, blue) {
            var rgb = blue | (green << 8) | (red << 16);
            return '#' + (0x1000000 + rgb).toString(16).slice(1);
        }

        $(document).on('click', '.seat', function() {
            var seat_id = $(this).attr('seat_id');
            $('#locations').val(seat_id);
            $('.locations').val(seat_id);
            $('#seat_color').val(rgbToHex($(this).attr('background-color')));
        });

        $(document).on('click', '.seat button', function() {
            var seat_id = $(this)
                .closest('span')
                .attr('seat_id');
            var user_id = $(this).data('id');
            $('#locations-1').val(seat_id);
            $('#user-id-current').val(user_id);
        });

        $('#list-name')
            .change(function() {
                $('#list-name option:selected').each(function() {
                    var $this = $(this);
                    var url = 'workspace/avatar-info/';
                    var id = $(this).val();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content'
                            )
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: url + id,
                        success: function(data) {
                            var avatar = urlAvatar + data.avatar;
                            $('#modal-info-user')
                                .find('#imagePreview')
                                .css('background-image', 'url(' + avatar + ')');
                        }
                    });
                });
            })
            .change();

        $('.list-name')
            .change(function() {
                $('.list-name option:selected').each(function() {
                    var $this = $(this);
                    var url = 'workspace/avatar-info/';
                    var id = $(this).val();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content'
                            )
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: url + id,
                        success: function(data) {
                            if (data) {
                                var avatarInfo = urlAvatar + data.avatar;
                                $('#modal-info-user-1')
                                    .find('#imagePreview')
                                    .css(
                                        'background-image',
                                        'url(' + avatarInfo + ')'
                                    );
                            }
                        }
                    });
                });
            })
            .change();

        $('button[data-toggle=modal]').click(function() {
            var target = $($(this).attr('href'));
            var id = $(this).data('id');
            var program = $(this).attr('program');
            var position = $(this).attr('position');

            target.find('form').trigger('reset');
            target.find('#imagePreview').attr('style', '');
            target
                .find('.list-name')
                .val(id)
                .trigger('change');
            target
                .find('.list-program')
                .val(program)
                .trigger('change');
            target
                .find('.list-position')
                .val(position)
                .trigger('change');
        });

        $('.delete-cell').click(function() {
            swal({
                title: 'Are you sure?',
                text: 'Are you sure want to delete this seat?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then(result => {
                if (result.value) {
                    let id = $('#locations').val();
                    let span = $(`span[seat_id=${id}]`)[0];
                    let element = document.createElement('span');
                    let append = `<span seat_id="" avatar="" program="" position="" 
                                    user_id="" class="seat ui-selectee" id="${$(
                                        span
                                    ).attr('id')}">
                        <span class="seat-name">${span.innerText}</span>
                    </span>`;
                    $(element).html(append);

                    $.ajax({
                        url: route('delete_seat'),
                        method: 'post',
                        data: { seat_id: id },
                        success: function(result) {
                            $(span)
                                .parents('a')
                                .removeClass('disabled');
                            span.replaceWith(element);
                        },
                        error: function(request, status, error) {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: request.responseJSON.message
                            });
                        }
                    });
                }
            });
        });
    });
});
