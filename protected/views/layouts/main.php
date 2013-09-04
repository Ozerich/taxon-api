<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Taxon - онлайн заказ такси в Минске</title>


    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/site.less" rel="stylesheet/less" type="text/less">

    <script src="/js/jquery-2.0.3.min.js"></script>
    <script src="/js/less-1.3.3.min.js"></script>

    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <![endif]-->


    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-40067442-1']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();

    </script>


</head>
<body>

<div class="row-fluid">
    <div class="span12 line"></div>
</div>

<div class="container">
    <header>
        <div class="row-fluid head">

            <div class="span5 logo"><a href="/"><img src="/img/logo.png"></a> Онлайн заказ такси в <span
                    id="logo_minsk">Минске</span>
            </div>

            <nav class="span7 menu">
                <ul>
                    <li><a href="/driver/">Для водителей</a></li>
                    <li><a href="/contacts/">Контакты</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="row">
        <?=$content?>
    </div>
</div>


</body>
</html>