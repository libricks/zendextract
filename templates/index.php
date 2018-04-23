<?php



script('zendextract', 'bootstrap.min');
script('zendextract', 'moment');
script('zendextract', 'bootstrap-datetimepicker.min');
script('zendextract', 'bootstrap-select.min');
script('zendextract', 'defaults-fr_FR');
script('zendextract', 'script');

style('zendextract', 'vendor/bootstrap/css/bootstrap');
style('zendextract', 'vendor/bootstrap-datetimepicker.min');
style('zendextract', 'vendor/bootstrap-select.min');
style('zendextract', 'vendor/font-awesome-4.7.0/css/font-awesome.min');
style('zendextract', 'style');
?>

<div id="app">
    <div id="app-navigation">

        <?php print_unescaped($this->inc('navigation/index')); ?>
        <?php print_unescaped($this->inc('settings/index')); ?>


    </div>

    <div id="app-content">
        <div id="app-content-wrapper">
            <div class="container-fluid">
                <?php print_unescaped($this->inc("content/".$_['view'])); ?>
            </div>
        </div>
    </div>
</div>

