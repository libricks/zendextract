
    <ul >
        <?php
        $admin;
        foreach ($_['group'] as $group){
            if($group->getGid()=='admin'){
                $admin=true;
            }
        }

        if ($admin) : ?>

        <li><a href="/extranet/index.php/apps/zendextract/">Extractions</a></li>
        <?php endif; ?>
        <li>
            <a href="/extranet/index.php/apps/zendextract/export">Export</a>

        </li>
    </ul>
