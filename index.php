<?php
include 'config.php';
include('u24api.php');

if ($_POST) {


    $name   = trim($_POST['name']);
    $names  = explode(" ", $name);
    $phone  = substr($_POST['tel'], 2);
    $phone  = str_replace(array('(', ')', ' ', '-'), '', $phone);
    $email  = $_POST['email'];
    $anketa = array(
            'name'         => $names[1],
            'mobile_phone' => $phone,// Номер телефона клиента без +7
            'email'        => $email,
            'passport'     => $passport,
    );

    if (preg_match('/^[а-яёА-ЯЁ\s\-]+$/u', $name)) {

        if (isset($names[1])) {
            $anketa['surname'] = $names[1];
        } else {
            echo '<script type="text/javascript">alert("Введите ФИО полностью")</script>';
        }


        if (isset($names[2])) {
            $anketa['patronymic'] = $names[2];
        }

        if (isset($_POST['accept'])) {

            $U24API = new UnicomAPI($login, $password);

            if ($anketa['surname'] && $anketa['name'] && $anketa['passport'] && $anketa['mobile_phone'] && $locality) {
                $reqest = $U24API->createRequest($anketa, $locality);

                if ($reqest[1] == 400) {
                    if (array_key_exists('error',$reqest[0])){
                        $errors = $reqest[0];
                    }else{
                        foreach ($reqest[0] as $error) {
                            $errors[] = $error[0];
                        }

                    }
                    $errors = implode(",", $errors);
                    echo '<script type="text/javascript">alert("' . $errors . '");</script>';

                } else {
                    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
                    mysqli_set_charset($mysqli, 'utf8');

                    $result_mysql = $mysqli->query("INSERT INTO " . $db_table . " (name,surname,patronymic,mobile_phone,email,passport) VALUES ('$names[1]','$names[0]','$names[2]','$phone','$email','$passport')");

                    if ($result_mysql == true) {
                        header('Location: offers.php');
                    }
                }
            }

        } else {
            echo '<script type="text/javascript">alert("Вы должны согласится с условиями обработки и использования персональных данных")</script>';
        }
    } else {
        echo '<script type="text/javascript">alert("Введите ФИО на русском языке")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="css/style_i.css" rel="stylesheet">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="header-logo">
            <svg fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21.9 43.7C9.8 43.7 0 33.9 0 21.9S9.8 0 21.9 0c3.9 0 7.7 1 11 3 .7.4 1 1.3.5 2.1-.4.7-1.3 1-2.1.5C28.5 3.9 25.2 3 21.9 3 11.5 3 3 11.5 3 21.9c0 10.4 8.5 18.9 18.9 18.9 10.4 0 18.9-8.5 18.9-18.9 0-3.1-.8-6.2-2.2-8.9-.1-.2-.2-.3-.3-.5-.4-.7-.2-1.6.6-2 .7-.4 1.6-.2 2 .6l.3.6c1.7 3.1 2.6 6.7 2.6 10.3-.1 11.9-9.9 21.7-21.9 21.7z" fill="#3EC283"/>
                <path d="M56.8 30.9c0 .5-.5 1-1 1h-1.3c-.5 0-1-.5-1-1V14.2c0-.5.5-1 1-1h1.3c.5 0 1 .5 1 1V22l6.8-8.2c.3-.4.8-.6 1.3-.6h1.2c.5 0 .8.4.8.9 0 .2-.1.4-.2.6l-6.3 7.2 6.9 8.4c.2.2.2.4.2.6 0 .5-.4.9-.9.9h-1.4c-.5 0-1-.2-1.3-.6l-7.1-9v8.7zM85 19.2c0 3.2-2.4 6-6.8 6-.6 0-1.6 0-2.4-.1v5.7c0 .5-.5 1-1 1h-1.3c-.5 0-1-.5-1-1V14.2c0-.5.4-1 1-1 1.3 0 3.3-.1 4.3-.1 5.4.1 7.2 3 7.2 6.1zM75.7 22c.8.1 1.6.1 2.3.1 1.9 0 3.5-.9 3.5-3 0-1.9-1.2-2.9-3.4-2.9-.7 0-1.5 0-2.3.1V22h-.1zM101.1 30.9c0 .5-.5 1-1 1H91c-.5 0-1-.5-1-1V14.2c0-.5.5-1 1-1h8.8c.5 0 1 .5 1 1v1.1c0 .5-.5 1-1 1h-6.5v4.6H99c.5 0 1 .5 1 1V23c0 .5-.5 1-1 1h-5.7v4.8h6.8c.5 0 1 .5 1 1v1.1zM120 13.3c.5 0 1 .5 1 1v14.6h1.5c.5 0 1 .5 1 1v4.4c0 .5-.5 1-1 1h-1.1c-.5 0-1-.5-1-1V32h-13v2.3c0 .5-.5 1-1 1h-1.1c-.5 0-1-.5-1-1v-4.4c0-.5.5-1 1-1h1c.8-1 2.5-4.9 2.9-11.8l.1-2.7c0-.6.5-1 1-1h9.7v-.1zm-2.3 3h-5.1l-.3 3.2c-.3 4.8-1.7 8.4-2.3 9.2h7.8V16.3h-.1zM141.7 31.9c-.5 0-1-.5-1-1V18.7l-9.3 12.6c-.3.4-.7.6-1.2.6H129c-.5 0-1-.5-1-1V14.2c0-.5.5-1 1-1h1.3c.5 0 1 .5 1 1v12.2l9.3-12.6c.3-.4.7-.7 1.2-.7h1.2c.5 0 1 .5 1 1v16.6c0 .5-.5 1-1 1h-1.3v.2zM155.2 31.9c-.5 0-1-.5-1-1V16.3h-5c-.5 0-1-.5-1-1v-1.1c0-.5.5-1 1-1h13.4c.5 0 1 .5 1 1v1.1c0 .5-.5 1-1 1h-5v14.6c0 .5-.5 1-1 1h-1.4zM167.7 14.2c0-.5.5-1 1-1h1.3c.5 0 1 .5 1 1v5.4c.8-.1 1.5-.1 2.1-.1 4.6 0 6.8 2.4 6.8 6 0 3.9-2.8 6.4-7.5 6.4-1.3 0-2.6 0-3.6-.1-.6 0-1-.5-1-1V14.2h-.1zm3.2 14.6c.6 0 1.1.1 1.8.1 2.4 0 3.7-1.1 3.7-3.3 0-2-1.3-3-3.6-3-.5 0-1.1.1-1.8.1v6.1h-.1zm14.6 2.1c0 .5-.5 1-1 1h-1.3c-.5 0-1-.5-1-1V14.2c0-.5.5-1 1-1h1.3c.5 0 1 .5 1 1v16.7z" fill="#000"/>
                <path d="M201.9 30.9c0 .5-.5 1-1 1H191c-.5 0-1-.5-1-1v-.6c0-.6.3-1.1.9-1.6 3.1-2.9 7-6.9 7-10.2 0-1.5-.8-2.4-2.4-2.4-1.1 0-1.8.5-2.5 1.5-.2.3-.5.5-.9.5-.2 0-.3-.1-.5-.1l-.7-.4c-.3-.2-.5-.5-.5-.9 0-.1 0-.3.1-.4 1.2-2.3 2.8-3.3 5.4-3.3 3.5 0 5.3 2.4 5.3 4.9 0 5.2-5.6 9.9-7 11.1h6.7c.5 0 1 .5 1 1v.9zM216.4 30.9c0 .5-.5 1-1 1h-1.1c-.5 0-1-.5-1-1v-3.3h-7.6c-.5 0-1-.5-1-.9v-.5c0-.7.2-1.1.6-1.7l6.1-10.3c.3-.5.8-.9 1.4-.9h1.1c.5 0 .9.4.9.8 0 .1 0 .3-.1.5l-5.9 9.9h4.5v-3.6c0-.5.5-1 1-1h1.1c.5 0 1 .5 1 1v3.6h1.4c.5 0 1 .5 1 1v1.1c0 .5-.5 1-1 1h-1.4v3.3zM21.8 23.9c-.4 0-.8-.1-1.1-.4l-7.4-7.4c-.6-.6-.6-1.5 0-2.1.6-.6 1.5-.6 2.1 0l7.4 7.4c.6.6.6 1.5 0 2.1-.2.3-.6.4-1 .4z" fill="#3EC283"/>
                <path d="M21.9 24c-.4 0-.8-.1-1.1-.4-.6-.6-.6-1.5 0-2.1L39.6 2.6c.6-.6 1.5-.6 2.1 0 .6.6.6 1.5 0 2.1L22.9 23.5c-.3.3-.7.5-1 .5z" fill="#3EC283"/>
            </svg>
        </div>
        <div class="header-block">
            <div class="header-text">
                <h1 class="header-text__title">Деньги за 5 минут</h1>
                <p class="header-text__subtitle">Вы сталкивались с ситуацией, когда Вам не одобряли займ?<br>
                    Мы подбираем займы индивидуально для Вас - по вашему кредитному рейтингу.<br> Процент одобрения гораздо выше! Заполните анкету и убедитесь в этом самостоятельно!
                </p>
            </div>
            <div class="header-form">
                <div class="header-form__block">
                    <form id="get-offers-form2" action="" class="offers-form" method="post">
                        <div class="offers-form__wrapper">
                            <div class="form-control__block">
                                <div class="form-control">
                                    <input type="text" placeholder="Зубенко Михаил" name="name" id="fullname" required="required" <?php echo isset($_POST['name']) ? 'value="' . $_POST['name'] . '"' : '' ?>>
                                    <label class="lbl-inpt" for="fullname">ФИО</label>
                                </div>
                                <div class="form-control">
                                    <input type="tel" minlength="10" placeholder="+7-(___)-___-__-__" id="telephone" required="required" name="tel" class="phone_mask" <?php echo isset($_POST['tel']) ? 'value="' . $_POST['tel'] . '"' : '' ?>>
                                    <label class="lbl-inpt" for="telephone">Мобильный телефон</label>
                                </div>
                                <div class="form-control">
                                    <input type="email" name="email" placeholder="example@pochta.ru" id="email" required="required" <?php echo isset($_POST['email']) ? 'value="' . $_POST['email'] . '"' : '' ?>>
                                    <label class="lbl-inpt" for="email">Электронная почта</label>
                                </div>
                            </div>
                            <div class="check-box__control">
                                <input required="required" type="checkbox" id="check-box" name="accept" <?php echo isset($_POST['accept']) ? 'checked="checked"'  : ''?>>
                                <label for="check-box">Я соглашаюсь с
                                    <a class="check-box__href" href="#">условиями обработки и использования моих персональных данных</a></label>
                            </div>
                            <button class="btn-offers">Получить предложения</button>
                        </div>
                    </form>
                </div>
                <div class="header-form__img">
                    <img src="img/image 72.png" alt="girl">
                </div>
            </div>
        </div>
    </div>
</header>
<main>
    <section class="partners">
        <div class="container">
            <div class="partners-block">
                <img src="img/image 77@3x.png" alt="" class="logo1">
                <img src="img/image 78@3x.png" alt="" class="logo2">
                <img src="img/image 79@3x.png" alt="" class="logo3">
                <img src="img/image 80@3x.png" alt="" class="logo4">
                <img src="img/image 81@3x.png" alt="" class="logo5">
                <img src="img/image 82@3x.png" alt="" class="logo6">
            </div>
        </div>
    </section>
    <section class="advantages">
        <div class="container">
            <div class="advantages-block">
                <figure class="advantages-items">
                    <div class="advantages-img"><img src="img/001-search.svg" alt=""></div>
                    <figcaption class="advantages-text">
                        <p><span class="b">Вы сами выбираете предложения,</span> которым хотите воспользоваться</p>
                    </figcaption>
                </figure>

                <figure class="advantages-items">
                    <div class="advantages-img"><img src="img/Vector.svg" alt=""></div>
                    <figcaption class="advantages-text advantages-text-width">
                        <p>
                            <span class="b">Отправка заявки происходит в автоматическом режиме</span> во все подходящие предложения
                        </p>
                    </figcaption>
                </figure>

                <figure class="advantages-items">
                    <div class="advantages-img"><img src="img/002-shield.svg" alt=""></div>
                    <figcaption class="advantages-text">
                        <p><span class="b">Ваши данные защищены</span> и не будут обрабатываться без вашего согласия.
                        </p>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>
    <section class="how-work">
        <div class="container">
            <div class="how-work__block">
                <div class="how-work__text">
                    <h2 class="how-work__heading">
                        <span class="b">Почему у нас вы получаете деньги быстро?</span> 5 наших правил:
                    </h2>
                    <div class="how-work__subtitle">
                        <ol class="how-work__list">
                            <li class="how-work__list-item">Не заполняйте десятки анкет в МФО;</li>
                            <li class="how-work__list-item">Не ищете выгодные условия самостоятельно;</li>
                            <li class="how-work__list-item">Не ждите звонка с одобрением;</li>
                            <li class="how-work__list-item">Не бойтесь обратиться к чёрному кредитору;</li>
                            <li class="how-work__list-item">Не тратьте время на сравнение условий;</li>
                        </ol>
                    </div>
                </div>
                <div class="how-work__img">
                    <img src="img/image 73.png" alt="">
                </div>
            </div>
        </div>
    </section>
    <section class="get-offers">
        <div class="container-xs">
            <div class="get-offers__block">
                <div class="get-offers__text">
                    <div class="get-offers__heading">Заполните форму и получите лучшие предложения!</div>
                    <div class="get-offers__subtitle">
                        <img class="get-offers__subtitle-img" src="img/Vector (1).svg" alt="">
                        Ваши персональные данные не будут переданы третьим лицам
                    </div>
                </div>
                <div class="get-offers__form">
                    <form action="" id="get-offers-form" method="post">
                        <div class="get-offers-form__wrapper">
                            <div class="form-control">
                                <input type="text" placeholder="Зубенко Михаил" id="fullname" required="required" name="name" <?php echo isset($_POST['name']) ? 'value="' . $_POST['name'] . '"' : '' ?>>
                                <label class="lbl-inpt" for="fullname">ФИО</label>
                            </div>
                            <div class="form-control">
                                <input type="tel" class="phone_mask" minlength="10" placeholder="+7-(___)-___-__-__" id="telephone" required="required" name="tel" <?php echo isset($_POST['tel']) ? 'value="' . $_POST['tel'] . '"' : '' ?>>
                                <label class="lbl-inpt" for="telephone">Мобильный телефон</label>
                            </div>
                            <div class="form-control">
                                <input type="email" placeholder="example@pochta.ru" id="email" name="email" required="required" <?php echo isset($_POST['email']) ? 'value="' . $_POST['email'] . '"' : '' ?>>
                                <label class="lbl-inpt" for="email">Электронная почта</label>
                            </div>
                            <div class="check-box__control">
                                <input required="required" type="checkbox" id="check-box" name="accept" <?php echo  isset($_POST['accept']) ? 'checked="checked"' : ''?>>
                                <label for="check-box">Я соглашаюсь с
                                    <a class="check-box__href" href="#">условиями обработки и использования моих персональных данных</a>
                                </label>
                            </div>
                            <button class="btn-offers">Получить предложения</button>
                            <div class="show-offers">
                                <a class="show-offers__href" href="#">Показать предложение без заполнения формы</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>


<footer class="footer">
    <div class="container-xs">
        <div class="footer-text">
            Кстати, сделанные на базе интернет-аналитики выводы, которые представляют собой яркий пример континентально-европейского типа политической культуры, будут в равной степени предоставлены сами себе. Внезапно, интерактивные прототипы заблокированы в рамках своих собственных рациональных ограничений. Безусловно, укрепление и развитие внутренней структуры играет определяющее значение для благоприятных перспектив! Предварительные выводы неутешительны: консультация с широким активом напрямую зависит от переосмысления внешнеэкономических политик. Кстати, сделанные на базе интернет-аналитики выводы, которые представляют собой яркий пример континентально-европейского типа политической культуры, будут в равной степени предоставлены сами себе. Внезапно, интерактивные прототипы заблокированы в рамках своих собственных рациональных ограничений. Безусловно, укрепление и развитие внутренней структуры играет определяющее значение для благоприятных перспектив! Предварительные выводы неутешительны: консультация с широким активом напрямую зависит от переосмысления внешнеэкономических политик.
        </div>
        <div class="footer-copyright">
            &#169; 1997–2019 ООО «Неймкомпани»
        </div>
    </div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="js/libs_i.min.js"></script>
</body>
</html>