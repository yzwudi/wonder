<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=\backend\models\UserBackend::findOne($_SESSION['__id'])->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                "encodeLabels" => false,
                'options' => ['class' => 'sidebar-menu'],
                'items' =>\yii\helpers\ArrayHelper::merge([['label' => '目录', 'options' => ['class' => 'header']],],
                    \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id), [
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => '权限控制',
                        //'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => '路由', 'icon' => 'fa fa-file-code-o', 'url' => ['/admin/route'],],
                            ['label' => '权限', 'icon' => 'fa fa-dashboard', 'url' => ['/admin/permission'],],
                            ['label' => '角色', 'icon' => 'fa fa-file-code-o', 'url' => ['/admin/role'],],
                            ['label' => '分配', 'icon' => 'fa fa-dashboard', 'url' => ['/admin/assignment'],],
                            ['label' => '菜单', 'icon' => 'fa fa-dashboard', 'url' => ['/admin/menu'],],
                        ],
                    ],
                ])

            ]
        ) ;


        ?>

    </section>

</aside>
