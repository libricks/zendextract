
    <ul >
        <?php


        $admin = false;
        foreach ($_['groups'] as $group){
            if($group->getGid()=='admin'){
                $admin=true;
            }
        }

        if ($admin) : ?>

        <li><a href="/apps/zendextract/">Extractions</a></li>
        <?php  endif; ?>
        <li>
            <a href="/apps/zendextract/export">Export</a>

        </li>
    </ul>
