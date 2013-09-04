$(function () {
    $('.request-container').each(function () {
        var $request_container = $(this);

        // нажатие на "Тестировать / Закрыть форму"
        $request_container.find('.btn-test').on('click', function () {
            var $test_container = $request_container.find('.test-container');
            $(this).text($test_container.is(":visible") ? 'Тестировать' : 'Закрыть форму');
            $test_container.slideToggle();
            return false;
        });

        // нажатие на "Отправить запрос"
        $request_container.find('.submit').on('click', function () {
            var $btn = $(this);
            $btn.prop('disabled', true);

            var $error = $request_container.find('.error');
            var $response = $request_container.find('.response-area');

            $error.text('');
            $response.text('.....................');

            $('.error').text('');

            var request = {
                Command: $request_container.data('command'),
                Params: []
            };

            $request_container.find('.param').each(function () {
                request.Params.push({
                    key: $(this).data('key'),
                    value: $(this).find('input[type=text]').val()
                });
            });


            $.ajax({
                url: '/test/request',
                data: request,
                type: 'POST',
                complete: function () {
                    $btn.prop('disabled', false);
                },
                error: function (data) {
                    $error.text(data.status + ' - ' + data.statusText);
                    $response.text('');
                },
                success: function (data) {
                    $error.text('');
                    $response.html(data);
                }
            });

            return false;
        });
    });
});