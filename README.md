# flop-machine
翻牌机

1、composer 组建到本地
2、修改 config/app.php
   添加服务，在 app.php 中的 providers 中添加一个项：
   Wjdnw\FlopMachine\FlopMachineServiceProvider::class,
   
   添加别名，在 app.php 中的 aliases 中添加一个项：
   'Flopmachine' => Wjdnw\FlopMachine\Facades\FlopMachineFacade::class,
   
3、发布资源文件：
   php artisan vendor:publish --provider="Wjdnw\FlopMachine\FlopMachineServiceProvider"
   这时候，在项目根目录，config目录中，自动多加个文件，flopMachineConfig 配置文件
   
