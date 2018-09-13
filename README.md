在框架根目录下创建buffer缓存文件夹 并赋予777权限 Linux下需要确保 view下文件夹与控制器名称一致
|---compass 核心文件夹

|---application 应用文件夹

|---public web访问入口

|------ static 资源目录

|------index.php 入口文件

|---model 模型文件目录

|---buffer 缓存目录

===========================

===========================
Config类用于获取配置文件

$config=new Config();
//设置配置文件的路径
$config->setPath(COMPASS.'/configs');
//此时config类默认在 [项目路径]/compass/configs文件夹中加载
$config['app'];
//加载configs/app.php配置文件


===========================\
#容器
通过在compass/configs/app.app配置文件中将对象绑定到容器中\
然后在应用层通过App::get('绑定的服务名')\
如: $app=App::get('config'); $app->config类的所有公用方法();\
也可以通过
$app=new App();
$app->make(服务名,构造参数);

###绑定
App::set('服务名',完整命名空间路径);\
或则:
$app=new App();
$app->bind('服务名',完整命名空间路径);

###使用
使用App容器获取log类并调用log类中的write方法进行日志写入\
App::get('log')->write('aaa','systemInfo');\

#模型
###模型的插入方法
所有定义的模型都必须要继承compass\cores\Model基类模型
namespace model;
use compass\cores\Model;

class Article extends Model
{
    //指定数据表前置
    protected $prefix='blog_';
    //指定数据表名
    protected $table='test';
    public function index(){
    //执行插入方法
        $this->insert([
            'title'=>'test',
            'textdesc'=>'test',
            'author'=>'test',
            'content'=>'test',
            'cateid'=>1,
        ]);
    }
}
###模型更新操作
//获取10850id数据
$this->get(10850);
//修改title字段
$this->title='builder';
//保存,写库
$this->save();

#控制器
在控制器中继承基类控制器
use compass\cores\Controller;\
此时可以使用$this->fetch();来渲染视图\
如:定义控制器中的方法\
public function test(){
        $this->fetch('',[
            'a'=>'hello,world',
        ]);
    }\
则会自动加载当前模块下的view/当前控制器名/test.html\
html代码: <p>{$a}</p>\
则会输出hello,world
#数据库操作
#####在`compass/configs/database.php`中配置数据库的参数
connect参数\
connect($dbname=数据名, $host =主机, $username = 用户名, $password = 密码,$port=3306端口,$charset='utf8字符集')\
###使用App容器
####与数据库建立连接
App::get('db')->connect();\
//通过connect参数可以快速的连接第二个数据库\
如执行更新操作:\
App::get('db')->connect('shop')->table('test')->update([
            'name'=>'new',
],'power','name');\
##删除
删除索引id为1,2,3,4,5,6的数据并指定了表名为test,默认加载configs配置文件夹下的database.php配置文件\
App::get('db')->connect()->table('test')->delete('1,2,3,4,5,6');\
或则:\
App::get('db')->connect()->table('test')->delete([1,2,3,4,5,6]);\
删除掉power为1的数据\
App::get('db')->connect()->table('test')->delete(1,'power');
返回值为返回受到影响的行数
##插入
//在test表中插入索引为name的值joke,mobile...\
App::get('db')->connect()->table('test')->insert([
            'name'=>'joke',
            'mobile'=>'18888888888',
            'register'=>'1970-01-01',
]);
返回值为返回受到影响的行数
##查询
//查询test表中所有数据
App::get('db')->connect()->table('test')->select();\
//查询test表中字段为name,mobile的数据\
App::get('db')->connect()->table('test')->field('name,mobile')->select();\
//查询id为1的数据\
App::get('db')->connect()->where('id=1')->select();\
##更新
更改test表中id为303的字段将name改为new,mobile...\
App::get('db')->connect()->table('test')->where('id=303')->update([
            'name'=>'new',
            'mobile'=>'1234567890'
]);\
或则:\
App::get('db')->connect()->table('test')->update([
            'name'=>'newd',
            'mobile'=>'1234567890'
],303,'id');\
返回值为返回受到影响的行数

#路由
#url默认寻找路由是否注册,如果没有找到路由则会跳转到模块,控制器,方法
在compass/Route.php中定义路由\
get路由\
use compass\cores\Route;\
此时在地址栏输入index则可以访问到index模块Index控制器index方法\
Route::get('index','index/Index/index');\
//post路由\
Route::post('index','index/Index/index');\

有参路由\
Route::get('index/:id/:price','index/Index/index');\
此时如果在地址栏输入index/1/5\
则对应的路由为index/Index/index/id/1/price/5
post有参路由同理\
