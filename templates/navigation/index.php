<nav class="app-navigation-vue">


    <ul class="app-navigation-list app-navigation__list" >
        <?php


        $admin = false;
        foreach ($_['groups'] as $group){
            if($group->getGid()=='admin'){
                $admin=true;
            }
        }

        if ($admin) : ?>

        <li class="app-navigation-entry-wrapper"><a href="/apps/zendextract/">Extractions</a></li>
        <?php  endif; ?>
        <li class="app-navigation-entry-wrapper">
            <a href="/apps/zendextract/export">Export</a>

        </li>
    </ul>
</nav>