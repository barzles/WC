<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>

<header>
	<div class="container-header">
		<div class="header-wrapper">
			<div class="header-logo">
				<img src="img/logo.svg" alt="logo">
			</div>
			<div class="header-text">
				Лучшие предложения на рынке микрозаймов для вас
			</div>
		</div>
	</div>
</header>
<main>
	<section class="offers">
		<div class="container-offers">
			<div class="offers-list">
                <?php
include 'config.php';
                $mysqli = new mysqli($db_host,$db_user,$db_password,$db_base);
                mysqli_set_charset($mysqli, 'utf8');


                $res = $mysqli->query("SELECT * FROM offers");
               // $offers = mysqli_fetch_array($res);
                $offers = $res->fetch_all(MYSQLI_ASSOC);
              //  var_dump($offers);

?>
             <?php foreach ($offers as $offer) {?>
                 <div class="offers-list__item ">
                     <div class="offers-info">
                         <div class="offers-info__heading">
                             <div class="offers-info__heading-logo">
                                 <img src="<?php echo $offer['img_url'] ?>" alt="credit-star" class="credit-star">
                             </div>
                             <!--<div class="offers-info__heading-text">
                                 ЦБ № 2110
                             </div>-->
                         </div>
                         <div class="offers-info__bottom">

                             <div class="offers-info-data">
                                 <?php if($offer['amount']){?>
                                     <div class="offers-info-data__title">
                                         Сумма
                                     </div>
                                     <div class="offers-info-data__specific">
                                         до <?php echo $offer['amount'] ?> ₽
                                     </div>
                                 <?php }?>
                             </div>

                             <div class="offers-info-data">
                                 <?php if($offer['rate']){?>
                                 <div class="offers-info-data__title">
                                     Ставка в день
                                 </div>
                                 <div class="offers-info-data__specific">
                                     от <?php echo $offer['rate'] ?> %
                                 </div>
                                 <?php }?>
                             </div>

                             <div class="offers-info-data">
                                 <?php if($offer['time']){?>
                                 <div class="offers-info-data__title">
                                     Рассмотрение
                                 </div>
                                 <div class="offers-info-data__specific">
                                     <?php echo $offer['time'] ?>
                                 </div>
                                 <?php }?>
                             </div>

                         </div>
                     </div>
                     <div class="offers-get">
                         <div class="offers-get__heading">
                             <?php if($offer['label']){?>
                             <div class="offers-get__heading-icon">
                                 <img src="img/Vector.svg" alt="">
                             </div>
                             <div class="offers-get__heading-text">
                                 <?php echo $offer['label'] ?>
                             </div>
                             <?php }?>
                         </div>
                         <div class="offers-get__bottom">
                             <a href="<?php echo $offer['url'] ?>" class="credit-star-href">Получить кредит</a>
                         </div>
                     </div>
                 </div>
               <?php }?>


			</div>
		</div>
	</section>
	<section class="about-creditor">
		<div class="about-creditor__container">
			<div class="about-creditor__heading">
				Информация о кредиторах
			</div>
			<div class="about-creditor__block">
                <?php foreach ($offers as $offer) {?>
				<div class="about-creditor__block-item">
                    <p><?php echo $offer['name'] ?></p>
                    <?php echo $offer['info'] ?>
				</div>
                <?php }?>
			</div>
		</div>
	</section>
</main>
<footer>
	<div class="container-footer">
		<div class="footer-copyright">
			&#169; 1997–2019  ООО «Неймкомпани»
		</div>
	</div>
</footer>
<script src="js/libs.min.js"></script>
</body>
</html>