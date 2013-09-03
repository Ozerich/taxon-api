<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <title>Api документация</title>

    <link type="text/less" rel="stylesheet/less" href="/css/test.less">

    <script src="/js/jquery-2.0.3.min.js"></script>
    <script src="/js/less-1.3.3.min.js"></script>
    <script src="/js/test.js"></script>

    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <![endif]-->

</head>
<body>
<div id="page">
    <h1>API Taxon</h1>

    <div id="content">

        <? if (empty($requests)): ?>
            <p class="no-requests">Запросов нет</p>
        <? else: ?>
            <nav>
                <ul>
                    <? foreach ($requests as $num => $request): ?>
                        <a href="#request_<?= $num ?>"><strong><?=$request['Command']?></strong>
                            - <?=$request['Title']?></a>
                    <? endforeach; ?>
                </ul>
            </nav>
            <section>
                <? foreach ($requests as $num => $request): ?>
                    <article class="request-container" id="request_<?= $num ?>" data-command="<?=$request['Command']?>">
                        <h2><?=$request['Command']?></h2>

                        <p><?=$request["Title"]?></p>
                        <table>
                            <thead>
                            <tr>
                                <th>Параметр</th>
                                <th>Описание</th>
                                <th>Пример</th>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach ($request['Params'] as $key => $param): ?>
                                <tr>
                                    <td><?=$key?></td>
                                    <td><?=$param['name']?></td>
                                    <td><?=$param['example']?></td>
                                </tr>
                            <? endforeach; ?>
                            </tbody>
                        </table>
                        <button class="btn-test">Тестировать</button>
                        <div class="test-container" style="display: none">
                            <div class="params">
                                <? foreach ($request['Params'] as $key => $param): ?>
                                    <div class="param" data-key="<?=$key?>">
                                        <label for="param_<?= $num ?>_<?= $key ?>"><?=$key?>
                                            <small>(<?=$param['name']?>)</small>
                                        </label>
                                        <input type="text" id="param_<?= $num ?>_<?= $key ?>"
                                               value="<?= $param['example'] ?>">
                                    </div>
                                <? endforeach; ?>
                            </div>
                            <button class="submit">Отправить запрос</button>
                            <div class="response">
                                <h3>Ответ сервера:</h3>
                                <span class="error"></span>
                                <div class="response-area">[]</div>
                            </div>
                        </div>
                    </article>
                <? endforeach; ?>
            </section>
        <? endif; ?>
    </div>
</div>

</body>
</html>