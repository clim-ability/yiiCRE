<?php
/**
 * @copyright 2016 University Library of Freiburg
 * @copyright 2016 Leibniz Institute for Regional Geography
 * @copyright 2016 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => $menu['brand']['label'],
    'brandUrl' => $menu['brand']['url'],
    'options' => [
        'class' => 'navbar-top navbar-inverse navbar-fixed-top',
    ],
    'innerContainerOptions' => ['class' => 'container-fluid']
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => $menu['left'],
    'activateParents' => True,
    'encodeLabels' => false,
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menu['right'],
    'activateParents' => True,
    'encodeLabels' => false,
]);
NavBar::end();
?>