<?php

use yii\bootstrap\Html;
use humhub\modules\space\widgets\Image as SpaceImage;
use yii\bootstrap\Progress;

?>

<div class="container">
    <div class="row">
        <div class="col-md-12 fundingPanels">

            <?php if ( count( $fundings ) === 0 ): ?>
                <div class="panel">
                    <div class="panel-heading">
                        <?= Yii::t( 'XcoinModule.base', 'Crowd Funding' ); ?>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-warning">
                            <?= Yii::t( 'XcoinModule.base', 'Currently there are no running crowd fundings!' ); ?>
                        </div>
                    </div>
                    <br/>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php foreach ( $fundings as $funding ): ?>
                    <?php if ( $funding->getBaseMaximumAmount() > 0 && $funding->getRemainingDays() > 0 ): ?>
                        <?php
                        $space = $funding->getSpace()->one();
                        $cover = $funding->getCover();
                        ?>

                        <a href="<?= $space->createUrl('/xcoin/funding/overview', [
                            'fundingId' => $funding->id
                        ]); ?>">
                            <div class="col-md-3">
                                <div class="panel">
                                    <div class="panel-heading">

                                        <!-- campaign cover start -->
                                        <?php if ( $cover ) : ?>
                                            <div class="bg" style="background-image: url('<?= $cover->getUrl() ?>')"></div>
                                            <?= Html::img( $cover->getUrl(), [ 'height' => '140' ] ) ?>
                                        <?php else : ?>
                                            <div class="bg" style="background-image: url('<?= Yii::$app->getModule('xcoin')->getAssetsUrl() . '/images/default-funding-cover.png' ?>')"></div>
                                            <img src="<?= Yii::$app->getModule('xcoin')->getAssetsUrl() . '/images/default-funding-cover.png' ?>" height="140"/>
                                        <?php endif ?>
                                        <!-- campaign cover end -->

                                        <div class="project-owner">

                                            <!-- space image start -->
                                            <?= SpaceImage::widget( [
                                                    'space'       => $space,
                                                    'width'       => 34,
                                                    'showTooltip' => true,
                                                    'link'        => false
                                            ] ); ?>
                                            <!-- space image end -->

                                            <!-- campaign title start -->
                                            <span><?= "Project by <strong>" . Html::encode( $space->name ) . "</strong>"; ?></span>
                                            <!-- campaign title end -->

                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <h4 class="funding-title"><?= Html::encode( $funding->title ); ?></h4>
                                        <div class="media">
                                            <div class="media-left media-middle">
                                            </div>
                                            <div class="media-body">
                                                <!-- campaign description start -->
                                                <h4 class="media-heading"><?= Html::encode( $funding->shortenDescription() ); ?></h4>
                                                <!-- campaign description end -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">

                                        <div class="funding-progress">

                                            <div>
                                                <!-- campaign raised start -->
                                                Raised: <strong><?= $funding->getRaisedAmount() ?></strong>
                                                (<strong><?= $funding->getRaisedPercentage() ?>%</strong>)
                                                <!-- campaign raised end -->
                                            </div>

                                            <div class="pull-right">

                                                <!-- campaign remaining days start -->
                                                <?php if ($funding->getRemainingDays() > 2) : ?>
                                                    <div class="clock"></div>
                                                <?php else: ?>
                                                    <div class="clock red"></div>
                                                <?php endif; ?>
                                                <div class="days">
                                                    <strong><?= $funding->getRemainingDays() ?></strong> <?= $funding->getRemainingDays() > 1 ? 'Days' : 'Day' ?>
                                                    left
                                                </div>
                                                <!-- campaign remaining days end -->

                                            </div>

                                            <!-- campaign raised start -->
                                            <?php echo Progress::widget( [
                                                    'percent' => $funding->getRaisedPercentage(),
                                            ] ); ?>
                                        </div>
                                        <div class="funding-details row">

                                            <div class="col-md-6">
                                                <!-- campaign requesting start -->
                                                <span>
                                      Requesting:
                                        <strong><?= $funding->getRequestedAmount() ?></strong>
                                    </span>
                                                <?= SpaceImage::widget( [
                                                        'space'       => $funding->asset->space,
                                                        'width'       => 16,
                                                        'showTooltip' => true,
                                                        'link'        => false
                                                ] ); ?>
                                                <!-- campaign requesting end -->
                                            </div>
                                            <div class="col-md-6">
                                                <!-- campaign offering start -->
                                                <span>
                                      Offering:
                                        <strong><?= $funding->getOfferedAmountPercentage() ?>%</strong>
                                    </span>
                                                <?= SpaceImage::widget( [
                                                        'space'       => $funding->space,
                                                        'width'       => 16,
                                                        'showTooltip' => true,
                                                        'link'        => false
                                                ] ); ?>
                                                <!-- campaign offering end -->
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </a>


                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>

