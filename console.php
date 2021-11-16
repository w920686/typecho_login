<?php
define('__PLUGIN_ROOT__', __DIR__);
include_once 'common.php';
include 'header.php';
include 'menu.php';
?>
<style>
    svg{
        margin-bottom: -5px;
    }
    .icon{
        width:25px;
        height:25px;
    }
</style>
<div style="text-align:center;">
<?php
    $db = Typecho_Db::get();
    Typecho_Widget::widget('Widget_User')->to($user);
    $iconfile = __PLUGIN_ROOT__."/icon.json";
    $icon = @fopen($iconfile, "r") or die("登陆按钮图标文件丢失!");
    $site = json_decode(fread($icon,filesize($iconfile)),true);
    fclose($icon);
    $plugin = Typecho_Widget::widget('Widget_Options')->plugin('GmLogin');
    $arr = [];
    for ($i = 0; $i < count($site); $i++) {
        $c = $site[$i]['site'];
        if($plugin->$c){
            $arr[] = $site[$i];
        }
    }
    if($_GET['del']){
        $a = $_GET['del'];
        if($plugin->$a){
            if($db->query($db->delete('table.gm_oauth')->where('uid = ?',$user->uid)->where('app = ?',$_GET['del']))){
                exit('<script>alert("解绑成功");window.location.href="'.$options->adminUrl.'extending.php?panel=GmLogin%2Fconsole.php";</script>');
            }else{
                exit('<script>window.location.href="'.$options->adminUrl.'extending.php?panel=GmLogin/console.php";</script>');
            }
        }else{
            throw new Typecho_Exception(_t('未开通此第三方登陆'));
        }
    }
    if($_GET['add']){
        $a = $_GET['add'];
        if($plugin->$a){
            exit(header('location:https://sso.gmit.vip/'.$_GET['add'].'/redirect?redirect_url='.Typecho_Common::url('user/bangding', Helper::options()->index)));
        }else{
            throw new Typecho_Exception(_t('未开通此第三方登陆'));
        }
    }
    $data = [];
    for ($i = 0; $i < count($arr); $i++) {
        if($res = $db->fetchAll($db->select('app','uid','openid','time')->from('table.gm_oauth')->where('app = ?',$arr[$i]['site'])->where('uid = ?',$user->uid))){
            $data[$arr[$i]['site']] = $res;
        }else{
            $data[$arr[$i]['site']] = 0;
        }
    }
?>
</div>
<div class="main">
    <div class="body container">
        <div class="typecho-page-title">
            <h2>第三方登录设置</h2>
        </div>
        <div class="container typecho-page-main">
            <div class="col-mb-12 typecho-list">
                <div class="typecho-table-wrap">
                    <table class="typecho-list-table">
                        <colgroup>
                            <col width="20%">
                            <col width="20%">
                            <col width="20%">
                            <col width="30%">
                            <col width="20%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>站点</th>
                            <th>状态</th>
                            <th>绑定时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < count($arr); $i++) {
                                
                            ?>
                            <tr>
                                
                                <td><?php echo $arr[$i]['ico']?></td>
                                <td><?php echo $arr[$i]['name']?>账号</td>
                                <td>
                                    <?php 
                                    if($data[$arr[$i]['site']]){
                                    ?>
                                    <font style="color:green">已绑定</font>
                                    <?php
                                    }else{
                                    ?>
                                    未绑定
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if($data[$arr[$i]['site']]){
                                        echo date('Y-m-d',$data[$arr[$i]['site']][0]['time']);
                                    }else{
                                    ?>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td style="font-weight:bold">
                                    <?php 
                                    if($data[$arr[$i]['site']]){
                                        echo '<a href="'.$options->adminUrl.'extending.php?panel=GmLogin/console.php&del='.$arr[$i]['site'].'" style="color:#FF0000;">解绑</a>';
                                    }else{
                                        echo '<a href="'.$options->adminUrl.'extending.php?panel=GmLogin/console.php&add='.$arr[$i]['site'].'">绑定</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'copyright.php';
include 'common-js.php';
include 'table-js.php';
?>
<?php
include 'footer.php';
?>