<style>

    .fundingPanels .panel {
        border-radius: 8px;
        position: relative;
        transition: transform 0.3s ease-in-out;
    }

    .fundingPanels .panel::after {

        content: '';
        position: absolute;

        width: 100%;
        height: 100%;
        top: 0;
        left: 0;

        box-shadow: 0 0 40px #c5c5c5;
        -webkit-box-shadow: 0 0 40px #c5c5c5;
        -moz-box-shadow: 0 0 40px #c5c5c5;

        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .fundingPanels .panel:hover {
        transform: translate(0, -5px);
    }


    .fundingPanels .panel:hover::after {
        opacity: 1;
    }

    .fundingPanels .panel:hover::after {
        opacity: 1;
    }

    .fundingPanels .panel-heading {
        padding: 0;
        position: relative;
    }

    .fundingPanels .panel-heading > .bg {
        position: absolute;
        height: 100%;
        width: 100%;
        background-size: 1px 1px;
        border-top-right-radius: 4px;
        border-top-left-radius: 4px;
    }

    .fundingPanels .panel-heading > img {
        position: relative;
        width: 100%;
        border-top-right-radius: 4px;
        border-top-left-radius: 4px;
        object-fit: contain;
        object-position: center;
    }

    .fundingPanels .panel-heading .project-owner {
        position: absolute;
        bottom: -34px;
        left: 0;
        right: 0;
        text-align: center;
    }

    .fundingPanels .panel-heading .project-owner div.space-acronym {
        display: block;
        margin: 0 auto;
        border: white 2px solid;
    }

    .fundingPanels .panel-heading .project-owner img.profile-user-photo {
        border: white 2px solid;
    }

    .fundingPanels .panel-heading .project-owner span {
        display: block;
        width: 100%;
        text-align: center;
        font-size: 12px;
    }

    .fundingPanels .panel-heading .project-owner strong {
        font-weight: 600;
    }

    .fundingPanels .panel-body {
        margin-top: 38px;
        height: 100px;
    }

    .fundingPanels .panel-body .funding-title {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        margin: 0;
    }

    .fundingPanels .panel-body .media {
        margin-top: 6px;
    }

    .fundingPanels .panel-body .media h4.media-heading {
        font-size: 12px;
        line-height: 16px;
        text-align: center;
    }

    .fundingPanels .panel-footer {
        background-color: white;
        border: none;
        padding: 0;
    }

    .fundingPanels .panel-footer .funding-progress {
        padding: 0 15px;
    }

    .fundingPanels .panel-footer .funding-progress > div:not(.progress) {
        display: inline-block;
        font-size: 10px;
    }

    .fundingPanels .panel-footer .funding-progress .clock::before {
        content: 'L';
        color: white;
        text-align: center;
        width: 100%;
        display: block;
        margin-left: 1px;
        font-size: 10px;
    }

    .fundingPanels .panel-footer .funding-progress .clock {
        display: inline-block;
        vertical-align: middle;
        width: 18px;
        height: 18px;
        border-radius: 18px;
        background: gray;
        margin-right: 4px;
    }

    .fundingPanels .panel-footer .funding-progress .clock.red {
        background: red;
    }

    .fundingPanels .panel-footer .funding-progress .days {
        display: inline-block;
        vertical-align: middle;
    }

    .fundingPanels .panel-footer .funding-progress .clock.red + .days {
        color: red;
    }

    .fundingPanels .panel-footer .funding-progress .progress {
        width: 100%;
        height: 6px;
        margin-top: 3px;
        background: #e4e8eb;
    }

    .fundingPanels .panel-footer .funding-progress .progress-bar {
        background-color: #28aa69;
    }

    .fundingPanels .panel-footer .funding-details {
        padding: 0 15px;
        border-top: 1px solid #f0f5f8;
    }

    .fundingPanels .panel-footer .funding-details .col-md-6 {
        padding: 12px 2px 12px 15px;
        font-size: 12px;
    }

    .fundingPanels .panel-footer .funding-details .col-md-6:first-of-type {
        border-right: 1px solid #f0f5f8;
    }

    .fundingPanels .panel-footer .funding-details .col-md-6 span {
        vertical-align: middle;
        margin-right: 2px;
    }

</style>
